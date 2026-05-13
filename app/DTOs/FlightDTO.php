<?php

namespace App\DTOs;

readonly class FlightDTO
{
    public function __construct(
        public string $airline_name,
        public ?string $airline_logo,
        public string $flight_number,
        public float $price,
        public string $departure_time,
        public string $arrival_time,
        public string $origin_code,
        public string $destination_code,
        public string $duration,
        public ?string $stops,
        public bool $cheapest = false,
        public string $price_status = 'Price is Low',
        public ?string $origin_name = null,
        public ?string $destination_name = null,
    ) {}
}
