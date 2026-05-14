@props([
    'type' => 'outbound', // outbound, return
    'time' => '00:00 AM',
    'location' => 'City',
    'code' => 'ORG',
    'duration' => '0h 00m',
    'stops' => 'Non-stop',
    'isArrival' => false,
    'arrivalTime' => null,
    'arrivalLocation' => null,
    'arrivalCode' => null,
])

<div class="relative pl-6 sm:pl-8 border-l-2 border-dashed border-outline-variant">
    <div @class([
        'absolute -left-[9px] top-0 w-4 h-4 rounded-full ring-4',
        'bg-primary ring-primary-fixed dark:ring-primary-container' => $type === 'outbound',
        'bg-outline ring-surface-container dark:ring-surface-container-highest' => $type === 'return',
    ])></div>
    
    <div class="flex justify-between items-center mb-4">
        <span @class([
            'font-label-sm uppercase tracking-wider',
            'text-primary dark:text-primary-fixed' => $type === 'outbound',
            'text-on-surface-variant' => $type === 'return',
        ])>{{ __($type === 'outbound' ? 'Outbound' : 'Return') }}</span>
        <span class="font-label-sm text-on-surface-variant bg-surface-container-high px-2 py-0.5 rounded-full">{{ $duration }}</span>
    </div>

    <!-- Main Route Info -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 sm:gap-4">
        <!-- Departure -->
        <div class="flex-1">
            <div class="flex items-baseline gap-2">
                <p class="font-headline-lg-mobile sm:font-headline-md text-on-surface">{{ $time }}</p>
                <p class="font-headline-sm text-primary dark:text-primary-fixed">{{ $code }}</p>
            </div>
            <p class="font-body-sm text-on-surface-variant line-clamp-1" title="{{ $location }}">{{ $location }}</p>
        </div>
        
        <!-- Connection Visual -->
        <div class="flex sm:flex-col items-center gap-4 sm:gap-1">
            <div class="flex items-center gap-2 sm:flex-col sm:gap-0">
                <span class="material-symbols-outlined text-outline text-[20px] sm:text-[24px]">
                    {{ $type === 'outbound' ? 'flight_takeoff' : 'flight_land' }}
                </span>
                <div class="h-[1px] w-8 sm:h-8 sm:w-[1px] bg-outline-variant my-1"></div>
            </div>
            <p class="text-[10px] font-bold text-primary dark:text-primary-fixed uppercase tracking-tighter">{{ __($stops) }}</p>
        </div>

        <!-- Arrival -->
        <div class="flex-1 sm:text-right">
            <div class="flex items-baseline gap-2 sm:justify-end">
                <p class="font-headline-lg-mobile sm:font-headline-md text-on-surface">{{ $arrivalTime }}</p>
                <p class="font-headline-sm text-primary dark:text-primary-fixed">{{ $arrivalCode }}</p>
            </div>
            <p class="font-body-sm text-on-surface-variant line-clamp-1" title="{{ $arrivalLocation }}">{{ $arrivalLocation }}</p>
        </div>
    </div>
</div>
