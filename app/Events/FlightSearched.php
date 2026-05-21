<?php

namespace App\Events;

use App\DTOs\FlightDTO;
use App\DTOs\SearchCriteriaDTO;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class FlightSearched
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  Collection<int, FlightDTO>  $results
     */
    public function __construct(
        public SearchCriteriaDTO $criteria,
        public Collection $results,
        public bool $fromCache = false
    ) {}
}
