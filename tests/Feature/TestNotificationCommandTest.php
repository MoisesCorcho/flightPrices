<?php

use App\Models\DeviceToken;
use App\Models\User;
use App\Notifications\PriceDropNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

beforeEach(function () {
    Notification::fake();
});

test('fails gracefully when no users exist in database', function () {
    $this->artisan('flights:test-notify')
        ->expectsOutput('No user found in the database. Please create a user first.')
        ->assertFailed();
});

test('sends test notification to first user and warns if no device tokens exist', function () {
    $user = User::factory()->create(['email' => 'test@example.com']);

    $this->artisan('flights:test-notify')
        ->expectsOutputToContain('Target user: '.$user->name)
        ->expectsOutputToContain('El usuario no tiene ningún dispositivo registrado')
        ->expectsOutputToContain('¡Notificación de prueba despachada exitosamente!')
        ->assertSuccessful();

    Notification::assertSentTo($user, PriceDropNotification::class);
});

test('sends test notification to specific user by id or email with registered tokens', function () {
    $user1 = User::factory()->create(['email' => 'user1@example.com']);
    $user2 = User::factory()->create(['email' => 'user2@example.com']);

    DeviceToken::create([
        'user_id' => $user2->id,
        'token' => 'sample-fcm-token-123',
    ]);

    $this->artisan('flights:test-notify', ['user' => 'user2@example.com'])
        ->expectsOutputToContain('user2@example.com')
        ->expectsOutputToContain('Dispositivos/tokens registrados: 1')
        ->expectsOutputToContain('¡Notificación de prueba despachada exitosamente!')
        ->assertSuccessful();

    Notification::assertSentTo($user2, PriceDropNotification::class);
    Notification::assertNotSentTo($user1, PriceDropNotification::class);
});
