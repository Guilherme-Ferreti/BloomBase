<x-layouts.app :title="$family->name">
    <div>
        <h1 class="text-text-primary text-3xl font-bold tracking-tight">{{ $family->name }}</h1>

        @if ($family->description)
            <p class="text-text-muted mt-2">{{ $family->description }}</p>
        @endif

        <div class="mt-6">
            <livewire:search-flowers :family-id="$family->id" />
        </div>
    </div>
</x-layouts.app>
