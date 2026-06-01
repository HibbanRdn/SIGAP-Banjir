@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('eyebrow', 'Dashboard Respons Banjir')
@section('page-title', 'Dashboard Admin')

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
            'unverified' => 'border-amber-100 bg-amber-50 text-amber-700',
        ];
        $formatLabel = fn ($value) => ucfirst(str_replace('_', ' ', (string) $value));
        $formatStatNumber = fn ($value) => str_pad((string) (int) $value, 2, '0', STR_PAD_LEFT);
        $formatDate = fn ($value) => $value ? $value->format('d M Y H:i') : '-';
        $formatTime = fn ($value) => $value ? $value->diffForHumans() : '-';

        $statusCards = [
            ['key' => 'nyata', 'label' => 'Data nyata', 'value' => $dataStatusSummary['totals']['nyata'] ?? 0],
            ['key' => 'simulasi', 'label' => 'Data simulasi', 'value' => $dataStatusSummary['totals']['simulasi'] ?? 0],
            ['key' => 'dummy', 'label' => 'Data dummy', 'value' => $dataStatusSummary['totals']['dummy'] ?? 0],
            ['key' => 'unverified', 'label' => 'Belum diverifikasi', 'value' => $dataStatusSummary['unverified_total'] ?? 0],
        ];

        $quickActions = [
            [
                'label' => 'Tambah Kejadian Banjir',
                'hint' => 'Catat laporan kejadian baru',
                'tone' => 'border-red-100 bg-red-50/60 text-red-700',
                'href' => route('admin.flood-events.create'),
            ],
            [
                'label' => 'Kelola Titik Rawan',
                'hint' => 'Perbarui layer risiko banjir',
                'tone' => 'border-amber-100 bg-amber-50/70 text-amber-700',
                'href' => route('admin.flood-risks.index'),
            ],
            [
                'label' => 'Kelola Titik Evakuasi',
                'hint' => 'Cek kapasitas dan status titik aman',
                'tone' => 'border-teal-100 bg-teal-50/70 text-teal-700',
                'href' => route('admin.evacuation-points.index'),
            ],
            [
                'label' => 'Kelola Pos Alat Berat',
                'hint' => 'Pantau pos dan unit respons',
                'tone' => 'border-yellow-100 bg-yellow-50/70 text-yellow-700',
                'href' => route('admin.heavy-equipment-posts.index'),
            ],
            [
                'label' => 'Kelola Jenis & Unit Alat',
                'hint' => 'Atur inventaris alat berat',
                'tone' => 'border-slate-200 bg-white text-slate-700',
                'href' => route('admin.equipment.index'),
            ],
            [
                'label' => 'Buka Peta Publik',
                'hint' => 'Lihat layer GeoJSON dan rute referensi',
                'tone' => 'border-blue-100 bg-blue-50/70 text-blue-700',
                'href' => route('map'),
            ],
        ];
    @endphp

    <div class="space-y-6">
        <section class="sig-reveal overflow-hidden rounded-2xl border border-slate-200 bg-primary text-white shadow-soft">
            <div class="relative p-6 sm:p-7">
                <div class="absolute inset-y-0 right-0 hidden w-1/2 sig-grid-bg opacity-10 lg:block"></div>
                <div class="relative flex flex-col gap-5 xl:flex-row xl:items-end xl:justify-between">
                    <div class="max-w-3xl">
                        <span class="sig-badge border-white/10 bg-white/10 text-white">PostgreSQL + PostGIS</span>
                        <h2 class="mt-4 text-2xl font-bold tracking-tight sm:text-3xl">SIGAP Banjir Bandar Lampung</h2>
                        <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-200">
                            Dashboard ini membaca data kejadian banjir, titik rawan, titik evakuasi, pos alat berat, dan status validasi langsung dari database SIGAP Banjir.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.flood-events.create') }}" class="sig-button border border-white/10 bg-white text-primary hover:bg-slate-100">Tambah Kejadian</a>
                        <a href="{{ route('map') }}" class="sig-button border border-white/10 bg-white/10 text-white hover:bg-white/15">Buka Peta Publik</a>
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
                <span class="hidden text-sm text-slate-500 sm:inline">Sumber data internal SIGAP</span>
            </div>

            <div class="sig-stagger grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
                @foreach ($stats as $stat)
                    <article class="sig-card p-4 transition duration-150 hover:-translate-y-0.5 hover:border-slate-300">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-500">{{ $stat['label'] }}</p>
                                <p class="font-technical mt-3 text-3xl font-semibold tracking-tight text-primary">{{ $formatStatNumber($stat['value']) }}</p>
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
                    <a href="{{ route('admin.flood-events.index') }}" class="sig-button sig-button-outline">Lihat Semua</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                        <thead class="bg-surface-gray text-xs font-bold uppercase tracking-[0.08em] text-slate-500">
                            <tr>
                                <th class="px-5 py-3">Kejadian</th>
                                <th class="px-5 py-3">Lokasi</th>
                                <th class="px-5 py-3">Severity</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3">Waktu</th>
                                <th class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($recentEvents as $event)
                                @php
                                    $eventTime = $event->reported_at ?: $event->updated_at;
                                @endphp
                                <tr class="transition hover:bg-slate-50">
                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-primary">{{ $event->name }}</p>
                                        <p class="font-technical mt-1 text-xs text-slate-500">EVT-{{ str_pad((string) $event->id, 3, '0', STR_PAD_LEFT) }}</p>
                                    </td>
                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-slate-700">{{ $event->district ?: '-' }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $event->subdistrict ?: '-' }}</p>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="sig-badge border {{ $severityClasses[$event->severity_level] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ ucfirst($event->severity_level) }}</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="sig-badge border {{ $statusClasses[$event->status] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ $formatLabel($event->status) }}</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <p class="font-technical text-xs text-slate-700">{{ $formatDate($eventTime) }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $formatTime($eventTime) }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <a href="{{ route('admin.flood-events.show', $event) }}" class="sig-button sig-button-ghost px-2.5 py-1.5">Lihat</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-12 text-center">
                                        <p class="font-semibold text-primary">Belum ada data kejadian banjir.</p>
                                        <p class="mt-2 text-sm text-slate-500">Tambahkan kejadian banjir untuk mulai menampilkan monitoring terbaru.</p>
                                    </td>
                                </tr>
                            @endforelse
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
                        <a href="{{ route('admin.equipment.index') }}" class="sig-button sig-button-outline px-3 py-2 text-xs">Kelola Unit</a>
                    </div>

                    <div class="space-y-3">
                        @forelse ($equipmentAvailability as $item)
                            <div class="rounded-xl border border-slate-200 bg-white p-3">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="font-semibold text-slate-700">{{ $item->label }}</span>
                                    <span class="font-technical rounded-lg bg-yellow-50 px-2 py-1 text-sm font-semibold text-yellow-700">{{ $formatStatNumber($item->available_quantity) }}/{{ $formatStatNumber($item->total_quantity) }}</span>
                                </div>
                                <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100">
                                    <div class="sig-progress-bar h-full rounded-full bg-resource-amber" style="width: {{ $item->percentage }}%"></div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-500">
                                Belum ada data unit alat berat.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="sig-card p-5" aria-labelledby="data-status">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-secondary">Status Data</p>
                    <h2 id="data-status" class="mt-1 text-lg font-bold text-primary">Transparansi Dataset</h2>
                    <div class="mt-4 grid grid-cols-2 gap-3">
                        @foreach ($statusCards as $status)
                            <div class="rounded-xl border p-3 {{ $dataClasses[$status['key']] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">
                                <p class="font-technical text-lg font-semibold">{{ $formatStatNumber($status['value']) }}</p>
                                <p class="mt-1 text-xs font-semibold">{{ $status['label'] }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 rounded-xl border border-blue-100 bg-blue-50 px-3 py-2 text-xs leading-5 text-blue-800">
                        Data simulasi dan dummy digunakan untuk kebutuhan demonstrasi akademik dan tidak diklaim sebagai data resmi.
                    </div>
                    <a href="{{ route('admin.data-sources.index', ['verification' => 'unverified']) }}" class="sig-button sig-button-outline mt-4 w-full px-3 py-2 text-xs">
                        Tinjau Data Perlu Validasi
                    </a>
                </div>
            </section>
        </div>

        <div class="grid gap-6 xl:grid-cols-[.9fr_1.1fr]">
            <section class="sig-card p-5" aria-labelledby="layer-summary">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-secondary">Layer Peta</p>
                        <h2 id="layer-summary" class="mt-1 text-lg font-bold text-primary">Ringkasan Layer Peta</h2>
                        <p class="mt-1 text-sm leading-6 text-slate-500">Jumlah data spasial yang tersedia untuk peta publik.</p>
                    </div>
                    <a href="{{ route('map') }}" class="sig-button sig-button-primary">Buka Peta Publik</a>
                </div>

                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                    @foreach ($layerSummary as $layer)
                        <div class="rounded-xl border border-slate-200 bg-white p-4">
                            <div class="flex items-center justify-between gap-3">
                                <p class="font-semibold text-slate-700">{{ $layer['label'] }}</p>
                                <span class="font-technical rounded-lg border px-2.5 py-1 text-sm font-semibold {{ $layer['tone'] }}">{{ $formatStatNumber($layer['value']) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="sig-card p-5" aria-labelledby="dataset-validation">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-secondary">Validasi Data</p>
                <h2 id="dataset-validation" class="mt-1 text-lg font-bold text-primary">Perlu Tinjauan Per Dataset</h2>
                <div class="mt-4 divide-y divide-slate-100 overflow-hidden rounded-xl border border-slate-200 bg-white">
                    @forelse ($dataStatusSummary['datasets'] as $dataset)
                        <div class="flex items-center justify-between gap-4 p-3">
                            <div>
                                <p class="font-semibold text-primary">{{ $dataset['label'] }}</p>
                                <p class="text-xs text-slate-500">Total data: <span class="font-technical">{{ $formatStatNumber($dataset['total']) }}</span></p>
                            </div>
                            <span class="font-technical rounded-lg border border-amber-100 bg-amber-50 px-2.5 py-1 text-sm font-semibold text-amber-700">
                                {{ $formatStatNumber($dataset['unverified']) }}
                            </span>
                        </div>
                    @empty
                        <div class="p-5 text-sm text-slate-500">Belum ada dataset spasial.</div>
                    @endforelse
                </div>
            </section>
        </div>

        <section class="sig-card p-5" aria-labelledby="quick-actions">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-secondary">Aksi Cepat</p>
                    <h2 id="quick-actions" class="mt-1 text-lg font-bold text-primary">Aksi Cepat</h2>
                </div>
                <p class="text-sm text-slate-500">Semua tautan mengarah ke modul yang sudah tersedia.</p>
            </div>

            <div class="sig-stagger mt-4 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($quickActions as $action)
                    <a href="{{ $action['href'] }}" class="group rounded-xl border p-4 transition duration-150 hover:-translate-y-0.5 hover:shadow-soft {{ $action['tone'] }}">
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
