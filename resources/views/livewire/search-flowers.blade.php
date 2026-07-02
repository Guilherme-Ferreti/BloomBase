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

    @if ($flowers->count() > 0)
        <div
            class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3"
            role="list"
        >
            @foreach ($flowers as $flower)
                <x-ui.flower-card
                    :flower="$flower"
                    show-scientific
                    :wire:key="$flower->id"
                />
            @endforeach
        </div>

        <div class="mt-8">{{ $flowers->links() }}</div>
    @else
        <div class="border-border rounded-lg border border-dashed py-16 text-center">
            <p class="text-text-muted text-lg">{{ __('No flowers found matching your search.') }}</p>
        </div>
    @endif
</div>
