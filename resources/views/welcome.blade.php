<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Baby Tracker') }}</title>

        @include('partials.favicon')

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-blue-50/40 text-gray-800">
        <div class="min-h-screen flex flex-col">
            <header class="max-w-5xl w-full mx-auto px-6 py-8 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <x-application-logo class="w-9 h-9" />
                    <span class="text-xl font-semibold text-gray-800">Baby Tracker</span>
                </div>

                <nav class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" wire:navigate>
                            <x-secondary-button type="button">Dashboard</x-secondary-button>
                        </a>
                    @else
                        <a href="{{ route('login') }}" wire:navigate class="text-base font-medium text-gray-600 hover:text-blue-700 px-3 py-2">
                            Log in
                        </a>
                        <a href="{{ route('register') }}" wire:navigate>
                            <x-primary-button type="button">Get Started</x-primary-button>
                        </a>
                    @endauth
                </nav>
            </header>

            <main class="flex-1 flex items-center">
                <div class="max-w-5xl w-full mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 leading-tight">
                            Every feed, nap, and milestone — in one place.
                        </h1>
                        <p class="mt-5 text-lg text-gray-600">
                            Track your baby's weight, feeds, diapers, and sleep. Follow their growth on a chart, celebrate milestones, and get age-appropriate tips along the way.
                        </p>
                        <div class="mt-8 flex flex-wrap gap-3">
                            @auth
                                <a href="{{ route('dashboard') }}" wire:navigate>
                                    <x-primary-button type="button">Go to Dashboard</x-primary-button>
                                </a>
                            @else
                                <a href="{{ route('register') }}" wire:navigate>
                                    <x-primary-button type="button">Create your account</x-primary-button>
                                </a>
                                <a href="{{ route('login') }}" wire:navigate>
                                    <x-secondary-button type="button">Log in</x-secondary-button>
                                </a>
                            @endauth
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                        <x-card class="p-8">
                            <p class="text-base font-semibold text-amber-600">Feeding &amp; Sleep</p>
                            <p class="text-base text-gray-500 mt-2">Log feeds, diapers and sleep in seconds.</p>
                        </x-card>
                        <x-card class="p-8">
                            <p class="text-base font-semibold text-blue-700">Growth Chart</p>
                            <p class="text-base text-gray-500 mt-2">Watch your baby's weight trend over time.</p>
                        </x-card>
                        <x-card class="p-8">
                            <p class="text-base font-semibold text-violet-600">Milestones</p>
                            <p class="text-base text-gray-500 mt-2">Celebrate and record every first.</p>
                        </x-card>
                        <x-card class="p-8">
                            <p class="text-base font-semibold text-indigo-600">Parent's Guide</p>
                            <p class="text-base text-gray-500 mt-2">Tips tailored to your baby's age.</p>
                        </x-card>
                    </div>
                </div>
            </main>

            <footer class="max-w-5xl w-full mx-auto px-6 py-6 text-center text-sm text-gray-400">
                Made for keeping track of the little things.
            </footer>
        </div>
    </body>
</html>
