@extends('layouts.admin')

@section('title', 'Analisis Spasial')
@section('eyebrow', 'Rekomendasi Lokasi Terdekat')
@section('page-title', 'Analisis Spasial')

@section('content')
    <div class="space-y-6">
        <section class="sig-card p-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <span class="sig-badge bg-blue-50 text-blue-700">Pratinjau UI</span>
                    <h2 class="mt-3 text-xl font-bold text-primary">Rekomendasi Evakuasi dan Alat Berat</h2>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                        Hitung rekomendasi evakuasi dan pos alat berat berdasarkan jarak spasial. Tampilan ini belum menjalankan query PostGIS.
                    </p>
                </div>
                <span class="sig-badge border border-slate-200 bg-slate-50 text-slate-600">Belum terhubung database</span>
            </div>
        </section>

        <div class="grid gap-6 xl:grid-cols-[330px_1fr_360px]">
            <aside class="space-y-6">
                <section class="sig-card p-5">
                    <h2 class="text-lg font-bold text-primary">Parameter Analisis</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Form ini hanya pratinjau UI. Perhitungan final nanti memakai PostGIS di backend.</p>
                    <div class="mt-5 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Pilih Kejadian Banjir</label>
                            <select class="sig-input mt-2">
                                <option>Banjir Teluk Betung Selatan</option>
                                <option>Genangan Way Halim</option>
                                <option>Banjir Korpri Sukarame</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700">Batas Hasil</label>
                                <input class="sig-input font-technical mt-2" value="3">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700">Jarak Maksimum</label>
                                <input class="sig-input font-technical mt-2" value="10 km">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Jenis Alat</label>
                            <select class="sig-input mt-2">
                                <option>Semua jenis</option>
                                <option>excavator</option>
                                <option>dump_truck</option>
                                <option>pompa_air</option>
                            </select>
                        </div>
                        <button type="button" class="sig-button sig-button-primary w-full">Cari Evakuasi Terdekat</button>
                        <button type="button" class="sig-button sig-button-outline w-full">Cari Pos Alat Berat</button>
                    </div>
                </section>

                <section class="rounded-2xl border border-blue-100 bg-blue-50 p-4 text-sm leading-6 text-blue-800">
                    Menghitung jarak spasial... State memuat ini contoh visual. Tidak ada query PostGIS yang dijalankan.
                </section>
            </aside>

            <section class="sig-card overflow-hidden">
                <div class="relative min-h-[680px] bg-slate-100">
                    <div class="absolute inset-0 sig-grid-bg"></div>
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(0,88,190,0.11),transparent_34%)]"></div>
                    <div class="map-marker map-marker-flood map-marker-selected left-[36%] top-[58%] h-6 w-6"></div>
                    <div class="map-marker map-marker-evacuation map-marker-recommended left-[54%] top-[42%] h-5 w-5"></div>
                    <div class="map-marker map-marker-equipment left-[68%] top-[62%] h-5 w-5"></div>
                    <div class="map-marker map-marker-evacuation left-[44%] top-[28%] h-4 w-4"></div>
                    <svg class="absolute inset-0 h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                        <path d="M36 58 C44 49 48 45 54 42" fill="none" stroke="#0058be" stroke-width="0.8" stroke-dasharray="2 1.2" stroke-linecap="round" />
                        <path d="M36 58 C48 61 58 64 68 62" fill="none" stroke="#FBBF24" stroke-width="0.7" stroke-dasharray="1.6 1.4" stroke-linecap="round" />
                    </svg>
                    <div class="absolute bottom-4 left-4 rounded-xl border border-slate-200 bg-white/95 p-3 shadow-soft">
                        <p class="text-sm font-semibold text-primary">Pratinjau peta analisis</p>
                        <p class="font-technical mt-1 text-xs text-slate-500">Preview ST_Distance</p>
                    </div>
                </div>
            </section>

            <aside class="space-y-6">
                <section class="sig-card p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-secondary">Hasil Analisis</p>
                            <h2 class="mt-1 text-lg font-bold text-primary">Evakuasi Terdekat</h2>
                        </div>
                        <span class="sig-badge bg-teal-50 text-teal-700">Terdekat</span>
                    </div>
                    <div class="mt-4 rounded-xl border border-teal-100 bg-teal-50/50 p-4">
                        <p class="font-semibold text-primary">Masjid Al-Furqon Lungsir</p>
                        <p class="mt-1 text-sm text-slate-600">Kapasitas 180 · status aktif</p>
                        <p class="font-technical mt-3 text-xl font-semibold text-primary">1.8 km</p>
                    </div>
                </section>

                <section class="sig-card p-5">
                    <h2 class="text-lg font-bold text-primary">Pos Alat Berat Terdekat</h2>
                    <div class="mt-4 space-y-3">
                        @foreach ([['Pos Alat Berat Panjang', '3.2 km', 'Excavator 2'], ['Gudang Logistik Rajabasa', '5.4 km', 'Dump truck 3'], ['Pos Pembantu Kemiling', '6.9 km', 'Wheel loader 1']] as $index => $item)
                            <div class="rounded-xl border {{ $index === 0 ? 'border-yellow-100 bg-yellow-50/60' : 'border-slate-200 bg-white' }} p-3">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-primary">{{ $item[0] }}</p>
                                        <p class="mt-1 text-sm text-slate-500">{{ $item[2] }}</p>
                                    </div>
                                    <span class="font-technical text-sm font-semibold text-primary">{{ $item[1] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </aside>
        </div>
    </div>
@endsection
