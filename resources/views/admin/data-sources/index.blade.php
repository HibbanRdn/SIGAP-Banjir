@extends('layouts.admin')

@section('title', 'Sumber Data & Validasi')
@section('eyebrow', 'Transparansi Dataset')
@section('page-title', 'Sumber Data & Validasi')

@section('content')
    @php
        $formatStatNumber = fn ($value) => str_pad((string) (int) $value, 2, '0', STR_PAD_LEFT);
        $formatDate = fn ($value) => $value ? $value->format('d M Y H:i') : '-';

        $moduleClasses = [
            'flood_events' => 'border-red-100 bg-red-50 text-red-700',
            'flood_risk_points' => 'border-amber-100 bg-amber-50 text-amber-700',
            'evacuation_points' => 'border-teal-100 bg-teal-50 text-teal-700',
            'heavy_equipment_posts' => 'border-yellow-100 bg-yellow-50 text-yellow-700',
        ];
        $statusClasses = [
            'nyata' => 'border-emerald-100 bg-emerald-50 text-emerald-700',
            'simulasi' => 'border-blue-100 bg-blue-50 text-blue-700',
            'dummy' => 'border-yellow-100 bg-yellow-50 text-yellow-700',
        ];
    @endphp

    <div class="space-y-6">
        <section class="overflow-hidden rounded-2xl border border-blue-100 bg-blue-50 shadow-soft">
            <div class="relative p-5 sm:p-6">
                <div class="absolute inset-y-0 right-0 hidden w-1/3 sig-grid-bg opacity-25 lg:block"></div>
                <div class="relative flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
                    <div class="max-w-3xl">
                        <span class="sig-badge border border-blue-100 bg-white text-blue-700">Dataset SIGAP Banjir</span>
                        <h2 class="mt-3 text-xl font-bold tracking-tight text-primary">Transparansi Dataset SIGAP Banjir</h2>
                        <p class="mt-2 text-sm leading-6 text-blue-900/80">
                            Data berstatus simulasi dan dummy digunakan untuk kebutuhan demonstrasi akademik. Data tersebut tidak diklaim sebagai laporan resmi atau data operasional pemerintah. Status sumber dan verifikasi ditampilkan agar proses pengelolaan data tetap transparan.
                        </p>
                    </div>
                    <a href="{{ route('admin.data-sources.index', ['verification' => 'unverified']) }}" class="sig-button sig-button-primary">
                        Tinjau Data Perlu Validasi
                    </a>
                </div>
            </div>
        </section>

        <section aria-labelledby="data-source-statistics">
            <div class="mb-3 flex items-end justify-between gap-3">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-secondary">Ringkasan Data Real</p>
                    <h2 id="data-source-statistics" class="mt-1 text-lg font-bold text-primary">Statistik Transparansi Data</h2>
                </div>
                <span class="hidden text-sm text-slate-500 sm:inline">Bersumber dari empat tabel spasial utama</span>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
                @foreach ($stats as $stat)
                    <article class="sig-card p-4">
                        <span class="sig-badge border {{ $stat['tone'] }}">{{ $stat['label'] }}</span>
                        <p class="font-technical mt-4 text-3xl font-semibold tracking-tight text-primary">{{ $formatStatNumber($stat['value']) }}</p>
                        <p class="mt-2 text-sm leading-5 text-slate-500">{{ $stat['hint'] }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <div class="grid gap-6 xl:grid-cols-[.95fr_1.05fr]">
            <section class="sig-card p-5" aria-labelledby="module-coverage">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-secondary">Cakupan Validasi</p>
                        <h2 id="module-coverage" class="mt-1 text-lg font-bold text-primary">Cakupan Validasi per Modul</h2>
                    </div>
                    <span class="sig-badge border border-amber-100 bg-amber-50 text-amber-700">Read-only</span>
                </div>

                <div class="mt-5 space-y-3">
                    @foreach ($moduleCoverage as $module)
                        <div class="rounded-xl border border-slate-200 bg-white p-3">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="font-semibold text-primary">{{ $module['label'] }}</p>
                                    <p class="text-xs text-slate-500">
                                        <span class="font-technical">{{ $formatStatNumber($module['total']) }}</span> data
                                        <span class="mx-1">·</span>
                                        <span class="font-technical">{{ $formatStatNumber($module['unverified']) }}</span> perlu validasi
                                    </p>
                                </div>
                                <span class="font-technical rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                    {{ $module['verified_percentage'] }}% valid
                                </span>
                            </div>
                            <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full bg-secondary" style="width: {{ $module['verified_percentage'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="sig-card p-5" aria-labelledby="data-source-filters">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-secondary">Filter Monitoring</p>
                        <h2 id="data-source-filters" class="mt-1 text-lg font-bold text-primary">Pencarian dan Filter</h2>
                    </div>
                    @if ($hasFilters)
                        <span class="sig-badge border border-blue-100 bg-blue-50 text-blue-700">Filter aktif</span>
                    @endif
                </div>

                <form method="GET" action="{{ route('admin.data-sources.index') }}" class="mt-5 grid gap-3 sm:grid-cols-2">
                    <label class="sm:col-span-2">
                        <span class="mb-1 block text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Pencarian</span>
                        <input
                            type="search"
                            name="search"
                            value="{{ $filters['search'] }}"
                            class="sig-input"
                            placeholder="Cari nama, kecamatan, kelurahan, atau referensi sumber..."
                        >
                    </label>

                    <label>
                        <span class="mb-1 block text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Modul</span>
                        <select name="module" class="sig-input">
                            <option value="">Semua Modul</option>
                            @foreach ($options['modules'] as $module)
                                <option value="{{ $module['value'] }}" @selected($filters['module'] === $module['value'])>{{ $module['label'] }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label>
                        <span class="mb-1 block text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Status Data</span>
                        <select name="data_status" class="sig-input">
                            <option value="">Semua Status</option>
                            @foreach ($options['data_statuses'] as $status)
                                <option value="{{ $status['value'] }}" @selected($filters['data_status'] === $status['value'])>{{ $status['label'] }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label>
                        <span class="mb-1 block text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Jenis Sumber</span>
                        <select name="source_type" class="sig-input">
                            <option value="">Semua Sumber</option>
                            @foreach ($options['source_types'] as $source)
                                <option value="{{ $source['value'] }}" @selected($filters['source_type'] === $source['value'])>{{ $source['label'] }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label>
                        <span class="mb-1 block text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Verifikasi</span>
                        <select name="verification" class="sig-input">
                            <option value="">Semua Verifikasi</option>
                            @foreach ($options['verifications'] as $verification)
                                <option value="{{ $verification['value'] }}" @selected($filters['verification'] === $verification['value'])>{{ $verification['label'] }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="sm:col-span-2">
                        <span class="mb-1 block text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Kecamatan</span>
                        <select name="district" class="sig-input">
                            <option value="">Semua Kecamatan</option>
                            @foreach ($options['districts'] as $district)
                                <option value="{{ $district }}" @selected($filters['district'] === $district)>{{ $district }}</option>
                            @endforeach
                        </select>
                    </label>

                    <div class="flex flex-wrap gap-2 sm:col-span-2">
                        <button type="submit" class="sig-button sig-button-primary">Terapkan Filter</button>
                        <a href="{{ route('admin.data-sources.index') }}" class="sig-button sig-button-outline">Reset Filter</a>
                    </div>
                </form>
            </section>
        </div>

        <section class="sig-card overflow-hidden" aria-labelledby="data-source-table">
            <div class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-secondary">Monitoring Sumber</p>
                    <h2 id="data-source-table" class="mt-1 text-lg font-bold text-primary">Daftar Data Spasial dan Status Validasi</h2>
                </div>
                <p class="text-sm text-slate-500">
                    <span class="font-technical font-semibold text-primary">{{ $records->total() }}</span> data ditemukan
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-surface-gray text-xs font-bold uppercase tracking-[0.08em] text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Data / Nama Lokasi</th>
                            <th class="px-5 py-3">Modul</th>
                            <th class="px-5 py-3">Kecamatan</th>
                            <th class="px-5 py-3">Jenis Sumber</th>
                            <th class="px-5 py-3">Status Data</th>
                            <th class="px-5 py-3">Verifikasi</th>
                            <th class="px-5 py-3">Referensi</th>
                            <th class="px-5 py-3">Diperbarui</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($records as $record)
                            <tr class="transition hover:bg-slate-50">
                                <td class="max-w-xs px-5 py-4">
                                    <p class="font-semibold text-primary">{{ $record['name'] }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $record['subdistrict'] ?: 'Kelurahan belum diisi' }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="sig-badge border {{ $moduleClasses[$record['module']] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ $record['module_label'] }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="font-semibold text-slate-700">{{ $record['district'] ?: '-' }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="text-slate-700">{{ $record['source_label'] }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="sig-badge border {{ $statusClasses[$record['data_status']] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ $record['data_status_label'] }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    @if ($record['is_verified'])
                                        <span class="sig-badge border border-emerald-100 bg-emerald-50 text-emerald-700">Sudah Diverifikasi</span>
                                    @else
                                        <span class="sig-badge border border-amber-100 bg-amber-50 text-amber-700">Perlu Validasi</span>
                                    @endif
                                </td>
                                <td class="max-w-xs px-5 py-4">
                                    @if ($record['source_url'])
                                        <div class="space-y-1">
                                            <p class="truncate text-xs text-slate-500">{{ \Illuminate\Support\Str::limit($record['source_reference'], 42) }}</p>
                                            <a href="{{ $record['source_url'] }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-secondary hover:text-blue-800">Buka Sumber</a>
                                        </div>
                                    @elseif ($record['source_reference'])
                                        <p class="text-sm leading-5 text-slate-600">{{ \Illuminate\Support\Str::limit($record['source_reference'], 70) }}</p>
                                    @else
                                        <span class="text-sm text-slate-400">Belum ada referensi</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <span class="font-technical text-xs text-slate-600">{{ $formatDate($record['updated_at']) }}</span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ $record['detail_url'] }}" class="sig-button sig-button-ghost px-2.5 py-1.5 text-xs">Lihat Detail</a>
                                        <a href="{{ $record['edit_url'] }}" class="sig-button sig-button-outline px-2.5 py-1.5 text-xs">Edit Data</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-5 py-14 text-center">
                                    <p class="font-semibold text-primary">Tidak ada data sesuai filter yang dipilih.</p>
                                    <p class="mt-2 text-sm text-slate-500">Coba ubah kata kunci atau reset filter untuk melihat semua data spasial.</p>
                                    <a href="{{ route('admin.data-sources.index') }}" class="sig-button sig-button-primary mt-4">Reset Filter</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($records->hasPages())
                <div class="border-t border-slate-200 px-5 py-4">
                    {{ $records->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection
