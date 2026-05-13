<?php

namespace App\Livewire;

use App\Models\FlightSearch;
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
        $search = FlightSearch::with(['results' => fn ($q) => $q->latest()])
            ->findOrFail($this->alertId);

        $lastResult = $search->results->first();

        return (object) [
            'route_name' => "{$search->origin} to {$search->destination}",
            'dates' => $search->date->format('M d, Y'),
            'price' => $lastResult?->price ?? 'N/A',
            'price_drop' => 'Monitoring',
            'status' => 'Last checked: '.($lastResult?->created_at?->diffForHumans() ?? 'Never'),
            'outbound' => (object) [
                'time' => $lastResult?->departure_time ?? 'N/A',
                'location' => $search->origin,
                'code' => $search->origin,
                'duration' => $lastResult?->duration ?? 'N/A',
                'stops' => $lastResult?->stops ?? 'N/A',
                'arrivalTime' => $lastResult?->arrival_time ?? 'N/A',
                'arrivalLocation' => $search->destination,
                'arrivalCode' => $search->destination,
            ],
            'return' => null, // One-way for now as per requirements
        ];
    }

    #[Layout('layouts.pwa')]
    public function render()
    {
        return view('livewire.alert-details');
    }
}
