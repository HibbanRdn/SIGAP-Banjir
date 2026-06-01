<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="overflow-x-hidden">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Dashboard Admin') - SIGAP Banjir Bandar Lampung</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800|jetbrains-mono:400,500,600" rel="stylesheet" />
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('head')
    </head>
    <body class="min-h-screen overflow-x-hidden bg-surface-gray text-slate-950 antialiased">
        @php
            $adminUser = auth()->user();
            $adminName = $adminUser?->name ?? 'Admin Demo';
            $adminEmail = $adminUser?->email ?? 'SIGAP Banjir';
            $initialParts = preg_split('/\s+/', trim($adminName)) ?: [];
            $adminInitials = collect($initialParts)
                ->filter()
                ->map(fn ($part) => mb_substr($part, 0, 1))
                ->take(2)
                ->implode('');
            $adminInitials = mb_strtoupper($adminInitials ?: 'AD');

            $navItems = [
                ['label' => 'Dashboard', 'href' => route('admin.dashboard'), 'active' => request()->routeIs('admin.dashboard'), 'icon' => 'M3.75 5.75A2 2 0 0 1 5.75 3.75h3.5a2 2 0 0 1 2 2v3.5a2 2 0 0 1-2 2h-3.5a2 2 0 0 1-2-2v-3.5ZM14.75 3.75h3.5a2 2 0 0 1 2 2v3.5a2 2 0 0 1-2 2h-3.5a2 2 0 0 1-2-2v-3.5a2 2 0 0 1 2-2ZM3.75 14.75a2 2 0 0 1 2-2h3.5a2 2 0 0 1 2 2v3.5a2 2 0 0 1-2 2h-3.5a2 2 0 0 1-2-2v-3.5ZM14.75 12.75h3.5a2 2 0 0 1 2 2v3.5a2 2 0 0 1-2 2h-3.5a2 2 0 0 1-2-2v-3.5a2 2 0 0 1 2-2Z'],
                ['label' => 'Peta Banjir', 'href' => route('map'), 'active' => request()->routeIs('map') || request()->routeIs('home'), 'icon' => 'M9 18.25 3.75 20V5.75L9 4m0 14.25 6 2m-6-2V4m6 16.25 5.25-1.75V4.25L15 6m0 14.25V6M9 4l6 2'],
                ['label' => 'Titik Rawan Banjir', 'href' => route('admin.flood-risks.index'), 'active' => request()->routeIs('admin.flood-risks.*'), 'icon' => 'M12 3.75 21.25 20H2.75L12 3.75Zm0 5.75v4.5m0 3.25h.01'],
                ['label' => 'Kejadian Banjir', 'href' => route('admin.flood-events.index'), 'active' => request()->routeIs('admin.flood-events.*'), 'icon' => 'M4 14.25c2-2 4-2 6 0s4 2 6 0 3-1.5 4-1.5M4 18.25c2-2 4-2 6 0s4 2 6 0 3-1.5 4-1.5M5.75 10.25a6.25 6.25 0 1 1 12.5 0'],
                ['label' => 'Titik Evakuasi', 'href' => route('admin.evacuation-points.index'), 'active' => request()->routeIs('admin.evacuation-points.*'), 'icon' => 'M12 3.75 20.25 7.5v5.75c0 4.5-3.25 7-8.25 8-5-1-8.25-3.5-8.25-8V7.5L12 3.75Zm-3 8.75 2 2 4-4'],
                ['label' => 'Pos Alat Berat', 'href' => route('admin.heavy-equipment-posts.index'), 'active' => request()->routeIs('admin.heavy-equipment-posts.*'), 'icon' => 'M3.75 15.25h10.5V7.75H3.75v7.5Zm10.5 0h2.5l2-3h1.5v3h-6Zm-8.5 2.5h.01m10.5 0h.01M7 17.75a1.25 1.25 0 1 1-2.5 0 1.25 1.25 0 0 1 2.5 0Zm10.5 0a1.25 1.25 0 1 1-2.5 0 1.25 1.25 0 0 1 2.5 0Z'],
                ['label' => 'Jenis & Unit Alat', 'href' => route('admin.equipment.index'), 'active' => request()->routeIs('admin.equipment.*'), 'icon' => 'M4.75 6.75h14.5M4.75 12h14.5M4.75 17.25h14.5M7.75 4.75v4M16.25 10v4M11.75 15.25v4'],
                ['label' => 'Sumber Data', 'href' => route('admin.data-sources.index'), 'active' => request()->routeIs('admin.data-sources.*'), 'icon' => 'M5.25 5.75c0-1.1 3.02-2 6.75-2s6.75.9 6.75 2-3.02 2-6.75 2-6.75-.9-6.75-2Zm0 0v12.5c0 1.1 3.02 2 6.75 2s6.75-.9 6.75-2V5.75m-13.5 6.25c0 1.1 3.02 2 6.75 2s6.75-.9 6.75-2'],
            ];
        @endphp

        <div class="admin-shell-bg min-h-screen lg:flex">
            <aside class="admin-sidebar-shell border-b border-outline-variant/70 lg:fixed lg:inset-y-0 lg:left-0 lg:z-30 lg:w-[292px] lg:border-b-0 lg:border-r">
                <div class="px-4 py-4">
                    <div class="admin-brand-panel flex items-center gap-3 px-4 py-3">
                        <img src="{{ asset('assets/brand/logo-icon.png') }}" alt="SIGAP Banjir Bandar Lampung" class="h-11 w-11 shrink-0 object-contain">
                        <div class="min-w-0">
                            <p class="text-base font-extrabold leading-tight text-primary">SIGAP Banjir</p>
                            <p class="mt-0.5 truncate text-xs font-semibold text-slate-500">Admin Bandar Lampung</p>
                        </div>
                        <span class="ml-auto hidden h-2 w-2 rounded-full bg-safe-teal sm:block" aria-hidden="true"></span>
                    </div>
                </div>

                <nav class="flex gap-2 overflow-x-auto px-3 pb-4 lg:block lg:space-y-1.5 lg:overflow-visible lg:px-4 lg:py-2" aria-label="Navigasi admin">
                    @foreach ($navItems as $item)
                        <a
                            href="{{ $item['href'] }}"
                            class="admin-nav-item group inline-flex min-w-max items-center gap-3 rounded-xl border px-3.5 py-2.5 text-sm font-semibold lg:flex lg:min-w-0 {{ $item['active'] ? 'admin-nav-item-active border-blue-100 bg-blue-50 text-secondary shadow-soft' : 'border-transparent text-slate-600 hover:border-slate-200 hover:bg-white hover:text-primary' }}"
                            @if ($item['href'] === '#') aria-disabled="true" @endif
                        >
                            <svg class="h-4.5 w-4.5 shrink-0 {{ $item['active'] ? 'text-secondary' : 'text-slate-400 group-hover:text-secondary' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="{{ $item['icon'] }}" />
                            </svg>
                            <span class="truncate">{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </nav>

                <div class="hidden border-t border-outline-variant/70 px-4 py-4 lg:block">
                    <div class="overflow-hidden rounded-2xl border border-blue-100 bg-blue-50/70 p-3">
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-secondary">Status MVP</p>
                        <p class="mt-2 text-sm font-bold leading-5 text-primary">CRUD, GeoJSON, Analisis, dan Routing Aktif</p>
                        <p class="mt-1 text-xs leading-5 text-blue-900/70">Ringkasan data dibaca dari database dan layer peta memakai endpoint GeoJSON.</p>
                    </div>
                    <div class="mt-4 space-y-1">
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="admin-nav-item flex w-full items-center gap-3 rounded-xl border border-transparent px-3 py-2 text-left text-sm font-semibold text-slate-500 transition hover:border-red-100 hover:bg-red-50 hover:text-red-700">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <div class="min-w-0 flex-1 lg:pl-[292px]">
                <header class="admin-topbar sticky top-0 z-20 border-b border-outline-variant/70 backdrop-blur">
                    <div class="flex min-h-20 flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-secondary">@yield('eyebrow', 'Panel Admin')</p>
                            <h1 class="mt-1 text-xl font-bold tracking-tight text-primary sm:text-2xl">@yield('page-title', 'Dashboard Admin')</h1>
                        </div>
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                            <label class="relative block sm:w-72">
                                <span class="sr-only">Cari</span>
                                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="m21 21-4.35-4.35M10.75 18.25a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" />
                                    </svg>
                                </span>
                                <input class="sig-input pl-9" type="search" placeholder="Cari data admin..." disabled>
                            </label>
                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-3 rounded-full border border-slate-200 bg-white px-3 py-2 shadow-soft">
                                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary text-xs font-bold text-white">{{ $adminInitials }}</span>
                                    <div class="hidden max-w-44 text-left sm:block">
                                        <p class="truncate text-sm font-semibold text-primary">{{ $adminName }}</p>
                                        <p class="truncate text-xs text-slate-500">{{ $adminEmail }}</p>
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('admin.logout') }}" class="hidden sm:block">
                                    @csrf
                                    <button type="submit" class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-soft transition hover:border-red-200 hover:bg-red-50 hover:text-red-700">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <main class="sig-reveal px-4 py-6 sm:px-6 lg:px-8">
                    @if (session('success'))
                        <div class="mb-6 rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800 shadow-soft">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6 rounded-2xl border border-red-100 bg-red-50 px-4 py-3 text-sm font-semibold text-red-800 shadow-soft">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('warning'))
                        <div class="mb-6 rounded-2xl border border-amber-100 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-800 shadow-soft">
                            {{ session('warning') }}
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
