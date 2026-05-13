<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class PriceDropNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected $flightNotification
    ) {}

    public function via($notifiable): array
    {
        return [FcmChannel::class, 'database'];
    }

    public function toFcm($notifiable): FcmMessage
    {
        return FcmMessage::create()
            ->setNotification(FcmNotification::create()
                ->setTitle('¡Baja de precio detectada!')
                ->setBody("Vuelo de {$this->flightNotification->origin} a {$this->flightNotification->destination} por \${$this->flightNotification->price}"))
            ->setData([
                'url' => route('alerts.show', ['id' => $this->flightNotification->id]),
            ]);
    }

    public function toArray($notifiable): array
    {
        return [
            'notification_id' => $this->flightNotification->id,
            'origin' => $this->flightNotification->origin,
            'destination' => $this->flightNotification->destination,
            'price' => $this->flightNotification->price,
        ];
    }
}
