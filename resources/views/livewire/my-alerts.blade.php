<div class="bg-background text-on-background min-h-screen pb-32">
    <x-ui.top-app-bar title="Flight Alerts">
        <x-slot:actions>
            <button class="hover:opacity-80 transition-opacity active:scale-95 transition-transform duration-150">
                <span class="material-symbols-outlined text-on-surface-variant">more_vert</span>
            </button>
        </x-slot:actions>
    </x-ui.top-app-bar>

    <main class="pt-24 px-container-padding-mobile max-w-max-width mx-auto">
        <!-- Screen Header -->
        <div class="mb-8">
            <h2 class="font-headline-lg text-on-surface mb-2">
                My Alerts
            </h2>
            <p class="font-body-md text-on-surface-variant">
                Monitoring {{ count($this->alerts) }} active flight routes for price drops.
            </p>
        </div>

        <!-- Alerts Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">
            @foreach($this->alerts as $alert)
                <x-flight.card variant="alert" :flight="$alert" />
            @endforeach
        </div>
    </main>

    <!-- FAB for adding alert -->
    <div class="fixed bottom-24 right-container-padding-mobile z-40">
        <button class="bg-primary text-on-primary w-14 h-14 rounded-2xl flex items-center justify-center shadow-lg active:scale-90 transition-transform">
            <span class="material-symbols-outlined">add</span>
        </button>
    </div>

    <x-ui.bottom-nav active="alerts" />
</div>
