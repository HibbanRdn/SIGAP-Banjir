@extends('layouts.admin')

@section('title', 'Detail Jenis Alat')
@section('eyebrow', 'Detail Jenis Alat')
@section('page-title', 'Detail Jenis Alat')

@section('content')
    @php
        $statusClasses = [
            'tersedia' => 'border-teal-100 bg-teal-50 text-teal-700',
            'digunakan' => 'border-blue-100 bg-blue-50 text-blue-700',
            'perawatan' => 'border-amber-100 bg-amber-50 text-amber-700',
            'tidak_aktif' => 'border-slate-200 bg-slate-100 text-slate-600',
        ];
        $formatLabel = fn ($value) => ucfirst(str_replace('_', ' ', (string) $value));
        $totalQuantity = $equipmentType->units->sum('quantity');
        $availableQuantity = $equipmentType->units->sum('available_quantity');
    @endphp

    <div class="space-y-6">
        <section class="sig-card p-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <span class="sig-badge bg-yellow-50 text-yellow-700">Master Jenis</span>
                    <h2 class="font-technical mt-3 text-2xl font-bold text-primary">{{ $equipmentType->name }}</h2>
                    <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">{{ $equipmentType->description ?: 'Belum ada deskripsi untuk jenis alat ini.' }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.equipment-types.edit', $equipmentType) }}" class="sig-button sig-button-primary">Edit Data</a>
                    <a href="{{ route('admin.equipment-types.index') }}" class="sig-button sig-button-outline">Kembali</a>
                </div>
            </div>
        </section>

        <div class="grid gap-4 md:grid-cols-3">
            <section class="sig-card p-5">
                <p class="text-sm text-slate-500">Catatan Unit</p>
                <p class="font-technical mt-2 text-3xl font-bold text-primary">{{ $equipmentType->units->count() }}</p>
            </section>
            <section class="sig-card p-5">
                <p class="text-sm text-slate-500">Total Unit</p>
                <p class="font-technical mt-2 text-3xl font-bold text-primary">{{ $totalQuantity }}</p>
            </section>
            <section class="sig-card p-5">
                <p class="text-sm text-slate-500">Unit Tersedia</p>
                <p class="font-technical mt-2 text-3xl font-bold text-teal-700">{{ $availableQuantity }}</p>
            </section>
        </div>

        <section class="sig-card overflow-hidden">
            <div class="border-b border-slate-200 px-5 py-4">
                <h3 class="text-lg font-bold text-primary">Unit yang Memakai Jenis Ini</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-surface-gray text-xs font-bold uppercase tracking-[0.08em] text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Pos</th>
                            <th class="px-5 py-3">Quantity</th>
                            <th class="px-5 py-3">Available</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($equipmentType->units as $unit)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-5 py-4 font-semibold text-primary">{{ $unit->post?->name ?: '-' }}</td>
                                <td class="px-5 py-4 font-technical font-semibold text-primary">{{ $unit->quantity }}</td>
                                <td class="px-5 py-4 font-technical font-semibold text-teal-700">{{ $unit->available_quantity }}</td>
                                <td class="px-5 py-4"><span class="sig-badge border {{ $statusClasses[$unit->status] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ $formatLabel($unit->status) }}</span></td>
                                <td class="px-5 py-4 text-right"><a href="{{ route('admin.heavy-equipment-units.show', $unit) }}" class="sig-button sig-button-ghost px-2.5 py-1.5">Lihat Unit</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-5 py-10 text-center text-sm text-slate-500">Jenis alat ini belum dipakai oleh unit alat.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
