<div>
    <x-ui.page.title :title="$family->name" />

    @if ($family->description)
        <x-ui.page.subtitle :subtitle="$family->description" />
    @endif

    <div class="mt-6">
        <livewire:search-flowers :family-id="$family->id" />
    </div>
</div>
