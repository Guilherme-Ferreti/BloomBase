<div class="flex flex-col items-center gap-16 text-center">
    <x-layout.hero class="mt-8" />

    <x-ui.section>
        <x-ui.section.title :title="__('Featured Flowers')" />
        <div
            class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4"
            role="list"
        >
            @foreach ($this->featuredFlowers as $flower)
                <x-ui.flower-card :flower="$flower->toArray()" />
            @endforeach
        </div>
    </x-ui.section>

    <x-ui.section class="space-y-4">
        <x-ui.section.title
            class="mb-6"
            :title="__('Browse by category')"
        />

        <div>
            <p class="mb-3 text-sm font-medium text-zinc-500">{{ __('By Family') }}</p>
            <div class="flex flex-wrap items-center justify-center gap-4">
                @foreach ($this->families as $family)
                    <x-ui.category-link
                        :href="route('families.show', $family)"
                        :label="$family->name"
                    />
                @endforeach
            </div>
        </div>

        <div>
            <p class="mb-3 text-sm font-medium text-zinc-500">{{ __('By Climate') }}</p>
            <div class="flex flex-wrap items-center justify-center gap-4">
                @foreach ($this->climates as $climate)
                    <x-ui.category-link
                        :href="route('climates.show', $climate)"
                        :label="$climate->name"
                    />
                @endforeach
            </div>
        </div>

        <div>
            <p class="mb-3 text-sm font-medium text-zinc-500">{{ __('By Season') }}</p>
            <div class="flex flex-wrap items-center justify-center gap-4">
                @foreach ($this->floweringSeasons as $season)
                    <x-ui.category-link
                        :href="route('flowering-seasons.show', $season)"
                        :label="$season->name"
                    />
                @endforeach
            </div>
        </div>
    </x-ui.section>
</div>
