@props([
    'variant' => 'primary', // primary, secondary, outline, danger
    'type' => 'button',
])

@php
    $baseClasses = 'w-full h-[52px] rounded-xl font-headline-md flex items-center justify-center gap-2 active:scale-95 transition-all duration-150 cursor-pointer';
    
    $variants = [
        'primary' => 'price-gradient text-on-primary shadow-lg hover:opacity-90',
        'secondary' => 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container-highest',
        'outline' => 'bg-transparent border border-outline-variant text-primary hover:bg-primary/5',
        'danger' => 'bg-error-container text-on-error-container hover:bg-error/10',
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

<button {{ $attributes->merge(['class' => $classes, 'type' => $type]) }}>
    {{ $slot }}
</button>
