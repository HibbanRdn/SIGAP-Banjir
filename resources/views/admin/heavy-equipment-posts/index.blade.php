@extends('layouts.admin')

@section('title', 'Pos Alat Berat')
@section('eyebrow', 'Respons Sumber Daya')
@section('page-title', 'Pos Alat Berat')

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
        $activeFilterCount = collect($filters)->filter(fn ($value) => $value !== null && $value !== '')->count();
        $formatCoordinate = fn ($value) => $value === null ? '-' : number_format((float) $value, 5, '.', '');
        $formatLabel = fn ($value) => ucfirst(str_replace('_', ' ', (string) $value));
    @endphp

    <div class="space-y-6">
        <section class="sig-card p-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <span class="sig-badge bg-yellow-50 text-yellow-700">Alat Berat</span>
                    <h2 class="mt-3 text-xl font-bold text-primary">Manajemen Pos Alat Berat</h2>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                        Kelola lokasi pos alat berat, kontak penanggung jawab, status data, dan koordinat PostGIS untuk kebutuhan rekomendasi sumber daya terdekat.
                    </p>
                </div>
                <a href="{{ route('admin.heavy-equipment-posts.create') }}" class="sig-button sig-button-primary">Tambah Pos Alat Berat</a>
            </div>
        </section>

        <section class="sig-card overflow-hidden">
            <form method="GET" action="{{ route('admin.heavy-equipment-posts.index') }}" class="border-b border-slate-200 px-5 py-4">
                <div class="grid gap-3 xl:grid-cols-[1.2fr_.75fr_.75fr_.75fr_.75fr_.75fr_auto]">
                    <label class="block">
                        <span class="sr-only">Cari pos alat berat</span>
                        <input
                            class="sig-input"
                            name="search"
                            type="search"
                            value="{{ $filters['search'] ?? '' }}"
                            placeholder="Cari nama pos, alamat, kecamatan, atau penanggung jawab..."
                        >
                    </label>

                    <select name="status" class="sig-input">
                        <option value="">Semua status</option>
                        @foreach (\App\Models\HeavyEquipmentPost::STATUSES as $status)
                            <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ $formatLabel($status) }}</option>
                        @endforeach
                    </select>

                    <select name="data_status" class="sig-input">
                        <option value="">Semua data</option>
                        @foreach (\App\Models\HeavyEquipmentPost::DATA_STATUSES as $dataStatus)
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
                        @foreach (\App\Models\HeavyEquipmentPost::SOURCE_TYPES as $sourceType)
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
                            <a href="{{ route('admin.heavy-equipment-posts.index') }}" class="sig-button sig-button-ghost whitespace-nowrap">Reset</a>
                        @endif
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="sig-badge border border-teal-100 bg-teal-50 text-teal-700">Aktif {{ $statusCounts['aktif'] ?? 0 }}</span>
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
                            <th class="px-5 py-3">Nama Pos</th>
                            <th class="px-5 py-3">Kecamatan</th>
                            <th class="px-5 py-3">Kelurahan</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Ringkasan Unit</th>
                            <th class="px-5 py-3">Kontak</th>
                            <th class="px-5 py-3">Data Status</th>
                            <th class="px-5 py-3">Koordinat</th>
                            <th class="px-5 py-3">Updated At</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($posts as $post)
                            @php
                                $typeCount = $post->units->pluck('equipment_type_id')->filter()->unique()->count();
                                $totalQuantity = $post->units->sum('quantity');
                                $availableQuantity = $post->units->sum('available_quantity');
                            @endphp
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-primary">{{ $post->name }}</p>
                                    <p class="font-technical mt-1 text-xs text-slate-500">POST-{{ str_pad((string) $post->id, 3, '0', STR_PAD_LEFT) }}</p>
                                </td>
                                <td class="px-5 py-4 text-slate-600">{{ $post->district ?: '-' }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ $post->subdistrict ?: '-' }}</td>
                                <td class="px-5 py-4">
                                    <span class="sig-badge border {{ $statusClasses[$post->status] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ $formatLabel($post->status) }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    @if ($post->units->isNotEmpty())
                                        <p class="font-technical text-sm font-semibold text-primary">{{ $availableQuantity }}/{{ $totalQuantity }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $typeCount }} jenis alat</p>
                                    @else
                                        <span class="text-sm text-slate-500">Belum ada unit</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-700">{{ $post->contact_person ?: '-' }}</p>
                                    <p class="font-technical mt-1 text-xs text-slate-500">{{ $post->contact_phone ?: '-' }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="sig-badge border {{ $dataClasses[$post->data_status] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ $post->data_status }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="font-technical text-xs text-slate-600">{{ $formatCoordinate($post->longitude) }}</p>
                                    <p class="font-technical text-xs text-slate-500">{{ $formatCoordinate($post->latitude) }}</p>
                                </td>
                                <td class="px-5 py-4 font-technical text-xs text-slate-500">{{ $post->updated_at?->diffForHumans() }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-1">
                                        <a href="{{ route('admin.heavy-equipment-posts.show', $post) }}" class="sig-button sig-button-ghost px-2.5 py-1.5">Lihat</a>
                                        <a href="{{ route('admin.heavy-equipment-posts.edit', $post) }}" class="sig-button sig-button-ghost px-2.5 py-1.5">Edit</a>
                                        <form method="POST" action="{{ route('admin.heavy-equipment-posts.destroy', $post) }}" onsubmit="return confirm('Hapus data pos alat berat ini?')">
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
                                        <p class="font-semibold text-primary">Tidak ada pos alat berat</p>
                                        <p class="mt-2 text-sm leading-6 text-slate-500">Belum ada data sesuai filter. Reset filter atau tambahkan pos alat berat baru.</p>
                                        <div class="mt-4">
                                            <a href="{{ route('admin.heavy-equipment-posts.create') }}" class="sig-button sig-button-primary">Tambah Pos Alat Berat</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($posts->hasPages())
                <div class="border-t border-slate-200 px-5 py-4">
                    {{ $posts->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection
