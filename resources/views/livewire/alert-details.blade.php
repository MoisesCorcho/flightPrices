<div class="bg-background text-on-surface min-h-screen flex flex-col">
    <x-ui.top-app-bar title="Alert Details" show-back back-url="{{ route('alerts.index') }}">
        <x-slot:actions>
            <button class="active:scale-95 transition-transform duration-150 text-on-surface-variant p-2 hover:opacity-80 transition-opacity">
                <span class="material-symbols-outlined">share</span>
            </button>
            <button class="active:scale-95 transition-transform duration-150 text-on-surface-variant p-2 hover:opacity-80 transition-opacity">
                <span class="material-symbols-outlined">more_vert</span>
            </button>
        </x-slot:actions>
    </x-ui.top-app-bar>

    <main class="flex-1 mt-16 pb-24 max-w-max-width mx-auto w-full px-container-padding-mobile pt-6">
        <!-- Hero Alert Card -->
        <section class="bg-surface-container-lowest rounded-xl p-6 shadow-[0px_4px_20px_rgba(0,0,0,0.05)] mb-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <span class="inline-flex items-center gap-1 bg-tertiary-container text-on-tertiary-container px-3 py-1 rounded-full font-label-sm mb-2">
                        <span class="material-symbols-outlined text-[14px]">trending_down</span>
                        Price Drop -{{ $this->alert->price_drop }}
                    </span>
                    <h2 class="font-headline-lg-mobile text-on-surface">
                        {{ $this->alert->route_name }}
                    </h2>
                    <p class="font-body-md text-on-surface-variant">
                        {{ $this->alert->dates }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="font-display-price text-primary">
                        ${{ $this->alert->price }}
                    </p>
                    <p class="font-label-sm text-outline">
                        {{ $this->alert->status }}
                    </p>
                </div>
            </div>

            <!-- Route Details -->
            <div class="space-y-6">
                <x-flight.route-detail 
                    type="outbound" 
                    :time="$this->alert->outbound->time"
                    :location="$this->alert->outbound->location"
                    :code="$this->alert->outbound->code"
                    :duration="$this->alert->outbound->duration"
                    :stops="$this->alert->outbound->stops"
                    :arrival-time="$this->alert->outbound->arrivalTime"
                    :arrival-location="$this->alert->outbound->arrivalLocation"
                    :arrival-code="$this->alert->outbound->arrivalCode"
                />
                
                <x-flight.route-detail 
                    type="return" 
                    :time="$this->alert->return->time"
                    :location="$this->alert->return->location"
                    :code="$this->alert->return->code"
                    :duration="$this->alert->return->duration"
                    :stops="$this->alert->return->stops"
                    :arrival-time="$this->alert->return->arrivalTime"
                    :arrival-location="$this->alert->return->arrivalLocation"
                    :arrival-code="$this->alert->return->arrivalCode"
                />
            </div>
        </section>

        <!-- Action Buttons -->
        <div class="flex flex-col gap-3 mt-8">
            <x-ui.button>
                View on Airline Website
                <span class="material-symbols-outlined">open_in_new</span>
            </x-ui.button>
            <x-ui.button variant="secondary">
                Delete Alert
                <span class="material-symbols-outlined">delete</span>
            </x-ui.button>
        </div>
    </main>

    <x-ui.bottom-nav active="alerts" />
</div>
