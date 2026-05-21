@extends('layouts.admin')

@section('title', 'Titik Evakuasi')
@section('eyebrow', 'Manajemen Evakuasi')
@section('page-title', 'Titik Evakuasi')

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
        $activeFilterCount = collect($filters)->filter(fn ($value) => $value !== null && $value !== '')->count();
        $formatCoordinate = fn ($value) => $value === null ? '-' : number_format((float) $value, 5, '.', '');
        $formatLabel = fn ($value) => ucfirst(str_replace('_', ' ', (string) $value));
    @endphp

    <div class="space-y-6">
        <section class="sig-card p-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <span class="sig-badge bg-teal-50 text-teal-700">Evakuasi</span>
                    <h2 class="mt-3 text-xl font-bold text-primary">Manajemen Titik Evakuasi</h2>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                        Kelola fasilitas evakuasi, kapasitas, kontak, status, dan koordinat PostGIS untuk kebutuhan rekomendasi evakuasi.
                    </p>
                </div>
                <a href="{{ route('admin.evacuation-points.create') }}" class="sig-button sig-button-primary">Tambah Titik Evakuasi</a>
            </div>
        </section>

        <section class="sig-card overflow-hidden">
            <form method="GET" action="{{ route('admin.evacuation-points.index') }}" class="border-b border-slate-200 px-5 py-4">
                <div class="grid gap-3 xl:grid-cols-[1.2fr_.75fr_.75fr_.75fr_.75fr_.75fr_.75fr_auto]">
                    <label class="block">
                        <span class="sr-only">Cari titik evakuasi</span>
                        <input
                            class="sig-input"
                            name="search"
                            type="search"
                            value="{{ $filters['search'] ?? '' }}"
                            placeholder="Cari nama, fasilitas, alamat, kecamatan..."
                        >
                    </label>

                    <select name="type" class="sig-input">
                        <option value="">Semua jenis</option>
                        @foreach (\App\Models\EvacuationPoint::TYPES as $type)
                            <option value="{{ $type }}" @selected(($filters['type'] ?? '') === $type)>{{ $formatLabel($type) }}</option>
                        @endforeach
                    </select>

                    <select name="status" class="sig-input">
                        <option value="">Semua status</option>
                        @foreach (\App\Models\EvacuationPoint::STATUSES as $status)
                            <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ $formatLabel($status) }}</option>
                        @endforeach
                    </select>

                    <select name="data_status" class="sig-input">
                        <option value="">Semua data</option>
                        @foreach (\App\Models\EvacuationPoint::DATA_STATUSES as $dataStatus)
                            <option value="{{ $dataStatus }}" @selected(($filters['data_status'] ?? '') === $dataStatus)>{{ ucfirst($dataStatus) }}</option>
                        @endforeach
                    </select>

                    <select name="district" class="sig-input">
                        <option value="">Semua kecamatan</option>
                        @foreach ($districts as $district)
                            <option value="{{ $district }}" @selected(($filters['district'] ?? '') === $district)>{{ $district }}</option>
                        @endforeach
                    </select>

                    <select name="source_type" class="sig-input">
                        <option value="">Semua sumber</option>
                        @foreach (\App\Models\EvacuationPoint::SOURCE_TYPES as $sourceType)
                            <option value="{{ $sourceType }}" @selected(($filters['source_type'] ?? '') === $sourceType)>{{ $sourceType }}</option>
                        @endforeach
                    </select>

                    <select name="is_verified" class="sig-input">
                        <option value="">Semua verifikasi</option>
                        <option value="1" @selected(($filters['is_verified'] ?? '') === '1')>Terverifikasi</option>
                        <option value="0" @selected(($filters['is_verified'] ?? '') === '0')>Perlu Validasi</option>
                    </select>

                    <div class="flex gap-2">
                        <button type="submit" class="sig-button sig-button-outline whitespace-nowrap">Terapkan</button>
                        @if ($activeFilterCount > 0)
                            <a href="{{ route('admin.evacuation-points.index') }}" class="sig-button sig-button-ghost whitespace-nowrap">Reset</a>
                        @endif
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="sig-badge border border-teal-100 bg-teal-50 text-teal-700">Aktif {{ $statusCounts['aktif'] ?? 0 }}</span>
                    <span class="sig-badge border border-amber-100 bg-amber-50 text-amber-700">Penuh {{ $statusCounts['penuh'] ?? 0 }}</span>
                    <span class="sig-badge border border-slate-200 bg-slate-50 text-slate-600">Tidak Aktif {{ $statusCounts['tidak_aktif'] ?? 0 }}</span>
                    @if ($activeFilterCount > 0)
                        <span class="sig-badge border border-blue-100 bg-white text-blue-700">{{ $activeFilterCount }} filter aktif</span>
                    @endif
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-surface-gray text-xs font-bold uppercase tracking-[0.08em] text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Nama Tempat</th>
                            <th class="px-5 py-3">Jenis</th>
                            <th class="px-5 py-3">Kecamatan</th>
                            <th class="px-5 py-3">Kelurahan</th>
                            <th class="px-5 py-3">Kapasitas</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Data Status</th>
                            <th class="px-5 py-3">Koordinat</th>
                            <th class="px-5 py-3">Updated At</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($evacuationPoints as $point)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-primary">{{ $point->name }}</p>
                                    <p class="mt-1 max-w-xs truncate text-xs text-slate-500">{{ $point->facilities ?: 'Fasilitas belum dicatat' }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="sig-badge border {{ $typeClasses[$point->type] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ $formatLabel($point->type) }}</span>
                                </td>
                                <td class="px-5 py-4 text-slate-600">{{ $point->district ?: '-' }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ $point->subdistrict ?: '-' }}</td>
                                <td class="px-5 py-4 font-technical text-sm font-semibold text-primary">{{ $point->capacity !== null ? number_format($point->capacity) : '-' }}</td>
                                <td class="px-5 py-4">
                                    <span class="sig-badge border {{ $statusClasses[$point->status] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ $formatLabel($point->status) }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="sig-badge border {{ $dataClasses[$point->data_status] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ $point->data_status }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="font-technical text-xs text-slate-600">{{ $formatCoordinate($point->longitude) }}</p>
                                    <p class="font-technical text-xs text-slate-500">{{ $formatCoordinate($point->latitude) }}</p>
                                </td>
                                <td class="px-5 py-4 font-technical text-xs text-slate-500">{{ $point->updated_at?->diffForHumans() }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-1">
                                        <a href="{{ route('admin.evacuation-points.show', $point) }}" class="sig-button sig-button-ghost px-2.5 py-1.5">Lihat</a>
                                        <a href="{{ route('admin.evacuation-points.edit', $point) }}" class="sig-button sig-button-ghost px-2.5 py-1.5">Edit</a>
                                        <form method="POST" action="{{ route('admin.evacuation-points.destroy', $point) }}" onsubmit="return confirm('Hapus data titik evakuasi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="sig-button sig-button-ghost px-2.5 py-1.5 text-red-600 hover:bg-red-50 hover:text-red-700">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-5 py-12 text-center">
                                    <div class="mx-auto max-w-sm">
                                        <p class="font-semibold text-primary">Tidak ada titik evakuasi</p>
                                        <p class="mt-2 text-sm leading-6 text-slate-500">Belum ada data sesuai filter. Reset filter atau tambahkan titik evakuasi baru.</p>
                                        <div class="mt-4">
                                            <a href="{{ route('admin.evacuation-points.create') }}" class="sig-button sig-button-primary">Tambah Titik Evakuasi</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($evacuationPoints->hasPages())
                <div class="border-t border-slate-200 px-5 py-4">
                    {{ $evacuationPoints->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection
