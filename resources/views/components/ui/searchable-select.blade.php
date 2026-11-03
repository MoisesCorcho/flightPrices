@props([
    'label' => null,
    'icon' => null,
    'placeholder' => '',
    'airports' => [],
])

@php
    $airportsJson = json_encode(collect($airports)->map(function ($airport) {
        return [
            'iata' => $airport->iata,
            'city' => $airport->city,
            'name' => $airport->name,
        ];
    })->values());
@endphp

<div 
    class="flex flex-col gap-1 w-full relative" 
    x-data="{
        open: false,
        search: '',
        selectedIata: @entangle($attributes->wire('model')),
        airports: {{ $airportsJson }},
        
        get selectedAirport() {
            if (!this.selectedIata) return null;
            return this.airports.find(a => a.iata.toUpperCase() === this.selectedIata.toUpperCase()) || null;
        },

        get filteredAirports() {
            if (!this.search.trim()) {
                return this.airports;
            }
            const q = this.search.toLowerCase().trim();
            return this.airports.filter(a => 
                (a.iata && a.iata.toLowerCase().includes(q)) ||
                (a.city && a.city.toLowerCase().includes(q)) ||
                (a.name && a.name.toLowerCase().includes(q))
            );
        },

        selectAirport(iata) {
            this.selectedIata = iata;
            this.open = false;
            this.search = '';
        },

        openDropdown() {
            this.open = true;
            this.$nextTick(() => {
                if (this.$refs.searchInput) {
                    this.$refs.searchInput.focus();
                }
            });
        }
    }"
    @click.outside="open = false; search = ''"
    @keydown.escape.window="open = false"
>
    @if($label)
        <label class="font-label-sm text-on-surface-variant px-1">{{ $label }}</label>
    @endif
    
    <!-- Trigger Button -->
    <button 
        type="button"
        @click="openDropdown()"
        class="group flex items-center justify-between bg-surface-container-low rounded-xl px-4 h-14 border border-transparent focus:border-primary transition-all cursor-pointer text-left w-full"
        :class="{ 'border-primary ring-2 ring-primary/20': open }"
    >
        <div class="flex items-center gap-3 min-w-0 flex-1">
            @if($icon)
                <span class="material-symbols-outlined text-on-surface-variant group-focus:text-primary shrink-0">{{ $icon }}</span>
            @endif
            <div class="truncate">
                <template x-if="selectedIata && selectedAirport">
                    <div class="flex items-center gap-2">
                        <span class="font-body-md font-semibold text-on-surface" x-text="selectedAirport.city"></span>
                        <span class="text-xs font-mono font-bold px-1.5 py-0.5 rounded bg-primary/15 text-primary" x-text="selectedAirport.iata"></span>
                    </div>
                </template>
                <template x-if="!selectedIata || !selectedAirport">
                    <span class="font-body-md text-outline" x-text="'{{ $placeholder }}'"></span>
                </template>
            </div>
        </div>
        <span 
            class="material-symbols-outlined text-on-surface-variant transition-transform duration-200 shrink-0 ml-2"
            :class="{ 'rotate-180 text-primary': open }"
        >
            expand_more
        </span>
    </button>

    <!-- Dropdown List -->
    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 translate-y-1 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-1 scale-95"
        class="absolute left-0 right-0 top-full mt-2 z-50 bg-surface-container-lowest rounded-2xl shadow-2xl border border-outline-variant/30 p-2 flex flex-col gap-2"
        style="display: none;"
    >
        <!-- Search Input -->
        <div class="relative flex items-center px-3 py-2 bg-surface-container-low rounded-xl border border-outline-variant/20 focus-within:border-primary">
            <span class="material-symbols-outlined text-on-surface-variant text-[20px] mr-2">search</span>
            <input 
                x-ref="searchInput"
                x-model="search"
                type="text"
                placeholder="{{ __('Buscar ciudad, aeropuerto o IATA (ej: Medellín, MDE)...') }}"
                class="bg-transparent border-none focus:outline-none focus:ring-0 w-full text-sm font-body-md text-on-surface placeholder:text-outline p-0"
                @keydown.escape.stop="open = false"
            >
            <button 
                x-show="search.length > 0" 
                @click="search = ''; $refs.searchInput.focus()" 
                type="button" 
                class="text-on-surface-variant hover:text-on-surface p-0.5 cursor-pointer"
            >
                <span class="material-symbols-outlined text-[16px]">close</span>
            </button>
        </div>

        <!-- Options Scrollable Container -->
        <div class="max-h-60 overflow-y-auto space-y-1 pr-1 overscroll-contain">
            <template x-for="airport in filteredAirports" :key="airport.iata">
                <button 
                    type="button"
                    @click="selectAirport(airport.iata)"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-left hover:bg-surface-container-low transition-colors group cursor-pointer"
                    :class="{ 'bg-primary/10 text-primary': selectedIata === airport.iata }"
                >
                    <div class="min-w-0 pr-2">
                        <p class="font-label-md font-semibold text-on-surface group-hover:text-primary transition-colors" x-text="airport.city"></p>
                        <p class="font-label-sm text-on-surface-variant text-xs truncate opacity-75" x-text="airport.name"></p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <span 
                            class="px-2 py-0.5 rounded font-mono text-xs font-bold transition-colors"
                            :class="selectedIata === airport.iata ? 'bg-primary text-on-primary' : 'bg-surface-container text-on-surface-variant group-hover:bg-primary/20 group-hover:text-primary'"
                            x-text="airport.iata"
                        ></span>
                        <span x-show="selectedIata === airport.iata" class="material-symbols-outlined text-primary text-[18px]">check</span>
                    </div>
                </button>
            </template>

            <div x-show="filteredAirports.length === 0" class="py-6 text-center text-on-surface-variant text-sm">
                <span class="material-symbols-outlined text-[28px] opacity-40 mb-1 block">search_off</span>
                <p>{{ __('No encontramos aeropuertos para') }} "<span class="font-semibold text-on-surface" x-text="search"></span>"</p>
            </div>
        </div>
    </div>
</div>
