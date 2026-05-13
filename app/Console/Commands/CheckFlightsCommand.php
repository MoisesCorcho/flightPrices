<?php

namespace App\Console\Commands;

use App\Actions\FetchFlightsAction;
use App\Actions\ProcessFlightsAction;
use App\DTOs\SearchCriteriaDTO;
use App\Models\FlightSearch;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('flights:check')]
#[Description('Checks for flight price updates and notifies users.')]
class CheckFlightsCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(FetchFlightsAction $fetchAction, ProcessFlightsAction $processAction)
    {
        $searches = FlightSearch::where('is_active', true)->get();

        foreach ($searches as $search) {
            $this->info("Checking flights for: {$search->origin} -> {$search->destination} on {$search->date->format('Y-m-d')}");

            try {
                $criteria = new SearchCriteriaDTO(
                    origin: $search->origin,
                    destination: $search->destination,
                    date: $search->date->format('Y-m-d'),
                );

                $flights = $fetchAction->execute($criteria);
                $processAction->execute($search, $flights);

                $this->info('Found '.count($flights).' flights.');
            } catch (\Exception $e) {
                $this->error("Failed to check flights for search ID {$search->id}: {$e->getMessage()}");
            }
        }
    }
}
