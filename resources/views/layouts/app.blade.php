<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    style="scrollbar-gutter: stable"
>
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
                str()
                    ->of($title)
                    ->append(' | ' . config('app.name'))
            }}
        </title>

        @fonts

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
    </head>
    <body class="bg-cream text-text-primary flex min-h-screen flex-col antialiased">
        <x-ui.skip-to-content />

        <x-layout.header />

        <main
            id="main-content"
            class="mx-auto w-full max-w-7xl flex-1 px-4 py-8 sm:px-6 lg:px-8"
        >
            {{ $slot }}
        </main>

        <x-layout.footer />
    </body>
</html>
