@extends('layouts.admin')

@section('title', 'Detail Titik Evakuasi')
@section('eyebrow', 'Detail Titik Evakuasi')
@section('page-title', 'Detail Titik Evakuasi')

@section('content')
    @php
        $statusClasses = [
            'aktif' => 'border-teal-100 bg-teal-50 text-teal-700',
            'penuh' => 'border-amber-100 bg-amber-50 text-amber-700',
            'tidak_aktif' => 'border-slate-200 bg-slate-100 text-slate-600',
        ];
        $typeClasses = [
            'sekolah' => 'border-blue-100 bg-blue-50 text-blue-700',
            'masjid' => 'border-teal-100 bg-teal-50 text-teal-700',
            'gedung_pemerintah' => 'border-indigo-100 bg-indigo-50 text-indigo-700',
            'aula' => 'border-violet-100 bg-violet-50 text-violet-700',
            'lapangan' => 'border-emerald-100 bg-emerald-50 text-emerald-700',
            'puskesmas' => 'border-red-100 bg-red-50 text-red-700',
        ];
        $dataClasses = [
            'nyata' => 'border-emerald-100 bg-emerald-50 text-emerald-700',
            'dummy' => 'border-yellow-100 bg-yellow-50 text-yellow-700',
            'simulasi' => 'border-blue-100 bg-blue-50 text-blue-700',
        ];
        $formatCoordinate = fn ($value) => $value === null ? '-' : number_format((float) $value, 6, '.', '');
        $formatLabel = fn ($value) => ucfirst(str_replace('_', ' ', (string) $value));
    @endphp

    <div class="space-y-6">
        <section class="sig-card p-5">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
                <div>
                    <div class="flex flex-wrap gap-2">
                        <span class="sig-badge border {{ $typeClasses[$evacuationPoint->type] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ $formatLabel($evacuationPoint->type) }}</span>
                        <span class="sig-badge border {{ $statusClasses[$evacuationPoint->status] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ $formatLabel($evacuationPoint->status) }}</span>
                        <span class="sig-badge border {{ $dataClasses[$evacuationPoint->data_status] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ $evacuationPoint->data_status }}</span>
                        @if ($evacuationPoint->is_verified)
                            <span class="sig-badge border border-emerald-100 bg-emerald-50 text-emerald-700">Terverifikasi</span>
                        @else
                            <span class="sig-badge border border-amber-100 bg-amber-50 text-amber-700">Perlu Validasi</span>
                        @endif
                    </div>
                    <h2 class="mt-4 text-2xl font-bold tracking-tight text-primary">{{ $evacuationPoint->name }}</h2>
                    <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">
                        {{ $evacuationPoint->description ?: 'Belum ada catatan tambahan untuk titik evakuasi ini.' }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('map') }}" class="sig-button sig-button-outline">Buka Peta Publik</a>
                    <a href="{{ route('admin.evacuation-points.edit', $evacuationPoint) }}" class="sig-button sig-button-primary">Edit Data</a>
                    <a href="{{ route('admin.evacuation-points.index') }}" class="sig-button sig-button-outline">Kembali</a>
                </div>
            </div>
        </section>

        <div class="grid items-start gap-6 xl:grid-cols-[1.2fr_.8fr]">
            <section class="sig-card sig-detail-map-card">
                <div class="sig-section-header flex flex-col gap-2 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-secondary">Konteks Spasial</p>
                        <h3 class="mt-1 text-lg font-bold text-primary">Mini Map Titik Evakuasi</h3>
                    </div>
                    <span class="sig-badge border border-teal-100 bg-teal-50 text-teal-700">Leaflet + OSM</span>
                </div>
                @if ($evacuationPoint->longitude !== null && $evacuationPoint->latitude !== null)
                    <div
                        id="admin-spatial-detail-map"
                        class="admin-spatial-detail-map sig-detail-map"
                        data-map-type="evacuation"
                        data-name="{{ $evacuationPoint->name }}"
                        data-longitude="{{ $formatCoordinate($evacuationPoint->longitude) }}"
                        data-latitude="{{ $formatCoordinate($evacuationPoint->latitude) }}"
                        data-record-type="{{ $evacuationPoint->type }}"
                        data-status="{{ $evacuationPoint->status }}"
                        data-capacity="{{ $evacuationPoint->capacity }}"
                        data-district="{{ $evacuationPoint->district }}"
                        data-data-status="{{ $evacuationPoint->data_status }}"
                    ></div>
                @else
                    <div class="flex min-h-[320px] items-center justify-center bg-slate-50 p-6 text-center text-sm leading-6 text-slate-500 lg:min-h-[460px]">
                        Koordinat belum tersedia untuk menampilkan mini map.
                    </div>
                @endif
            </section>

            <aside class="space-y-6">
                <section class="sig-card p-5">
                    <h3 class="text-lg font-bold text-primary">Informasi Evakuasi</h3>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Jenis</dt><dd class="font-semibold text-slate-700">{{ $formatLabel($evacuationPoint->type) }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Status</dt><dd class="font-semibold text-slate-700">{{ $formatLabel($evacuationPoint->status) }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Kapasitas</dt><dd class="font-technical font-semibold text-primary">{{ $evacuationPoint->capacity !== null ? number_format($evacuationPoint->capacity).' orang' : '-' }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Kecamatan</dt><dd class="font-semibold text-slate-700">{{ $evacuationPoint->district ?: '-' }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Kelurahan</dt><dd class="font-semibold text-slate-700">{{ $evacuationPoint->subdistrict ?: '-' }}</dd></div>
                    </dl>
                </section>

                <section class="sig-card p-5">
                    <h3 class="text-lg font-bold text-primary">Fasilitas dan Kontak</h3>
                    <div class="mt-4 space-y-3">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Fasilitas</p>
                            <p class="mt-1 text-sm leading-6 text-slate-600">{{ $evacuationPoint->facilities ?: 'Belum ada fasilitas yang dicatat.' }}</p>
                        </div>
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Penanggung Jawab</p>
                                <p class="mt-1 text-sm font-semibold text-slate-700">{{ $evacuationPoint->contact_person ?: '-' }}</p>
                            </div>
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                                <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Telepon</p>
                                <p class="font-technical mt-1 text-sm font-semibold text-slate-700">{{ $evacuationPoint->contact_phone ?: '-' }}</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="sig-card p-5">
                    <h3 class="text-lg font-bold text-primary">Alamat dan Koordinat</h3>
                    <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-3">
                        <p class="text-sm leading-6 text-slate-600">{{ $evacuationPoint->address ?: 'Alamat belum diisi.' }}</p>
                        <div class="mt-3 grid grid-cols-2 gap-2">
                            <div class="rounded-lg bg-white p-3">
                                <p class="text-xs text-slate-500">Longitude</p>
                                <p class="font-technical mt-1 text-sm font-semibold text-primary">{{ $formatCoordinate($evacuationPoint->longitude) }}</p>
                            </div>
                            <div class="rounded-lg bg-white p-3">
                                <p class="text-xs text-slate-500">Latitude</p>
                                <p class="font-technical mt-1 text-sm font-semibold text-primary">{{ $formatCoordinate($evacuationPoint->latitude) }}</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="sig-card p-5">
                    <h3 class="text-lg font-bold text-primary">Sumber Data</h3>
                    <div class="mt-4 space-y-3">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Tipe Sumber</p>
                            <p class="mt-1 text-sm font-semibold text-slate-700">{{ $evacuationPoint->source_type }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Referensi Sumber</p>
                            <p class="mt-1 break-words text-sm leading-6 text-slate-600">{{ $evacuationPoint->source_reference ?: 'Belum ada referensi sumber.' }}</p>
                        </div>
                    </div>
                </section>
            </aside>
        </div>
    </div>
@endsection
