<header x-data="{ open: false }">
    <nav
        class="bg-forest-700 text-white"
        aria-label="Main navigation"
    >
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
            <x-ui.base-link
                wire:navigate
                :href="route('home')"
                class="px-2 text-xl font-bold tracking-tight focus:ring-amber-400/50"
            >
                {{ config('app.name') }}
            </x-ui.base-link>

            <div class="hidden sm:flex sm:items-center sm:gap-6">
                <x-ui.base-link
                    wire:navigate
                    :href="route('flowers.index')"
                    class="px-3 text-sm font-medium hover:text-amber-400 focus:ring-amber-400/50"
                >
                    {{ __('Browse Flowers') }}
                </x-ui.base-link>
            </div>

            <button
                @click="open = !open"
                class="inline-flex min-h-11 min-w-11 items-center justify-center rounded-lg focus:ring-2 focus:ring-amber-400/50 focus:outline-none sm:hidden"
                aria-label="{{ __('Toggle navigation') }}"
                aria-expanded="false"
                :aria-expanded="open"
            >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path
                        x-show="!open"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"
                    />
                    <path
                        x-show="open"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>
        </div>

        <div
            x-show="open"
            @click.outside="open = false"
            @keydown.escape.window="open = false"
            class="border-t border-white/10 px-4 pt-2 pb-3 sm:hidden"
            x-cloak
        >
            <x-ui.base-link
                wire:navigate
                :href="route('flowers.index')"
                class="w-full px-3 text-sm font-medium hover:text-amber-400 focus:ring-amber-400/50"
                @click="open = false"
            >
                {{ __('Browse Flowers') }}
            </x-ui.base-link>
        </div>
    </nav>
</header>
