<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            /* Fallback styles if Vite is not available */
            body {
                font-family: 'Figtree', sans-serif;
                background-color: #f9fafb;
                color: #374151;
            }
        </style>
    @endif
</head>
<body class="font-sans antialiased dark:bg-black dark:text-white/50">
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <!-- Header -->
                <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                    <div class="flex lg:justify-center lg:col-start-2">
                        <svg class="h-12 w-auto text-white lg:h-16 lg:text-[#FF2D20]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 62 65">
                            <path d="..." fill="currentColor" />
                        </svg>
                    </div>
                    @if (Route::has('login'))
                        <nav class="-mx-3 flex flex-1 justify-end">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                                    Log in
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                                        Register
                                    </a>
                                @endif
                            @endauth
                        </nav>
                    @endif
                </header>

                <!-- Main Content -->
                <main class="mt-6">
                    <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
                        <!-- Documentation Card -->
                        <a href="https://laravel.com/docs" class="flex flex-col items-start gap-6 rounded-lg bg-white p-6 shadow-md ring-1 ring-gray-200 transition hover:ring-gray-400 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:ring-zinc-700">
                            <div class="flex items-center gap-4">
                                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-[#FF2D20]/10">
                                    <svg class="w-6 h-6 text-[#FF2D20]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <path d="..." fill="currentColor" />
                                    </svg>
                                </div>
                                <h2 class="text-xl font-semibold text-black dark:text-white">Documentation</h2>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Laravel has wonderful documentation covering every aspect of the framework. Whether you are a newcomer or have prior experience with Laravel, we recommend reading our documentation from beginning to end.
                            </p>
                        </a>

                        <!-- Laracasts Card -->
                        <a href="https://laracasts.com" class="flex flex-col items-start gap-6 rounded-lg bg-white p-6 shadow-md ring-1 ring-gray-200 transition hover:ring-gray-400 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:ring-zinc-700">
                            <div class="flex items-center gap-4">
                                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-[#FF2D20]/10">
                                    <svg class="w-6 h-6 text-[#FF2D20]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <path d="..." fill="currentColor" />
                                    </svg>
                                </div>
                                <h2 class="text-xl font-semibold text-black dark:text-white">Laracasts</h2>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Laracasts offers thousands of video tutorials on Laravel, PHP, and JavaScript development. Check them out, see for yourself, and massively level up your development skills in the process.
                            </p>
                        </a>
                    </div>
                </main>

                <!-- Footer -->
                <footer class="py-16 text-center text-sm text-black dark:text-white/70">
                    Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                </footer>
            </div>
        </div>
    </div>
</body>
</html>