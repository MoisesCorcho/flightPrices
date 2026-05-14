@props([
    'label' => null,
    'icon' => null,
    'placeholder' => '',
])

<div class="flex flex-col gap-1 w-full">
    @if($label)
        <label class="font-label-sm text-on-surface-variant px-1">{{ $label }}</label>
    @endif
    
    <div class="group flex items-center bg-surface-container-low rounded-xl px-4 h-14 border border-transparent focus-within:border-primary transition-all relative">
        @if($icon)
            <span class="material-symbols-outlined text-on-surface-variant group-focus-within:text-primary mr-3">{{ $icon }}</span>
        @endif
        
        <select 
            {{ $attributes->merge([
                'class' => 'bg-transparent border-none focus:ring-0 w-full font-body-md text-on-surface appearance-none pr-8 cursor-pointer dark:bg-zinc-900',
            ]) }}
        >
            <option value="" disabled selected class="bg-surface-container-low text-on-surface-variant">{{ $placeholder }}</option>
            {{ $slot }}
        </select>

        <span class="material-symbols-outlined absolute right-4 pointer-events-none text-on-surface-variant group-focus-within:text-primary">
            expand_more
        </span>
    </div>
</div>
