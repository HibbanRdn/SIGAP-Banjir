@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('eyebrow', 'Dashboard Respons Banjir')
@section('page-title', 'Dashboard Admin')

@section('content')
    @php
        $stats = [
            ['label' => 'Banjir Aktif', 'value' => '04', 'hint' => 'Kejadian aktif untuk demo UI', 'tone' => 'bg-red-50 text-red-700 border-red-100', 'icon' => 'M4 14.25c2-2 4-2 6 0s4 2 6 0 3-1.5 4-1.5M4 18.25c2-2 4-2 6 0s4 2 6 0 3-1.5 4-1.5M5.75 10.25a6.25 6.25 0 1 1 12.5 0'],
            ['label' => 'Titik Rawan', 'value' => '12', 'hint' => 'Layer risiko banjir sementara', 'tone' => 'bg-amber-50 text-amber-700 border-amber-100', 'icon' => 'M12 3.75 21.25 20H2.75L12 3.75Zm0 5.75v4.5m0 3.25h.01'],
            ['label' => 'Titik Evakuasi Aktif', 'value' => '08', 'hint' => 'Belum tersambung database', 'tone' => 'bg-teal-50 text-teal-700 border-teal-100', 'icon' => 'M12 3.75 20.25 7.5v5.75c0 4.5-3.25 7-8.25 8-5-1-8.25-3.5-8.25-8V7.5L12 3.75Zm-3 8.75 2 2 4-4'],
            ['label' => 'Unit Alat Tersedia', 'value' => '45', 'hint' => 'Data dummy untuk pratinjau inventaris', 'tone' => 'bg-yellow-50 text-yellow-700 border-yellow-100', 'icon' => 'M3.75 15.25h10.5V7.75H3.75v7.5Zm10.5 0h2.5l2-3h1.5v3h-6Zm-8.5 2.5h.01m10.5 0h.01'],
            ['label' => 'Data Perlu Validasi', 'value' => '06', 'hint' => 'Sumber/koordinat perlu dicek', 'tone' => 'bg-blue-50 text-blue-700 border-blue-100', 'icon' => 'M5.25 5.75c0-1.1 3.02-2 6.75-2s6.75.9 6.75 2-3.02 2-6.75 2-6.75-.9-6.75-2Zm0 0v12.5c0 1.1 3.02 2 6.75 2s6.75-.9 6.75-2V5.75m-13.5 6.25c0 1.1 3.02 2 6.75 2s6.75-.9 6.75-2'],
        ];

        $events = [
            ['name' => 'Banjir Teluk Betung Selatan', 'district' => 'Teluk Betung Selatan', 'severity' => 'Kritis', 'severityClass' => 'bg-red-50 text-red-700 border-red-100', 'status' => 'Aktif', 'statusClass' => 'bg-red-50 text-red-700 border-red-100', 'updated' => '10 menit lalu'],
            ['name' => 'Genangan Way Halim', 'district' => 'Way Halim', 'severity' => 'Tinggi', 'severityClass' => 'bg-orange-50 text-orange-700 border-orange-100', 'status' => 'Ditangani', 'statusClass' => 'bg-blue-50 text-blue-700 border-blue-100', 'updated' => '28 menit lalu'],
            ['name' => 'Banjir Korpri Sukarame', 'district' => 'Sukarame', 'severity' => 'Sedang', 'severityClass' => 'bg-amber-50 text-amber-700 border-amber-100', 'status' => 'Surut', 'statusClass' => 'bg-slate-100 text-slate-600 border-slate-200', 'updated' => '1 jam lalu'],
        ];

        $equipment = [
            ['name' => 'Excavator', 'available' => '06', 'total' => '08', 'tone' => 'bg-yellow-50 text-yellow-700'],
            ['name' => 'Dump Truck', 'available' => '14', 'total' => '18', 'tone' => 'bg-yellow-50 text-yellow-700'],
            ['name' => 'Pompa Air', 'available' => '10', 'total' => '12', 'tone' => 'bg-teal-50 text-teal-700'],
            ['name' => 'Pickup Operasional', 'available' => '15', 'total' => '18', 'tone' => 'bg-blue-50 text-blue-700'],
        ];

        $quickActions = [
            ['label' => 'Tambah Kejadian Banjir', 'hint' => 'Form statis untuk pratinjau UI', 'tone' => 'border-red-100 bg-red-50/50 text-red-700', 'href' => route('admin.flood-events.create')],
            ['label' => 'Buka Peta Banjir', 'hint' => 'Lihat map explorer publik', 'tone' => 'border-blue-100 bg-blue-50/60 text-blue-700', 'href' => route('map')],
            ['label' => 'Cari Evakuasi Terdekat', 'hint' => 'Halaman analisis statis', 'tone' => 'border-teal-100 bg-teal-50/60 text-teal-700', 'href' => route('admin.spatial-analysis.index')],
            ['label' => 'Kelola Pos Alat Berat', 'hint' => 'Pratinjau manajemen pos', 'tone' => 'border-yellow-100 bg-yellow-50/70 text-yellow-700', 'href' => route('admin.heavy-equipment-posts.index')],
        ];
    @endphp

    <div class="space-y-6">
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-primary text-white shadow-soft">
            <div class="relative p-6 sm:p-7">
                <div class="absolute inset-y-0 right-0 hidden w-1/2 sig-grid-bg opacity-10 lg:block"></div>
                <div class="relative max-w-3xl">
                    <span class="sig-badge border-white/10 bg-white/10 text-white">Mode Demo Akademik</span>
                    <h2 class="mt-4 text-2xl font-bold tracking-tight sm:text-3xl">SIGAP Banjir Bandar Lampung</h2>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-200">
                        Fondasi UI admin untuk pengelolaan data banjir, titik evakuasi, pos alat berat, dan analisis spasial. Data pada halaman ini masih dummy untuk pratinjau UI dan belum terhubung ke database.
                    </p>
                    <div class="mt-5 flex flex-wrap gap-2">
                        <span class="sig-badge bg-white/10 text-white">Laravel Blade</span>
                        <span class="sig-badge bg-white/10 text-white">Tailwind CSS</span>
                        <span class="sig-badge bg-white/10 text-white">Siap PostGIS</span>
                    </div>
                </div>
            </div>
        </section>

        <section aria-labelledby="overview-statistics">
            <div class="mb-3 flex items-end justify-between gap-3">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-secondary">Ringkasan Statistik</p>
                    <h2 id="overview-statistics" class="mt-1 text-lg font-bold text-primary">Ringkasan Data Respons</h2>
                </div>
                <span class="hidden text-sm text-slate-500 sm:inline">Sebelum fase database</span>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
                @foreach ($stats as $stat)
                    <article class="sig-card p-4 transition duration-150 hover:-translate-y-0.5 hover:border-slate-300">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-500">{{ $stat['label'] }}</p>
                                <p class="font-technical mt-3 text-3xl font-semibold tracking-tight text-primary">{{ $stat['value'] }}</p>
                            </div>
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl border {{ $stat['tone'] }}">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="{{ $stat['icon'] }}" />
                                </svg>
                            </span>
                        </div>
                        <p class="mt-3 text-sm leading-5 text-slate-500">{{ $stat['hint'] }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <div class="grid gap-6 xl:grid-cols-[1.45fr_.85fr]">
            <section class="sig-card overflow-hidden" aria-labelledby="recent-events">
                <div class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-secondary">Kejadian Terbaru</p>
                        <h2 id="recent-events" class="mt-1 text-lg font-bold text-primary">Kejadian Banjir Terbaru</h2>
                    </div>
                    <a href="#" class="sig-button sig-button-outline">Lihat Semua</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                        <thead class="bg-surface-gray text-xs font-bold uppercase tracking-[0.08em] text-slate-500">
                            <tr>
                                <th class="px-5 py-3">Kejadian</th>
                                <th class="px-5 py-3">Keparahan</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3">Diperbarui</th>
                                <th class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($events as $event)
                                <tr class="transition hover:bg-slate-50">
                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-primary">{{ $event['name'] }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $event['district'] }}</p>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="sig-badge border {{ $event['severityClass'] }}">{{ $event['severity'] }}</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="sig-badge border {{ $event['statusClass'] }}">{{ $event['status'] }}</span>
                                    </td>
                                    <td class="px-5 py-4 font-technical text-xs text-slate-500">{{ $event['updated'] }}</td>
                                    <td class="px-5 py-4 text-right">
                                        <a href="#" class="sig-button sig-button-ghost px-2.5 py-1.5">Lihat</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="space-y-6">
                <div class="sig-card p-5" aria-labelledby="equipment-availability">
                    <div class="mb-4 flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-secondary">Ketersediaan Alat</p>
                            <h2 id="equipment-availability" class="mt-1 text-lg font-bold text-primary">Ketersediaan Alat</h2>
                        </div>
                        <span class="sig-badge bg-yellow-50 text-yellow-700">Data Dummy</span>
                    </div>

                    <div class="space-y-3">
                        @foreach ($equipment as $item)
                            <div class="rounded-xl border border-slate-200 bg-white p-3">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="font-semibold text-slate-700">{{ $item['name'] }}</span>
                                    <span class="font-technical rounded-lg px-2 py-1 text-sm font-semibold {{ $item['tone'] }}">{{ $item['available'] }}/{{ $item['total'] }}</span>
                                </div>
                                <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full rounded-full bg-resource-amber" style="width: {{ ((int) $item['available'] / (int) $item['total']) * 100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="sig-card p-5" aria-labelledby="data-status">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-secondary">Status Data</p>
                    <h2 id="data-status" class="mt-1 text-lg font-bold text-primary">Transparansi Dataset</h2>
                    <div class="mt-4 grid grid-cols-2 gap-3">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <p class="font-technical text-lg font-semibold text-primary">18</p>
                            <p class="text-xs text-slate-500">Data nyata</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <p class="font-technical text-lg font-semibold text-primary">22</p>
                            <p class="text-xs text-slate-500">Data dummy</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <p class="font-technical text-lg font-semibold text-primary">09</p>
                            <p class="text-xs text-slate-500">Simulasi</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <p class="font-technical text-lg font-semibold text-primary">06</p>
                            <p class="text-xs text-slate-500">Perlu validasi</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <section class="sig-card p-5" aria-labelledby="quick-actions">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-secondary">Aksi Cepat</p>
                    <h2 id="quick-actions" class="mt-1 text-lg font-bold text-primary">Aksi Cepat</h2>
                </div>
                <p class="text-sm text-slate-500">Semua aksi masih sementara sampai fase fitur dimulai.</p>
            </div>

            <div class="mt-4 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($quickActions as $action)
                    <a href="{{ $action['href'] ?? '#' }}" class="group rounded-xl border p-4 transition duration-150 hover:-translate-y-0.5 hover:shadow-soft {{ $action['tone'] }}">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="font-semibold">{{ $action['label'] }}</p>
                                <p class="mt-1 text-sm leading-5 opacity-75">{{ $action['hint'] }}</p>
                            </div>
                            <span class="transition group-hover:translate-x-0.5" aria-hidden="true">→</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    </div>
@endsection
