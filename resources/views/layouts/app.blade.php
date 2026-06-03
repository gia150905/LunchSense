<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-app-bg">
        <div class="min-h-screen text-text-main relative overflow-x-hidden">
            <!-- Decorative Background Gradients -->
            <div class="absolute top-0 left-0 w-full h-[600px] bg-gradient-to-br from-primary/15 via-app-bg to-accent/10 -z-10 pointer-events-none"></div>
            <div class="absolute top-[-10%] right-[-5%] w-[500px] h-[500px] rounded-full bg-primary/20 blur-[100px] -z-10 pointer-events-none animate-float"></div>
            <div class="absolute top-[20%] left-[-10%] w-[400px] h-[400px] rounded-full bg-accent/20 blur-[100px] -z-10 pointer-events-none animate-float" style="animation-delay: 2s;"></div>
            
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="pb-20">
                {{ $slot }}
            </main>
            
            <x-bottom-nav />
        </div>
    </body>
</html>
