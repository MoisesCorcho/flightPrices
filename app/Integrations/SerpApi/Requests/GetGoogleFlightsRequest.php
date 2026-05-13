<?php

namespace App\Integrations\SerpApi\Requests;

use App\DTOs\FlightDTO;
use App\DTOs\SearchCriteriaDTO;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetGoogleFlightsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected SearchCriteriaDTO $criteria
    ) {}

    public function resolveEndpoint(): string
    {
        return '/search.json';
    }

    protected function defaultQuery(): array
    {
        return [
            'departure_id' => $this->criteria->origin,
            'arrival_id' => $this->criteria->destination,
            'outbound_date' => $this->criteria->date,
            'currency' => $this->criteria->currency,
            'type' => $this->criteria->type,
        ];
    }

    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();
        $flights = [];

        $bestFlights = $data['best_flights'] ?? [];
        $otherFlights = $data['other_flights'] ?? [];

        foreach ($bestFlights as $flightData) {
            $flights[] = $this->mapToDto($flightData, true);
        }

        foreach ($otherFlights as $flightData) {
            $flights[] = $this->mapToDto($flightData, false);
        }

        return array_filter($flights);
    }

    protected function mapToDto(array $flightData, bool $isBest): ?FlightDTO
    {
        $mainFlight = $flightData['flights'][0] ?? null;
        $lastFlight = end($flightData['flights']) ?: $mainFlight;

        if (! $mainFlight) {
            return null;
        }

        return new FlightDTO(
            airline_name: $mainFlight['airline'] ?? 'Unknown',
            airline_logo: $mainFlight['airline_logo'] ?? null,
            flight_number: $mainFlight['flight_number'] ?? 'N/A',
            price: (float) ($flightData['price'] ?? 0),
            departure_time: $mainFlight['departure_airport']['time'] ?? 'N/A',
            arrival_time: $lastFlight['arrival_airport']['time'] ?? 'N/A',
            origin_code: $mainFlight['departure_airport']['id'] ?? 'ORG',
            destination_code: $lastFlight['arrival_airport']['id'] ?? 'DST',
            origin_name: $mainFlight['departure_airport']['name'] ?? null,
            destination_name: $lastFlight['arrival_airport']['name'] ?? null,
            duration: (string) ($flightData['total_duration'] ?? 'N/A'),
            stops: count($flightData['flights'] ?? []) > 1
                ? (count($flightData['flights']) - 1).' stop(s)'
                : 'Non-stop',
            cheapest: $isBest,
            price_status: $isBest ? 'Price is Low' : 'Standard price',
        );
    }
}
