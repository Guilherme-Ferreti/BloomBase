@props(['title'])

<h2
    {{
        $attributes->class([
            'text-text-primary text-2xl font-semibold',
        ])
    }}
>
    {{ $title }}
</h2>
