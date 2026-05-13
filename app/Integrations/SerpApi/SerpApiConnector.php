<?php

namespace App\Integrations\SerpApi;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;

class SerpApiConnector extends Connector
{
    use AcceptsJson;

    public function resolveBaseUrl(): string
    {
        return 'https://serpapi.com';
    }

    protected function defaultQuery(): array
    {
        return [
            'api_key' => config('services.serpapi.key'),
            'engine' => 'google_flights',
            'hl' => 'es',
            'gl' => 'co',
        ];
    }
}
