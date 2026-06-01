@extends('layouts.auth')

@section('title', 'Login Admin - SIGAP Banjir')

@section('content')
    <main class="grid min-h-screen overflow-hidden bg-white lg:grid-cols-[1.05fr_.95fr]">
        <section class="sig-login-hero relative hidden min-h-screen overflow-hidden bg-primary text-white lg:flex">
            <div class="absolute inset-0 opacity-70 sig-grid-bg"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_18%_14%,rgba(45,212,191,0.20),transparent_32%),radial-gradient(circle_at_78%_72%,rgba(0,88,190,0.38),transparent_36%),linear-gradient(135deg,rgba(9,20,38,0.96),rgba(0,38,82,0.92))]"></div>
            <div class="absolute -left-24 top-24 h-72 w-72 rounded-full border border-white/10"></div>
            <div class="absolute -right-28 bottom-20 h-96 w-96 rounded-full border border-cyan-200/10"></div>

            <div class="relative flex min-h-screen w-full flex-col justify-between px-10 py-10 xl:px-14">
                <div>
                    <div class="relative z-10">
                        <a href="/" class="inline-flex items-center gap-2">
                        <img src="{{ asset('assets/brand/logo-utama.png') }}" alt="SIGAP" class="h-15 w-auto object-contain brightness-0 invert" />
                        </a>
                    </div>

                    <div class="sig-login-copy mt-16 max-w-xl">
                       
                        <h1 class="mt-5 text-4xl font-extrabold leading-tight tracking-tight xl:text-5xl">
                            Ruang kendali data banjir untuk keputusan berbasis lokasi.
                        </h1>
                        <p class="mt-5 max-w-lg text-sm leading-7 text-slate-300">
                            Kelola kejadian banjir, titik evakuasi, pos alat berat, dan analisis spasial dalam satu dashboard SIGAP Banjir Bandar Lampung.
                        </p>
                    </div>
                </div>

                <div class="sig-login-visual relative mt-10 overflow-hidden rounded-3xl border border-white/10 bg-white/8 p-6 shadow-[0_24px_80px_rgba(0,0,0,0.26)] backdrop-blur">
                    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-cyan-200/50 to-transparent"></div>
                    <div class="mb-5 flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-cyan-100">Civic Spatial Layer</p>
                            <p class="mt-1 text-sm text-slate-300">Peta, resource, dan rute referensi</p>
                        </div>
                        <span class="font-technical rounded-full border border-white/12 bg-white/10 px-3 py-1 text-xs font-semibold text-white">SRID 4326</span>
                    </div>

                    <div class="relative h-72 overflow-hidden rounded-2xl border border-white/10 bg-[linear-gradient(180deg,rgba(255,255,255,0.10),rgba(255,255,255,0.04))]">
                        <svg class="absolute inset-0 h-full w-full" viewBox="0 0 760 360" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path class="sig-login-line" d="M64 262C138 222 181 256 246 216C316 173 365 193 425 150C494 102 572 126 700 62" stroke="rgba(125,211,252,0.48)" stroke-width="3" stroke-linecap="round"/>
                            <path class="sig-login-line" d="M72 308C158 260 224 294 300 242C376 190 454 221 534 162C584 126 630 110 700 98" stroke="rgba(20,184,166,0.45)" stroke-width="2" stroke-linecap="round"/>
                            <g opacity="0.18" stroke="white">
                                <path d="M0 72H760" />
                                <path d="M0 144H760" />
                                <path d="M0 216H760" />
                                <path d="M0 288H760" />
                                <path d="M95 0V360" />
                                <path d="M190 0V360" />
                                <path d="M285 0V360" />
                                <path d="M380 0V360" />
                                <path d="M475 0V360" />
                                <path d="M570 0V360" />
                                <path d="M665 0V360" />
                            </g>
                            <g transform="translate(88 136)">
                                <path d="M0 144H584" stroke="rgba(255,255,255,0.28)" stroke-width="2"/>
                                <rect x="18" y="68" width="52" height="76" rx="4" fill="rgba(255,255,255,0.82)"/>
                                <rect x="92" y="32" width="68" height="112" rx="5" fill="rgba(186,230,253,0.86)"/>
                                <rect x="184" y="78" width="54" height="66" rx="4" fill="rgba(255,255,255,0.76)"/>
                                <rect x="263" y="16" width="78" height="128" rx="6" fill="rgba(125,211,252,0.88)"/>
                                <rect x="372" y="54" width="62" height="90" rx="5" fill="rgba(255,255,255,0.74)"/>
                                <rect x="468" y="88" width="46" height="56" rx="4" fill="rgba(153,246,228,0.82)"/>
                                <rect x="536" y="42" width="42" height="102" rx="4" fill="rgba(255,255,255,0.70)"/>
                                <g fill="rgba(9,20,38,0.22)">
                                    <rect x="34" y="84" width="8" height="8" rx="1" />
                                    <rect x="49" y="84" width="8" height="8" rx="1" />
                                    <rect x="34" y="104" width="8" height="8" rx="1" />
                                    <rect x="49" y="104" width="8" height="8" rx="1" />
                                    <rect x="113" y="53" width="9" height="9" rx="1" />
                                    <rect x="134" y="53" width="9" height="9" rx="1" />
                                    <rect x="113" y="78" width="9" height="9" rx="1" />
                                    <rect x="134" y="78" width="9" height="9" rx="1" />
                                    <rect x="288" y="40" width="10" height="10" rx="1" />
                                    <rect x="314" y="40" width="10" height="10" rx="1" />
                                    <rect x="288" y="70" width="10" height="10" rx="1" />
                                    <rect x="314" y="70" width="10" height="10" rx="1" />
                                </g>
                            </g>
                            <g>
                                <circle class="sig-login-pin" cx="214" cy="212" r="8" fill="#EF4444" stroke="white" stroke-width="4"/>
                                <circle class="sig-login-pin" cx="432" cy="154" r="8" fill="#0D9488" stroke="white" stroke-width="4"/>
                                <circle class="sig-login-pin" cx="580" cy="124" r="8" fill="#D97706" stroke="white" stroke-width="4"/>
                            </g>
                        </svg>
                        <div class="absolute bottom-4 left-4 right-4 flex flex-wrap gap-2">
                            <span class="rounded-full border border-white/12 bg-white/10 px-3 py-1 text-xs font-semibold text-white">PostGIS</span>
                            <span class="rounded-full border border-white/12 bg-white/10 px-3 py-1 text-xs font-semibold text-white">GeoJSON</span>
                            <span class="rounded-full border border-white/12 bg-white/10 px-3 py-1 text-xs font-semibold text-white">Leaflet</span>
                            <span class="rounded-full border border-white/12 bg-white/10 px-3 py-1 text-xs font-semibold text-white">OSRM</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="flex min-h-screen items-center justify-center bg-white px-5 py-10 sm:px-8 lg:px-12">
            <div class="sig-login-form w-full max-w-[30rem]">
                <a href="{{ route('map') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-secondary">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m15 18-6-6 6-6" />
                    </svg>
                    Kembali ke peta publik
                </a>

                <div class="mt-9 lg:hidden">
                    <img src="{{ asset('assets/brand/logo-utama.png') }}" alt="SIGAP" class="h-12 w-auto object-contain">
                    <p class="mt-2 text-sm font-semibold text-slate-500">Banjir Bandar Lampung</p>
                </div>

                <div class="mt-10">
                    <span class="inline-flex rounded-full border border-blue-100 bg-blue-50 px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] text-secondary">Area Admin</span>
                    <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-primary sm:text-4xl">Masuk ke Dashboard SIGAP Banjir</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">
                        Masuk untuk mengelola data banjir, titik evakuasi, pos alat berat, dan analisis spasial SIGAP Banjir Bandar Lampung.
                    </p>
                </div>

                @if (session('status'))
                    <div class="mt-7 rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mt-7 rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm leading-6 text-red-800">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.store') }}" class="mt-8 space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-bold text-slate-700">Email Admin</label>
                        <div class="relative mt-2">
                            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M4.75 6.75h14.5v10.5H4.75z" />
                                    <path d="m5 7 7 6 7-6" />
                                </svg>
                            </span>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                placeholder="admin@sigap.local"
                                autocomplete="email"
                                autofocus
                                class="sig-input h-14 pl-12 @error('email') border-red-300 focus:border-red-400 focus:ring-red-100 @enderror"
                            >
                        </div>
                        @error('email')
                            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-bold text-slate-700">Password</label>
                        <div class="relative mt-2">
                            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M7.75 10.75V8a4.25 4.25 0 0 1 8.5 0v2.75" />
                                    <path d="M5.75 10.75h12.5v9.5H5.75z" />
                                </svg>
                            </span>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                placeholder="Masukkan password admin"
                                autocomplete="current-password"
                                class="sig-input h-14 pl-12 @error('password') border-red-300 focus:border-red-400 focus:ring-red-100 @enderror"
                            >
                        </div>
                        @error('password')
                            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <label class="flex items-center gap-2 text-sm font-semibold text-slate-600">
                            <input name="remember" type="checkbox" value="1" class="h-4 w-4 rounded border-slate-300 text-secondary focus:ring-secondary/30">
                            Ingat sesi admin
                        </label>
                    </div>

                    <button type="submit" class="sig-button h-14 w-full justify-center bg-primary text-base font-bold text-white shadow-soft hover:bg-secondary">
                        Masuk ke Dashboard
                    </button>
                </form>

                <div class="mt-8 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm leading-6 text-slate-600">
                    <p class="font-semibold text-primary">Akses terbatas administrator.</p>
                    <p class="mt-1">Gunakan halaman ini hanya untuk pengelolaan data dan administrasi SIGAP Banjir.</p>
                </div>
            </div>
        </section>
    </main>
@endsection
