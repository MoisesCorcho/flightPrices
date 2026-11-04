<?php

use App\Contracts\FlightProviderInterface;
use App\DTOs\FlightDTO;
use App\DTOs\SearchCriteriaDTO;
use App\Models\FlightResult;
use App\Models\FlightSearch;
use App\Models\User;
use App\Notifications\PriceDropNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

beforeEach(function () {
    Cache::flush();
});

function createSampleFlightDTO(float $price = 150000.0, string $origin = 'MDE', string $destination = 'MTR'): FlightDTO
{
    return new FlightDTO(
        airline_name: 'Avianca',
        airline_logo: 'https://example.com/avianca.png',
        flight_number: 'AV9246',
        price: $price,
        departure_time: '2026-12-22 10:00',
        arrival_time: '2026-12-22 11:00',
        origin_code: $origin,
        destination_code: $destination,
        origin_name: 'Jose Maria Cordova',
        destination_name: 'Los Garzones',
        duration: '1h 0m',
        stops: 'Non-stop',
    );
}

it('skips execution when no active searches exist', function () {
    FlightSearch::factory()->create(['is_active' => false]);

    $mockProvider = Mockery::mock(FlightProviderInterface::class);
    $mockProvider->shouldNotReceive('searchFlights');
    $this->app->instance(FlightProviderInterface::class, $mockProvider);

    $this->artisan('flights:check')
        ->expectsOutput('No searches need updating at this time.')
        ->assertSuccessful();
});

it('executes immediately for a new active search without previous results', function () {
    $search = FlightSearch::factory()->create([
        'origin' => 'MDE',
        'destination' => 'MTR',
        'date' => now()->addDays(10),
        'is_active' => true,
    ]);

    $mockProvider = Mockery::mock(FlightProviderInterface::class);
    $mockProvider->shouldReceive('searchFlights')
        ->once()
        ->andReturn(collect([createSampleFlightDTO()]));
    $this->app->instance(FlightProviderInterface::class, $mockProvider);

    $this->artisan('flights:check')
        ->assertSuccessful();

    expect($search->results()->count())->toBe(1);
});

it('skips flights whose departure date has already passed', function () {
    $search = FlightSearch::factory()->create([
        'origin' => 'MDE',
        'destination' => 'MTR',
        'date' => now()->subDays(2),
        'is_active' => true,
    ]);

    $mockProvider = Mockery::mock(FlightProviderInterface::class);
    $mockProvider->shouldNotReceive('searchFlights');
    $this->app->instance(FlightProviderInterface::class, $mockProvider);

    $this->artisan('flights:check')
        ->expectsOutput('No searches need updating at this time.')
        ->assertSuccessful();
});

it('skips search when less than 6 hours have passed for a flight within 7 days', function () {
    $search = FlightSearch::factory()->create([
        'origin' => 'MDE',
        'destination' => 'MTR',
        'date' => now()->addDays(3),
        'is_active' => true,
    ]);

    // Last result was created 5 hours ago
    FlightResult::factory()->create([
        'flight_search_id' => $search->id,
        'created_at' => now()->subHours(5),
    ]);

    $mockProvider = Mockery::mock(FlightProviderInterface::class);
    $mockProvider->shouldNotReceive('searchFlights');
    $this->app->instance(FlightProviderInterface::class, $mockProvider);

    $this->artisan('flights:check')
        ->expectsOutput('No searches need updating at this time.')
        ->assertSuccessful();
});

it('executes search when 6 or more hours have passed for a flight within 7 days', function () {
    $search = FlightSearch::factory()->create([
        'origin' => 'MDE',
        'destination' => 'MTR',
        'date' => now()->addDays(3),
        'is_active' => true,
    ]);

    FlightResult::factory()->create([
        'flight_search_id' => $search->id,
        'created_at' => now()->subHours(6)->subMinutes(5),
    ]);

    $mockProvider = Mockery::mock(FlightProviderInterface::class);
    $mockProvider->shouldReceive('searchFlights')
        ->once()
        ->andReturn(collect([createSampleFlightDTO()]));
    $this->app->instance(FlightProviderInterface::class, $mockProvider);

    $this->artisan('flights:check')
        ->assertSuccessful();

    expect($search->results()->count())->toBe(2);
});

it('skips search when less than 12 hours have passed for a flight between 7 and 30 days', function () {
    $search = FlightSearch::factory()->create([
        'origin' => 'MDE',
        'destination' => 'MTR',
        'date' => now()->addDays(15),
        'is_active' => true,
    ]);

    FlightResult::factory()->create([
        'flight_search_id' => $search->id,
        'created_at' => now()->subHours(10),
    ]);

    $mockProvider = Mockery::mock(FlightProviderInterface::class);
    $mockProvider->shouldNotReceive('searchFlights');
    $this->app->instance(FlightProviderInterface::class, $mockProvider);

    $this->artisan('flights:check')
        ->expectsOutput('No searches need updating at this time.')
        ->assertSuccessful();
});

it('executes search when 12 or more hours have passed for a flight between 7 and 30 days', function () {
    $search = FlightSearch::factory()->create([
        'origin' => 'MDE',
        'destination' => 'MTR',
        'date' => now()->addDays(15),
        'is_active' => true,
    ]);

    FlightResult::factory()->create([
        'flight_search_id' => $search->id,
        'created_at' => now()->subHours(12)->subMinutes(5),
    ]);

    $mockProvider = Mockery::mock(FlightProviderInterface::class);
    $mockProvider->shouldReceive('searchFlights')
        ->once()
        ->andReturn(collect([createSampleFlightDTO()]));
    $this->app->instance(FlightProviderInterface::class, $mockProvider);

    $this->artisan('flights:check')
        ->assertSuccessful();

    expect($search->results()->count())->toBe(2);
});

it('skips search when less than 24 hours have passed for a flight over 30 days', function () {
    $search = FlightSearch::factory()->create([
        'origin' => 'MDE',
        'destination' => 'MTR',
        'date' => now()->addDays(45),
        'is_active' => true,
    ]);

    FlightResult::factory()->create([
        'flight_search_id' => $search->id,
        'created_at' => now()->subHours(20),
    ]);

    $mockProvider = Mockery::mock(FlightProviderInterface::class);
    $mockProvider->shouldNotReceive('searchFlights');
    $this->app->instance(FlightProviderInterface::class, $mockProvider);

    $this->artisan('flights:check')
        ->expectsOutput('No searches need updating at this time.')
        ->assertSuccessful();
});

it('executes search when 24 or more hours have passed for a flight over 30 days (resolves negative diff bug)', function () {
    $search = FlightSearch::factory()->create([
        'origin' => 'MDE',
        'destination' => 'MTR',
        'date' => now()->addDays(45),
        'is_active' => true,
    ]);

    // Check done 42 hours ago (exact scenario experienced in VPS!)
    FlightResult::factory()->create([
        'flight_search_id' => $search->id,
        'created_at' => now()->subHours(42),
    ]);

    $mockProvider = Mockery::mock(FlightProviderInterface::class);
    $mockProvider->shouldReceive('searchFlights')
        ->once()
        ->andReturn(collect([createSampleFlightDTO()]));
    $this->app->instance(FlightProviderInterface::class, $mockProvider);

    $this->artisan('flights:check')
        ->assertSuccessful();

    expect($search->results()->count())->toBe(2);
});

it('forces search execution with --force flag regardless of elapsed time', function () {
    $search = FlightSearch::factory()->create([
        'origin' => 'MDE',
        'destination' => 'MTR',
        'date' => now()->addDays(45),
        'is_active' => true,
    ]);

    // Updated just 5 minutes ago!
    FlightResult::factory()->create([
        'flight_search_id' => $search->id,
        'created_at' => now()->subMinutes(5),
    ]);

    $mockProvider = Mockery::mock(FlightProviderInterface::class);
    $mockProvider->shouldReceive('searchFlights')
        ->once()
        ->andReturn(collect([createSampleFlightDTO()]));
    $this->app->instance(FlightProviderInterface::class, $mockProvider);

    $this->artisan('flights:check', ['--force' => true])
        ->assertSuccessful();

    expect($search->results()->count())->toBe(2);
});

it('deduplicates provider API calls by grouping identical routes on the same date', function () {
    $flightDate = now()->addDays(10)->startOfDay();

    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $search1 = FlightSearch::factory()->create([
        'user_id' => $user1->id,
        'origin' => 'MDE',
        'destination' => 'MTR',
        'date' => $flightDate,
        'target_price' => 100000,
        'is_active' => true,
    ]);

    $search2 = FlightSearch::factory()->create([
        'user_id' => $user2->id,
        'origin' => 'MDE',
        'destination' => 'MTR',
        'date' => $flightDate,
        'target_price' => 120000,
        'is_active' => true,
    ]);

    // Both need update
    FlightResult::factory()->create([
        'flight_search_id' => $search1->id,
        'created_at' => now()->subHours(15),
    ]);
    FlightResult::factory()->create([
        'flight_search_id' => $search2->id,
        'created_at' => now()->subHours(15),
    ]);

    $mockProvider = Mockery::mock(FlightProviderInterface::class);
    // CRITICAL: searchFlights must only be called ONCE for this shared route
    $mockProvider->shouldReceive('searchFlights')
        ->once()
        ->andReturn(collect([createSampleFlightDTO(price: 90000)]));
    $this->app->instance(FlightProviderInterface::class, $mockProvider);

    $this->artisan('flights:check')
        ->assertSuccessful();

    expect($search1->results()->count())->toBe(2)
        ->and($search2->results()->count())->toBe(2);
});

it('continues processing subsequent routes if one route encounters an API exception', function () {
    $date = now()->addDays(5)->startOfDay();

    $searchFail = FlightSearch::factory()->create([
        'origin' => 'MDE',
        'destination' => 'MTR',
        'date' => $date,
        'is_active' => true,
    ]);

    $searchSuccess = FlightSearch::factory()->create([
        'origin' => 'BOG',
        'destination' => 'CTG',
        'date' => $date,
        'is_active' => true,
    ]);

    $mockProvider = Mockery::mock(FlightProviderInterface::class);
    $mockProvider->shouldReceive('searchFlights')
        ->withArgs(fn (SearchCriteriaDTO $criteria) => $criteria->origin === 'MDE')
        ->andThrow(new RuntimeException('SerpApi rate limit exceeded'));

    $mockProvider->shouldReceive('searchFlights')
        ->withArgs(fn (SearchCriteriaDTO $criteria) => $criteria->origin === 'BOG')
        ->andReturn(collect([createSampleFlightDTO(origin: 'BOG', destination: 'CTG')]));

    $this->app->instance(FlightProviderInterface::class, $mockProvider);

    Log::spy();

    $this->artisan('flights:check')
        ->assertSuccessful();

    Log::shouldHaveReceived('error')
        ->once()
        ->withArgs(fn ($message) => str_contains($message, 'SerpApi rate limit exceeded'));

    // The successful route must still have stored its result
    expect($searchSuccess->results()->count())->toBe(1)
        ->and($searchFail->results()->count())->toBe(0);
});

it('dispatches price drop notification when retrieved price is at or below target price', function () {
    Notification::fake();

    $user = User::factory()->create();
    $search = FlightSearch::factory()->create([
        'user_id' => $user->id,
        'origin' => 'MDE',
        'destination' => 'MTR',
        'date' => now()->addDays(10),
        'target_price' => 100000,
        'is_active' => true,
    ]);

    $mockProvider = Mockery::mock(FlightProviderInterface::class);
    $mockProvider->shouldReceive('searchFlights')
        ->once()
        ->andReturn(collect([createSampleFlightDTO(price: 95000)]));
    $this->app->instance(FlightProviderInterface::class, $mockProvider);

    $this->artisan('flights:check')
        ->assertSuccessful();

    Notification::assertSentTo($user, PriceDropNotification::class);
});

it('does not dispatch notification when retrieved price is above target price', function () {
    Notification::fake();

    $user = User::factory()->create();
    $search = FlightSearch::factory()->create([
        'user_id' => $user->id,
        'origin' => 'MDE',
        'destination' => 'MTR',
        'date' => now()->addDays(10),
        'target_price' => 100000,
        'is_active' => true,
    ]);

    $mockProvider = Mockery::mock(FlightProviderInterface::class);
    $mockProvider->shouldReceive('searchFlights')
        ->once()
        ->andReturn(collect([createSampleFlightDTO(price: 150000)]));
    $this->app->instance(FlightProviderInterface::class, $mockProvider);

    $this->artisan('flights:check')
        ->assertSuccessful();

    Notification::assertNothingSent();
});
