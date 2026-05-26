@extends('layouts.admin')

@section('title', 'Jenis Alat Berat')
@section('eyebrow', 'Master Alat')
@section('page-title', 'Jenis Alat Berat')

@section('content')
    @php
        $activeFilterCount = collect($filters)->filter(fn ($value) => $value !== null && $value !== '')->count();
    @endphp

    <div class="space-y-6">
        <section class="sig-card p-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <span class="sig-badge bg-yellow-50 text-yellow-700">Master Data</span>
                    <h2 class="mt-3 text-xl font-bold text-primary">Manajemen Jenis Alat Berat</h2>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                        Kelola kategori alat berat yang dipakai pada inventaris unit per pos.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.equipment.index') }}" class="sig-button sig-button-outline">Ringkasan Inventaris</a>
                    <a href="{{ route('admin.equipment-types.create') }}" class="sig-button sig-button-primary">Tambah Jenis Alat</a>
                </div>
            </div>
        </section>

        <section class="sig-card overflow-hidden">
            <form method="GET" action="{{ route('admin.equipment-types.index') }}" class="border-b border-slate-200 px-5 py-4">
                <div class="grid gap-3 md:grid-cols-[1fr_auto]">
                    <input class="sig-input" name="search" type="search" value="{{ $filters['search'] ?? '' }}" placeholder="Cari nama jenis atau deskripsi...">
                    <div class="flex gap-2">
                        <button type="submit" class="sig-button sig-button-outline whitespace-nowrap">Terapkan</button>
                        @if ($activeFilterCount > 0)
                            <a href="{{ route('admin.equipment-types.index') }}" class="sig-button sig-button-ghost whitespace-nowrap">Reset</a>
                        @endif
                    </div>
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-surface-gray text-xs font-bold uppercase tracking-[0.08em] text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Nama Jenis</th>
                            <th class="px-5 py-3">Deskripsi</th>
                            <th class="px-5 py-3">Total Unit</th>
                            <th class="px-5 py-3">Unit Tersedia</th>
                            <th class="px-5 py-3">Updated At</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($types as $type)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-5 py-4">
                                    <p class="font-technical font-semibold text-primary">{{ $type->name }}</p>
                                    <p class="font-technical mt-1 text-xs text-slate-500">TYPE-{{ str_pad((string) $type->id, 3, '0', STR_PAD_LEFT) }}</p>
                                </td>
                                <td class="px-5 py-4 text-slate-600">{{ $type->description ?: '-' }}</td>
                                <td class="px-5 py-4 font-technical text-sm font-semibold text-primary">{{ (int) ($type->units_sum_quantity ?? 0) }}</td>
                                <td class="px-5 py-4 font-technical text-sm font-semibold text-teal-700">{{ (int) ($type->units_sum_available_quantity ?? 0) }}</td>
                                <td class="px-5 py-4 font-technical text-xs text-slate-500">{{ $type->updated_at?->diffForHumans() }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-1">
                                        <a href="{{ route('admin.equipment-types.show', $type) }}" class="sig-button sig-button-ghost px-2.5 py-1.5">Lihat</a>
                                        <a href="{{ route('admin.equipment-types.edit', $type) }}" class="sig-button sig-button-ghost px-2.5 py-1.5">Edit</a>
                                        <form method="POST" action="{{ route('admin.equipment-types.destroy', $type) }}" onsubmit="return confirm('Hapus jenis alat ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="sig-button sig-button-ghost px-2.5 py-1.5 text-red-600 hover:bg-red-50 hover:text-red-700">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center">
                                    <p class="font-semibold text-primary">Tidak ada jenis alat</p>
                                    <p class="mt-2 text-sm text-slate-500">Tambahkan master jenis alat untuk mulai mengisi unit per pos.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($types->hasPages())
                <div class="border-t border-slate-200 px-5 py-4">
                    {{ $types->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection
