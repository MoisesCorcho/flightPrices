<?php

namespace Database\Factories;

use App\Models\FlightSearch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FlightSearch>
 */
class FlightSearchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'origin' => 'MDE',
            'destination' => 'BOG',
            'date' => now()->addDays(7),
            'target_price' => 200000,
            'is_active' => true,
        ];
    }
}
