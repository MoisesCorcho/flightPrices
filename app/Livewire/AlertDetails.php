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
        $search = auth()->user()->searches()
            ->with(['results' => fn ($q) => $q->orderBy('price', 'asc')->limit(1)])
            ->findOrFail($this->alertId);

        $lastResult = $search->results->first();

        return (object) [
            'route_name' => "{$search->origin} to {$search->destination}",
            'dates' => $search->date->format('M d, Y'),
            'price' => $lastResult?->price ?? 'N/A',
            'airline_name' => $lastResult?->airline ?? 'N/A',
            'airline_logo' => $lastResult?->airline_logo ?? null,
            'flight_number' => $lastResult?->flight_number ?? 'N/A',
            'price_drop' => 'Monitoring',
            'status_time' => $lastResult?->created_at?->diffForHumans(),
            'status' => 'Last checked: '.($lastResult?->created_at?->diffForHumans() ?? 'Never'),
            'outbound' => (object) [
                'time' => $lastResult?->departure_time ?? 'N/A',
                'location' => $lastResult?->origin_name ?? $search->origin,
                'code' => $search->origin,
                'duration' => $lastResult?->duration ?? 'N/A',
                'stops' => $lastResult?->stops ?? 'N/A',
                'arrivalTime' => $lastResult?->arrival_time ?? 'N/A',
                'arrivalLocation' => $lastResult?->destination_name ?? $search->destination,
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
