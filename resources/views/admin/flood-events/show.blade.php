@extends('layouts.admin')

@section('title', 'Detail Kejadian Banjir')
@section('eyebrow', 'Detail Kejadian')
@section('page-title', 'Detail Kejadian Banjir')

@section('content')
    @php
        $severityClasses = [
            'rendah' => 'border-teal-100 bg-teal-50 text-teal-700',
            'sedang' => 'border-amber-100 bg-amber-50 text-amber-700',
            'tinggi' => 'border-orange-100 bg-orange-50 text-orange-700',
            'kritis' => 'border-red-100 bg-red-50 text-red-700',
        ];
        $statusClasses = [
            'aktif' => 'border-red-100 bg-red-50 text-red-700',
            'ditangani' => 'border-blue-100 bg-blue-50 text-blue-700',
            'surut' => 'border-slate-200 bg-slate-100 text-slate-600',
            'arsip' => 'border-slate-200 bg-slate-50 text-slate-500',
        ];
        $dataClasses = [
            'nyata' => 'border-emerald-100 bg-emerald-50 text-emerald-700',
            'dummy' => 'border-yellow-100 bg-yellow-50 text-yellow-700',
            'simulasi' => 'border-blue-100 bg-blue-50 text-blue-700',
        ];
        $formatCoordinate = fn ($value) => $value === null ? '-' : number_format((float) $value, 6, '.', '');
        $formatDate = fn ($value) => $value ? $value->format('d M Y H:i') : '-';
    @endphp

    <div class="space-y-6">
        <section class="sig-card p-5">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
                <div>
                    <div class="flex flex-wrap gap-2">
                        <span class="sig-badge border {{ $statusClasses[$floodEvent->status] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ ucfirst($floodEvent->status) }}</span>
                        <span class="sig-badge border {{ $severityClasses[$floodEvent->severity_level] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ ucfirst($floodEvent->severity_level) }}</span>
                        <span class="sig-badge border {{ $dataClasses[$floodEvent->data_status] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ $floodEvent->data_status }}</span>
                        @if ($floodEvent->is_verified)
                            <span class="sig-badge border border-emerald-100 bg-emerald-50 text-emerald-700">Terverifikasi</span>
                        @else
                            <span class="sig-badge border border-amber-100 bg-amber-50 text-amber-700">Perlu Validasi</span>
                        @endif
                    </div>
                    <h2 class="mt-4 text-2xl font-bold tracking-tight text-primary">{{ $floodEvent->name }}</h2>
                    <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">
                        {{ $floodEvent->description ?: 'Belum ada catatan tambahan untuk kejadian ini.' }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button type="button" disabled class="sig-button cursor-not-allowed border border-slate-200 bg-slate-100 text-slate-400">Cari Evakuasi Terdekat</button>
                    <button type="button" disabled class="sig-button cursor-not-allowed border border-slate-200 bg-slate-100 text-slate-400">Cari Pos Alat Berat</button>
                    <button type="button" disabled class="sig-button cursor-not-allowed border border-slate-200 bg-slate-100 text-slate-400">Tampilkan Rute</button>
                    <a href="{{ route('admin.flood-events.edit', $floodEvent) }}" class="sig-button sig-button-primary">Edit Data</a>
                    <a href="{{ route('admin.flood-events.index') }}" class="sig-button sig-button-outline">Kembali</a>
                </div>
            </div>
        </section>

        <div class="grid gap-6 xl:grid-cols-[1.2fr_.8fr]">
            <section class="sig-card overflow-hidden">
                <div class="relative min-h-[430px] bg-slate-100">
                    <div class="absolute inset-0 sig-grid-bg"></div>
                    <div class="map-marker map-marker-flood map-marker-selected left-[46%] top-[50%] h-6 w-6"></div>
                    <div class="absolute left-4 top-4 rounded-xl border border-slate-200 bg-white/95 p-3 text-sm shadow-soft">
                        <p class="font-semibold text-primary">Lokasi Kejadian</p>
                        <p class="font-technical mt-1 text-xs text-slate-500">{{ $formatCoordinate($floodEvent->longitude) }}, {{ $formatCoordinate($floodEvent->latitude) }}</p>
                    </div>
                    <div class="absolute bottom-4 left-4 max-w-sm rounded-xl border border-blue-100 bg-blue-50/95 p-3 text-sm leading-6 text-blue-800 shadow-soft">
                        Mini map masih placeholder. Integrasi Leaflet dan GeoJSON dikerjakan pada fase peta final.
                    </div>
                </div>
            </section>

            <aside class="space-y-6">
                <section class="sig-card p-5">
                    <h3 class="text-lg font-bold text-primary">Informasi Kejadian</h3>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Kecamatan</dt><dd class="font-semibold text-slate-700">{{ $floodEvent->district ?: '-' }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Kelurahan</dt><dd class="font-semibold text-slate-700">{{ $floodEvent->subdistrict ?: '-' }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Kedalaman Air</dt><dd class="font-technical font-semibold text-primary">{{ $floodEvent->water_depth_cm !== null ? $floodEvent->water_depth_cm.' cm' : '-' }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Waktu Kejadian</dt><dd class="font-technical text-right text-slate-700">{{ $formatDate($floodEvent->occurred_at) }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Waktu Laporan</dt><dd class="font-technical text-right text-slate-700">{{ $formatDate($floodEvent->reported_at) }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Dibuat Oleh</dt><dd class="font-semibold text-right text-slate-700">{{ $floodEvent->creator?->name ?: '-' }}</dd></div>
                    </dl>
                </section>

                <section class="sig-card p-5">
                    <h3 class="text-lg font-bold text-primary">Alamat dan Koordinat</h3>
                    <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-3">
                        <p class="text-sm leading-6 text-slate-600">{{ $floodEvent->address ?: 'Alamat belum diisi.' }}</p>
                        <div class="mt-3 grid grid-cols-2 gap-2">
                            <div class="rounded-lg bg-white p-3">
                                <p class="text-xs text-slate-500">Longitude</p>
                                <p class="font-technical mt-1 text-sm font-semibold text-primary">{{ $formatCoordinate($floodEvent->longitude) }}</p>
                            </div>
                            <div class="rounded-lg bg-white p-3">
                                <p class="text-xs text-slate-500">Latitude</p>
                                <p class="font-technical mt-1 text-sm font-semibold text-primary">{{ $formatCoordinate($floodEvent->latitude) }}</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="sig-card p-5">
                    <h3 class="text-lg font-bold text-primary">Sumber Data</h3>
                    <div class="mt-4 space-y-3">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Source Type</p>
                            <p class="mt-1 text-sm font-semibold text-slate-700">{{ $floodEvent->source_type }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Source Reference</p>
                            <p class="mt-1 break-words text-sm leading-6 text-slate-600">{{ $floodEvent->source_reference ?: 'Belum ada referensi sumber.' }}</p>
                        </div>
                    </div>
                </section>
            </aside>
        </div>

        <div class="rounded-2xl border border-blue-100 bg-blue-50 p-4 text-sm leading-6 text-blue-800">
            Rekomendasi evakuasi, pos alat berat, dan rute masih dinonaktifkan pada fase CRUD. Analisis final akan dihitung di backend menggunakan PostGIS dan routing provider.
        </div>
    </div>
@endsection
