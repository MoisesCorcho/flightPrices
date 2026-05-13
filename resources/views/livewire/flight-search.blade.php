<div class="flex-grow flex flex-col items-center justify-center px-container-padding-mobile pt-24 pb-32 max-w-max-width mx-auto w-full">
    <x-ui.top-app-bar title="Flight Alerts">
        <x-slot:actions>
            <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-primary-fixed">
                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBUOQmAmrUA1Y8Er69N6V_HkErODFFsQ_4JK6gdS1Wo0_8I8jPiBswdLtwKxNW4p2rdoU35hBUarCDQwWamJbVDzznMBFNnzzr6kke9LBOmEVs4V1pygKmtNiWcQ4hSlMIui85mr4YwFT0sD7ichLSOt0zyh5qZFguC-VZUTIKzOw_k9Anni6RTGYWxRoMUIdKsyashFPCz5doiHvO99-VN6dqNlYUa9Znk4dV8ro4w6kxyNJKa-K29lyELT7VBXV4dB0yy-dPviwcU" alt="Profile" class="w-full h-full object-cover">
            </div>
        </x-slot:actions>
    </x-ui.top-app-bar>

    <!-- Hero Section -->
    <div class="w-full text-center mb-8">
        <h2 class="font-headline-lg mb-2 text-on-surface">
            Find your next journey
        </h2>
        <p class="font-body-md text-on-surface-variant">
            Track prices and get instant notifications on price drops.
        </p>
    </div>

    <!-- Search Card -->
    <div class="w-full max-w-lg bg-surface-container-lowest rounded-xl p-6 shadow-[0px_4px_20px_rgba(0,0,0,0.05)] border border-outline-variant/30">
        <form wire:submit="search" class="space-y-4">
            <!-- Origin & Destination Inputs with Swap -->
            <div class="relative flex flex-col gap-2">
                <x-ui.input wire:model="origin" label="From" icon="flight_takeoff" placeholder="Origin City or Airport" />

                <!-- Swap Button -->
                <div class="absolute right-6 top-1/2 -translate-y-1/2 z-10">
                    <button type="button" class="bg-white shadow-md border border-outline-variant w-10 h-10 rounded-full flex items-center justify-center active:scale-90 transition-transform text-primary">
                        <span class="material-symbols-outlined">swap_vert</span>
                    </button>
                </div>

                <x-ui.input wire:model="destination" label="To" icon="flight_land" placeholder="Destination City or Airport" />
            </div>

            <!-- Date Picker -->
            <x-ui.input wire:model="date" label="Travel Date" icon="calendar_today" placeholder="Select date" type="date" />

            <!-- Primary CTA -->
            <x-ui.button type="submit" class="mt-4">
                <span class="material-symbols-outlined">search</span>
                Search Flights
            </x-ui.button>
        </form>
    </div>

    <x-ui.bottom-nav active="search" />
</div>
