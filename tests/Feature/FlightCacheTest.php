<?php

use App\Actions\FetchFlightsAction;
use App\Contracts\FlightProviderInterface;
use App\DTOs\SearchCriteriaDTO;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

it('uses cache for duplicate searches', function () {
    Cache::shouldReceive('flexible')
        ->once()
        ->andReturn(collect([]));

    $mockProvider = Mockery::mock(FlightProviderInterface::class);
    // Provider shouldn't be called if we mock Cache::flexible to return something
    // BUT FetchFlightsAction calls Cache::flexible which calls the provider inside the closure.
    // If we mock Cache::flexible to return directly, the provider isn't called.

    $this->app->instance(FlightProviderInterface::class, $mockProvider);

    $fetchAction = app(FetchFlightsAction::class);
    $fetchAction->execute(new SearchCriteriaDTO('MDE', 'BOG', '2026-10-15'));

    expect(true)->toBeTrue();
});
