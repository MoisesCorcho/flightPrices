<div class="bg-background text-on-background min-h-screen pb-32">
    <x-ui.top-app-bar 
        :title="$origin . ' → ' . $destination" 
        :subtitle="$date . ' • ' . $passengers . ' Traveler'"
        show-back
        back-url="{{ route('search') }}"
    >
        <x-slot:actions>
            <button class="active:scale-95 transition-transform duration-150 text-primary">
                <span class="material-symbols-outlined">tune</span>
            </button>
        </x-slot:actions>
    </x-ui.top-app-bar>

    <main class="mt-20 px-container-padding-mobile max-w-max-width mx-auto">
        <!-- Search Summary & Filters Chips -->
        <div class="flex gap-2 mb-6 overflow-x-auto pb-2 no-scrollbar">
            <div class="bg-primary-container text-on-primary-container px-4 py-2 rounded-full font-label-md flex items-center gap-2 whitespace-nowrap">
                <span class="material-symbols-outlined text-[18px]">sort</span>
                Cheapest
            </div>
            <div class="bg-surface-container-high text-on-surface-variant px-4 py-2 rounded-full font-label-md flex items-center gap-2 whitespace-nowrap">
                Stops
                <span class="material-symbols-outlined text-[18px]">expand_more</span>
            </div>
            <div class="bg-surface-container-high text-on-surface-variant px-4 py-2 rounded-full font-label-md flex items-center gap-2 whitespace-nowrap">
                Airlines
                <span class="material-symbols-outlined text-[18px]">expand_more</span>
            </div>
        </div>

        <!-- Results List -->
        <div class="space-y-4">
            @foreach($this->flights as $flight)
                <x-flight.card variant="result" :flight="$flight" />
            @endforeach

            <!-- Skeleton Loading State Example -->
            <div class="bg-surface-container-lowest rounded-xl p-6 shadow-[0px_4px_20px_rgba(0,0,0,0.05)] opacity-50">
                <div class="flex justify-between items-start mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-surface-container-high animate-pulse"></div>
                        <div class="space-y-2">
                            <div class="w-32 h-6 bg-surface-container-high rounded animate-pulse"></div>
                            <div class="w-20 h-4 bg-surface-container-high rounded animate-pulse"></div>
                        </div>
                    </div>
                    <div class="w-24 h-10 bg-surface-container-high rounded animate-pulse"></div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="w-16 h-8 bg-surface-container-high rounded animate-pulse"></div>
                    <div class="w-32 h-2 bg-surface-container-high rounded animate-pulse"></div>
                    <div class="w-16 h-8 bg-surface-container-high rounded animate-pulse"></div>
                </div>
            </div>
        </div>
    </main>

    <x-ui.bottom-nav active="search" />
</div>
