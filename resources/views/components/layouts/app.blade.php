<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1"
        />
        <meta
            name="csrf-token"
            content="{{ csrf_token() }}"
        />

        <title>
            {{
                isset($title)
                    ? "{$title} - " . config('app.name')
                    : config('app.name')
            }}
        </title>

        @fonts

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-cream text-text-primary flex min-h-screen flex-col antialiased">
        <a
            href="#main-content"
            class="focus:bg-forest-700 sr-only focus:not-sr-only focus:fixed focus:z-50 focus:m-3 focus:rounded-lg focus:px-4 focus:py-2 focus:text-white"
        >
            {{ __('Skip to content') }}
        </a>

        <nav
            class="bg-forest-700 text-white"
            aria-label="Main navigation"
        >
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
                <a
                    href="{{ route('home') }}"
                    class="text-xl font-bold tracking-tight"
                >
                    BloomBase
                </a>

                <button
                    type="button"
                    class="hover:bg-forest-500 inline-flex items-center justify-center rounded-md p-2 focus:ring-2 focus:ring-white focus:outline-none focus:ring-inset lg:hidden"
                    aria-controls="mobile-menu"
                    aria-expanded="false"
                    x-data="{ open: false }"
                    x-on:click="open = !open"
                    x-bind:aria-expanded="open"
                >
                    <span class="sr-only">{{ __('Open main menu') }}</span>
                    <svg
                        class="size-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        x-show="!open"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg
                        class="size-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        x-show="open"
                        x-cloak
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="hidden items-center gap-6 lg:flex">
                    <a
                        href="{{ route('flowers.index') }}"
                        class="text-sm font-medium transition-colors hover:text-amber-400"
                    >
                        {{ __('Browse Flowers') }}
                    </a>
                </div>
            </div>

            <div
                id="mobile-menu"
                class="lg:hidden"
                x-data="{ open: false }"
                x-show="open"
                x-cloak
                x-on:click.away="open = false"
            >
                <div class="space-y-1 px-4 pt-2 pb-3">
                    <a
                        href="{{ route('flowers.index') }}"
                        class="hover:bg-forest-500 block rounded-md px-3 py-2 text-base font-medium transition-colors"
                    >
                        {{ __('Browse Flowers') }}
                    </a>
                </div>
            </div>
        </nav>

        <main
            id="main-content"
            class="mx-auto w-full max-w-7xl flex-1 px-4 py-8 sm:px-6 lg:px-8"
        >
            {{ $slot }}
        </main>

        <footer class="border-border border-t bg-white py-6">
            <div class="text-text-muted mx-auto max-w-7xl px-4 text-center text-sm sm:px-6 lg:px-8">
                &copy; {{ date('Y') }} {{ config('app.name', 'BloomBase') }}. {{ __('All rights reserved.') }}
            </div>
        </footer>
    </body>
</html>
