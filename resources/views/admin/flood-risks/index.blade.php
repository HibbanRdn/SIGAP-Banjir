@extends('layouts.admin')

@section('title', 'Titik Rawan Banjir')
@section('eyebrow', 'Manajemen Risiko')
@section('page-title', 'Titik Rawan Banjir')

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
        $activeFilterCount = collect($filters)->filter(fn ($value) => $value !== null && $value !== '')->count();
        $formatCoordinate = fn ($value) => $value === null ? '-' : number_format((float) $value, 5, '.', '');
    @endphp

    <div class="space-y-6">
        <section class="sig-card p-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <span class="sig-badge bg-amber-50 text-amber-700">Layer Risiko</span>
                    <h2 class="mt-3 text-xl font-bold text-primary">Manajemen Titik Rawan Banjir</h2>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                        Kelola lokasi yang memiliki potensi atau riwayat genangan. Koordinat dibaca dari kolom <span class="font-technical">geom</span> PostGIS.
                    </p>
                </div>
                <a href="{{ route('admin.flood-risks.create') }}" class="sig-button sig-button-primary">Tambah Titik Rawan</a>
            </div>
        </section>

        <section class="sig-card overflow-hidden">
            <form method="GET" action="{{ route('admin.flood-risks.index') }}" class="border-b border-slate-200 px-5 py-4">
                <div class="grid gap-3 lg:grid-cols-[1.2fr_.8fr_.8fr_.8fr_.8fr_.8fr_auto]">
                    <label class="block">
                        <span class="sr-only">Cari titik rawan</span>
                        <input
                            class="sig-input"
                            name="search"
                            type="search"
                            value="{{ $filters['search'] ?? '' }}"
                            placeholder="Cari nama lokasi, alamat, kecamatan, atau kelurahan..."
                        >
                    </label>

                    <select name="risk_level" class="sig-input">
                        <option value="">Semua risiko</option>
                        @foreach (\App\Models\FloodRiskPoint::RISK_LEVELS as $riskLevel)
                            <option value="{{ $riskLevel }}" @selected(($filters['risk_level'] ?? '') === $riskLevel)>{{ ucfirst($riskLevel) }}</option>
                        @endforeach
                    </select>

                    <select name="data_status" class="sig-input">
                        <option value="">Semua data</option>
                        @foreach (\App\Models\FloodRiskPoint::DATA_STATUSES as $dataStatus)
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
                        @foreach (\App\Models\FloodRiskPoint::SOURCE_TYPES as $sourceType)
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
                            <a href="{{ route('admin.flood-risks.index') }}" class="sig-button sig-button-ghost whitespace-nowrap">Reset</a>
                        @endif
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="sig-badge border border-red-100 bg-red-50 text-red-700">Tinggi {{ $riskCounts['tinggi'] ?? 0 }}</span>
                    <span class="sig-badge border border-amber-100 bg-amber-50 text-amber-700">Sedang {{ $riskCounts['sedang'] ?? 0 }}</span>
                    <span class="sig-badge border border-teal-100 bg-teal-50 text-teal-700">Rendah {{ $riskCounts['rendah'] ?? 0 }}</span>
                    @if ($activeFilterCount > 0)
                        <span class="sig-badge border border-blue-100 bg-white text-blue-700">{{ $activeFilterCount }} filter aktif</span>
                    @endif
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-surface-gray text-xs font-bold uppercase tracking-[0.08em] text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Nama Lokasi</th>
                            <th class="px-5 py-3">Kecamatan</th>
                            <th class="px-5 py-3">Kelurahan</th>
                            <th class="px-5 py-3">Risk Level</th>
                            <th class="px-5 py-3">Data Status</th>
                            <th class="px-5 py-3">Verifikasi</th>
                            <th class="px-5 py-3">Koordinat</th>
                            <th class="px-5 py-3">Updated At</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($riskPoints as $risk)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-primary">{{ $risk->name }}</p>
                                    <p class="font-technical mt-1 text-xs text-slate-500">RISK-{{ str_pad((string) $risk->id, 3, '0', STR_PAD_LEFT) }}</p>
                                </td>
                                <td class="px-5 py-4 text-slate-600">{{ $risk->district ?: '-' }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ $risk->subdistrict ?: '-' }}</td>
                                <td class="px-5 py-4">
                                    <span class="sig-badge border {{ $riskClasses[$risk->risk_level] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ ucfirst($risk->risk_level) }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="sig-badge border {{ $dataClasses[$risk->data_status] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ $risk->data_status }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    @if ($risk->is_verified)
                                        <span class="sig-badge border border-emerald-100 bg-emerald-50 text-emerald-700">Terverifikasi</span>
                                    @else
                                        <span class="sig-badge border border-amber-100 bg-amber-50 text-amber-700">Perlu Validasi</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <p class="font-technical text-xs text-slate-600">{{ $formatCoordinate($risk->longitude) }}</p>
                                    <p class="font-technical text-xs text-slate-500">{{ $formatCoordinate($risk->latitude) }}</p>
                                </td>
                                <td class="px-5 py-4 font-technical text-xs text-slate-500">{{ $risk->updated_at?->diffForHumans() }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-1">
                                        <a href="{{ route('admin.flood-risks.show', $risk) }}" class="sig-button sig-button-ghost px-2.5 py-1.5">Lihat</a>
                                        <a href="{{ route('admin.flood-risks.edit', $risk) }}" class="sig-button sig-button-ghost px-2.5 py-1.5">Edit</a>
                                        <form method="POST" action="{{ route('admin.flood-risks.destroy', $risk) }}" onsubmit="return confirm('Hapus data titik rawan banjir ini?')">
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
                                        <p class="font-semibold text-primary">Tidak ada titik rawan banjir</p>
                                        <p class="mt-2 text-sm leading-6 text-slate-500">Belum ada data sesuai filter. Reset filter atau tambahkan titik rawan baru.</p>
                                        <div class="mt-4">
                                            <a href="{{ route('admin.flood-risks.create') }}" class="sig-button sig-button-primary">Tambah Titik Rawan</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($riskPoints->hasPages())
                <div class="border-t border-slate-200 px-5 py-4">
                    {{ $riskPoints->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection
