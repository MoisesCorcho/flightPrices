<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FlightSearch>
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
