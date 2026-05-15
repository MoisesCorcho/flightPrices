<div class="flex-grow flex flex-col items-center px-container-padding-mobile pt-24 pb-32 max-w-max-width mx-auto w-full">
    <x-ui.top-app-bar :title="__('My Searches')" />

    <div class="w-full space-y-4">
        @forelse($searches as $search)
            <div @class([
                'w-full bg-surface-container-lowest rounded-xl p-4 shadow-sm border transition-all',
                'border-primary/30 bg-primary/5' => $search->is_active,
                'border-outline-variant/30' => !$search->is_active,
            ])>
                <div class="flex justify-between items-start mb-3">
                    <div class="flex flex-col">
                        <span class="font-headline-sm text-on-surface flex items-center gap-2">
                            {{ $search->origin }} 
                            <span class="material-symbols-outlined text-outline">arrow_forward</span> 
                            {{ $search->destination }}
                        </span>
                        <span class="font-body-sm text-on-surface-variant flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                            {{ $search->date->format('M d, Y') }}
                        </span>
                    </div>

                    <button 
                        wire:click="toggleActive({{ $search->id }})"
                        @class([
                            'flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold transition-all',
                            'bg-primary text-on-primary' => $search->is_active,
                            'bg-surface-container-high text-on-surface-variant' => !$search->is_active,
                        ])
                    >
                        <span class="material-symbols-outlined text-[14px]">
                            {{ $search->is_active ? 'notifications_active' : 'notifications_off' }}
                        </span>
                        {{ $search->is_active ? __('Active') : __('Inactive') }}
                    </button>
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-outline-variant/20">
                    <div class="flex flex-col">
                        <span class="font-label-sm text-on-surface-variant">{{ __('Target Price') }}</span>
                        @if($editingId === $search->id)
                            <div class="flex items-center gap-2 mt-1">
                                <input 
                                    type="number" 
                                    wire:model="newTargetPrice" 
                                    class="w-24 h-8 bg-surface-container-low border border-primary rounded-md px-2 text-sm focus:ring-0"
                                    step="0.01"
                                >
                                <button wire:click="saveTargetPrice" class="text-primary active:scale-90">
                                    <span class="material-symbols-outlined text-[20px]">check</span>
                                </button>
                                <button wire:click="cancelEdit" class="text-error active:scale-90">
                                    <span class="material-symbols-outlined text-[20px]">close</span>
                                </button>
                            </div>
                        @else
                            <div class="flex items-center gap-2">
                                <span class="font-title-md text-primary font-bold">
                                    ${{ number_format($search->target_price, 2) }}
                                </span>
                                <button wire:click="editTargetPrice({{ $search->id }})" class="text-on-surface-variant opacity-60 hover:opacity-100 transition-opacity">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </button>
                            </div>
                        @endif
                    </div>

                    <a href="{{ route('results', ['search_id' => $search->id, 'origin' => $search->origin, 'destination' => $search->destination, 'date' => $search->date->format('Y-m-d')]) }}" wire:navigate class="text-primary flex items-center gap-1 font-label-md">
                        {{ __('Results') }}
                        <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                    </a>
                </div>
            </div>
        @empty
            <div class="w-full py-12 flex flex-col items-center justify-center text-center px-6">
                <div class="w-16 h-16 bg-surface-container-high rounded-full flex items-center justify-center mb-4 text-outline">
                    <span class="material-symbols-outlined text-4xl">search_off</span>
                </div>
                <h3 class="font-title-lg text-on-surface mb-1">{{ __('No searches found') }}</h3>
                <p class="font-body-md text-on-surface-variant max-w-[250px]">
                    {{ __('Your search history will appear here. Start by looking for some flights!') }}
                </p>
                <x-ui.button href="{{ route('search') }}" wire:navigate class="mt-6">
                    {{ __('Go to Search') }}
                </x-ui.button>
            </div>
        @endforelse
    </div>

    <x-ui.bottom-nav active="searches" />
</div>
