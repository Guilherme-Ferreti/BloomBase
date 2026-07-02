<x-layouts.app :title="$flower->common_name">
    <div class="mx-auto max-w-4xl">
        <div class="bg-forest-900/10 aspect-[21/9] overflow-hidden rounded-lg">
            <img
                src="{{ $flower->hero_image }}"
                alt="{{ $flower->common_name }}"
                class="size-full object-cover"
            />
        </div>

        <div class="mt-8">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h1 class="text-text-primary text-4xl font-bold tracking-tight">{{ $flower->common_name }}</h1>
                    <p class="text-text-muted mt-1 text-xl italic">{{ $flower->scientific_name }}</p>

                    @if ($flower->relationLoaded('family') && $flower->family)
                        <x-badge
                            variant="family"
                            class="mt-2"
                        >
                            {{ $flower->family->name }}
                        </x-badge>
                    @endif
                </div>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                @if ($flower->lifespan)
                    <x-badge variant="neutral"> {{ $flower->lifespan }} </x-badge>
                @endif

                @foreach ($flower->climates as $climate)
                    <x-badge variant="climate"> {{ $climate->name }} </x-badge>
                @endforeach

                @foreach ($flower->floweringSeasons as $season)
                    <x-badge variant="season"> {{ $season->name }} </x-badge>
                @endforeach
            </div>

            @if ($flower->description)
                <div class="prose prose-sm text-text-primary mt-8 max-w-none">
                    <h2 class="text-xl font-semibold">{{ __('Description') }}</h2>
                    <p>{{ $flower->description }}</p>
                </div>
            @endif

            @if (!empty($flower->images))
                <section class="mt-10">
                    <h2 class="text-text-primary text-xl font-semibold">{{ __('Gallery') }}</h2>
                    <div class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-3">
                        @foreach ($flower->images as $image)
                            <img
                                src="{{ $image['url'] }}"
                                alt="{{ $image['alt'] }}"
                                class="bg-forest-900/10 rounded-lg object-cover"
                                loading="lazy"
                            />
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($flower->interesting_facts)
                <section class="mt-10">
                    <h2 class="text-text-primary text-xl font-semibold">{{ __('Interesting Facts') }}</h2>
                    <div class="border-border mt-4 rounded-lg border bg-white p-6">
                        <p class="text-text-muted">{{ $flower->interesting_facts }}</p>
                    </div>
                </section>
            @endif

            @if ($flower->care_instructions)
                <section class="mt-10">
                    <h2 class="text-text-primary text-xl font-semibold">{{ __('Care Instructions') }}</h2>
                    <div class="prose prose-sm text-text-muted mt-4 max-w-none">
                        <p>{{ $flower->care_instructions }}</p>
                    </div>
                </section>
            @endif
        </div>
    </div>
</x-layouts.app>
