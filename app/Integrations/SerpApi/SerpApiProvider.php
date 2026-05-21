<?php

namespace App\Integrations\SerpApi;

use App\Contracts\FlightProviderInterface;
use App\DTOs\SearchCriteriaDTO;
use App\Integrations\SerpApi\Requests\GetGoogleFlightsRequest;
use Illuminate\Support\Collection;

class SerpApiProvider implements FlightProviderInterface
{
    public function __construct(
        protected SerpApiConnector $connector
    ) {}

    public function searchFlights(SearchCriteriaDTO $criteria): Collection
    {
        $request = new GetGoogleFlightsRequest($criteria);
        $response = $this->connector->send($request);

        return collect($response->dto());
    }
}
