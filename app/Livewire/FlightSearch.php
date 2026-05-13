<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

class FlightSearch extends Component
{
    public $origin = '';

    public $destination = '';

    public $date = '';

    public function search()
    {
        $this->validate([
            'origin' => 'required|string|max:10',
            'destination' => 'required|string|max:10',
            'date' => 'required|date|after_or_equal:today',
        ]);

        $search = \App\Models\FlightSearch::create([
            'user_id' => auth()->id(),
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
        return view('livewire.flight-search');
    }
}
