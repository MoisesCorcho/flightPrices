<?php

namespace App\Livewire;

use App\Models\Airport;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

class FlightSearch extends Component
{
    public $origin = '';

    public $destination = '';

    public $date = '';

    public function swap()
    {
        $temp = $this->origin;
        $this->origin = $this->destination;
        $this->destination = $temp;
    }

    public function search()
    {
        $this->validate([
            'origin' => 'required|string|size:3',
            'destination' => 'required|string|size:3',
            'date' => 'required|date|after_or_equal:today',
        ]);

        // Deactivate all previous searches
        \App\Models\FlightSearch::query()->update(['is_active' => false]);

        $search = \App\Models\FlightSearch::create([
            'user_id' => User::first()?->id ?? 1,
            'origin' => strtoupper($this->origin),
            'destination' => strtoupper($this->destination),
            'date' => $this->date,
            'is_active' => true,
        ]);

        return redirect()->route('results', [
            'search_id' => $search->id,
            'origin' => strtoupper($this->origin),
            'destination' => strtoupper($this->destination),
            'date' => $this->date,
        ]);
    }

    #[Layout('layouts.pwa')]
    public function render()
    {
        $airports = Airport::orderBy('city')->get();

        return view('livewire.flight-search', [
            'airports' => $airports,
        ]);
    }
}
