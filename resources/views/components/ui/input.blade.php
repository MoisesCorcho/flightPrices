@props([
    'label' => null,
    'icon' => null,
    'placeholder' => '',
])

<div class="flex flex-col gap-1 w-full">
    @if($label)
        <label class="font-label-sm text-on-surface-variant px-1 cursor-pointer" onclick="this.parentElement.querySelector('input').focus()">{{ $label }}</label>
    @endif
    
    <div 
        class="group flex items-center bg-surface-container-low rounded-xl px-4 h-14 border border-transparent focus-within:border-primary transition-all cursor-text"
        onclick="this.querySelector('input').focus(); if(this.querySelector('input').type === 'date') this.querySelector('input').showPicker()"
    >
        @if($icon)
            <span class="material-symbols-outlined text-on-surface-variant group-focus-within:text-primary mr-3 shrink-0">{{ $icon }}</span>
        @endif
        
        <input 
            {{ $attributes->merge([
                'class' => 'bg-transparent border-none focus:ring-0 focus:outline-none w-full h-full font-body-md text-on-surface placeholder:text-outline cursor-pointer',
                'placeholder' => $placeholder
            ]) }}
        >
    </div>
</div>
