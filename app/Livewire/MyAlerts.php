<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

class MyAlerts extends Component
{
    public function getAlertsProperty()
    {
        return auth()->user()->searches()
            ->with(['results' => fn ($q) => $q->orderBy('price', 'asc')->limit(1)])
            ->latest()
            ->get()
            ->map(function ($search) {
                $lastResult = $search->results->first();

                return (object) [
                    'route_code' => "{$search->origin} → {$search->destination}",
                    'route_name' => "{$search->origin} to {$search->destination}",
                    'trend' => 'stable', // Logic to determine trend could be added
                    'trend_value' => 'Stable',
                    'current_price' => $lastResult?->price ?? 'N/A',
                    'old_price' => null,
                    'dates' => $search->date->format('M d, Y'),
                    'details_url' => route('alerts.show', ['id' => $search->id]),
                ];
            });
    }

    #[Layout('layouts.pwa')]
    public function render()
    {
        return view('livewire.my-alerts');
    }
}
