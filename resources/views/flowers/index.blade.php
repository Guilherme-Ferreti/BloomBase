<x-layouts.app title="All Flowers">
    <h1 class="text-text-primary text-3xl font-bold tracking-tight">{{ __('All Flowers') }}</h1>

    <p class="text-text-muted mt-2">{{ __('Browse our collection of flowers.') }}</p>

    <div class="mt-6">
        <livewire:search-flowers />
    </div>
</x-layouts.app>
