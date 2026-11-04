<div class="bg-background text-on-surface min-h-screen flex flex-col">
    <x-ui.top-app-bar :title="__('Alert Details')" show-back back-url="{{ route('alerts.index') }}" />

    <main class="flex-1 mt-16 pb-24 max-w-max-width mx-auto w-full px-container-padding-mobile pt-6 overflow-x-hidden space-y-6">
        <!-- Hero Alert Card -->
        <section class="bg-surface-container-lowest rounded-2xl p-4 sm:p-6 shadow-[0px_4px_20px_rgba(0,0,0,0.05)] border border-outline-variant/10 space-y-4 mb-6">
            <!-- Header Row: Route + Price -->
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0 flex-1">
                    <h2 class="flex items-center gap-1.5 sm:gap-2 font-headline-lg-mobile sm:font-headline-lg font-bold text-on-surface tracking-tight" title="{{ $this->alert->route_name }}">
                        <span>{{ $this->alert->origin }}</span>
                        <span class="material-symbols-outlined text-primary text-[18px] sm:text-[22px] shrink-0">arrow_forward</span>
                        <span>{{ $this->alert->destination }}</span>
                        <span class="sr-only">{{ $this->alert->route_name }}</span>
                    </h2>
                    <div class="flex items-center gap-1.5 text-on-surface-variant font-body-sm mt-1">
                        <span class="material-symbols-outlined text-[16px] opacity-70">calendar_month</span>
                        <span>{{ $this->alert->dates }}</span>
                    </div>
                </div>

                <div class="text-right shrink-0">
                    <p class="font-display-price text-primary text-2xl sm:text-4xl font-extrabold leading-none">
                        ${{ $this->alert->price }}
                    </p>
                </div>
            </div>

            <!-- Tracking & Schedule Intel Bar (Full Width) -->
            <div class="bg-surface-container-low/80 rounded-xl p-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2 border border-outline-variant/10">
                <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-3 text-xs text-on-surface-variant">
                    <span class="inline-flex items-center gap-1 text-outline">
                        <span class="material-symbols-outlined text-[14px]">history</span>
                        {{ __('Last checked: :time', ['time' => $this->alert->status_time ?? 'Never']) }}
                    </span>
                    <span class="hidden sm:inline opacity-30">•</span>
                    <span class="inline-flex items-center gap-1 font-medium text-on-surface">
                        <span class="material-symbols-outlined text-[14px] text-primary">schedule</span>
                        {{ $this->alert->next_check_text }}
                    </span>
                </div>

                <div class="self-start sm:self-auto shrink-0">
                    <flux:badge variant="pill" size="sm" color="zinc">
                        {{ $this->alert->frequency_label }}
                    </flux:badge>
                </div>
            </div>

            <!-- Airline info snippet -->
            <div class="flex items-center gap-3 p-3 bg-surface-container-low rounded-xl border border-outline-variant/10">
                <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center overflow-hidden shrink-0">
                    @if($this->alert->airline_logo)
                        <img src="{{ $this->alert->airline_logo }}" alt="{{ $this->alert->airline_name }}" class="w-8 h-8 object-contain">
                    @else
                        <span class="material-symbols-outlined text-outline text-xl">flight</span>
                    @endif
                </div>
                <div class="min-w-0 flex-1">
                    <p class="font-label-md text-on-surface truncate">{{ $this->alert->airline_name }}</p>
                    <p class="font-label-sm text-on-surface-variant">{{ $this->alert->flight_number }}</p>
                </div>
                <div class="shrink-0">
                    <flux:badge variant="pill" size="sm" color="success">{{ __('Price is Low') }}</flux:badge>
                </div>
            </div>

            <!-- Route Details -->
            <div class="space-y-6 pt-2 border-t border-outline-variant/10">
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

        <!-- Metrics Summary Cards -->
        @if($history->total_checks > 0)
            <section class="grid grid-cols-3 gap-3">
                <div class="bg-surface-container-lowest rounded-xl p-3 text-center border border-outline-variant/10 shadow-sm">
                    <p class="font-label-sm text-on-surface-variant">{{ __('Mínimo') }}</p>
                    <p class="font-headline-md text-primary font-bold mt-0.5">${{ $history->min_price }}</p>
                </div>
                <div class="bg-surface-container-lowest rounded-xl p-3 text-center border border-outline-variant/10 shadow-sm">
                    <p class="font-label-sm text-on-surface-variant">{{ __('Promedio') }}</p>
                    <p class="font-headline-md text-on-surface font-semibold mt-0.5">${{ $history->avg_price }}</p>
                </div>
                <div class="bg-surface-container-lowest rounded-xl p-3 text-center border border-outline-variant/10 shadow-sm">
                    <p class="font-label-sm text-on-surface-variant">{{ __('Chequeos') }}</p>
                    <p class="font-headline-md text-on-surface font-semibold mt-0.5">{{ $history->total_checks }}</p>
                </div>
            </section>
        @endif

        <!-- Check History Section (Snapshots) -->
        <section class="space-y-3" x-data="{ openCheck: '{{ $history->checks->first()?->id ?? '' }}' }">
            <div class="flex items-center justify-between px-1">
                <div>
                    <h3 class="font-title-medium text-on-surface font-bold flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[20px]">history</span>
                        {{ __('Historial de Chequeos') }}
                    </h3>
                    <p class="font-body-sm text-on-surface-variant">
                        {{ __('Tarifas detectadas por aerolínea en cada ejecución automática.') }}
                    </p>
                </div>
            </div>

            @forelse($history->checks as $check)
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/20 overflow-hidden shadow-sm transition-all">
                    <!-- Check Header (Toggle button) -->
                    <button 
                        type="button"
                        @click="openCheck = (openCheck === '{{ $check->id }}' ? null : '{{ $check->id }}')"
                        class="w-full flex items-center justify-between p-4 text-left hover:bg-surface-container-low/50 transition-colors"
                    >
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[18px]">schedule</span>
                            </div>
                            <div class="min-w-0">
                                <p class="font-label-md text-on-surface font-bold truncate">
                                    {{ $check->date_formatted }}
                                </p>
                                <p class="font-label-sm text-on-surface-variant">
                                    {{ $check->diff_for_humans }} · {{ $check->options_count }} {{ __('opciones') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 shrink-0 ml-2">
                            <div class="text-right">
                                <span class="font-label-sm text-on-surface-variant block">{{ __('Desde') }}</span>
                                <span class="font-title-small font-bold text-primary">${{ $check->lowest_price }}</span>
                            </div>
                            <span 
                                class="material-symbols-outlined text-on-surface-variant transition-transform duration-200"
                                :class="{ 'rotate-180': openCheck === '{{ $check->id }}' }"
                            >
                                expand_more
                            </span>
                        </div>
                    </button>

                    <!-- Check Body (Airlines list) -->
                    <div 
                        x-show="openCheck === '{{ $check->id }}'"
                        x-collapse
                        class="border-t border-outline-variant/10 bg-surface-container-low/30 p-3 space-y-2.5"
                    >
                        @foreach($check->flights as $flight)
                            <div class="flex items-center justify-between p-3 rounded-lg bg-surface-container-lowest border border-outline-variant/10 shadow-xs">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-9 h-9 rounded-md bg-surface-container flex items-center justify-center overflow-hidden shrink-0">
                                        @if($flight->airline_logo)
                                            <img src="{{ $flight->airline_logo }}" alt="{{ $flight->airline_name }}" class="w-7 h-7 object-contain">
                                        @else
                                            <span class="material-symbols-outlined text-outline text-[18px]">flight</span>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="font-label-md text-on-surface font-semibold truncate">{{ $flight->airline_name }}</span>
                                            @if($flight->cheapest)
                                                <span class="px-1.5 py-0.2 rounded text-[10px] font-bold bg-success/15 text-success uppercase tracking-wider">
                                                    {{ __('Mejor precio') }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="font-label-sm text-on-surface-variant">
                                            {{ $flight->departure_time }} → {{ $flight->arrival_time }} · {{ $flight->duration }}
                                            @if($flight->stops)
                                                · {{ $flight->stops }}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="text-right shrink-0 ml-3">
                                    <span class="font-title-medium font-bold text-primary">${{ $flight->price }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="bg-surface-container-lowest rounded-xl p-6 text-center border border-outline-variant/10">
                    <span class="material-symbols-outlined text-outline text-[32px] mb-1">hourglass_empty</span>
                    <p class="font-label-md text-on-surface font-semibold">{{ __('Sin historial registrado aún') }}</p>
                    <p class="font-body-sm text-on-surface-variant mt-1">
                        {{ __('A medida que el comando programado se ejecute, aquí verás la evolución de todas las aerolíneas.') }}
                    </p>
                </div>
            @endforelse

            <!-- Load More Checks Button -->
            @if($history->has_more)
                <div class="pt-2 text-center">
                    <button 
                        type="button"
                        wire:click="loadMore"
                        wire:loading.attr="disabled"
                        class="px-5 py-2.5 rounded-full text-sm font-semibold bg-surface-container-high hover:bg-surface-container-highest text-primary border border-outline-variant/20 transition-all inline-flex items-center gap-2 cursor-pointer active:scale-95"
                    >
                        <span wire:loading.remove wire:target="loadMore" class="material-symbols-outlined text-[18px]">expand_more</span>
                        <span wire:loading wire:target="loadMore" class="material-symbols-outlined text-[18px] animate-spin">progress_activity</span>
                        {{ __('Cargar chequeos anteriores') }}
                    </button>
                </div>
            @endif
        </section>
    </main>

    <x-ui.bottom-nav active="alerts" />
</div>
