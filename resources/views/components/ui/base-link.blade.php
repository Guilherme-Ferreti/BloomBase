@props(['href' => '#'])

@php
    $isActive = url()->current() === url($href);
@endphp

<a
    href="{{ $href }}"
    @if ($isActive) aria-current="page" @endif
    {{
        $attributes->class([
            'min-h-11 inline-flex items-center rounded-md transition-colors focus:ring-2 focus:outline-none',
        ])
    }}
>
    {{ $slot }}
</a>
