<?php

use App\Actions\FetchFlightsAction;
use App\Actions\ProcessFlightsAction;
use App\DTOs\SearchCriteriaDTO;
use App\Integrations\SerpApi\Requests\GetGoogleFlightsRequest;
use App\Integrations\SerpApi\SerpApiConnector;
use App\Models\FlightResult;
use App\Models\FlightSearch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

uses(RefreshDatabase::class);

it('correctly processes and stores flight data from SerpApi response', function () {
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

    // 2. Mock SerpApi Response
    $mockResponseData = [
        'best_flights' => [
            [
                'flights' => [
                    [
                        'departure_airport' => [
                            'name' => 'Aeropuerto Internacional José María Córdova',
                            'id' => 'MDE',
                            'time' => '2026-08-11 17:00',
                        ],
                        'arrival_airport' => [
                            'name' => 'Aeropuerto Internacional Los Garzones - Montería',
                            'id' => 'MTR',
                            'time' => '2026-08-11 18:01',
                        ],
                        'duration' => 61,
                        'airplane' => 'Airbus A320',
                        'airline' => 'JetSMART',
                        'airline_logo' => 'https://www.gstatic.com/flights/airline_logos/70px/JA.png',
                        'travel_class' => 'Economy',
                        'flight_number' => 'JA 5482',
                    ],
                ],
                'total_duration' => 61,
                'price' => 151180,
                'type' => 'One way',
            ],
        ],
    ];

    $mockClient = new MockClient([
        GetGoogleFlightsRequest::class => MockResponse::make($mockResponseData, 200),
    ]);

    // 3. Execute Fetch (Injecting MockClient)
    // FetchFlightsAction creates its own connector, so we need a way to mock it.
    // Let's refactor FetchFlightsAction slightly or use Saloon's global faking if available,
    // but better to use the connector directly or mock the connector.
    // Actually, FetchFlightsAction.php:
    // $connector = new SerpApiConnector;
    // $connector->send($request);

    // Saloon doesn't have a global "fake everything" like Laravel's Http::fake() out of the box easily without changing the instance
    // unless we use Saloon\Config::setMockClient($mockClient) if it exists, or pass the connector.

    // Let's check FetchFlightsAction again.
    /*
    public function execute(SearchCriteriaDTO $criteria): array
    {
        $connector = new SerpApiConnector;
        $request = new GetGoogleFlightsRequest($criteria);

        $response = $connector->send($request);

        return $response->dto();
    }
    */

    // I'll use Saloon's MockClient by passing it to the connector.
    // To make FetchFlightsAction testable, I should probably allow passing a connector or use a factory.
    // For now, I'll use a trick: Saloon's MockClient can be globally registered in some versions or I can mock the Connector class.

    $connector = new SerpApiConnector;
    $connector->withMockClient($mockClient);

    $request = new GetGoogleFlightsRequest($criteria);
    $response = $connector->send($request);
    $dtos = $response->dto();

    // 4. Execute Process
    $action = new ProcessFlightsAction;
    $action->execute($flightSearch, $dtos);

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

it('correctly handles flights with stops', function () {
    Notification::fake();

    $user = User::factory()->create();
    $flightSearch = FlightSearch::create([
        'user_id' => $user->id,
        'origin' => 'MDE',
        'destination' => 'BOG',
        'date' => '2026-08-11',
    ]);

    $mockResponseData = [
        'best_flights' => [
            [
                'flights' => [
                    [
                        'departure_airport' => ['id' => 'MDE', 'time' => '2026-08-11 10:00'],
                        'arrival_airport' => ['id' => 'CLO', 'time' => '2026-08-11 11:00'],
                        'airline' => 'Avianca',
                        'flight_number' => 'AV 123',
                    ],
                    [
                        'departure_airport' => ['id' => 'CLO', 'time' => '2026-08-11 13:00'],
                        'arrival_airport' => ['id' => 'BOG', 'time' => '2026-08-11 14:00'],
                        'airline' => 'Avianca',
                        'flight_number' => 'AV 456',
                    ],
                ],
                'total_duration' => 240,
                'price' => 250000,
            ],
        ],
    ];

    $mockClient = new MockClient([
        GetGoogleFlightsRequest::class => MockResponse::make($mockResponseData, 200),
    ]);

    $connector = new SerpApiConnector;
    $connector->withMockClient($mockClient);

    $request = new GetGoogleFlightsRequest(new SearchCriteriaDTO('MDE', 'BOG', '2026-08-11'));
    $dtos = $connector->send($request)->dto();

    (new ProcessFlightsAction)->execute($flightSearch, $dtos);

    $storedFlight = FlightResult::first();
    expect($storedFlight->stops)->toBe('1 stop(s)');
    expect($storedFlight->departure_time)->toBe('2026-08-11 10:00');
    // Here we check if it's the arrival at the final destination (BOG) or the intermediate one (CLO)
    expect($storedFlight->arrival_time)->toBe('2026-08-11 14:00');
});
