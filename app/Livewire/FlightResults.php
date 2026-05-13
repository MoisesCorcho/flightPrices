<?php

namespace App\Livewire;

use App\Actions\FetchFlightsAction;
use App\Actions\ProcessFlightsAction;
use App\DTOs\SearchCriteriaDTO;
use App\Models\FlightSearch;
use Livewire\Attributes\Layout;
use Livewire\Component;

class FlightResults extends Component
{
    public $origin = 'SFO';

    public $destination = 'LHR';

    public $date = 'Oct 24';

    public $passengers = 1;

    public $search_id;

    public function mount()
    {
        $this->origin = request('origin', $this->origin);
        $this->destination = request('destination', $this->destination);
        $this->date = request('date', $this->date);
        $this->search_id = request('search_id');
    }

    public function getFlightsProperty(FetchFlightsAction $fetchAction, ProcessFlightsAction $processAction)
    {
        try {
            $criteria = new SearchCriteriaDTO(
                origin: $this->origin,
                destination: $this->destination,
                date: $this->date,
            );

            $flights = $fetchAction->execute($criteria);

            // Persist results if we have a search_id
            if ($this->search_id) {
                $search = FlightSearch::find($this->search_id);
                if ($search) {
                    $processAction->execute($search, $flights);
                }
            }

            return $flights;
        } catch (\Exception $e) {
            session()->flash('error', 'Error fetching flights: '.$e->getMessage());

            return [];
        }
    }

    #[Layout('layouts.pwa')]
    public function render()
    {
        return view('livewire.flight-results');
    }
}
