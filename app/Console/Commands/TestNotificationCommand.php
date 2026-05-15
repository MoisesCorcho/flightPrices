<?php

namespace App\Console\Commands;

use App\Models\FlightNotification;
use App\Models\User;
use App\Notifications\PriceDropNotification;
use Illuminate\Console\Command;

class TestNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flights:test-notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a test price drop notification to the first user.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::first();

        if (! $user) {
            $this->error('No user found in the database. Please create a user first.');

            return;
        }

        $this->info("Sending test notification to: {$user->email}");

        // Create a dummy notification record for the notice
        $notificationData = FlightNotification::create([
            'user_id' => $user->id,
            'origin' => 'MDE',
            'destination' => 'BOG',
            'date' => now()->addDays(7),
            'airline' => 'Avianca (TEST)',
            'price' => 99000,
            'departure_time' => '10:00 AM',
            'arrival_time' => '11:00 AM',
            'duration' => '1h 0m',
        ]);

        $user->notify(new PriceDropNotification($notificationData));

        $this->info('Test notification sent successfully!');
        $this->info('Check your device or the database notifications table.');
    }
}
