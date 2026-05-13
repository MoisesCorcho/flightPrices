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

<div class="relative pl-8 border-l-2 border-dashed border-outline-variant">
    <div @class([
        'absolute -left-[9px] top-0 w-4 h-4 rounded-full ring-4',
        'bg-primary ring-primary-fixed' => $type === 'outbound',
        'bg-outline ring-surface-container' => $type === 'return',
    ])></div>
    
    <div class="flex justify-between items-center mb-2">
        <span @class([
            'font-label-sm uppercase tracking-wider',
            'text-primary' => $type === 'outbound',
            'text-on-surface-variant' => $type === 'return',
        ])>{{ ucfirst($type) }}</span>
        <span class="font-label-sm text-on-surface-variant">{{ $duration }}</span>
    </div>

    <div class="flex justify-between">
        <div>
            <p class="font-headline-md text-headline-md">{{ $time }}</p>
            <p class="font-body-md text-on-surface-variant">{{ $code }} • {{ $location }}</p>
        </div>
        
        <div class="text-center px-4">
            <span class="material-symbols-outlined text-outline">
                {{ $type === 'outbound' ? 'flight_takeoff' : 'flight_land' }}
            </span>
            <div class="h-[2px] w-12 bg-outline-variant my-1"></div>
            <p class="text-[10px] font-bold text-outline uppercase">{{ $stops }}</p>
        </div>

        <div class="text-right">
            <p class="font-headline-md text-headline-md">{{ $arrivalTime }}</p>
            <p class="font-body-md text-on-surface-variant">{{ $arrivalCode }} • {{ $arrivalLocation }}</p>
        </div>
    </div>
</div>
