<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-base text-body">
        <!-- Page Heading -->
        <x-header />

        <!-- Page Content -->
        <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div>
                <p
                    class="flex items-center gap-2 font-mono text-sm font-medium tracking-widest text-gray-400 uppercase">
                    @yield('subtitle', '')
                </p>
                <h1 class="tracking-tighter text-balance text-2xl lg:text-5xl font-medium py-4">@yield('title')</h1>
                <p class="text-gray-400 text-base">
                    @yield('description', '')
                </p>
            </div>
            <div class="pt-6">
                @yield('content')
            </div>
        </main>
    </div>
</body>

</html>
