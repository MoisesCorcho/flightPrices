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
    protected $signature = 'flights:test-notify {user? : User ID or email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a test price drop notification to a user device.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $identifier = $this->argument('user');

        $user = $identifier
            ? User::where('id', $identifier)->orWhere('email', $identifier)->first()
            : User::first();

        if (! $user) {
            $this->error('No user found in the database. Please create a user first.');

            return self::FAILURE;
        }

        $this->info("Target user: {$user->name} ({$user->email}) [ID: {$user->id}]");

        $deviceTokens = $user->deviceTokens;
        $tokenCount = $deviceTokens->count();

        if ($tokenCount === 0) {
            $this->warn("⚠️  El usuario no tiene ningún dispositivo registrado en la tabla 'device_tokens'.");
            $this->line('Para recibir notificaciones push en tu celular:');
            $this->line(' 1. Ingresá a la PWA o web desde tu dispositivo.');
            $this->line(" 2. Iniciá sesión con esta cuenta ({$user->email}).");
            $this->line(' 3. Aceptá el permiso de notificaciones del navegador.');
            $this->newLine();
            $this->comment('Despachando notificación para registrar en base de datos...');
        } else {
            $this->info("📱 Dispositivos/tokens registrados: {$tokenCount}");
        }

        try {
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

            $this->info('✅ ¡Notificación de prueba despachada exitosamente!');
            if ($tokenCount > 0) {
                $this->info('Revisá la pantalla de tu dispositivo.');
            }
        } catch (\Throwable $e) {
            $this->error("❌ Error al despachar la notificación FCM: {$e->getMessage()}");
            $this->line('Asegurate de que las credenciales de Firebase estén configuradas en tu .env.');

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
