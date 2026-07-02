<div>
    <x-ui.page.title :title="$climate->name" />

    <x-ui.page.subtitle :subtitle="__('Flowers that thrive in :climate climates.', ['climate' => $climate->name])" />

    <div class="mt-6">
        <livewire:search-flowers :climate-id="$climate->id" />
    </div>
</div>
