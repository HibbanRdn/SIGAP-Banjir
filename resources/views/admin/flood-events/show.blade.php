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
        $formatLabel = fn ($value) => ucfirst(str_replace('_', ' ', (string) $value));
    @endphp

    <div class="space-y-6">
        <section class="sig-card p-5">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
                <div>
                    <div class="flex flex-wrap gap-2">
                        <span class="sig-badge border {{ $statusClasses[$floodEvent->status] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ $formatLabel($floodEvent->status) }}</span>
                        <span class="sig-badge border {{ $severityClasses[$floodEvent->severity_level] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ $formatLabel($floodEvent->severity_level) }}</span>
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
                    <button type="button" class="sig-button sig-button-outline" data-admin-detail-scroll="evacuations">Cari Evakuasi Terdekat</button>
                    <button type="button" class="sig-button sig-button-outline" data-admin-detail-scroll="equipment">Cari Pos Alat Berat</button>
                    <button type="button" class="sig-button sig-button-primary" data-admin-detail-route-nearest>Tampilkan Rute Evakuasi Terdekat</button>
                    <a href="{{ route('admin.flood-events.edit', $floodEvent) }}" class="sig-button sig-button-outline">Edit Data</a>
                    <a href="{{ route('admin.flood-events.index') }}" class="sig-button sig-button-ghost">Kembali</a>
                </div>
            </div>
        </section>

        @if (in_array($floodEvent->data_status, ['simulasi', 'dummy'], true))
            <div class="rounded-2xl border border-blue-100 bg-blue-50 p-4 text-sm leading-6 text-blue-800">
                Data kejadian ini digunakan untuk demonstrasi akademik dan tidak diklaim sebagai laporan resmi.
            </div>
        @endif

        <div class="grid items-start gap-6 xl:grid-cols-[1.2fr_.8fr]">
            <section class="space-y-6 xl:self-start">
                <section class="sig-card overflow-hidden" aria-labelledby="spatial-context">
                    <div class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-secondary">Konteks Spasial</p>
                            <h3 id="spatial-context" class="mt-1 text-lg font-bold text-primary">Peta Kejadian dan Resource Terdekat</h3>
                        </div>
                        <span class="sig-badge border border-blue-100 bg-blue-50 text-blue-700">Leaflet + OSM</span>
                    </div>
                    <div class="relative h-[360px] min-h-[360px] overflow-hidden bg-slate-100 lg:h-[460px]">
                        <div
                            id="admin-flood-event-map"
                            class="h-full w-full"
                            data-flood-event-id="{{ $floodEvent->id }}"
                            data-flood-event-name="{{ $floodEvent->name }}"
                            data-longitude="{{ $floodEvent->longitude }}"
                            data-latitude="{{ $floodEvent->latitude }}"
                            data-nearest-resources-url="{{ route('api.v1.analysis.flood-events.nearest-resources', $floodEvent) }}"
                            data-route-nearest-url="{{ route('api.v1.routing.flood-events.to-nearest-evacuation', $floodEvent) }}"
                            data-route-evacuation-base-url="{{ url('/api/v1/routing/flood-events/'.$floodEvent->id.'/to-evacuation') }}"
                        ></div>
                        <div class="pointer-events-none absolute left-4 top-4 z-[450] rounded-xl border border-slate-200 bg-white/95 p-3 text-sm shadow-soft backdrop-blur">
                            <p class="font-semibold text-primary">Lokasi Kejadian</p>
                            <p class="font-technical mt-1 text-xs text-slate-500">{{ $formatCoordinate($floodEvent->longitude) }}, {{ $formatCoordinate($floodEvent->latitude) }}</p>
                        </div>
                    </div>
                </section>

                <section class="sig-card overflow-hidden" aria-labelledby="route-info">
                    <div class="border-b border-slate-200 bg-blue-50/70 px-5 py-4">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.18em] text-blue-700">Rute Referensi</p>
                                <h3 id="route-info" class="mt-1 text-lg font-bold text-primary">Rute Evakuasi Referensi</h3>
                            </div>
                            <span class="sig-badge border border-blue-100 bg-white text-blue-700">Rute Referensi</span>
                        </div>
                    </div>
                    <div class="space-y-3 p-5">
                        <div id="admin-detail-route-empty" class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm leading-6 text-slate-500">
                            Pilih titik evakuasi atau tampilkan rute terdekat untuk melihat jalur referensi.
                        </div>
                        <div id="admin-detail-route-loading" class="hidden rounded-xl border border-blue-100 bg-blue-50 p-4 text-sm font-semibold text-blue-800">
                            Menghitung rute referensi...
                        </div>
                        <div id="admin-detail-route-error" class="hidden rounded-xl border border-red-100 bg-red-50 p-4 text-sm font-semibold text-red-700"></div>
                        <div id="admin-detail-route-panel" class="sig-route-panel hidden space-y-4">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <p class="text-sm text-slate-500">Tujuan evakuasi</p>
                                    <p id="admin-detail-route-destination" class="mt-1 font-bold text-primary">-</p>
                                </div>
                                <span id="admin-detail-route-provider" class="font-technical rounded-full border border-slate-200 bg-white px-2.5 py-1 text-xs font-bold uppercase text-slate-600">OSRM</span>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                                    <p class="text-xs text-slate-500">Jarak</p>
                                    <p id="admin-detail-route-distance" class="font-technical mt-1 font-semibold text-primary">-</p>
                                </div>
                                <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                                    <p class="text-xs text-slate-500">Durasi</p>
                                    <p id="admin-detail-route-duration" class="font-technical mt-1 font-semibold text-primary">-</p>
                                </div>
                            </div>
                            <p id="admin-detail-route-note" class="rounded-xl border border-blue-100 bg-blue-50 px-3 py-2 text-xs leading-5 text-blue-800">
                                Rute ini bersifat referensi dan belum mempertimbangkan jalan tertutup akibat banjir.
                            </p>
                        </div>
                    </div>
                </section>
            </section>

            <aside class="space-y-6">
                <section class="sig-card p-5" aria-labelledby="recommendations">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-secondary">Analisis PostGIS</p>
                            <h3 id="recommendations" class="mt-1 text-lg font-bold text-primary">Resource Terdekat</h3>
                        </div>
                        <button type="button" class="sig-button sig-button-outline px-3 py-2 text-xs" data-admin-detail-reload-resources>Muat Ulang</button>
                    </div>

                    <div id="admin-detail-resources-loading" class="mt-4 rounded-xl border border-blue-100 bg-blue-50 p-3 text-sm font-semibold text-blue-800">
                        Memuat rekomendasi spasial...
                    </div>
                    <div id="admin-detail-resources-error" class="hidden mt-4 rounded-xl border border-red-100 bg-red-50 p-3 text-sm font-semibold text-red-700"></div>

                    <div id="admin-detail-evacuations-section" class="mt-5">
                        <div class="mb-3 flex items-center justify-between">
                            <h4 class="text-sm font-bold text-primary">Titik Evakuasi Terdekat</h4>
                            <span class="sig-badge border border-teal-100 bg-teal-50 text-teal-700">aktif</span>
                        </div>
                        <div id="admin-detail-evacuations-list" class="space-y-3">
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-500">Memuat titik evakuasi...</div>
                        </div>
                    </div>

                    <div id="admin-detail-equipment-section" class="mt-6">
                        <div class="mb-3 flex items-center justify-between">
                            <h4 class="text-sm font-bold text-primary">Pos Alat Berat Terdekat</h4>
                            <span class="sig-badge border border-yellow-100 bg-yellow-50 text-yellow-700">tersedia</span>
                        </div>
                        <div id="admin-detail-equipment-list" class="space-y-3">
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-500">Memuat pos alat berat...</div>
                        </div>
                    </div>
                </section>

                <section class="sig-card p-5">
                    <h3 class="text-lg font-bold text-primary">Informasi Kejadian</h3>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Kecamatan</dt><dd class="font-semibold text-right text-slate-700">{{ $floodEvent->district ?: '-' }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Kelurahan</dt><dd class="font-semibold text-right text-slate-700">{{ $floodEvent->subdistrict ?: '-' }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Kedalaman Air</dt><dd class="font-technical font-semibold text-primary">{{ $floodEvent->water_depth_cm !== null ? $floodEvent->water_depth_cm.' cm' : '-' }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Waktu Kejadian</dt><dd class="font-technical text-right text-slate-700">{{ $formatDate($floodEvent->occurred_at) }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Waktu Laporan</dt><dd class="font-technical text-right text-slate-700">{{ $formatDate($floodEvent->reported_at) }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Dibuat Oleh</dt><dd class="font-semibold text-right text-slate-700">{{ $floodEvent->creator?->name ?: '-' }}</dd></div>
                    </dl>
                </section>
            </aside>
        </div>

        <div class="grid gap-6 xl:grid-cols-[.95fr_1.05fr]">
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
                <h3 class="text-lg font-bold text-primary">Sumber dan Status Data</h3>
                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Jenis Sumber</p>
                        <p class="mt-1 text-sm font-semibold text-slate-700">{{ $formatLabel($floodEvent->source_type) }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Status Data</p>
                        <p class="mt-1 text-sm font-semibold text-slate-700">{{ $floodEvent->data_status }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 sm:col-span-2">
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Referensi Sumber</p>
                        <p class="mt-1 break-words text-sm leading-6 text-slate-600">{{ $floodEvent->source_reference ?: 'Belum ada referensi sumber.' }}</p>
                    </div>
                </div>
            </section>
        </div>

        <div class="rounded-2xl border border-blue-100 bg-blue-50 p-4 text-sm leading-6 text-blue-800">
            Analisis jarak dihitung oleh backend menggunakan PostGIS. Rute evakuasi diambil dari Routing API backend dengan OSRM demo server dan bersifat referensi, bukan rute resmi kebencanaan.
        </div>
    </div>
@endsection
