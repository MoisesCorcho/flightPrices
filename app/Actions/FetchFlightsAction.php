<?php

namespace App\Actions;

use App\DTOs\SearchCriteriaDTO;
use App\Integrations\SerpApi\Requests\GetGoogleFlightsRequest;
use App\Integrations\SerpApi\SerpApiConnector;

class FetchFlightsAction
{
    public function execute(SearchCriteriaDTO $criteria): array
    {
        $connector = new SerpApiConnector;
        $request = new GetGoogleFlightsRequest($criteria);

        $response = $connector->send($request);

        return $response->dto();
    }
}
