<div>
    <div class="mb-6">
        <label
            for="search-flowers"
            class="sr-only"
            >{{ __('Search flowers') }}</label
        >
        <x-search-input
            id="search-flowers"
            placeholder="{{ __('Search by common or scientific name...') }}"
            wire:model.live.debounce.300ms="search"
        />
    </div>

    @if (count($flowers) > 0)
        <div
            class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3"
            role="list"
        >
            @foreach ($flowers as $flower)
                <x-ui.flower-card
                    :flower="$flower"
                    show-scientific
                    :wire:key="$flower['id']"
                />
            @endforeach
        </div>

        @if ($hasMorePages)
            <x-ui.loading-indicator
                class="mt-8"
                wire:intersect.margin.100px="loadFlowers"
                :loadingLabel="__('Loading more flowers...')"
            />
        @endif
    @else
        <x-ui.empty-state :text="__('No flowers found matching your search.')" />
    @endif
</div>
