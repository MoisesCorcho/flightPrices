<div class="bg-background text-on-surface min-h-screen flex flex-col">
    <x-ui.top-app-bar title="Alert Details" show-back back-url="{{ route('alerts.index') }}">
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
                    <div class="flex items-center gap-2 mt-3">
                        <div class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center overflow-hidden shrink-0">
                            @if($this->alert->airline_logo)
                                <img src="{{ $this->alert->airline_logo }}" alt="{{ $this->alert->airline_name }}" class="w-6 h-6 object-contain">
                            @else
                                <span class="material-symbols-outlined text-outline text-xl">flight</span>
                            @endif
                        </div>
                        <span class="font-label-md text-on-surface-variant">{{ $this->alert->airline_name }} • {{ $this->alert->flight_number }}</span>
                    </div>
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

                @if($this->alert->return)
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
                @endif
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
