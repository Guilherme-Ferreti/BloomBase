@props(['text'])

<div
    {{
        $attributes->class([
            'border-border rounded-lg border bg-white p-4',
        ])
    }}
>
    <p class="text-text-muted">{{ $text }}</p>
</div>
