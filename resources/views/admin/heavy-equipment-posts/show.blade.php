@extends('layouts.admin')

@section('title', 'Detail Pos Alat Berat')
@section('eyebrow', 'Detail Pos Alat Berat')
@section('page-title', 'Detail Pos Alat Berat')

@section('content')
    @php
        $statusClasses = [
            'aktif' => 'border-teal-100 bg-teal-50 text-teal-700',
            'tidak_aktif' => 'border-slate-200 bg-slate-100 text-slate-600',
        ];
        $dataClasses = [
            'nyata' => 'border-emerald-100 bg-emerald-50 text-emerald-700',
            'dummy' => 'border-yellow-100 bg-yellow-50 text-yellow-700',
            'simulasi' => 'border-blue-100 bg-blue-50 text-blue-700',
        ];
        $unitStatusClasses = [
            'tersedia' => 'border-teal-100 bg-teal-50 text-teal-700',
            'digunakan' => 'border-blue-100 bg-blue-50 text-blue-700',
            'perawatan' => 'border-amber-100 bg-amber-50 text-amber-700',
            'tidak_aktif' => 'border-slate-200 bg-slate-100 text-slate-600',
        ];
        $formatCoordinate = fn ($value) => $value === null ? '-' : number_format((float) $value, 6, '.', '');
        $formatLabel = fn ($value) => ucfirst(str_replace('_', ' ', (string) $value));
        $totalQuantity = $post->units->sum('quantity');
        $availableQuantity = $post->units->sum('available_quantity');
        $typeCount = $post->units->pluck('equipment_type_id')->filter()->unique()->count();
    @endphp

    <div class="space-y-6">
        <section class="sig-card p-5">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
                <div>
                    <div class="flex flex-wrap gap-2">
                        <span class="sig-badge border {{ $statusClasses[$post->status] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ $formatLabel($post->status) }}</span>
                        <span class="sig-badge border {{ $dataClasses[$post->data_status] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ $post->data_status }}</span>
                        @if ($post->is_verified)
                            <span class="sig-badge border border-emerald-100 bg-emerald-50 text-emerald-700">Terverifikasi</span>
                        @else
                            <span class="sig-badge border border-amber-100 bg-amber-50 text-amber-700">Perlu Validasi</span>
                        @endif
                    </div>
                    <h2 class="mt-4 text-2xl font-bold tracking-tight text-primary">{{ $post->name }}</h2>
                    <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">
                        {{ $post->description ?: 'Belum ada catatan tambahan untuk pos alat berat ini.' }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.heavy-equipment-posts.edit', $post) }}" class="sig-button sig-button-primary">Edit Data</a>
                    <a href="{{ route('admin.heavy-equipment-posts.index') }}" class="sig-button sig-button-outline">Kembali</a>
                </div>
            </div>
        </section>

        <div class="grid gap-6 xl:grid-cols-[1.2fr_.8fr]">
            <section class="sig-card overflow-hidden">
                <div class="relative min-h-[430px] bg-slate-100">
                    <div class="absolute inset-0 sig-grid-bg"></div>
                    <div class="map-marker map-marker-equipment map-marker-selected left-[48%] top-[45%] h-6 w-6"></div>
                    <div class="absolute left-4 top-4 rounded-xl border border-slate-200 bg-white/95 p-3 text-sm shadow-soft">
                        <p class="font-semibold text-primary">Lokasi Pos Alat Berat</p>
                        <p class="font-technical mt-1 text-xs text-slate-500">{{ $formatCoordinate($post->longitude) }}, {{ $formatCoordinate($post->latitude) }}</p>
                    </div>
                    <div class="absolute bottom-4 left-4 max-w-sm rounded-xl border border-yellow-100 bg-yellow-50/95 p-3 text-sm leading-6 text-yellow-800 shadow-soft">
                        Mini map masih placeholder. Integrasi Leaflet dan GeoJSON dikerjakan pada fase peta final.
                    </div>
                </div>
            </section>

            <aside class="space-y-6">
                <section class="sig-card p-5">
                    <h3 class="text-lg font-bold text-primary">Ringkasan Unit</h3>
                    <div class="mt-4 grid grid-cols-3 gap-3">
                        <div class="rounded-xl border border-yellow-100 bg-yellow-50 p-3">
                            <p class="text-xs text-yellow-700">Tersedia</p>
                            <p class="font-technical mt-1 text-lg font-bold text-primary">{{ $availableQuantity }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <p class="text-xs text-slate-500">Total</p>
                            <p class="font-technical mt-1 text-lg font-bold text-primary">{{ $totalQuantity }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <p class="text-xs text-slate-500">Jenis</p>
                            <p class="font-technical mt-1 text-lg font-bold text-primary">{{ $typeCount }}</p>
                        </div>
                    </div>
                    <div class="mt-4 space-y-3">
                        @forelse ($post->units as $unit)
                            <div class="rounded-xl border border-slate-200 bg-white p-3">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-primary">{{ $formatLabel($unit->type?->name ?? 'Unit alat') }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $unit->notes ?: 'Tidak ada catatan unit.' }}</p>
                                    </div>
                                    <span class="sig-badge border {{ $unitStatusClasses[$unit->status] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ $formatLabel($unit->status) }}</span>
                                </div>
                                <p class="font-technical mt-3 text-sm font-semibold text-primary">{{ $unit->available_quantity }}/{{ $unit->quantity }} tersedia</p>
                            </div>
                        @empty
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-500">Belum ada unit alat pada pos ini.</div>
                        @endforelse
                    </div>
                </section>
            </aside>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <section class="sig-card p-5">
                <h3 class="text-lg font-bold text-primary">Informasi Pos</h3>
                <dl class="mt-4 space-y-3 text-sm">
                    <div class="flex justify-between gap-4"><dt class="text-slate-500">Status</dt><dd class="font-semibold text-slate-700">{{ $formatLabel($post->status) }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-slate-500">Kecamatan</dt><dd class="font-semibold text-slate-700">{{ $post->district ?: '-' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-slate-500">Kelurahan</dt><dd class="font-semibold text-slate-700">{{ $post->subdistrict ?: '-' }}</dd></div>
                </dl>
            </section>

            <section class="sig-card p-5">
                <h3 class="text-lg font-bold text-primary">Kontak</h3>
                <dl class="mt-4 space-y-3 text-sm">
                    <div><dt class="text-slate-500">Penanggung Jawab</dt><dd class="mt-1 font-semibold text-slate-700">{{ $post->contact_person ?: '-' }}</dd></div>
                    <div><dt class="text-slate-500">Telepon</dt><dd class="font-technical mt-1 font-semibold text-slate-700">{{ $post->contact_phone ?: '-' }}</dd></div>
                </dl>
            </section>

            <section class="sig-card p-5">
                <h3 class="text-lg font-bold text-primary">Sumber Data</h3>
                <dl class="mt-4 space-y-3 text-sm">
                    <div class="flex justify-between gap-4"><dt class="text-slate-500">Tipe Sumber</dt><dd class="font-semibold text-slate-700">{{ $post->source_type }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-slate-500">Status Data</dt><dd class="font-semibold text-slate-700">{{ $post->data_status }}</dd></div>
                    <div><dt class="text-slate-500">Referensi</dt><dd class="mt-1 break-words text-slate-600">{{ $post->source_reference ?: '-' }}</dd></div>
                </dl>
            </section>
        </div>

        <section class="sig-card p-5">
            <h3 class="text-lg font-bold text-primary">Alamat dan Koordinat</h3>
            <p class="mt-2 text-sm leading-6 text-slate-600">{{ $post->address ?: 'Alamat belum diisi.' }}</p>
            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                    <p class="text-xs text-slate-500">Longitude</p>
                    <p class="font-technical mt-1 text-sm font-semibold text-primary">{{ $formatCoordinate($post->longitude) }}</p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                    <p class="text-xs text-slate-500">Latitude</p>
                    <p class="font-technical mt-1 text-sm font-semibold text-primary">{{ $formatCoordinate($post->latitude) }}</p>
                </div>
            </div>
        </section>
    </div>
@endsection
