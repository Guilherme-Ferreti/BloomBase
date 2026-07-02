<div>
    <x-ui.page.title :title="$floweringSeason->name" />

    <x-ui.page.subtitle :subtitle="__('Flowers that bloom in :season.', ['season' => $floweringSeason->name])" />

    <div class="mt-6">
        <livewire:search-flowers :season-id="$floweringSeason->id" />
    </div>
</div>
