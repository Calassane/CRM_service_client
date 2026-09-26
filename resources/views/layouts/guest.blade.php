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
    <body class="font-sans text-slate-900 antialiased">
        <div class="grid min-h-screen bg-white lg:grid-cols-[1.05fr_.95fr]">
            <section class="relative hidden overflow-hidden bg-slate-950 p-12 text-white lg:flex lg:flex-col lg:justify-between xl:p-16">
                <div class="absolute -right-32 -top-32 h-96 w-96 rounded-full border border-brand-400/20"></div>
                <div class="absolute -right-16 -top-16 h-96 w-96 rounded-full border border-brand-400/20"></div>
                <div class="absolute bottom-0 left-0 h-72 w-72 -translate-x-1/3 translate-y-1/3 rounded-full bg-brand-600/20 blur-3xl"></div>

                <a href="/" class="relative flex items-center gap-3">
                    <x-application-logo class="h-11 w-11 text-brand-500" />
                    <div>
                        <p class="text-xl font-extrabold tracking-tight">bolli.</p>
                        <p class="text-[0.65rem] font-bold uppercase tracking-[0.28em] text-slate-400">Rental CRM</p>
                    </div>
                </a>

                <div class="relative max-w-xl">
                    <p class="mb-5 text-xs font-extrabold uppercase tracking-[0.25em] text-brand-400">Customer operations</p>
                    <h1 class="text-4xl font-extrabold leading-tight tracking-tight xl:text-5xl">Chaque interaction client mérite un suivi d’exception.</h1>
                    <p class="mt-6 max-w-lg text-base leading-7 text-slate-300">Centralisez les appels, les réservations et la relation client dans un espace conçu pour des équipes efficaces.</p>
                    <div class="mt-10 grid grid-cols-3 gap-4 border-t border-white/10 pt-6 text-sm text-slate-300">
                        <div><span class="block text-2xl font-extrabold text-white">360°</span>Vue client</div>
                        <div><span class="block text-2xl font-extrabold text-white">1</span>Espace unifié</div>
                        <div><span class="block text-2xl font-extrabold text-white">24/7</span>Suivi continu</div>
                    </div>
                </div>

                <p class="relative text-xs text-slate-500">Bolli Rental · Abidjan, Côte d’Ivoire</p>
            </section>

            <section class="flex min-h-screen items-center justify-center bg-slate-50 px-5 py-10 sm:px-8">
                <div class="w-full max-w-md">
                    <a href="/" class="mb-10 flex items-center gap-3 lg:hidden">
                        <x-application-logo class="h-10 w-10 text-brand-600" />
                        <span class="text-xl font-extrabold tracking-tight">bolli.</span>
                    </a>
                    {{ $slot }}
                </div>
            </section>
        </div>
    </body>
</html>
