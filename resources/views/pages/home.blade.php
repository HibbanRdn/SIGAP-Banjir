@extends('layouts.app')

@section('title', 'Peta Banjir Bandar Lampung - SIGAP Banjir')
@section('body-class', 'h-screen overflow-hidden bg-surface-gray text-slate-950 antialiased')
@section('app-shell-class', 'flex h-screen min-h-0 flex-col overflow-hidden')
@section('header-class', 'shrink-0 border-b border-outline-variant/70 bg-white/95 backdrop-blur')
@section('header-inner-class', 'flex h-16 items-center justify-between px-4 sm:px-5 lg:px-6')
@section('main-class', 'min-h-0 flex-1 overflow-hidden')

@section('content')
    @php
        $layers = [
            ['label' => 'Titik rawan banjir', 'count' => '12', 'dot' => 'bg-amber-500', 'tone' => 'bg-amber-50 text-amber-700 border-amber-100'],
            ['label' => 'Kejadian banjir aktif', 'count' => '04', 'dot' => 'bg-danger-coral', 'tone' => 'bg-red-50 text-red-700 border-red-100'],
            ['label' => 'Titik evakuasi', 'count' => '08', 'dot' => 'bg-safe-teal', 'tone' => 'bg-teal-50 text-teal-700 border-teal-100'],
            ['label' => 'Pos alat berat', 'count' => '05', 'dot' => 'bg-resource-amber', 'tone' => 'bg-yellow-50 text-yellow-700 border-yellow-100'],
            ['label' => 'Rute evakuasi referensi', 'count' => '01', 'dot' => 'bg-secondary', 'tone' => 'bg-blue-50 text-blue-700 border-blue-100'],
        ];

        $results = [
            ['name' => 'Banjir Teluk Betung Selatan', 'type' => 'Kejadian banjir', 'district' => 'Teluk Betung Selatan', 'status' => 'Aktif', 'meta' => 'Kritis · simulasi', 'tone' => 'border-red-100 bg-red-50/50 text-red-700', 'active' => true],
            ['name' => 'Masjid Al-Furqon Lungsir', 'type' => 'Titik evakuasi', 'district' => 'Tanjung Karang Pusat', 'status' => 'Aktif', 'meta' => 'Kapasitas 180 orang', 'tone' => 'border-teal-100 bg-teal-50/60 text-teal-700', 'active' => false],
            ['name' => 'Pos Alat Berat Panjang', 'type' => 'Pos alat berat', 'district' => 'Panjang', 'status' => 'Aktif', 'meta' => '6 unit tersedia · dummy', 'tone' => 'border-yellow-100 bg-yellow-50/70 text-yellow-700', 'active' => false],
            ['name' => 'Rawan Banjir Way Halim', 'type' => 'Titik rawan', 'district' => 'Way Halim', 'status' => 'Risiko tinggi', 'meta' => 'Perlu validasi koordinat', 'tone' => 'border-amber-100 bg-amber-50/70 text-amber-700', 'active' => false],
            ['name' => 'Banjir Rajabasa Raya', 'type' => 'Kejadian banjir', 'district' => 'Rajabasa', 'status' => 'Ditangani', 'meta' => 'Tinggi · simulasi', 'tone' => 'border-blue-100 bg-blue-50/60 text-blue-700', 'active' => false],
        ];

        $markers = [
            ['label' => 'Banjir Teluk Betung Selatan', 'class' => 'left-[36%] top-[62%] map-marker-flood map-marker-selected', 'size' => 'h-5 w-5', 'tag' => 'Kejadian aktif', 'tagTone' => 'border-red-100 bg-white text-red-700'],
            ['label' => 'Masjid Al-Furqon Lungsir', 'class' => 'left-[54%] top-[43%] map-marker-evacuation map-marker-recommended', 'size' => 'h-[1.125rem] w-[1.125rem]', 'tag' => 'Rekomendasi', 'tagTone' => 'border-teal-100 bg-white text-teal-700'],
            ['label' => 'Pos Alat Berat Panjang', 'class' => 'left-[76%] top-[69%] map-marker-equipment', 'size' => 'h-4 w-4'],
            ['label' => 'Rawan Banjir Way Halim', 'class' => 'left-[60%] top-[29%] map-marker-risk', 'size' => 'h-3.5 w-3.5'],
            ['label' => 'Rawan Banjir Rajabasa', 'class' => 'left-[42%] top-[26%] map-marker-risk', 'size' => 'h-3.5 w-3.5'],
        ];
    @endphp

    <section class="flex h-full min-h-0 flex-col overflow-hidden bg-surface-gray lg:flex-row">
        <aside class="sig-scrollbar h-[44vh] min-h-0 shrink-0 overflow-y-auto border-b border-outline-variant/70 bg-white lg:h-full lg:w-[390px] lg:border-b-0 lg:border-r">
            <div class="space-y-4 p-4 sm:p-5">
                <div class="space-y-3">
                    <span class="sig-badge bg-blue-50 text-blue-700">Peta Publik</span>
                    <div>
                        <h1 class="text-xl font-bold leading-tight tracking-tight text-primary sm:text-2xl">Peta Respons Banjir Bandar Lampung</h1>
                        <p class="mt-2 text-sm leading-6 text-slate-600">
                            Pratinjau UI statis untuk layer titik rawan banjir, kejadian banjir, titik evakuasi, pos alat berat, dan rute evakuasi referensi.
                        </p>
                    </div>
                </div>

                <label class="relative block">
                    <span class="sr-only">Cari lokasi</span>
                    <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m21 21-4.35-4.35M10.75 18.25a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" />
                        </svg>
                    </span>
                    <input class="sig-input pl-9" type="search" placeholder="Cari lokasi, kecamatan, atau status..." disabled>
                </label>

                <div class="flex flex-wrap gap-2">
                    <span class="sig-badge border border-blue-100 bg-blue-50 text-blue-700">Semua Layer</span>
                    <span class="sig-badge border border-red-100 bg-red-50 text-red-700">Aktif</span>
                    <span class="sig-badge border border-slate-200 bg-white text-slate-600">Simulasi</span>
                    <span class="sig-badge border border-slate-200 bg-white text-slate-600">Perlu Validasi</span>
                </div>

                <section class="sig-card p-4">
                    <div class="mb-3 flex items-center justify-between">
                        <h2 class="text-sm font-bold text-primary">Layer Peta</h2>
                        <span class="font-technical text-xs text-slate-500">5 layer</span>
                    </div>
                    <div class="space-y-2">
                        @foreach ($layers as $layer)
                            <button type="button" class="flex w-full items-center justify-between rounded-xl border border-slate-200 bg-white p-3 text-left transition hover:border-secondary/40 hover:bg-slate-50">
                                <span class="flex min-w-0 items-center gap-3">
                                    <span class="h-2.5 w-2.5 shrink-0 rounded-full {{ $layer['dot'] }}"></span>
                                    <span class="truncate text-sm font-semibold text-slate-700">{{ $layer['label'] }}</span>
                                </span>
                                <span class="sig-badge border {{ $layer['tone'] }}">{{ $layer['count'] }}</span>
                            </button>
                        @endforeach
                    </div>
                </section>

                <section>
                    <div class="mb-3 flex items-center justify-between">
                        <h2 class="text-sm font-bold text-primary">Hasil Terlihat</h2>
                        <button type="button" class="sig-button sig-button-ghost px-2 py-1.5">Reset Filter</button>
                    </div>
                    <div class="space-y-2.5">
                        @foreach ($results as $result)
                            <article class="relative overflow-hidden rounded-xl border bg-white p-3 transition hover:border-secondary hover:shadow-soft {{ $result['active'] ? 'border-primary/70 ring-1 ring-primary/10' : 'border-slate-200' }}">
                                @if ($result['active'])
                                    <span class="absolute inset-y-3 left-0 w-1 rounded-r-full bg-secondary"></span>
                                @endif
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="truncate font-semibold text-primary">{{ $result['name'] }}</p>
                                        <p class="mt-1 truncate text-xs text-slate-500">{{ $result['type'] }} · {{ $result['district'] }}</p>
                                    </div>
                                    <span class="sig-badge shrink-0 border {{ $result['tone'] }}">{{ $result['status'] }}</span>
                                </div>
                                <p class="mt-2 text-sm text-slate-500">{{ $result['meta'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </section>

                <div class="rounded-xl border border-blue-100 bg-blue-50 p-3 text-sm leading-6 text-blue-800">
                    Peta masih placeholder. Integrasi Leaflet, GeoJSON, dan PostGIS belum diaktifkan.
                </div>
            </div>
        </aside>

        <section class="relative min-h-0 min-w-0 flex-1 overflow-hidden bg-slate-100">
            <div class="absolute inset-0 sig-grid-bg"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_53%_47%,rgba(0,88,190,0.14),transparent_34%),radial-gradient(circle_at_74%_28%,rgba(45,212,191,0.1),transparent_24%),linear-gradient(135deg,rgba(255,255,255,0.78),rgba(248,250,252,0.34))]"></div>
            <svg class="absolute inset-0 h-full w-full opacity-70" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                <path d="M6 83 C22 76 36 78 49 70 C62 62 72 66 92 54" fill="none" stroke="#93c5fd" stroke-width="1.1" stroke-linecap="round" opacity="0.22" />
                <path d="M12 25 L88 10" fill="none" stroke="#ffffff" stroke-width="0.72" stroke-linecap="round" filter="drop-shadow(0 1px 2px rgb(15 23 42 / 0.12))" />
                <path d="M18 69 L90 78" fill="none" stroke="#ffffff" stroke-width="0.74" stroke-linecap="round" filter="drop-shadow(0 1px 2px rgb(15 23 42 / 0.12))" />
                <path d="M52 5 L46 84" fill="none" stroke="#ffffff" stroke-width="0.7" stroke-linecap="round" filter="drop-shadow(0 1px 2px rgb(15 23 42 / 0.12))" />
                <path d="M28 38 C43 35 55 34 73 27" fill="none" stroke="#f8fafc" stroke-width="0.38" stroke-linecap="round" opacity="0.82" />
            </svg>
            <svg class="absolute inset-0 h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                <path d="M36 62 C42 54 48 48 54 43" fill="none" stroke="#dbeafe" stroke-width="1.5" stroke-linecap="round" opacity="0.8" />
                <path d="M36 62 C42 54 48 48 54 43" fill="none" stroke="#0058be" stroke-width="0.82" stroke-dasharray="2.2 1.35" stroke-linecap="round" />
            </svg>

            @foreach ($markers as $marker)
                <button type="button" class="map-marker {{ $marker['class'] }} {{ $marker['size'] }}" aria-label="{{ $marker['label'] }}">
                    @isset($marker['tag'])
                        <span class="pointer-events-none absolute left-1/2 top-full mt-2 -translate-x-1/2 whitespace-nowrap rounded-full border px-2 py-1 text-[11px] font-bold shadow-soft {{ $marker['tagTone'] }}">
                            {{ $marker['tag'] }}
                        </span>
                    @endisset
                </button>
            @endforeach

            <div class="absolute left-[42%] top-[52%] hidden -translate-x-1/2 rounded-full border border-blue-100 bg-white/92 px-3 py-1.5 text-xs font-bold text-blue-800 shadow-soft backdrop-blur md:block">
                <span class="font-technical">1.8 km</span> · Rute Referensi
            </div>

            <div class="absolute right-4 top-4 z-30 grid gap-2 sm:right-5 sm:top-5">
                <button type="button" class="map-control-button font-technical text-lg" aria-label="Perbesar peta">+</button>
                <button type="button" class="map-control-button font-technical text-lg" aria-label="Perkecil peta">-</button>
                <button type="button" class="map-control-button" aria-label="Lihat lokasi saat ini">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 2v3M12 19v3M2 12h3M19 12h3" />
                        <circle cx="12" cy="12" r="4" />
                    </svg>
                </button>
                <button type="button" class="map-control-button" aria-label="Toggle layer peta">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m12 2 9 5-9 5-9-5 9-5Z" />
                        <path d="m3 12 9 5 9-5" />
                        <path d="m3 17 9 5 9-5" />
                    </svg>
                </button>
                <button type="button" class="map-control-button" aria-label="Reset tampilan peta">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 12a9 9 0 1 0 3-6.7" />
                        <path d="M3 4v6h6" />
                    </svg>
                </button>
            </div>

            <div class="absolute right-4 top-20 z-20 w-[min(330px,calc(100%-2rem))] overflow-hidden rounded-2xl border border-t-4 border-slate-200 border-t-danger-coral bg-white/96 shadow-soft backdrop-blur sm:right-16 sm:top-5 lg:right-20">
                <div class="p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <span class="sig-badge border border-red-100 bg-red-50 text-red-700">Kejadian Aktif</span>
                            <h2 class="mt-3 text-base font-bold leading-snug text-primary sm:text-lg">Banjir Teluk Betung Selatan</h2>
                            <p class="mt-1 text-xs font-semibold text-slate-500">Kejadian banjir · Teluk Betung Selatan</p>
                        </div>
                        <span class="font-technical text-right text-xs leading-5 text-slate-500">-5.45<br>105.26</span>
                    </div>

                    <div class="mt-4 grid gap-2 text-sm">
                        <div class="flex items-center justify-between rounded-xl bg-slate-50 px-3 py-2">
                            <span class="text-slate-500">Keparahan</span>
                            <span class="sig-badge border border-red-100 bg-white text-red-700">Kritis</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl bg-slate-50 px-3 py-2">
                            <span class="text-slate-500">Data</span>
                            <span class="sig-badge border border-amber-100 bg-white text-amber-700">simulasi</span>
                        </div>
                    </div>

                    <div class="mt-3 grid grid-cols-2 gap-2 text-sm">
                        <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                            <p class="text-xs text-slate-500">Evakuasi terdekat</p>
                            <p class="font-technical mt-1 font-semibold text-primary">1.8 km</p>
                        </div>
                        <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                            <p class="text-xs text-slate-500">Pos alat berat</p>
                            <p class="font-technical mt-1 font-semibold text-primary">3.2 km</p>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center gap-2">
                        <button type="button" class="sig-button sig-button-primary px-3 py-2 text-xs">Lihat Detail</button>
                        <button type="button" class="sig-button sig-button-outline px-3 py-2 text-xs">Lihat Rute</button>
                    </div>
                </div>
            </div>

            <div class="absolute bottom-4 left-4 z-20 rounded-2xl border border-slate-200 bg-white/95 p-3 shadow-soft backdrop-blur sm:bottom-5 sm:left-5">
                <div class="flex items-center justify-between gap-5">
                    <h3 class="text-sm font-bold text-primary">Legend</h3>
                    <span class="font-technical text-[11px] text-slate-400">5 layer</span>
                </div>
                <div class="mt-3 grid gap-2 text-sm text-slate-600">
                    <div class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-danger-coral shadow-[0_0_0_4px_rgba(248,113,113,0.15)]"></span>Kejadian banjir</div>
                    <div class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-amber-500 shadow-[0_0_0_4px_rgba(245,158,11,0.15)]"></span>Titik rawan</div>
                    <div class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-safe-teal shadow-[0_0_0_4px_rgba(45,212,191,0.15)]"></span>Titik evakuasi</div>
                    <div class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-resource-amber shadow-[0_0_0_4px_rgba(251,191,36,0.18)]"></span>Pos alat berat</div>
                    <div class="flex items-center gap-2"><span class="h-0.5 w-6 rounded-full border-t-2 border-dashed border-secondary"></span>Rute referensi</div>
                </div>
            </div>

            <div class="absolute bottom-4 right-4 z-20 rounded-full border border-blue-100 bg-blue-50/95 px-3 py-2 text-xs font-semibold text-blue-800 shadow-soft backdrop-blur sm:bottom-5 sm:right-5 sm:px-4 sm:text-sm">
                Placeholder peta · Leaflet/GeoJSON akan diintegrasikan
            </div>
        </section>
    </section>
@endsection
