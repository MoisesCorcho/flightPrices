@props([
    'title' => '',
    'subtitle' => null,
    'showBack' => false,
    'backUrl' => '#',
    'actions' => null,
])

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

        <div class="flex items-center gap-2" x-data="{ 
            isDark: document.documentElement.classList.contains('dark'),
            toggle() {
                this.isDark = !this.isDark;
                if (this.isDark) {
                    document.documentElement.classList.add('dark');
                    document.documentElement.classList.remove('light');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    document.documentElement.classList.add('light');
                    localStorage.setItem('theme', 'light');
                }
            }
        }">
            {{ $actions }}
            
            <!-- Desktop Controls -->
            <div class="hidden sm:flex items-center gap-2">
                <select 
                    onchange="window.location.href = this.value"
                    class="bg-transparent border-none text-sm font-medium text-on-surface-variant focus:ring-0 cursor-pointer p-1"
                >
                    <option value="{{ route('language.switch', 'es') }}" {{ app()->getLocale() === 'es' ? 'selected' : '' }}>ES</option>
                    <option value="{{ route('language.switch', 'en') }}" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>EN</option>
                </select>

                <button 
                    @click="toggle()"
                    class="p-2 text-on-surface-variant hover:bg-surface-container rounded-full transition-colors flex items-center justify-center w-10 h-10"
                >
                    <template x-if="isDark">
                        <span class="material-symbols-outlined text-[20px]">light_mode</span>
                    </template>
                    <template x-if="!isDark">
                        <span class="material-symbols-outlined text-[20px]">dark_mode</span>
                    </template>
                </button>
            </div>

            <!-- Mobile Hamburger Menu -->
            <div class="sm:hidden">
                <flux:modal.trigger name="mobile-menu">
                    <flux:button variant="subtle" icon="bars-3" />
                </flux:modal.trigger>

                <flux:modal name="mobile-menu" class="min-w-64 space-y-6">
                    <flux:heading size="lg">{{ __('Settings') }}</flux:heading>

                    <!-- Language Section -->
                    <div class="space-y-3">
                        <flux:label>{{ __('Language') }}</flux:label>
                        <div class="grid grid-cols-2 gap-2">
                            <flux:button 
                                :href="route('language.switch', 'es')" 
                                :variant="app()->getLocale() === 'es' ? 'filled' : 'outline'"
                                size="sm"
                            >
                                ES
                                @if(app()->getLocale() === 'es') <flux:icon icon="check" variant="micro" class="ms-1" /> @endif
                            </flux:button>
                            <flux:button 
                                :href="route('language.switch', 'en')" 
                                :variant="app()->getLocale() === 'en' ? 'filled' : 'outline'"
                                size="sm"
                            >
                                EN
                                @if(app()->getLocale() === 'en') <flux:icon icon="check" variant="micro" class="ms-1" /> @endif
                            </flux:button>
                        </div>
                    </div>

                    <flux:separator />

                    <!-- Theme Section -->
                    <div class="space-y-3">
                        <flux:label>{{ __('Appearance') }}</flux:label>
                        <flux:button 
                            x-on:click="toggle()" 
                            variant="outline" 
                            class="w-full justify-between"
                        >
                            <span x-text="isDark ? '{{ __('Light Mode') }}' : '{{ __('Dark Mode') }}'"></span>
                            <span class="material-symbols-outlined text-[18px]" x-text="isDark ? 'light_mode' : 'dark_mode'"></span>
                        </flux:button>
                    </div>
                </flux:modal>
            </div>
        </div>
    </div>
</header>
