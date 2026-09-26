<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Bolli Rental CRM') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-[#f5f8fc]">
            @include('layouts.navigation')

            <div class="lg:pl-72">
                @isset($header)
                    <header class="border-b border-slate-200/80 bg-white/90 backdrop-blur">
                        <div class="mx-auto max-w-[1600px] px-4 py-5 sm:px-6 lg:px-10 lg:py-7">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main>{{ $slot }}</main>

                <footer class="mx-auto flex max-w-[1600px] items-center justify-between px-4 pb-8 pt-2 text-xs text-slate-400 sm:px-6 lg:px-10">
                    <span>© {{ date('Y') }} Bolli Rental</span>
                    <span>Customer Operations</span>
                </footer>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
