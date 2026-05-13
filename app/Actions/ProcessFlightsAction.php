<?php

namespace App\Actions;

use App\DTOs\FlightDTO;
use App\Models\FlightNotification;
use App\Models\FlightResult;
use App\Models\FlightSearch;
use App\Notifications\PriceDropNotification;

class ProcessFlightsAction
{
    public function execute(FlightSearch $search, array $flights): void
    {
        foreach ($flights as $flightDto) {
            /** @var FlightDTO $flightDto */

            // 1. Save historical result
            $result = $search->results()->create([
                'airline' => $flightDto->airline_name,
                'airline_logo' => $flightDto->airline_logo,
                'price' => $flightDto->price,
                'departure_time' => $flightDto->departure_time,
                'arrival_time' => $flightDto->arrival_time,
                'duration' => $flightDto->duration,
                'stops' => $flightDto->stops,
            ]);

            // 2. Check conditions for notification
            if ($search->target_price && $flightDto->price <= $search->target_price) {
                $this->createNotification($search, $result);
            }
        }
    }

    protected function createNotification(FlightSearch $search, FlightResult $result): void
    {
        // Avoid duplicate notification for the same result in a short window
        $exists = FlightNotification::where('user_id', $search->user_id)
            ->where('flight_result_id', $result->id)
            ->exists();

        if (! $exists) {
            $notification = FlightNotification::create([
                'user_id' => $search->user_id,
                'flight_result_id' => $result->id,
                'origin' => $search->origin,
                'destination' => $search->destination,
                'date' => $search->date,
                'airline' => $result->airline,
                'price' => $result->price,
                'departure_time' => $result->departure_time,
                'arrival_time' => $result->arrival_time,
                'duration' => $result->duration,
            ]);

            // Trigger FCM
            $search->user->notify(new PriceDropNotification($notification));
        }
    }
}
