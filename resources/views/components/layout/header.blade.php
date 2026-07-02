<header>
    <nav
        class="bg-forest-700 text-white"
        aria-label="Main navigation"
    >
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
            <a
                href="{{ route('home') }}"
                class="text-xl font-bold tracking-tight"
            >
                {{ config('app.name') }}
            </a>

            <div class="flex items-center gap-6">
                <a
                    href="{{ route('flowers.index') }}"
                    class="text-sm font-medium transition-colors hover:text-amber-400"
                >
                    {{ __('Browse Flowers') }}
                </a>
            </div>
        </div>
    </nav>
</header>
