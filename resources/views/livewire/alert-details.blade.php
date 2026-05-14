<div class="bg-background text-on-surface min-h-screen flex flex-col">
    <x-ui.top-app-bar :title="__('Alert Details')" show-back back-url="{{ route('alerts.index') }}" />

    <main class="flex-1 mt-16 pb-24 max-w-max-width mx-auto w-full px-container-padding-mobile pt-6 overflow-x-hidden">
        <!-- Hero Alert Card -->
        <section class="bg-surface-container-lowest rounded-xl p-5 sm:p-6 shadow-[0px_4px_20px_rgba(0,0,0,0.05)] mb-6 border border-outline-variant/10">
            <div class="flex flex-col gap-4 mb-8">
                <div class="flex justify-between items-start gap-4">
                    <div class="flex-1 min-w-0">
                        <h2 class="font-headline-lg-mobile sm:font-headline-lg text-on-surface truncate leading-tight">
                            {{ $this->alert->route_name }}
                        </h2>
                        <p class="font-body-md text-on-surface-variant">
                            {{ $this->alert->dates }}
                        </p>
                    </div>

                    <div class="text-right shrink-0">
                        <p class="font-display-price text-primary text-3xl sm:text-4xl">
                            ${{ $this->alert->price }}
                        </p>
                        <p class="font-label-sm text-outline mt-1">
                            {{ __('Last checked: :time', ['time' => $this->alert->status_time ?? 'Never']) }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 bg-surface-container-low rounded-xl border border-outline-variant/10">
                    <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center overflow-hidden shrink-0">
                        @if($this->alert->airline_logo)
                            <img src="{{ $this->alert->airline_logo }}" alt="{{ $this->alert->airline_name }}" class="w-8 h-8 object-contain">
                        @else
                            <span class="material-symbols-outlined text-outline text-xl">flight</span>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="font-label-md text-on-surface truncate">{{ $this->alert->airline_name }}</p>
                        <p class="font-label-sm text-on-surface-variant">{{ $this->alert->flight_number }}</p>
                    </div>
                    <div class="ml-auto">
                        <flux:badge variant="pill" size="sm" color="success">{{ __('Price is Low') }}</flux:badge>
                    </div>
                </div>
            </div>

            <!-- Route Details -->
            <div class="space-y-10 py-2">
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
    </main>

    <x-ui.bottom-nav active="alerts" />
</div>
