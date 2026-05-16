<div class="flex-grow flex flex-col items-center px-container-padding-mobile pt-24 pb-32 max-w-max-width mx-auto w-full">
    <x-ui.top-app-bar :title="__('My Searches')" />

    <!-- Info Notice -->
    <div class="w-full mb-6">
        <div class="flex gap-4 p-4 bg-primary/10 border border-primary/20 rounded-2xl text-on-surface">
            <span class="material-symbols-outlined text-primary text-[24px] shrink-0">info</span>
            <p class="font-body-sm leading-relaxed">
                {{ __('Price Alerts Notice') }}
            </p>
        </div>
    </div>

    <div class="w-full space-y-4">
        @forelse($searches as $search)
            <div @class([
                'w-full bg-surface-container-lowest rounded-xl p-4 shadow-sm border transition-all',
                'border-primary/30 bg-primary/5' => $search->is_active,
                'border-outline-variant/30' => !$search->is_active,
            ])>
                <div class="flex justify-between items-start mb-4">
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2">
                            <div class="flex flex-col items-center">
                                <span class="font-headline-sm text-on-surface leading-none">{{ $search->origin }}</span>
                            </div>

                            <div class="flex items-center px-2">
                                <div class="h-[2px] w-6 bg-outline-variant/50 relative">
                                    <span class="material-symbols-outlined text-primary text-[16px] absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 bg-surface-container-lowest rounded-full p-0.5">flight_takeoff</span>
                                </div>
                            </div>

                            <div class="flex flex-col items-center">
                                <span class="font-headline-sm text-on-surface leading-none">{{ $search->destination }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 text-on-surface-variant">
                            <span class="material-symbols-outlined text-[18px] opacity-70">calendar_month</span>
                            <span class="font-title-small">{{ $search->date->format('M d, Y') }}</span>
                        </div>
                        </div>

                        <div class="flex flex-col items-end gap-2">
                        <button 
                            wire:click="toggleActive({{ $search->id }})"
                            @class([
                                'flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold transition-all shadow-sm',
                                'bg-primary text-on-primary ring-2 ring-primary/20' => $search->is_active,
                                'bg-surface-container-highest text-on-surface-variant' => !$search->is_active,
                            ])
                        >
                            <span @class(['material-symbols-outlined text-[16px]', 'fill-1' => $search->is_active])>
                                {{ $search->is_active ? 'notifications_active' : 'notifications_off' }}
                            </span>
                            {{ $search->is_active ? __('Active') : __('Inactive') }}
                        </button>

                        <button 
                            wire:click="deleteSearch({{ $search->id }})"
                            wire:confirm="{{ __('Are you sure you want to delete this search?') }}"
                            class="p-2 text-on-surface-variant/40 hover:text-error hover:bg-error/10 rounded-full transition-all"
                            title="{{ __('Delete Search') }}"
                        >
                            <span class="material-symbols-outlined text-[20px]">delete</span>
                        </button>
                        </div>
                        </div>
                <div class="flex items-center justify-between pt-4 border-t border-outline-variant/20">
                    <div class="flex flex-col">
                        <span class="font-label-sm text-on-surface-variant mb-0.5">{{ __('Target Price') }}</span>
                        @if($editingId === $search->id)
                            <div class="flex items-center gap-2">
                                <input
                                    type="number"
                                    wire:model="newTargetPrice"
                                    class="w-24 h-9 bg-surface-container-low border border-primary rounded-lg px-2 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all"
                                    step="0.01"
                                    autofocus
                                >
                                <button wire:click="saveTargetPrice" class="w-9 h-9 flex items-center justify-center bg-primary text-on-primary rounded-lg active:scale-90 transition-transform shadow-sm">
                                    <span class="material-symbols-outlined text-[20px]">check</span>
                                </button>
                                <button wire:click="cancelEdit" class="w-9 h-9 flex items-center justify-center bg-error/10 text-error rounded-lg active:scale-90 transition-transform">
                                    <span class="material-symbols-outlined text-[20px]">close</span>
                                </button>
                            </div>
                        @else
                            <div class="flex items-center gap-2 group">
                                <span class="font-headline-sm text-primary font-bold">
                                    ${{ number_format($search->target_price, 2) }}
                                </span>
                                <button wire:click="editTargetPrice({{ $search->id }})" class="p-1 rounded-full text-on-surface-variant opacity-40 group-hover:opacity-100 hover:bg-surface-container-high transition-all">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </button>
                            </div>
                        @endif
                    </div>

                    {{-- <a href="{{ route('results', ['search_id' => $search->id, 'origin' => $search->origin, 'destination' => $search->destination, 'date' => $search->date->format('Y-m-d')]) }}" wire:navigate class="flex items-center gap-1.5 px-4 py-2 bg-primary/10 text-primary rounded-xl font-label-lg active:scale-95 transition-all">
                        {{ __('Results') }}
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </a> --}}
                </div>
            </div>
        @empty
            <div class="w-full py-16 flex flex-col items-center justify-center text-center px-6 bg-surface-container-lowest rounded-3xl border border-dashed border-outline-variant/50">
                <div class="w-20 h-20 bg-surface-container-high rounded-full flex items-center justify-center mb-6 text-primary/40">
                    <span class="material-symbols-outlined text-5xl">search_off</span>
                </div>
                <h3 class="font-headline-small text-on-surface mb-2">{{ __('No searches found') }}</h3>
                <p class="font-body-medium text-on-surface-variant max-w-[280px]">
                    {{ __('Your search history will appear here. Start by looking for some flights!') }}
                </p>
                <x-ui.button href="{{ route('search') }}" wire:navigate class="mt-8 px-8">
                    {{ __('Go to Search') }}
                </x-ui.button>
            </div>
        @endforelse
    </div>

    <x-ui.bottom-nav active="searches" />
</div>
