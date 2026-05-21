<?php

namespace App\Actions;

use App\Contracts\FlightProviderInterface;
use App\DTOs\FlightDTO;
use App\DTOs\SearchCriteriaDTO;
use App\Events\FlightSearched;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class FetchFlightsAction
{
    public function __construct(
        protected FlightProviderInterface $provider
    ) {}

    /**
     * @return Collection<int, FlightDTO>
     */
    public function execute(SearchCriteriaDTO $criteria): Collection
    {
        $cacheKey = "flights:{$criteria->origin}:{$criteria->destination}:{$criteria->date}";

        // Use flexible cache: serve fresh for 6 hours, stale but refreshable for 12 hours.
        $results = Cache::flexible($cacheKey, [now()->addHours(6), now()->addHours(12)], function () use ($criteria) {
            return $this->provider->searchFlights($criteria);
        });

        FlightSearched::dispatch($criteria, $results);

        return $results;
    }
}
