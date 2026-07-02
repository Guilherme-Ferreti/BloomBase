@props([
    'href' => '#',
])

<a
    href="{{ $href }}"
    {{
        $attributes->class([
            'border-border text-text-primary hover:border-forest-500 hover:text-forest-500',
            'rounded-lg border bg-white px-6 py-3 text-sm font-medium transition-colors',
        ])
    }}
>
    {{ $slot }}
</a>
