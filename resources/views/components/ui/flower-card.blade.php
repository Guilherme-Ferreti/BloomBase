@props(['flower', 'showScientific' => false])

<a
    wire:navigate
    href="{{ route('flowers.show', $flower) }}"
    role="listitem"
    {{
        $attributes->class([
            'group border-border overflow-hidden rounded-lg border bg-white text-left',
            'transition-shadow hover:shadow-lg',
        ])
    }}
>
    <div class="bg-forest-900/10 aspect-4/3 overflow-hidden">
        <img
            src="{{ $flower->hero_image }}"
            alt="{{ $flower->common_name }}"
            class="size-full object-cover transition-transform duration-300 group-hover:scale-105"
            loading="lazy"
        />
    </div>

    <div class="p-4">
        <h3 class="text-text-primary font-semibold">{{ $flower->common_name }}</h3>

        @if ($showScientific && $flower->scientific_name)
            <p class="text-text-muted mt-1 text-sm italic">{{ $flower->scientific_name }}</p>
        @endif

        <x-ui.badge
            variant="family"
            size="xs"
            class="mt-2"
        >
            {{ $flower->family->name }}
        </x-ui.badge>
    </div>
</a>
