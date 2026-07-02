@props(['title'])

<h1
    {{
        $attributes->class(
            'text-text-primary text-3xl font-bold tracking-tight',
        )
    }}
>
    {{ $title }}
</h1>
