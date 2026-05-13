<?php

namespace App\DTOs;

readonly class SearchCriteriaDTO
{
    public function __construct(
        public string $origin,
        public string $destination,
        public string $date,
        public string $currency = 'COP',
        public int $type = 2, // 2 for one way
    ) {}
}
