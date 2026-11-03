<?php

use App\Livewire\AlertDetails;
use App\Models\FlightResult;
use App\Models\FlightSearch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('authenticated user can view alert details with check history and multiple airlines', function () {
    $user = User::factory()->create();

    $search = FlightSearch::factory()->create([
        'user_id' => $user->id,
        'origin' => 'BOG',
        'destination' => 'MDE',
        'date' => now()->addDays(10),
    ]);

    // Create execution 1 (e.g. 2 hours ago with 2 airlines)
    $run1Time = now()->subHours(2);
    FlightResult::factory()->create([
        'flight_search_id' => $search->id,
        'airline' => 'Avianca',
        'flight_number' => 'AV101',
        'price' => 200000,
        'created_at' => $run1Time,
        'updated_at' => $run1Time,
    ]);

    FlightResult::factory()->create([
        'flight_search_id' => $search->id,
        'airline' => 'LATAM',
        'flight_number' => 'LA202',
        'price' => 250000,
        'created_at' => $run1Time,
        'updated_at' => $run1Time,
    ]);

    // Create execution 2 (e.g. 1 hour ago with cheaper price)
    $run2Time = now()->subHour();
    FlightResult::factory()->create([
        'flight_search_id' => $search->id,
        'airline' => 'Wingo',
        'flight_number' => 'W303',
        'price' => 150000,
        'created_at' => $run2Time,
        'updated_at' => $run2Time,
    ]);

    $this->actingAs($user);

    Livewire::test(AlertDetails::class, ['id' => $search->id])
        ->assertStatus(200)
        ->assertSee('BOG to MDE')
        ->assertSee('Historial de Chequeos')
        ->assertSee('Avianca')
        ->assertSee('LATAM')
        ->assertSee('Wingo')
        ->assertSee('200.000')
        ->assertSee('250.000')
        ->assertSee('150.000');
});

test('it groups results by run and supports loadMore for older checks', function () {
    $user = User::factory()->create();

    $search = FlightSearch::factory()->create([
        'user_id' => $user->id,
        'origin' => 'CTG',
        'destination' => 'BOG',
        'date' => now()->addDays(5),
    ]);

    // Create 7 separate runs (each 1 hour apart)
    for ($i = 0; $i < 7; $i++) {
        $runTime = now()->subHours(7 - $i);
        FlightResult::factory()->create([
            'flight_search_id' => $search->id,
            'airline' => "Airline Run {$i}",
            'price' => 100000 + ($i * 10000),
            'created_at' => $runTime,
            'updated_at' => $runTime,
        ]);
    }

    $this->actingAs($user);

    $component = Livewire::test(AlertDetails::class, ['id' => $search->id])
        ->assertSet('perPage', 5);

    expect($component->get('checkHistory')->total_checks)->toBe(7);
    expect($component->get('checkHistory')->has_more)->toBeTrue();

    // Call loadMore
    $component->call('loadMore')
        ->assertSet('perPage', 10);

    expect($component->get('checkHistory')->has_more)->toBeFalse();
});

test('user cannot view alert details of another user', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $search = FlightSearch::factory()->create([
        'user_id' => $userA->id,
    ]);

    $this->actingAs($userB);

    Livewire::test(AlertDetails::class, ['id' => $search->id])
        ->assertStatus(404);
});
