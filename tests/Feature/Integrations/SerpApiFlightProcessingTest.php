<?php

use App\Actions\FetchFlightsAction;
use App\Actions\ProcessFlightsAction;
use App\Contracts\FlightProviderInterface;
use App\DTOs\FlightDTO;
use App\DTOs\SearchCriteriaDTO;
use App\Models\FlightResult;
use App\Models\FlightSearch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

it('correctly processes and stores flight data using mocked provider', function () {
    Notification::fake();

    // 1. Prepare data
    $user = User::factory()->create();
    $flightSearch = FlightSearch::create([
        'user_id' => $user->id,
        'origin' => 'MDE',
        'destination' => 'MTR',
        'date' => '2026-08-11',
        'currency' => 'COP',
        'target_price' => 200000,
    ]);

    $criteria = new SearchCriteriaDTO(
        origin: 'MDE',
        destination: 'MTR',
        date: '2026-08-11',
        currency: 'COP'
    );

    // 2. Mock FlightProviderInterface
    $mockFlight = new FlightDTO(
        airline_name: 'JetSMART',
        airline_logo: 'https://www.gstatic.com/flights/airline_logos/70px/JA.png',
        flight_number: 'JA 5482',
        price: 151180.0,
        departure_time: '2026-08-11 17:00',
        arrival_time: '2026-08-11 18:01',
        origin_code: 'MDE',
        destination_code: 'MTR',
        origin_name: 'Aeropuerto Internacional José María Córdova',
        destination_name: 'Aeropuerto Internacional Los Garzones - Montería',
        duration: '61',
        stops: 'Non-stop',
        cheapest: true,
        price_status: 'Price is Low',
    );

    $mockProvider = Mockery::mock(FlightProviderInterface::class);
    $mockProvider->shouldReceive('searchFlights')
        ->once()
        ->with(Mockery::type(SearchCriteriaDTO::class))
        ->andReturn(collect([$mockFlight]));

    $this->app->instance(FlightProviderInterface::class, $mockProvider);

    // 3. Execute Action
    $fetchAction = app(FetchFlightsAction::class);
    $flights = $fetchAction->execute($criteria);

    // 4. Execute Process
    $processAction = new ProcessFlightsAction;
    $processAction->execute($flightSearch, $flights);

    // 5. Assertions
    expect(FlightResult::count())->toBe(1);

    $storedFlight = FlightResult::first();
    expect($storedFlight->airline)->toBe('JetSMART');
    expect($storedFlight->price)->toEqual(151180.00);
    expect($storedFlight->departure_time)->toBe('2026-08-11 17:00');
    expect($storedFlight->arrival_time)->toBe('2026-08-11 18:01');
    expect($storedFlight->duration)->toBe('61');
    expect($storedFlight->stops)->toBe('Non-stop');
    expect($storedFlight->airline_logo)->toBe('https://www.gstatic.com/flights/airline_logos/70px/JA.png');
});

it('correctly handles flights with stops in the collection', function () {
    Notification::fake();

    $user = User::factory()->create();
    $flightSearch = FlightSearch::create([
        'user_id' => $user->id,
        'origin' => 'MDE',
        'destination' => 'BOG',
        'date' => '2026-08-11',
    ]);

    $mockFlight = new FlightDTO(
        airline_name: 'Avianca',
        airline_logo: null,
        flight_number: 'AV 123',
        price: 250000.0,
        departure_time: '2026-08-11 10:00',
        arrival_time: '2026-08-11 14:00',
        origin_code: 'MDE',
        destination_code: 'BOG',
        origin_name: null,
        destination_name: null,
        duration: '240',
        stops: '1 stop(s)',
        cheapest: false,
        price_status: 'Standard price',
    );

    $mockProvider = Mockery::mock(FlightProviderInterface::class);
    $mockProvider->shouldReceive('searchFlights')
        ->once()
        ->andReturn(collect([$mockFlight]));

    $this->app->instance(FlightProviderInterface::class, $mockProvider);

    $fetchAction = app(FetchFlightsAction::class);
    $flights = $fetchAction->execute(new SearchCriteriaDTO('MDE', 'BOG', '2026-08-11'));

    (new ProcessFlightsAction)->execute($flightSearch, $flights);

    $storedFlight = FlightResult::first();
    expect($storedFlight->stops)->toBe('1 stop(s)');
    expect($storedFlight->departure_time)->toBe('2026-08-11 10:00');
    expect($storedFlight->arrival_time)->toBe('2026-08-11 14:00');
});
