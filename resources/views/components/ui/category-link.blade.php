@props(['label', 'href' => '#',])

<x-ui.base-link
    wire:navigate
    :href="$href"
    class="border-border text-text-primary hover:border-forest-500 hover:text-forest-500 focus:ring-forest-500/20 rounded-lg border bg-white px-6 py-3 text-sm font-medium"
>
    {{ $label }}
</x-ui.base-link>
