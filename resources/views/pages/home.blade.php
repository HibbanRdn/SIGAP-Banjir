@extends('layouts.app')

@section('title', 'Peta Banjir Bandar Lampung - SIGAP Banjir')
@section('body-class', 'h-screen overflow-hidden bg-surface-gray text-slate-950 antialiased')
@section('app-shell-class', 'flex h-screen min-h-0 flex-col overflow-hidden')
@section('header-class', 'shrink-0 border-b border-outline-variant/70 bg-white/95 backdrop-blur')
@section('header-inner-class', 'flex h-16 items-center justify-between px-4 sm:px-5 lg:px-6')
@section('main-class', 'min-h-0 flex-1 overflow-hidden')

@section('content')
    <section class="flex h-full min-h-0 flex-col overflow-hidden bg-surface-gray lg:flex-row" data-public-map-shell>
        <aside class="sig-scrollbar h-[46vh] min-h-0 shrink-0 overflow-y-auto border-b border-outline-variant/70 bg-white lg:h-full lg:w-[410px] lg:border-b-0 lg:border-r">
            <div class="space-y-4 p-4 sm:p-5">
                <div class="space-y-3">
                    <span class="sig-badge bg-blue-50 text-blue-700">Peta Publik</span>
                    <div>
                        <h1 class="text-xl font-bold leading-tight tracking-tight text-primary sm:text-2xl">Peta Respons Banjir Bandar Lampung</h1>
                        <p class="mt-2 text-sm leading-6 text-slate-600">
                            Layer peta membaca data GeoJSON, analisis PostGIS, dan rute referensi dari API internal SIGAP Banjir.
                        </p>
                    </div>
                </div>

                <div id="map-alert" class="hidden rounded-xl border border-red-100 bg-red-50 px-3 py-2 text-sm font-semibold text-red-700"></div>

                <label class="relative block">
                    <span class="sr-only">Cari kejadian banjir</span>
                    <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m21 21-4.35-4.35M10.75 18.25a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" />
                        </svg>
                    </span>
                    <input id="flood-search" class="sig-input pl-9" type="search" placeholder="Cari kejadian, kecamatan, atau kelurahan...">
                </label>

                <section class="sig-card p-4">
                    <div class="mb-3 flex items-center justify-between">
                        <h2 class="text-sm font-bold text-primary">Filter Kejadian</h2>
                        <button id="reset-filters" type="button" class="sig-button sig-button-ghost px-2 py-1.5 text-xs">Reset</button>
                    </div>
                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-3 lg:grid-cols-1 xl:grid-cols-3">
                        <label class="block">
                            <span class="mb-1 block text-xs font-bold text-slate-500">Status</span>
                            <select id="status-filter" class="sig-input py-2">
                                <option value="">Semua</option>
                                <option value="aktif">Aktif</option>
                                <option value="ditangani">Ditangani</option>
                                <option value="surut">Surut</option>
                                <option value="arsip">Arsip</option>
                            </select>
                        </label>
                        <label class="block">
                            <span class="mb-1 block text-xs font-bold text-slate-500">Severity</span>
                            <select id="severity-filter" class="sig-input py-2">
                                <option value="">Semua</option>
                                <option value="rendah">Rendah</option>
                                <option value="sedang">Sedang</option>
                                <option value="tinggi">Tinggi</option>
                                <option value="kritis">Kritis</option>
                            </select>
                        </label>
                        <label class="block">
                            <span class="mb-1 block text-xs font-bold text-slate-500">Kecamatan</span>
                            <select id="district-filter" class="sig-input py-2">
                                <option value="">Semua</option>
                            </select>
                        </label>
                    </div>
                </section>

                <section class="sig-card p-4">
                    <div class="mb-3 flex items-center justify-between">
                        <h2 class="text-sm font-bold text-primary">Layer Peta</h2>
                        <span id="layer-loading" class="font-technical text-xs text-slate-500">memuat</span>
                    </div>
                    <div class="space-y-2">
                        <label class="map-layer-toggle">
                            <input type="checkbox" class="sr-only" data-layer-toggle="districtIntensity" checked>
                            <span class="map-layer-switch"></span>
                            <span class="flex min-w-0 flex-1 items-center gap-3">
                                <span class="map-layer-thematic-swatch shrink-0" aria-hidden="true"></span>
                                <span class="truncate text-sm font-semibold text-slate-700">Intensitas Kecamatan</span>
                            </span>
                            <span id="count-district-intensity" class="sig-badge border border-blue-100 bg-blue-50 text-blue-700">0/0</span>
                        </label>
                        <label class="map-layer-toggle">
                            <input type="checkbox" class="sr-only" data-layer-toggle="floodEvents" checked>
                            <span class="map-layer-switch"></span>
                            <span class="flex min-w-0 flex-1 items-center gap-3">
                                <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-danger-coral"></span>
                                <span class="truncate text-sm font-semibold text-slate-700">Kejadian banjir</span>
                            </span>
                            <span id="count-flood-events" class="sig-badge border border-red-100 bg-red-50 text-red-700">0</span>
                        </label>
                        <label class="map-layer-toggle">
                            <input type="checkbox" class="sr-only" data-layer-toggle="floodRisks" checked>
                            <span class="map-layer-switch"></span>
                            <span class="flex min-w-0 flex-1 items-center gap-3">
                                <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-amber-500"></span>
                                <span class="truncate text-sm font-semibold text-slate-700">Titik rawan banjir</span>
                            </span>
                            <span id="count-flood-risks" class="sig-badge border border-amber-100 bg-amber-50 text-amber-700">0</span>
                        </label>
                        <label class="map-layer-toggle">
                            <input type="checkbox" class="sr-only" data-layer-toggle="evacuations" checked>
                            <span class="map-layer-switch"></span>
                            <span class="flex min-w-0 flex-1 items-center gap-3">
                                <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-safe-teal"></span>
                                <span class="truncate text-sm font-semibold text-slate-700">Titik evakuasi</span>
                            </span>
                            <span id="count-evacuations" class="sig-badge border border-teal-100 bg-teal-50 text-teal-700">0</span>
                        </label>
                        <label class="map-layer-toggle">
                            <input type="checkbox" class="sr-only" data-layer-toggle="equipment" checked>
                            <span class="map-layer-switch"></span>
                            <span class="flex min-w-0 flex-1 items-center gap-3">
                                <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-resource-amber"></span>
                                <span class="truncate text-sm font-semibold text-slate-700">Pos alat berat</span>
                            </span>
                            <span id="count-equipment" class="sig-badge border border-yellow-100 bg-yellow-50 text-yellow-700">0</span>
                        </label>
                    </div>
                </section>

                <section>
                    <div class="mb-3 flex items-center justify-between">
                        <h2 class="text-sm font-bold text-primary">Kejadian Banjir</h2>
                        <span id="flood-result-count" class="font-technical text-xs text-slate-500">0 data</span>
                    </div>
                    <div id="flood-events-list" class="space-y-2.5">
                        <div class="rounded-xl border border-slate-200 bg-white p-3 text-sm text-slate-500">Memuat kejadian banjir...</div>
                    </div>
                </section>

                <section id="selected-event-panel" class="sig-card hidden overflow-hidden">
                    <div class="border-b border-slate-200 bg-slate-50 px-4 py-3">
                        <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Kejadian Terpilih</p>
                        <h2 id="selected-event-name" class="mt-1 text-base font-bold text-primary">-</h2>
                        <p id="selected-event-meta" class="mt-1 text-sm text-slate-500">-</p>
                    </div>
                    <div class="space-y-3 p-4">
                        <div class="grid grid-cols-1 gap-2 sm:grid-cols-3 lg:grid-cols-1 xl:grid-cols-3">
                            <button type="button" class="sig-button sig-button-outline px-3 py-2 text-xs" data-analysis-action="evacuation">Cari Evakuasi</button>
                            <button type="button" class="sig-button sig-button-outline px-3 py-2 text-xs" data-analysis-action="equipment">Cari Alat Berat</button>
                            <button type="button" class="sig-button sig-button-primary px-3 py-2 text-xs" data-analysis-action="resources">Cari Resource</button>
                        </div>
                        <button type="button" class="sig-button sig-button-primary w-full px-3 py-2 text-xs" data-route-action="nearest">Tampilkan Rute Evakuasi</button>
                        <div id="analysis-loading" class="hidden rounded-xl border border-blue-100 bg-blue-50 px-3 py-2 text-sm text-blue-700">Memuat rekomendasi...</div>
                        <div id="recommendations-panel" class="space-y-2"></div>
                    </div>
                </section>

                <section id="route-info-panel" class="sig-card hidden overflow-hidden">
                    <div class="border-b border-slate-200 bg-blue-50/70 px-4 py-3">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wide text-blue-700">Rute Referensi</p>
                                <h2 id="route-destination" class="mt-1 text-base font-bold text-primary">-</h2>
                            </div>
                            <div class="flex shrink-0 flex-col items-end gap-1">
                                <span class="sig-badge border border-blue-100 bg-white text-blue-700">Rute Referensi</span>
                                <span id="route-provider" class="font-technical rounded-full border border-slate-200 bg-white px-2 py-1 text-[11px] font-bold uppercase text-slate-600">OSRM</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-2 gap-2">
                            <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                                <p class="text-xs text-slate-500">Jarak</p>
                                <p id="route-distance" class="font-technical mt-1 font-semibold text-primary">-</p>
                            </div>
                            <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                                <p class="text-xs text-slate-500">Durasi</p>
                                <p id="route-duration" class="font-technical mt-1 font-semibold text-primary">-</p>
                            </div>
                        </div>
                        <p id="route-note" class="mt-3 rounded-xl border border-blue-100 bg-blue-50 px-3 py-2 text-xs leading-5 text-blue-800"></p>
                    </div>
                </section>
            </div>
        </aside>

        <section class="relative min-h-0 min-w-0 flex-1 overflow-hidden bg-slate-100">
            <div id="public-map" class="h-full w-full"></div>

            <div class="pointer-events-none absolute left-4 top-4 z-[450] rounded-2xl border border-slate-200 bg-white/95 px-3 py-2 text-xs font-semibold text-slate-600 shadow-soft backdrop-blur sm:left-5 sm:top-5">
                <span class="font-technical text-secondary">Leaflet</span> · <span id="current-basemap-label">Standar</span> · Data API SIGAP
            </div>

            <div id="basemap-selector" class="absolute left-4 top-14 z-[450] rounded-2xl border border-slate-200 bg-white/95 p-1 shadow-soft backdrop-blur sm:left-auto sm:right-5 sm:top-5" aria-label="Mode basemap">
                <div class="flex gap-1">
                    <button type="button" class="map-basemap-button" data-basemap-mode="standard" aria-pressed="true">Standar</button>
                    <button type="button" class="map-basemap-button" data-basemap-mode="humanitarian" aria-pressed="false">Humanitarian</button>
                    <button type="button" class="map-basemap-button" data-basemap-mode="satellite" aria-pressed="false">Satelit</button>
                </div>
            </div>

            <div id="map-loading-overlay" class="absolute inset-x-4 top-16 z-[450] rounded-xl border border-blue-100 bg-blue-50/95 px-3 py-2 text-sm font-semibold text-blue-800 shadow-soft backdrop-blur sm:inset-x-auto sm:left-5">
                Memuat layer peta...
            </div>

            <div class="absolute bottom-4 left-4 z-[450] max-w-[calc(100%-2rem)] rounded-2xl border border-slate-200 bg-white/95 p-3 shadow-soft backdrop-blur sm:bottom-5 sm:left-5">
                <div class="flex items-center justify-between gap-5">
                    <h3 class="text-sm font-bold text-primary">Legend</h3>
                    <span id="legend-basemap-label" class="font-technical text-[11px] text-slate-400">Standar</span>
                </div>
                <div id="district-intensity-legend" class="mt-3 border-b border-slate-200 pb-3">
                    <p class="mb-2 text-xs font-bold text-slate-500">Intensitas Kecamatan</p>
                    <div class="grid grid-cols-2 gap-x-3 gap-y-1.5 text-xs text-slate-600">
                        <div class="flex items-center gap-2"><span class="map-legend-district-swatch map-legend-district-none"></span>0</div>
                        <div class="flex items-center gap-2"><span class="map-legend-district-swatch map-legend-district-low"></span>1-4 Rendah</div>
                        <div class="flex items-center gap-2"><span class="map-legend-district-swatch map-legend-district-medium"></span>5-7 Sedang</div>
                        <div class="flex items-center gap-2"><span class="map-legend-district-swatch map-legend-district-high"></span>8+ Tinggi</div>
                    </div>
                </div>
                <div class="mt-3 grid gap-2 text-sm text-slate-600">
                    <div class="flex items-center gap-2"><span class="map-legend-marker map-legend-marker-flood"><span></span></span>Kejadian banjir</div>
                    <div class="flex items-center gap-2"><span class="map-legend-marker map-legend-marker-risk"><span></span></span>Titik rawan</div>
                    <div class="flex items-center gap-2"><span class="map-legend-marker map-legend-marker-evacuation"><span></span></span>Titik evakuasi</div>
                    <div class="flex items-center gap-2"><span class="map-legend-marker map-legend-marker-equipment"><span></span></span>Pos alat berat</div>
                    <div class="flex items-center gap-2"><span class="map-legend-route"></span>Rute referensi</div>
                </div>
            </div>
        </section>
    </section>
@endsection
