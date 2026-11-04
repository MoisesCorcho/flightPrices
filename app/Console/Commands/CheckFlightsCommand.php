<?php

namespace App\Console\Commands;

use App\Actions\FetchFlightsAction;
use App\Actions\ProcessFlightsAction;
use App\DTOs\SearchCriteriaDTO;
use App\Models\FlightSearch;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

#[Signature('flights:check {--force : Force check ignoring adaptive timing}')]
#[Description('Checks for flight price updates and notifies users.')]
class CheckFlightsCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(FetchFlightsAction $fetchAction, ProcessFlightsAction $processAction): int
    {
        $force = (bool) $this->option('force');

        $searches = FlightSearch::where('is_active', true)
            ->with('user')
            ->get()
            ->filter(fn ($search) => $force || $this->shouldUpdate($search));

        if ($searches->isEmpty()) {
            $this->info('No searches need updating at this time.');

            return self::SUCCESS;
        }

        // Group searches by unique route to avoid duplicate API calls
        $groupedSearches = $searches->groupBy(function ($search) {
            return "{$search->origin}-{$search->destination}-{$search->date->format('Y-m-d')}";
        });

        foreach ($groupedSearches as $routeKey => $searchesInGroup) {
            $firstSearch = $searchesInGroup->first();
            $this->info("Checking flights for route: {$routeKey}");

            try {
                $criteria = new SearchCriteriaDTO(
                    origin: $firstSearch->origin,
                    destination: $firstSearch->destination,
                    date: $firstSearch->date->format('Y-m-d'),
                );

                // This will use the cache if available (from FetchFlightsAction)
                // but since it's a scheduled job, we want fresh data if possible.
                // However, our cache strategy already handles deduplication.
                $flights = $fetchAction->execute($criteria);

                foreach ($searchesInGroup as $search) {
                    $processAction->execute($search, $flights);
                }

                $this->info('Processed '.count($searchesInGroup).' users for this route. Found '.count($flights).' flights.');
            } catch (\Throwable $e) {
                Log::error("Failed to check flights for route {$routeKey}: {$e->getMessage()}", [
                    'route' => $routeKey,
                    'exception' => $e,
                ]);

                $this->error("Failed to check flights for route {$routeKey}: {$e->getMessage()}");
            }
        }

        return self::SUCCESS;
    }

    protected function shouldUpdate(FlightSearch $search): bool
    {
        if ($search->date->endOfDay()->isPast()) {
            return false;
        }

        $daysToFlight = now()->diffInDays($search->date, false);
        $lastUpdate = $search->results()->latest()->first()?->created_at;

        if (! $lastUpdate) {
            return true;
        }

        $hoursSinceLastUpdate = abs(now()->diffInHours($lastUpdate));

        if ($daysToFlight < 7) {
            return $hoursSinceLastUpdate >= 6;
        }

        if ($daysToFlight < 30) {
            return $hoursSinceLastUpdate >= 12;
        }

        return $hoursSinceLastUpdate >= 24;
    }
}
