<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

class MyAlerts extends Component
{
    public function getAlertsProperty()
    {
        return [
            (object) [
                'route_code' => 'MDE → MTR',
                'route_name' => 'Medellín to Montería',
                'trend' => 'down',
                'trend_value' => '-12%',
                'current_price' => '124',
                'old_price' => '142',
                'dates' => 'Oct 12 - Oct 18',
                'details_url' => route('alerts.show', ['id' => 1]),
            ],
            (object) [
                'route_code' => 'JFK → LHR',
                'route_name' => 'New York to London',
                'trend' => 'up',
                'trend_value' => '+5%',
                'current_price' => '589',
                'old_price' => null,
                'dates' => 'Dec 20 - Dec 27',
                'details_url' => route('alerts.show', ['id' => 2]),
            ],
            (object) [
                'route_code' => 'CDG → FCO',
                'route_name' => 'Paris to Rome',
                'trend' => 'stable',
                'trend_value' => 'Stable',
                'current_price' => '82',
                'old_price' => null,
                'dates' => 'Nov 04 - Nov 09',
                'details_url' => route('alerts.show', ['id' => 3]),
            ],
        ];
    }

    #[Layout('layouts.pwa')]
    public function render()
    {
        return view('livewire.my-alerts');
    }
}
