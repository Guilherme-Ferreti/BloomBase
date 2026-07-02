<div class="mx-auto max-w-4xl space-y-8">
    <div class="bg-forest-900/10 aspect-21/9 overflow-hidden rounded-lg">
        <img
            src="{{ $flower->hero_image }}"
            alt="{{ $flower->common_name }}"
            class="size-full object-cover"
        />
    </div>

    <div>
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="text-text-primary text-4xl font-bold tracking-tight">{{ $flower->common_name }}</h1>
                <p class="text-text-muted mt-1 text-xl italic">{{ $flower->scientific_name }}</p>

                <x-ui.badge
                    :text="$flower->family->name"
                    variant="family"
                    class="mt-2"
                />
            </div>
        </div>

        <div class="mt-6 flex flex-wrap gap-3">
            @if ($flower->lifespan)
                <x-ui.badge
                    :text="$flower->lifespan"
                    variant="neutral"
                />
            @endif

            @foreach ($flower->climates as $climate)
                <x-ui.badge
                    :text="$climate->name"
                    variant="climate"
                />
            @endforeach

            @foreach ($flower->floweringSeasons as $season)
                <x-ui.badge
                    :text="$season->name"
                    variant="season"
                />
            @endforeach
        </div>

        <div class="prose prose-sm text-text-primary mt-8 max-w-none">
            <h2 class="text-xl font-semibold">{{ __('Description') }}</h2>
            <p>{{ $flower->description }}</p>
        </div>

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
                <x-ui.text-panel
                    :text="$flower->interesting_facts"
                    class="mt-4"
                />
            </section>
        @endif

        @if ($flower->care_instructions)
            <section class="mt-10">
                <h2 class="text-text-primary text-xl font-semibold">{{ __('Care Instructions') }}</h2>
                <x-ui.text-panel
                    :text="$flower->care_instructions"
                    class="mt-4"
                />
            </section>
        @endif
    </div>
</div>
