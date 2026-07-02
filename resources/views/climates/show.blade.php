<x-layouts.app :title="$climate->name">
    <div>
        <h1 class="text-text-primary text-3xl font-bold tracking-tight">{{ $climate->name }}</h1>

        <p class="text-text-muted mt-2">{{
            __('Flowers that thrive in :climate climates.', [
                'climate' => $climate->name,
            ])
        }}</p>

        <div class="mt-6">
            <livewire:search-flowers :climate-id="$climate->id" />
        </div>
    </div>
</x-layouts.app>
