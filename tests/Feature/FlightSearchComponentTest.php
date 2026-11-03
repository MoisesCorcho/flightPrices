<?php

use App\Livewire\FlightSearch;
use App\Models\Airport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('authenticated user can view flight search screen with airports', function () {
    $user = User::factory()->create();

    Airport::create([
        'iata' => 'MDE',
        'name' => 'Jose Maria Cordova',
        'city' => 'Medellin',
        'country' => 'Colombia',
    ]);

    Airport::create([
        'iata' => 'BOG',
        'name' => 'El Dorado',
        'city' => 'Bogota',
        'country' => 'Colombia',
    ]);

    $this->actingAs($user);

    Livewire::test(FlightSearch::class)
        ->assertStatus(200)
        ->assertSee('Search Flights')
        ->assertSee('Medellin')
        ->assertSee('MDE')
        ->assertSee('Bogota')
        ->assertSee('BOG');
});

test('it can swap origin and destination', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(FlightSearch::class)
        ->set('origin', 'MDE')
        ->set('destination', 'BOG')
        ->call('swap')
        ->assertSet('origin', 'BOG')
        ->assertSet('destination', 'MDE');
});

test('it validates origin, destination and date before redirecting', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(FlightSearch::class)
        ->set('origin', '')
        ->set('destination', '')
        ->set('date', '')
        ->call('search')
        ->assertHasErrors(['origin', 'destination', 'date']);
});

test('it creates search and redirects on valid input', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $travelDate = now()->addDays(7)->format('Y-m-d');

    Livewire::test(FlightSearch::class)
        ->set('origin', 'MDE')
        ->set('destination', 'BOG')
        ->set('date', $travelDate)
        ->call('search')
        ->assertHasNoErrors()
        ->assertRedirect();

    $this->assertDatabaseHas('flight_searches', [
        'user_id' => $user->id,
        'origin' => 'MDE',
        'destination' => 'BOG',
        'is_active' => true,
    ]);
});
