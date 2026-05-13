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
        return redirect()->route('results', [
            'origin' => $this->origin,
            'destination' => $this->destination,
            'date' => $this->date,
        ]);
    }

    #[Layout('layouts.pwa')]
    public function render()
    {
        return view('livewire.flight-search');
    }
}
