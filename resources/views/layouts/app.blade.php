<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'SIG Banjir Bandar Lampung'))</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700|jetbrains-mono:400,500,600" rel="stylesheet" />
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('head')
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-950 antialiased">
        <div class="min-h-screen">
            <header class="border-b border-slate-200 bg-white/90 backdrop-blur">
                <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <img src="{{ asset('assets/brand/logo-utama.png') }}" alt="SIGAP Banjir Bandar Lampung" class="h-10 w-auto object-contain">
                        <span class="hidden sm:block">
                            <span class="block text-sm font-semibold text-slate-950">SIG Banjir Bandar Lampung</span>
                            <span class="block text-xs text-slate-500">Civic Flood Response Map Explorer</span>
                        </span>
                    </a>

                    <nav class="flex items-center gap-2 text-sm font-medium">
                        <a href="{{ route('home') }}" class="rounded-md px-3 py-2 text-slate-600 transition hover:bg-slate-100 hover:text-slate-950">Peta Publik</a>
                        <a href="{{ route('admin.login') }}" class="rounded-md border border-slate-200 px-3 py-2 text-slate-700 transition hover:border-civic-blue hover:text-civic-blue">Admin</a>
                    </nav>
                </div>
            </header>

            <main>
                @yield('content')
            </main>
        </div>

        @stack('scripts')
    </body>
</html>
