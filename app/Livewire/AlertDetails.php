<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

class AlertDetails extends Component
{
    public $alertId;

    public function mount($id = null)
    {
        $this->alertId = $id;
    }

    public function getAlertProperty()
    {
        return (object) [
            'route_name' => 'NYC to London',
            'dates' => 'Round trip • Sep 12 - Sep 19',
            'price' => '498',
            'price_drop' => '$142',
            'status' => 'Cheapest in 30 days',
            'outbound' => (object) [
                'time' => '10:20 PM',
                'location' => 'New York',
                'code' => 'JFK',
                'duration' => '7h 15m',
                'stops' => 'Non-stop',
                'arrivalTime' => '10:35 AM',
                'arrivalLocation' => 'London',
                'arrivalCode' => 'LHR',
            ],
            'return' => (object) [
                'time' => '2:15 PM',
                'location' => 'London',
                'code' => 'LHR',
                'duration' => '8h 05m',
                'stops' => 'Non-stop',
                'arrivalTime' => '5:20 PM',
                'arrivalLocation' => 'New York',
                'arrivalCode' => 'JFK',
            ],
        ];
    }

    #[Layout('layouts.pwa')]
    public function render()
    {
        return view('livewire.alert-details');
    }
}
