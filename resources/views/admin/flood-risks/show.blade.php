@extends('layouts.admin')

@section('title', 'Detail Titik Rawan Banjir')
@section('eyebrow', 'Detail Titik Rawan')
@section('page-title', 'Detail Titik Rawan Banjir')

@section('content')
    @php
        $riskClasses = [
            'rendah' => 'border-teal-100 bg-teal-50 text-teal-700',
            'sedang' => 'border-amber-100 bg-amber-50 text-amber-700',
            'tinggi' => 'border-red-100 bg-red-50 text-red-700',
        ];
        $dataClasses = [
            'nyata' => 'border-emerald-100 bg-emerald-50 text-emerald-700',
            'dummy' => 'border-yellow-100 bg-yellow-50 text-yellow-700',
            'simulasi' => 'border-blue-100 bg-blue-50 text-blue-700',
        ];
        $formatCoordinate = fn ($value) => $value === null ? '-' : number_format((float) $value, 6, '.', '');
    @endphp

    <div class="space-y-6">
        <section class="sig-card p-5">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
                <div>
                    <div class="flex flex-wrap gap-2">
                        <span class="sig-badge border {{ $riskClasses[$floodRisk->risk_level] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">Risiko {{ ucfirst($floodRisk->risk_level) }}</span>
                        <span class="sig-badge border {{ $dataClasses[$floodRisk->data_status] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ $floodRisk->data_status }}</span>
                        @if ($floodRisk->is_verified)
                            <span class="sig-badge border border-emerald-100 bg-emerald-50 text-emerald-700">Terverifikasi</span>
                        @else
                            <span class="sig-badge border border-amber-100 bg-amber-50 text-amber-700">Perlu Validasi</span>
                        @endif
                    </div>
                    <h2 class="mt-4 text-2xl font-bold tracking-tight text-primary">{{ $floodRisk->name }}</h2>
                    <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">
                        {{ $floodRisk->description ?: 'Belum ada catatan tambahan untuk titik rawan ini.' }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('map') }}" class="sig-button sig-button-outline">Buka Peta Publik</a>
                    <a href="{{ route('admin.flood-risks.edit', $floodRisk) }}" class="sig-button sig-button-primary">Edit Data</a>
                    <a href="{{ route('admin.flood-risks.index') }}" class="sig-button sig-button-outline">Kembali</a>
                </div>
            </div>
        </section>

        <div class="grid items-start gap-6 xl:grid-cols-[1.2fr_.8fr]">
            <section class="sig-card sig-detail-map-card">
                <div class="sig-section-header flex flex-col gap-2 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-secondary">Konteks Spasial</p>
                        <h3 class="mt-1 text-lg font-bold text-primary">Mini Map Titik Rawan</h3>
                    </div>
                    <span class="sig-badge border border-amber-100 bg-amber-50 text-amber-700">Leaflet + OSM</span>
                </div>
                @if ($floodRisk->longitude !== null && $floodRisk->latitude !== null)
                    <div
                        id="admin-spatial-detail-map"
                        class="admin-spatial-detail-map sig-detail-map"
                        data-map-type="risk"
                        data-name="{{ $floodRisk->name }}"
                        data-longitude="{{ $formatCoordinate($floodRisk->longitude) }}"
                        data-latitude="{{ $formatCoordinate($floodRisk->latitude) }}"
                        data-risk-level="{{ $floodRisk->risk_level }}"
                        data-district="{{ $floodRisk->district }}"
                        data-data-status="{{ $floodRisk->data_status }}"
                    ></div>
                @else
                    <div class="flex min-h-[320px] items-center justify-center bg-slate-50 p-6 text-center text-sm leading-6 text-slate-500 lg:min-h-[460px]">
                        Koordinat belum tersedia untuk menampilkan mini map.
                    </div>
                @endif
            </section>

            <aside class="space-y-6">
                <section class="sig-card p-5">
                    <h3 class="text-lg font-bold text-primary">Informasi Lokasi</h3>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Kecamatan</dt><dd class="font-semibold text-slate-700">{{ $floodRisk->district ?: '-' }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Kelurahan</dt><dd class="font-semibold text-slate-700">{{ $floodRisk->subdistrict ?: '-' }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Risk Level</dt><dd class="font-semibold text-slate-700">{{ ucfirst($floodRisk->risk_level) }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Dibuat Oleh</dt><dd class="font-semibold text-right text-slate-700">{{ $floodRisk->creator?->name ?: '-' }}</dd></div>
                    </dl>
                </section>

                <section class="sig-card p-5">
                    <h3 class="text-lg font-bold text-primary">Alamat dan Koordinat</h3>
                    <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-3">
                        <p class="text-sm leading-6 text-slate-600">{{ $floodRisk->address ?: 'Alamat belum diisi.' }}</p>
                        <div class="mt-3 grid grid-cols-2 gap-2">
                            <div class="rounded-lg bg-white p-3">
                                <p class="text-xs text-slate-500">Longitude</p>
                                <p class="font-technical mt-1 text-sm font-semibold text-primary">{{ $formatCoordinate($floodRisk->longitude) }}</p>
                            </div>
                            <div class="rounded-lg bg-white p-3">
                                <p class="text-xs text-slate-500">Latitude</p>
                                <p class="font-technical mt-1 text-sm font-semibold text-primary">{{ $formatCoordinate($floodRisk->latitude) }}</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="sig-card p-5">
                    <h3 class="text-lg font-bold text-primary">Sumber Data</h3>
                    <div class="mt-4 space-y-3">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Tipe Sumber</p>
                            <p class="mt-1 text-sm font-semibold text-slate-700">{{ $floodRisk->source_type }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Referensi Sumber</p>
                            <p class="mt-1 break-words text-sm leading-6 text-slate-600">{{ $floodRisk->source_reference ?: 'Belum ada referensi sumber.' }}</p>
                        </div>
                    </div>
                </section>
            </aside>
        </div>
    </div>
@endsection
