<?php

namespace Database\Factories;

use App\Models\FlightSearch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FlightResult>
 */
class FlightResultFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'flight_search_id' => FlightSearch::factory(),
            'airline' => 'Avianca',
            'flight_number' => 'AV9246',
            'airline_logo' => 'https://example.com/logo.png',
            'origin_name' => 'Medellin',
            'destination_name' => 'Bogota',
            'price' => 150000,
            'departure_time' => now()->addDays(7)->setHour(10),
            'arrival_time' => now()->addDays(7)->setHour(11),
            'duration' => '1h 0m',
            'stops' => 0,
        ];
    }
}
