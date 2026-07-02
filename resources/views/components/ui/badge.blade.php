@props(['text', 'variant' => 'family', 'size' => 'sm',])

@php
    $colors = [
        'family' => 'bg-forest-500/10 text-forest-700',
        'season' => 'bg-forest-500/10 text-forest-700',
        'climate' => 'bg-amber-400/20 text-amber-700',
        'neutral' => 'border-border text-text-muted border bg-white',
    ];

    $sizes = [
        'xs' => 'px-2 py-0.5 text-xs rounded-full',
        'sm' => 'px-3 py-1 text-sm rounded-full',
        'md' => 'px-3 py-1.5 text-sm rounded-md',
    ];
@endphp

<span
    {{
        $attributes->class([
            'inline-block font-medium',
            $colors[$variant],
            $sizes[$size],
        ])
    }}
>
    {{ $text }}
</span>
