<section {{ $attributes->class(['max-w-2xl']) }}>
    <h1 class="text-text-primary text-4xl font-bold tracking-tight sm:text-5xl">
        {{ __('Discover the world of flowers') }}
    </h1>
    <p class="text-text-muted mt-4 text-lg">
        {{
            __(
                'Explore our curated collection of flowers from around the globe.',
            )
        }}
    </p>

    <form
        action="{{ route('flowers.index') }}"
        method="GET"
        class="mt-8"
        role="search"
    >
        <label
            for="home-search"
            class="sr-only"
            >{{ __('Search flowers') }}</label
        >
        <div class="flex items-center justify-center gap-2">
            <x-search-input
                id="home-search"
                name="q"
                placeholder="{{ __('Search flowers...') }}"
                class="max-w-md"
            />
            <button
                type="submit"
                class="bg-forest-700 hover:bg-forest-500 focus:ring-forest-500/20 rounded-lg px-6 py-2.5 text-sm font-medium text-white transition-colors focus:ring-2 focus:outline-none"
            >
                {{ __('Search') }}
            </button>
        </div>
    </form>
</section>
