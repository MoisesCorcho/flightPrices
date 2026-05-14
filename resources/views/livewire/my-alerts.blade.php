<div class="bg-background text-on-background min-h-screen pb-32">
    <x-ui.top-app-bar title="Flight Alerts">
    </x-ui.top-app-bar>

    <main class="pt-24 px-container-padding-mobile max-w-max-width mx-auto">
        <!-- Screen Header -->
        <div class="mb-8">
            <h2 class="font-headline-lg text-on-surface mb-2">
                My Alerts
            </h2>
            <p class="font-body-md text-on-surface-variant">
                Tracking {{ count($this->alerts) }} active flight routes for price drops.
            </p>
        </div>

        <!-- Alerts Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">
            @foreach($this->alerts as $alert)
                <x-flight.card variant="alert" :flight="$alert" />
            @endforeach
        </div>
    </main>

    <x-ui.bottom-nav active="alerts" />
</div>
