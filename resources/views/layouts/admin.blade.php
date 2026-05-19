<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Admin Dashboard') - {{ config('app.name', 'SIG Banjir Bandar Lampung') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700|jetbrains-mono:400,500,600" rel="stylesheet" />
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('head')
    </head>
    <body class="min-h-screen bg-slate-100 text-slate-950 antialiased">
        <div class="min-h-screen lg:flex">
            <aside class="border-b border-slate-200 bg-white lg:fixed lg:inset-y-0 lg:left-0 lg:w-72 lg:border-b-0 lg:border-r">
                <div class="flex h-16 items-center gap-3 px-5">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-white">
                        <img src="{{ asset('assets/brand/logo-icon.png') }}" alt="SIGAP" class="h-9 w-9 object-contain">
                    </span>
                    <div>
                        <img src="{{ asset('assets/brand/logo-utama.png') }}" alt="SIGAP Banjir" class="h-8 w-auto object-contain">
                        <div class="text-xs text-slate-500">Mode Demo Akademik</div>
                    </div>
                </div>

                <nav class="flex gap-2 overflow-x-auto px-3 pb-3 lg:block lg:space-y-1 lg:overflow-visible lg:px-4 lg:py-4">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center rounded-md bg-blue-50 px-3 py-2 text-sm font-medium text-civic-blue lg:flex">Dashboard</a>
                    <a href="{{ route('home') }}" class="inline-flex items-center rounded-md px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950 lg:flex">Peta Publik</a>
                    <span class="inline-flex items-center rounded-md px-3 py-2 text-sm font-medium text-slate-400 lg:flex">Data SIG</span>
                </nav>
            </aside>

            <div class="lg:pl-72">
                <header class="border-b border-slate-200 bg-white">
                    <div class="flex h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
                        <div>
                            <p class="text-xs font-medium uppercase text-slate-500">Phase 1 Setup</p>
                            <h1 class="text-base font-semibold text-slate-950">@yield('page-title', 'Dashboard Admin')</h1>
                        </div>
                        <a href="{{ route('admin.login') }}" class="rounded-md border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 transition hover:border-civic-blue hover:text-civic-blue">Login Placeholder</a>
                    </div>
                </header>

                <main class="px-4 py-6 sm:px-6 lg:px-8">
                    @yield('content')
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
