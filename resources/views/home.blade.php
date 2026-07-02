<x-layouts.app title="Home">
    <div class="flex flex-col items-center gap-16 text-center">
        <section class="max-w-2xl">
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

        @if ($featuredFlowers->isNotEmpty())
            <section>
                <h2 class="text-text-primary text-2xl font-semibold">{{ __('Featured Flowers') }}</h2>
                <div
                    class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4"
                    role="list"
                >
                    @foreach ($featuredFlowers as $flower)
                        <x-flower-card :flower="$flower" />
                    @endforeach
                </div>
            </section>
        @endif

        <section>
            <h2 class="text-text-primary text-2xl font-semibold">{{ __('Browse by category') }}</h2>
            <div class="mt-6 flex flex-wrap items-center justify-center gap-4">
                @foreach ($families as $family)
                    <x-category-link :href="route('families.show', $family)"> {{ $family->name }} </x-category-link>
                @endforeach
            </div>

            <div class="mt-4 flex flex-wrap items-center justify-center gap-4">
                @foreach ($climates as $climate)
                    <x-category-link :href="route('climates.show', $climate)"> {{ $climate->name }} </x-category-link>
                @endforeach
            </div>

            <div class="mt-4 flex flex-wrap items-center justify-center gap-4">
                @foreach ($seasons as $season)
                    <x-category-link :href="route('seasons.show', $season)"> {{ $season->name }} </x-category-link>
                @endforeach
            </div>
        </section>
    </div>
</x-layouts.app>
