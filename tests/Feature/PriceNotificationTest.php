<?php

use App\Actions\ProcessFlightsAction;
use App\DTOs\FlightDTO;
use App\Models\FlightSearch;
use App\Models\User;
use App\Notifications\PriceDropNotification;
use Illuminate\Support\Facades\Notification;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('it sends a notification when flight price is below or equal to target price', function () {
    Notification::fake();

    // 1. Setup: User and a FlightSearch with a target price
    $user = User::factory()->create();
    $search = FlightSearch::factory()->create([
        'user_id' => $user->id,
        'target_price' => 100000,
        'is_active' => true,
    ]);

    // 2. Prepare a flight that matches the criteria (Price <= Target)
    $cheapFlight = new FlightDTO(
        airline_name: 'Avianca',
        airline_logo: 'logo.png',
        flight_number: 'AV123',
        price: 90000, // Below target
        departure_time: now()->addDays(1)->toDateTimeString(),
        arrival_time: now()->addDays(1)->addHours(1)->toDateTimeString(),
        origin_code: 'MDE',
        destination_code: 'BOG',
        duration: '1h',
        stops: '0',
        origin_name: 'Medellin',
        destination_name: 'Bogota'
    );

    // 3. Execute the action
    $action = app(ProcessFlightsAction::class);
    $action->execute($search, [$cheapFlight]);

    // 4. Verify notification was sent
    Notification::assertSentTo(
        $user,
        PriceDropNotification::class,
        function ($notification, $channels) use ($user) {
            return in_array('database', $channels) && 
                   $notification->toArray($user)['price'] == 90000;
        }
    );
});

test('it does not send a notification when flight price is above target price', function () {
    Notification::fake();

    $user = User::factory()->create();
    $search = FlightSearch::factory()->create([
        'user_id' => $user->id,
        'target_price' => 100000,
        'is_active' => true,
    ]);

    $expensiveFlight = new FlightDTO(
        airline_name: 'Avianca',
        airline_logo: 'logo.png',
        flight_number: 'AV123',
        price: 150000, // Above target
        departure_time: now()->addDays(1)->toDateTimeString(),
        arrival_time: now()->addDays(1)->addHours(1)->toDateTimeString(),
        origin_code: 'MDE',
        destination_code: 'BOG',
        duration: '1h',
        stops: '0',
        origin_name: 'Medellin',
        destination_name: 'Bogota'
    );

    $action = app(ProcessFlightsAction::class);
    $action->execute($search, [$expensiveFlight]);

    Notification::assertNothingSent();
});
