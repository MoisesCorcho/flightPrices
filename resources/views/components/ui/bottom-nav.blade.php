@props(['active' => 'search'])

<nav class="fixed bottom-0 w-full z-50 pb-safe bg-surface/90 dark:bg-surface-container/90 backdrop-blur-lg shadow-[0px_-4px_20px_rgba(0,0,0,0.05)] border-t border-outline-variant/10">
    <div class="flex justify-around items-center h-16 w-full px-2 max-w-max-width mx-auto">
        <a
            href="{{ route('search') }}"
            @class([
                'flex flex-col items-center justify-center transition-all duration-200 active:scale-90 px-4 py-1 rounded-full',
                'text-primary dark:text-primary-fixed font-bold bg-primary-fixed dark:bg-primary-container' => $active === 'search',
                'text-on-surface-variant hover:bg-surface-container-high' => $active !== 'search',
            ])
            wire:navigate
        >
            <span @class(['material-symbols-outlined', 'fill-1' => $active === 'search'])>search</span>
            <span class="font-label-sm">{{ __('Search') }}</span>
        </a>

        <a
            href="{{ route('searches.index') }}"
            @class([
                'flex flex-col items-center justify-center transition-all duration-200 active:scale-90 px-4 py-1 rounded-full',
                'text-primary dark:text-primary-fixed font-bold bg-primary-fixed dark:bg-primary-container' => $active === 'searches',
                'text-on-surface-variant hover:bg-surface-container-high' => $active !== 'searches',
            ])
            wire:navigate
        >
            <span @class(['material-symbols-outlined', 'fill-1' => $active === 'searches'])>list_alt</span>
            <span class="font-label-sm">{{ __('Searches') }}</span>
        </a>

        <a
            href="{{ route('alerts.index') }}"
            @class([
                'flex flex-col items-center justify-center transition-all duration-200 active:scale-90 px-4 py-1 rounded-full',
                'text-primary dark:text-primary-fixed font-bold bg-primary-fixed dark:bg-primary-container' => $active === 'alerts',
                'text-on-surface-variant hover:bg-surface-container-high' => $active !== 'alerts',
            ])
            wire:navigate
        >
            <span @class(['material-symbols-outlined', 'fill-1' => $active === 'alerts'])>notifications_active</span>
            <span class="font-label-sm">{{ __('Alerts') }}</span>
        </a>
    </div>
</nav>

<style>
    .fill-1 {
        font-variation-settings: "FILL" 1;
    }
</style>
