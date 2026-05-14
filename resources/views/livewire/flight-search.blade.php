<div class="flex-grow flex flex-col items-center justify-center px-container-padding-mobile pt-24 pb-32 max-w-max-width mx-auto w-full">
    <x-ui.top-app-bar :title="__('Search Flights')" />

    <!-- Hero Section -->
    <div class="w-full text-center mb-8">
        <h2 class="font-headline-lg mb-2 text-on-surface">
            {{ __('Find your next journey') }}
        </h2>
        <p class="font-body-md text-on-surface-variant">
            {{ __('Track prices and get instant notifications on price drops.') }}
        </p>
    </div>

    <!-- Search Card -->
    <div class="w-full max-w-lg bg-surface-container-lowest rounded-xl p-6 shadow-[0px_4px_20px_rgba(0,0,0,0.05)] border border-outline-variant/30">
        <form wire:submit="search" class="space-y-4">
            <!-- Origin & Destination Inputs with Swap -->
            <div class="relative flex flex-col gap-2">
                <x-ui.input wire:model="origin" :label="__('From')" icon="flight_takeoff" placeholder="Origin City or Airport" />

                <!-- Swap Button -->
                <div class="absolute right-6 top-1/2 -translate-y-1/2 z-10">
                    <button type="button" class="bg-surface-container-highest shadow-md border border-outline-variant w-10 h-10 rounded-full flex items-center justify-center active:scale-90 transition-transform text-primary">
                        <span class="material-symbols-outlined">swap_vert</span>
                    </button>
                </div>

                <x-ui.input wire:model="destination" :label="__('To')" icon="flight_land" placeholder="Destination City or Airport" />
            </div>

            <!-- Date Picker -->
            <x-ui.input wire:model="date" :label="__('Travel Date')" icon="calendar_today" placeholder="Select date" type="date" />

            <!-- Primary CTA -->
            <x-ui.button type="submit" class="mt-4">
                <span class="material-symbols-outlined">search</span>
                {{ __('Search Flights') }}
            </x-ui.button>
        </form>
    </div>

    <x-ui.bottom-nav active="search" />
</div>
