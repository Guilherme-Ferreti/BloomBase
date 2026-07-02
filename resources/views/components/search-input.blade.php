@props([
    'id' => 'search-input',
    'name' => null,
    'placeholder' => __('Search...'),
])

<input
    type="search"
    id="{{ $id }}"
    @if ($name) name="{{ $name }}" @endif
    placeholder="{{ $placeholder }}"
    {{
        $attributes->class([
            'border-border text-text-primary placeholder-text-muted',
            'focus:border-forest-500 focus:ring-forest-500/20',
            'w-full rounded-lg border bg-white px-4 py-2.5 text-sm',
            'focus:ring-2 focus:outline-none',
        ])
    }}
/>
