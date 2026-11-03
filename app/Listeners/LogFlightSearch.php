<?php

namespace App\Listeners;

use App\Events\FlightSearched;
use Illuminate\Support\Facades\Log;

class LogFlightSearch
{
    /**
     * Handle the event.
     */
    public function handle(FlightSearched $event): void
    {
        Log::info('Flight search performed', [
            'origin' => $event->criteria->origin,
            'destination' => $event->criteria->destination,
            'date' => $event->criteria->date,
            'results_count' => $event->results->count(),
            'from_cache' => $event->fromCache,
            'user_id' => auth()->id(),
        ]);
    }
}
