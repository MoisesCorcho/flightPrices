<?php

use App\Livewire\AlertDetails;
use App\Livewire\MySearches;
use App\Models\FlightResult;
use App\Models\FlightSearch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('computes correct frequency hours and label based on days to flight', function () {
    $searchImminent = FlightSearch::factory()->create([
        'date' => now()->addDays(3),
    ]);
    expect($searchImminent->frequency_hours)->toBe(6)
        ->and($searchImminent->frequency_label)->toBe(__('Intensive (every 6h)'));

    $searchMedium = FlightSearch::factory()->create([
        'date' => now()->addDays(15),
    ]);
    expect($searchMedium->frequency_hours)->toBe(12)
        ->and($searchMedium->frequency_label)->toBe(__('Twice daily (every 12h)'));

    $searchDistant = FlightSearch::factory()->create([
        'date' => now()->addDays(45),
    ]);
    expect($searchDistant->frequency_hours)->toBe(24)
        ->and($searchDistant->frequency_label)->toBe(__('Daily (every 24h)'));

    $searchExpired = FlightSearch::factory()->create([
        'date' => now()->subDays(2),
    ]);
    expect($searchExpired->frequency_label)->toBe(__('Flight Expired'));
});

test('computes next check estimation correctly', function () {
    $user = User::factory()->create();

    // Inactive alert
    $inactive = FlightSearch::factory()->create([
        'user_id' => $user->id,
        'is_active' => false,
    ]);
    expect($inactive->next_check_at)->toBeNull()
        ->and($inactive->next_check_text)->toBe(__('Alert paused'));

    // Expired flight
    $expired = FlightSearch::factory()->create([
        'user_id' => $user->id,
        'date' => now()->subDays(3),
        'is_active' => true,
    ]);
    expect($expired->next_check_at)->toBeNull()
        ->and($expired->next_check_text)->toBe(__('Flight has already passed'));

    // New active search without history
    $newSearch = FlightSearch::factory()->create([
        'user_id' => $user->id,
        'date' => now()->addDays(45),
        'is_active' => true,
    ]);
    expect($newSearch->next_check_text)->toBe(__('Next check: on upcoming run'));

    // Checked 20 hours ago on a 24h cycle (due in ~4 hours)
    FlightResult::factory()->create([
        'flight_search_id' => $newSearch->id,
        'created_at' => now()->subHours(20),
    ]);
    $newSearch->refresh();
    expect($newSearch->next_check_at)->not->toBeNull()
        ->and($newSearch->next_check_text)->toContain(now()->addHours(4)->diffForHumans());

    // Checked 25 hours ago on a 24h cycle (overdue, ready now)
    $newSearch->results()->delete();
    FlightResult::factory()->create([
        'flight_search_id' => $newSearch->id,
        'created_at' => now()->subHours(25),
    ]);
    $newSearch->refresh();
    expect($newSearch->next_check_text)->toBe(__('Next check: on upcoming run'));
});

test('alert details component renders frequency label and next check text', function () {
    $user = User::factory()->create();
    $search = FlightSearch::factory()->create([
        'user_id' => $user->id,
        'date' => now()->addDays(45),
        'is_active' => true,
    ]);

    FlightResult::factory()->create([
        'flight_search_id' => $search->id,
        'price' => 120000,
        'created_at' => now()->subHours(20),
    ]);

    $this->actingAs($user);

    Livewire::test(AlertDetails::class, ['id' => $search->id])
        ->assertOk()
        ->assertSee($search->frequency_label)
        ->assertSee(__('Next check: :time', ['time' => $search->next_check_at->diffForHumans()]));
});

test('my searches component renders adaptive frequency notice and search card badges', function () {
    $user = User::factory()->create();
    $search = FlightSearch::factory()->create([
        'user_id' => $user->id,
        'origin' => 'MDE',
        'destination' => 'MTR',
        'date' => now()->addDays(45),
        'is_active' => true,
    ]);

    $this->actingAs($user);

    Livewire::test(MySearches::class)
        ->assertOk()
        ->assertSee(__('Adaptive Frequency Notice'))
        ->assertSee($search->frequency_label)
        ->assertSee(__('Next check: on upcoming run'));
});
