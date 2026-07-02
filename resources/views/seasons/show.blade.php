<x-layouts.app :title="$floweringSeason->name">
    <div>
        <h1 class="text-text-primary text-3xl font-bold tracking-tight">{{ $floweringSeason->name }}</h1>

        <p class="text-text-muted mt-2">{{
            __('Flowers that bloom in :season.', [
                'season' => $floweringSeason->name,
            ])
        }}</p>

        <div class="mt-6">
            <livewire:search-flowers :season-id="$floweringSeason->id" />
        </div>
    </div>
</x-layouts.app>
