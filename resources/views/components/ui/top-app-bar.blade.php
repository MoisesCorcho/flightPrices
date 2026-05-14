<header class="fixed top-0 w-full z-50 bg-surface/80 dark:bg-surface-container/80 backdrop-blur-xl shadow-sm border-b border-outline-variant/20">
    <div class="flex items-center justify-between px-container-padding-mobile h-16 w-full max-w-max-width mx-auto">
        <div class="flex items-center gap-4">
            @if($showBack)
                <a href="{{ $backUrl }}" class="active:scale-95 transition-transform duration-150 text-primary dark:text-primary-fixed">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
            @else
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary dark:text-primary-fixed" style="font-variation-settings: 'FILL' 1">flight_takeoff</span>
                    <span class="font-headline-md text-primary dark:text-primary-fixed hidden sm:inline">{{ config('app.name') }}</span>
                </div>
            @endif

            <div class="flex flex-col">
                <h1 @class([
                    'font-headline-lg-mobile tracking-tight',
                    'text-primary dark:text-primary-fixed' => !$showBack,
                    'text-on-surface' => $showBack
                ])>
                    {{ $title }}
                </h1>
                @if($subtitle)
                    <span class="font-label-sm text-on-surface-variant">{{ $subtitle }}</span>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-1 sm:gap-2">
            {{ $actions }}
            
            <!-- Language Switcher -->
            <flux:dropdown position="bottom" align="end">
                <flux:button variant="subtle" icon="language" class="hidden sm:flex" />
                <flux:menu>
                    <flux:menu.item :href="route('language.switch', 'es')">{{ __('Spanish') }}</flux:menu.item>
                    <flux:menu.item :href="route('language.switch', 'en')">{{ __('English') }}</flux:menu.item>
                </flux:menu>
            </flux:dropdown>

            <!-- Theme Toggle -->
            <button 
                onclick="toggleTheme()"
                class="p-2 text-on-surface-variant hover:bg-surface-container rounded-full transition-colors"
                aria-label="{{ __('Toggle Theme') }}"
            >
                <span class="material-symbols-outlined dark:hidden">dark_mode</span>
                <span class="material-symbols-outlined hidden dark:block">light_mode</span>
            </button>

            <!-- User Menu -->
            @auth
                <div class="hidden sm:block">
                    <x-desktop-user-menu />
                </div>
                
                <!-- Mobile Hamburger -->
                <div class="sm:hidden">
                    <flux:dropdown position="bottom" align="end">
                        <flux:button variant="subtle" icon="bars-3" />
                        <flux:menu>
                            <div class="px-3 py-2 border-b border-outline-variant/20 mb-2">
                                <p class="font-label-md text-on-surface">{{ auth()->user()->name }}</p>
                                <p class="font-label-sm text-on-surface-variant">{{ auth()->user()->email }}</p>
                            </div>
                            <flux:menu.item :href="route('dashboard')" icon="home" wire:navigate>{{ __('Dashboard') }}</flux:menu.item>
                            <flux:menu.item :href="route('profile.edit')" icon="user" wire:navigate>{{ __('Profile') }}</flux:menu.item>
                            <flux:menu.separator />
                            <flux:menu.item :href="route('language.switch', 'es')">{{ __('Spanish') }}</flux:menu.item>
                            <flux:menu.item :href="route('language.switch', 'en')">{{ __('English') }}</flux:menu.item>
                            <flux:menu.separator />
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle">{{ __('Log out') }}</flux:menu.item>
                            </form>
                        </flux:menu>
                    </flux:dropdown>
                </div>
            @endauth
        </div>
    </div>
</header>

<script>
    function toggleTheme() {
        const isDark = document.documentElement.classList.contains('dark');
        if (isDark) {
            document.documentElement.classList.remove('dark');
            document.documentElement.classList.add('light');
            localStorage.setItem('theme', 'light');
        } else {
            document.documentElement.classList.add('dark');
            document.documentElement.classList.remove('light');
            localStorage.setItem('theme', 'dark');
        }
    }
</script>
