@props([
    'variant' => 'result', // result, alert
    'flight' => null,
])

@if($variant === 'result')
    <!-- Result Card -->
    <div class="bg-surface-container-lowest rounded-xl p-6 shadow-[0px_10px_30px_rgba(79,70,229,0.08)] relative border border-primary/10">
        @if($flight?->cheapest)
            <div class="absolute -top-3 left-6 bg-tertiary-container text-on-tertiary-container px-3 py-1 rounded-full font-label-sm flex items-center gap-1 uppercase tracking-wider">
                <span class="material-symbols-outlined text-[14px] fill-1">auto_awesome</span>
                Cheapest
            </div>
        @endif
        
        <div class="flex justify-between items-start mb-6 pt-2">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center overflow-hidden">
                    @if($flight?->airline_logo)
                        <img src="{{ $flight->airline_logo }}" alt="{{ $flight->airline_name }}" class="w-8 h-8 object-contain">
                    @else
                        <span class="material-symbols-outlined text-outline">flight</span>
                    @endif
                </div>
                <div>
                    <h3 class="font-headline-md">{{ $flight?->airline_name ?? 'Airline' }}</h3>
                    <p class="font-label-sm text-on-surface-variant">{{ $flight?->flight_number ?? 'Flight' }}</p>
                </div>
            </div>
            <div class="text-right">
                <span class="font-display-price text-primary">${{ $flight?->price ?? '0' }}</span>
                <p class="font-label-sm text-tertiary font-bold">
                    {{ $flight?->price_status ?? 'Price is Low' }}
                </p>
            </div>
        </div>

        <div class="flex items-center justify-between mb-6">
            <div class="flex-1">
                <p class="font-headline-md">{{ $flight?->departure_time ?? '00:00' }}</p>
                <p class="font-label-sm text-on-surface-variant">{{ $flight?->origin_code ?? 'ORG' }}</p>
            </div>
            <div class="flex-[2] flex flex-col items-center px-4">
                <p class="font-label-sm text-on-surface-variant mb-1">{{ $flight?->duration ?? '0h 00m' }}</p>
                <div class="w-full h-[2px] bg-outline-variant relative flex items-center justify-center">
                    <div class="w-2 h-2 rounded-full bg-outline absolute left-0"></div>
                    <div class="w-2 h-2 rounded-full bg-outline absolute right-0"></div>
                </div>
                <p class="font-label-sm text-on-surface-variant mt-1">{{ $flight?->stops ?? 'Non-stop' }}</p>
            </div>
            <div class="text-right flex-1">
                <p class="font-headline-md">{{ $flight?->arrival_time ?? '00:00' }}</p>
                <p class="font-label-sm text-on-surface-variant">{{ $flight?->destination_code ?? 'DST' }}</p>
            </div>
        </div>

        <x-ui.button>View Deal</x-ui.button>
    </div>
@else
    <!-- Alert Card -->
    <div class="bg-surface-container-lowest rounded-xl p-6 shadow-[0px_4px_20px_rgba(0,0,0,0.05)] flex flex-col gap-4 border border-outline-variant/10">
        <div class="flex justify-between items-start">
            <div class="flex flex-col">
                <span class="font-label-sm text-on-surface-variant mb-1">{{ $flight?->route_code ?? 'ORG → DST' }}</span>
                <h3 class="font-headline-md text-on-surface">{{ $flight?->route_name ?? 'Route Name' }}</h3>
            </div>
            
            @php
                $trendColor = match($flight?->trend ?? 'stable') {
                    'down' => 'bg-tertiary-fixed text-on-tertiary-fixed',
                    'up' => 'bg-error-container text-on-error-container',
                    default => 'bg-surface-container-high text-on-surface-variant',
                };
                $trendIcon = match($flight?->trend ?? 'stable') {
                    'down' => 'trending_down',
                    'up' => 'trending_up',
                    default => 'horizontal_rule',
                };
            @endphp

            <div class="{{ $trendColor }} px-3 py-1 rounded-full flex items-center gap-1">
                <span class="material-symbols-outlined text-[18px]">{{ $trendIcon }}</span>
                <span class="font-label-sm">{{ $flight?->trend_value ?? 'Stable' }}</span>
            </div>
        </div>

        <div class="flex items-baseline gap-2">
            <span @class(['font-display-price', 'text-primary' => ($flight?->trend ?? '') === 'down', 'text-on-surface' => ($flight?->trend ?? '') !== 'down'])>
                ${{ $flight?->current_price ?? '0' }}
            </span>
            @if($flight?->old_price)
                <span class="font-body-md text-on-surface-variant line-through">${{ $flight->old_price }}</span>
            @endif
        </div>

        <div class="flex items-center justify-between mt-2 pt-4 border-t border-surface-variant">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-on-surface-variant">calendar_month</span>
                <span class="font-label-md text-on-surface-variant">{{ $flight?->dates ?? 'Date Range' }}</span>
            </div>
            <a href="{{ $flight?->details_url ?? '#' }}" class="text-primary font-label-md hover:underline">
                Details
            </a>
        </div>
    </div>
@endif

<style>
    .fill-1 { font-variation-settings: "FILL" 1; }
</style>
