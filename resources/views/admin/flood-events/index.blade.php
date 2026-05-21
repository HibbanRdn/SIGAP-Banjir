@extends('layouts.admin')

@section('title', 'Kejadian Banjir')
@section('eyebrow', 'Manajemen Data')
@section('page-title', 'Kejadian Banjir')

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
        $activeFilterCount = collect($filters)->filter(fn ($value) => filled($value))->count();
        $formatCoordinate = fn ($value) => $value === null ? '-' : number_format((float) $value, 5, '.', '');
    @endphp

    <div class="space-y-6">
        <section class="sig-card p-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <span class="sig-badge bg-blue-50 text-blue-700">Data PostgreSQL + PostGIS</span>
                    <h2 class="mt-3 text-xl font-bold text-primary">Manajemen Kejadian Banjir</h2>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                        Kelola kejadian banjir aktif, historis, dan simulasi. Koordinat dibaca dari kolom <span class="font-technical">geom</span> PostGIS.
                    </p>
                </div>
                <a href="{{ route('admin.flood-events.create') }}" class="sig-button sig-button-primary">Tambah Kejadian</a>
            </div>
        </section>

        <section class="sig-card overflow-hidden">
            <form method="GET" action="{{ route('admin.flood-events.index') }}" class="border-b border-slate-200 px-5 py-4">
                <div class="grid gap-3 lg:grid-cols-[1.2fr_.8fr_.8fr_.8fr_.8fr_auto]">
                    <label class="block">
                        <span class="sr-only">Cari kejadian</span>
                        <input
                            class="sig-input"
                            name="search"
                            type="search"
                            value="{{ $filters['search'] ?? '' }}"
                            placeholder="Cari nama, alamat, kecamatan, atau kelurahan..."
                        >
                    </label>

                    <select name="status" class="sig-input">
                        <option value="">Semua status</option>
                        @foreach (\App\Models\FloodEvent::STATUSES as $status)
                            <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                        @endforeach
                    </select>

                    <select name="severity_level" class="sig-input">
                        <option value="">Semua severity</option>
                        @foreach (\App\Models\FloodEvent::SEVERITY_LEVELS as $severity)
                            <option value="{{ $severity }}" @selected(($filters['severity_level'] ?? '') === $severity)>{{ ucfirst($severity) }}</option>
                        @endforeach
                    </select>

                    <select name="data_status" class="sig-input">
                        <option value="">Semua data</option>
                        @foreach (\App\Models\FloodEvent::DATA_STATUSES as $dataStatus)
                            <option value="{{ $dataStatus }}" @selected(($filters['data_status'] ?? '') === $dataStatus)>{{ ucfirst($dataStatus) }}</option>
                        @endforeach
                    </select>

                    <select name="district" class="sig-input">
                        <option value="">Semua kecamatan</option>
                        @foreach ($districts as $district)
                            <option value="{{ $district }}" @selected(($filters['district'] ?? '') === $district)>{{ $district }}</option>
                        @endforeach
                    </select>

                    <div class="flex gap-2">
                        <button type="submit" class="sig-button sig-button-outline whitespace-nowrap">Terapkan</button>
                        @if ($activeFilterCount > 0)
                            <a href="{{ route('admin.flood-events.index') }}" class="sig-button sig-button-ghost whitespace-nowrap">Reset</a>
                        @endif
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="sig-badge border border-red-100 bg-red-50 text-red-700">Aktif {{ $statusCounts['aktif'] ?? 0 }}</span>
                    <span class="sig-badge border border-blue-100 bg-blue-50 text-blue-700">Ditangani {{ $statusCounts['ditangani'] ?? 0 }}</span>
                    <span class="sig-badge border border-slate-200 bg-slate-50 text-slate-600">Surut {{ $statusCounts['surut'] ?? 0 }}</span>
                    <span class="sig-badge border border-slate-200 bg-slate-50 text-slate-500">Arsip {{ $statusCounts['arsip'] ?? 0 }}</span>
                    @if ($activeFilterCount > 0)
                        <span class="sig-badge border border-blue-100 bg-white text-blue-700">{{ $activeFilterCount }} filter aktif</span>
                    @endif
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-surface-gray text-xs font-bold uppercase tracking-[0.08em] text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Nama Kejadian</th>
                            <th class="px-5 py-3">Kecamatan</th>
                            <th class="px-5 py-3">Kelurahan</th>
                            <th class="px-5 py-3">Severity</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Data Status</th>
                            <th class="px-5 py-3">Koordinat</th>
                            <th class="px-5 py-3">Updated At</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($events as $event)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-primary">{{ $event->name }}</p>
                                    <p class="font-technical mt-1 text-xs text-slate-500">EVT-{{ str_pad((string) $event->id, 3, '0', STR_PAD_LEFT) }}</p>
                                </td>
                                <td class="px-5 py-4 text-slate-600">{{ $event->district ?: '-' }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ $event->subdistrict ?: '-' }}</td>
                                <td class="px-5 py-4">
                                    <span class="sig-badge border {{ $severityClasses[$event->severity_level] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ ucfirst($event->severity_level) }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="sig-badge border {{ $statusClasses[$event->status] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ ucfirst($event->status) }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="sig-badge border {{ $dataClasses[$event->data_status] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ $event->data_status }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="font-technical text-xs text-slate-600">{{ $formatCoordinate($event->longitude) }}</p>
                                    <p class="font-technical text-xs text-slate-500">{{ $formatCoordinate($event->latitude) }}</p>
                                </td>
                                <td class="px-5 py-4 font-technical text-xs text-slate-500">{{ $event->updated_at?->diffForHumans() }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-1">
                                        <a href="{{ route('admin.flood-events.show', $event) }}" class="sig-button sig-button-ghost px-2.5 py-1.5">Lihat</a>
                                        <a href="{{ route('admin.flood-events.edit', $event) }}" class="sig-button sig-button-ghost px-2.5 py-1.5">Edit</a>
                                        <form method="POST" action="{{ route('admin.flood-events.destroy', $event) }}" onsubmit="return confirm('Hapus data kejadian banjir ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="sig-button sig-button-ghost px-2.5 py-1.5 text-red-600 hover:bg-red-50 hover:text-red-700">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-5 py-12 text-center">
                                    <div class="mx-auto max-w-sm">
                                        <p class="font-semibold text-primary">Tidak ada kejadian banjir</p>
                                        <p class="mt-2 text-sm leading-6 text-slate-500">Belum ada data sesuai filter. Reset filter atau tambahkan kejadian baru.</p>
                                        <div class="mt-4">
                                            <a href="{{ route('admin.flood-events.create') }}" class="sig-button sig-button-primary">Tambah Kejadian</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($events->hasPages())
                <div class="border-t border-slate-200 px-5 py-4">
                    {{ $events->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection
