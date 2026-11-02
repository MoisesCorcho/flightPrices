<?php

namespace App\Livewire;

use App\Models\FlightSearch;
use Livewire\Attributes\Layout;
use Livewire\Component;

class AlertDetails extends Component
{
    public $alertId;

    public int $perPage = 5;

    public function mount($id = null)
    {
        $this->alertId = $id;
    }

    public function loadMore(): void
    {
        $this->perPage += 5;
    }

    protected function getSearch(): FlightSearch
    {
        $search = auth()->user()->searches()->find($this->alertId);

        if (! $search) {
            $notification = auth()->user()->notifications()->with('flightResult.flightSearch')->find($this->alertId);
            $search = $notification?->flightResult?->flightSearch;
        }

        if (! $search) {
            abort(404);
        }

        return $search;
    }

    public function getAlertProperty()
    {
        $search = $this->getSearch();

        $lastResult = $search->results()->orderBy('price', 'asc')->first();

        return (object) [
            'id' => $search->id,
            'origin' => $search->origin,
            'destination' => $search->destination,
            'route_name' => "{$search->origin} to {$search->destination}",
            'dates' => $search->date->format('M d, Y'),
            'price' => $lastResult?->price ? number_format($lastResult->price, 0, ',', '.') : 'N/A',
            'raw_price' => $lastResult?->price,
            'airline_name' => $lastResult?->airline ?? 'N/A',
            'airline_logo' => $lastResult?->airline_logo ?? null,
            'flight_number' => $lastResult?->flight_number ?? 'N/A',
            'price_drop' => 'Monitoring',
            'status_time' => $lastResult?->created_at?->diffForHumans(),
            'status' => 'Last checked: '.($lastResult?->created_at?->diffForHumans() ?? 'Never'),
            'outbound' => (object) [
                'time' => $lastResult?->departure_time ?? 'N/A',
                'location' => $lastResult?->origin_name ?? $search->origin,
                'code' => $search->origin,
                'duration' => $lastResult?->duration ?? 'N/A',
                'stops' => $lastResult?->stops ?? 'N/A',
                'arrivalTime' => $lastResult?->arrival_time ?? 'N/A',
                'arrivalLocation' => $lastResult?->destination_name ?? $search->destination,
                'arrivalCode' => $search->destination,
            ],
            'return' => null,
        ];
    }

    public function getCheckHistoryProperty()
    {
        $search = $this->getSearch();

        $results = $search->results()
            ->orderBy('created_at', 'desc')
            ->orderBy('price', 'asc')
            ->get();

        if ($results->isEmpty()) {
            return (object) [
                'checks' => collect([]),
                'total_checks' => 0,
                'has_more' => false,
                'min_price' => null,
                'max_price' => null,
                'avg_price' => null,
            ];
        }

        // Group results by execution timestamp (down to the minute)
        $grouped = $results->groupBy(function ($item) {
            return $item->created_at->format('Y-m-d H:i');
        });

        $totalChecks = $grouped->count();

        $pagedChecks = $grouped->take($this->perPage)->map(function ($group, $timestamp) use ($search) {
            $first = $group->first();
            $date = $first->created_at;
            $minPrice = $group->min('price');

            return (object) [
                'id' => md5($timestamp),
                'timestamp' => $timestamp,
                'date_formatted' => $date->format('d M Y, h:i A'),
                'diff_for_humans' => $date->diffForHumans(),
                'lowest_price' => number_format($minPrice, 0, ',', '.'),
                'raw_lowest_price' => $minPrice,
                'options_count' => $group->count(),
                'flights' => $group->map(function ($flight) use ($search, $minPrice) {
                    return (object) [
                        'id' => $flight->id,
                        'airline_name' => $flight->airline,
                        'airline_logo' => $flight->airline_logo,
                        'flight_number' => $flight->flight_number,
                        'price' => number_format($flight->price, 0, ',', '.'),
                        'departure_time' => $flight->departure_time,
                        'arrival_time' => $flight->arrival_time,
                        'duration' => $flight->duration,
                        'stops' => $flight->stops,
                        'origin_code' => $search->origin,
                        'destination_code' => $search->destination,
                        'cheapest' => $flight->price == $minPrice,
                    ];
                })->values(),
            ];
        })->values();

        return (object) [
            'checks' => $pagedChecks,
            'total_checks' => $totalChecks,
            'has_more' => $totalChecks > $this->perPage,
            'min_price' => number_format($results->min('price'), 0, ',', '.'),
            'max_price' => number_format($results->max('price'), 0, ',', '.'),
            'avg_price' => number_format(round($results->avg('price')), 0, ',', '.'),
        ];
    }

    #[Layout('layouts.pwa')]
    public function render()
    {
        return view('livewire.alert-details', [
            'alert' => $this->alert,
            'history' => $this->checkHistory,
        ]);
    }
}
