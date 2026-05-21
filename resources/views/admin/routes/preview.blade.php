@extends('layouts.admin')

@section('title', 'Rute Evakuasi Referensi')
@section('eyebrow', 'Pratinjau Rute')
@section('page-title', 'Rute Evakuasi Referensi')

@section('content')
    <div class="space-y-6">
        <section class="sig-card p-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <span class="sig-badge bg-blue-50 text-blue-700">Rute Referensi</span>
                    <h2 class="mt-3 text-xl font-bold text-primary">Banjir Teluk Betung Selatan ke Masjid Al-Furqon</h2>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Pratinjau tampilan rute. Belum memanggil OSRM/OpenRouteService.</p>
                </div>
                <a href="{{ route('admin.flood-events.show', 1) }}" class="sig-button sig-button-outline">Kembali ke Detail</a>
            </div>
        </section>

        <div class="grid gap-6 xl:grid-cols-[1fr_360px]">
            <section class="sig-card overflow-hidden">
                <div class="relative min-h-[640px] bg-slate-100">
                    <div class="absolute inset-0 sig-grid-bg"></div>
                    <div class="map-marker map-marker-flood map-marker-selected left-[30%] top-[60%] h-6 w-6"></div>
                    <div class="map-marker map-marker-evacuation map-marker-recommended left-[66%] top-[34%] h-6 w-6"></div>
                    <svg class="absolute inset-0 h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                        <path d="M30 60 C38 50 45 49 52 42 C58 37 62 35 66 34" fill="none" stroke="#0058be" stroke-width="1.2" stroke-linecap="round" />
                        <path d="M30 60 C38 50 45 49 52 42 C58 37 62 35 66 34" fill="none" stroke="#ffffff" stroke-width="0.35" stroke-linecap="round" />
                    </svg>
                    <div class="absolute bottom-4 left-4 rounded-xl border border-slate-200 bg-white/95 p-3 shadow-soft">
                        <p class="text-sm font-semibold text-primary">Pratinjau GeoJSON LineString</p>
                        <p class="font-technical mt-1 text-xs text-slate-500">Provider: OSRM</p>
                    </div>
                </div>
            </section>

            <aside class="space-y-6">
                <section class="sig-card p-5">
                    <h3 class="text-lg font-bold text-primary">Informasi Rute</h3>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="rounded-xl bg-red-50 p-3"><dt class="text-red-700">Asal</dt><dd class="mt-1 font-semibold text-primary">Banjir Teluk Betung Selatan</dd></div>
                        <div class="rounded-xl bg-teal-50 p-3"><dt class="text-teal-700">Tujuan</dt><dd class="mt-1 font-semibold text-primary">Masjid Al-Furqon Lungsir</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Jarak</dt><dd class="font-technical font-semibold text-primary">1.8 km</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Durasi</dt><dd class="font-technical font-semibold text-primary">7 menit</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Provider</dt><dd class="font-technical font-semibold text-primary">OSRM</dd></div>
                    </dl>
                </section>

                <section class="rounded-2xl border border-amber-100 bg-amber-50 p-4 text-sm leading-6 text-amber-800">
                    Rute ini bersifat referensi dan belum mempertimbangkan jalan tertutup akibat banjir.
                </section>
            </aside>
        </div>
    </div>
@endsection
