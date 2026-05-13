@props([
    'title' => '',
    'subtitle' => null,
    'showBack' => false,
    'backUrl' => '#',
    'actions' => null, // slot for buttons
])

<header class="fixed top-0 w-full z-50 bg-surface/80 backdrop-blur-xl shadow-sm">
    <div class="flex items-center justify-between px-container-padding-mobile h-16 w-full max-w-max-width mx-auto">
        <div class="flex items-center gap-4">
            @if($showBack)
                <a href="{{ $backUrl }}" class="active:scale-95 transition-transform duration-150 text-primary">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
            @else
                <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1">flight_takeoff</span>
            @endif

            <div class="flex flex-col">
                <h1 class="font-headline-lg-mobile text-primary tracking-tight">
                    {{ $title }}
                </h1>
                @if($subtitle)
                    <span class="font-label-sm text-on-surface-variant">{{ $subtitle }}</span>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-2">
            {{ $actions }}
        </div>
    </div>
</header>
