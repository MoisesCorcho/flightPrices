<?php

namespace App\Contracts;

use App\DTOs\FlightDTO;
use App\DTOs\SearchCriteriaDTO;
use Illuminate\Support\Collection;

interface FlightProviderInterface
{
    /**
     * Search for flights based on the given criteria.
     *
     * @return Collection<int, FlightDTO>
     */
    public function searchFlights(SearchCriteriaDTO $criteria): Collection;
}
