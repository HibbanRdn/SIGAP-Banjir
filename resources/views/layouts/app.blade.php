<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'SIGAP Banjir Bandar Lampung')</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800|jetbrains-mono:400,500,600" rel="stylesheet" />
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('head')
    </head>
    <body class="@yield('body-class', 'min-h-screen bg-surface-gray text-slate-950 antialiased')">
        <div class="@yield('app-shell-class', 'min-h-screen')">
            <header class="@yield('header-class', 'shrink-0 border-b border-outline-variant/70 bg-white/90 backdrop-blur')">
                <div class="@yield('header-inner-class', 'mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8')">
                    <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-3">
                        <div class="min-w-0">
                        <img src="{{ asset('assets/brand/logo-utama.png') }}" alt="SIGAP" class="h-9 w-auto object-contain">
                        <p class="mt-1 truncate text-xs font-medium text-slate-500">Banjir Bandar Lampung</p>
                        </div>
                    </a>

                    <nav class="flex items-center gap-2 text-sm font-semibold" aria-label="Navigasi utama">
                        <a href="{{ route('map') }}" class="rounded-lg px-3 py-2 {{ request()->routeIs('home') || request()->routeIs('map') ? 'bg-blue-50 text-secondary' : 'text-slate-600 hover:bg-slate-100 hover:text-primary' }} transition">Peta Publik</a>
                        <a href="{{ route('admin.dashboard') }}" class="sig-button sig-button-outline py-2">Admin</a>
                    </nav>
                </div>
            </header>

            <main class="@yield('main-class')">
                @yield('content')
            </main>
        </div>

        @stack('scripts')
    </body>
</html>
