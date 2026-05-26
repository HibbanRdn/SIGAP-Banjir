@extends('layouts.admin')

@section('title', 'Jenis & Unit Alat')
@section('eyebrow', 'Inventaris Alat')
@section('page-title', 'Jenis & Unit Alat')

@section('content')
    @php
        $statusClasses = [
            'tersedia' => 'border-teal-100 bg-teal-50 text-teal-700',
            'digunakan' => 'border-blue-100 bg-blue-50 text-blue-700',
            'perawatan' => 'border-amber-100 bg-amber-50 text-amber-700',
            'tidak_aktif' => 'border-slate-200 bg-slate-100 text-slate-600',
        ];
        $formatLabel = fn ($value) => ucfirst(str_replace('_', ' ', (string) $value));
    @endphp

    <div class="space-y-6">
        <section class="sig-card p-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <span class="sig-badge bg-yellow-50 text-yellow-700">Inventaris Respons</span>
                    <h2 class="mt-3 text-xl font-bold text-primary">Jenis dan Unit Alat Berat</h2>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                        Kelola master jenis alat berat dan jumlah unit yang tersedia pada setiap pos respons.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.equipment-types.create') }}" class="sig-button sig-button-primary">Tambah Jenis Alat</a>
                    <a href="{{ route('admin.heavy-equipment-units.create') }}" class="sig-button sig-button-outline">Tambah Unit Alat</a>
                </div>
            </div>
        </section>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <section class="sig-card p-5">
                <p class="text-sm font-semibold text-slate-500">Total Jenis Alat</p>
                <p class="font-technical mt-3 text-3xl font-bold text-primary">{{ str_pad((string) $totalTypes, 2, '0', STR_PAD_LEFT) }}</p>
            </section>
            <section class="sig-card p-5">
                <p class="text-sm font-semibold text-slate-500">Total Unit</p>
                <p class="font-technical mt-3 text-3xl font-bold text-primary">{{ str_pad((string) $totalQuantity, 2, '0', STR_PAD_LEFT) }}</p>
            </section>
            <section class="sig-card p-5">
                <p class="text-sm font-semibold text-slate-500">Unit Tersedia</p>
                <p class="font-technical mt-3 text-3xl font-bold text-teal-700">{{ str_pad((string) $availableQuantity, 2, '0', STR_PAD_LEFT) }}</p>
            </section>
            <section class="sig-card p-5">
                <p class="text-sm font-semibold text-slate-500">Perawatan/Tidak Aktif</p>
                <p class="font-technical mt-3 text-3xl font-bold text-amber-700">{{ str_pad((string) $inactiveOrMaintenance, 2, '0', STR_PAD_LEFT) }}</p>
            </section>
        </div>

        <div class="grid gap-6 xl:grid-cols-[.9fr_1.1fr]">
            <section class="sig-card overflow-hidden">
                <div class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-primary">Ringkasan Jenis Alat</h3>
                        <p class="mt-1 text-sm text-slate-500">Master jenis alat dan total unit yang memakai jenis tersebut.</p>
                    </div>
                    <a href="{{ route('admin.equipment-types.index') }}" class="sig-button sig-button-outline">Kelola Jenis</a>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse ($types as $type)
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <a href="{{ route('admin.equipment-types.show', $type) }}" class="font-technical text-sm font-semibold text-primary hover:text-secondary">{{ $type->name }}</a>
                                    <p class="mt-1 text-sm leading-6 text-slate-600">{{ $type->description ?: 'Belum ada deskripsi jenis alat.' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-technical rounded-lg bg-yellow-50 px-2.5 py-1 text-sm font-semibold text-yellow-700">{{ (int) ($type->units_sum_available_quantity ?? 0) }}/{{ (int) ($type->units_sum_quantity ?? 0) }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $type->units_count }} catatan</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-sm text-slate-500">Belum ada jenis alat.</div>
                    @endforelse
                </div>
            </section>

            <section class="sig-card overflow-hidden">
                <div class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-primary">Ringkasan Unit Per Pos</h3>
                        <p class="mt-1 text-sm text-slate-500">Ketersediaan unit terbaru dari pos alat berat.</p>
                    </div>
                    <a href="{{ route('admin.heavy-equipment-units.index') }}" class="sig-button sig-button-outline">Kelola Unit</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                        <thead class="bg-surface-gray text-xs font-bold uppercase tracking-[0.08em] text-slate-500">
                            <tr>
                                <th class="px-5 py-3">Pos</th>
                                <th class="px-5 py-3">Jenis</th>
                                <th class="px-5 py-3">Tersedia</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($units as $unit)
                                <tr class="transition hover:bg-slate-50">
                                    <td class="px-5 py-4 font-semibold text-primary">{{ $unit->post?->name ?: '-' }}</td>
                                    <td class="px-5 py-4 font-technical text-xs text-slate-700">{{ $unit->type?->name ?: '-' }}</td>
                                    <td class="px-5 py-4 font-technical text-sm font-semibold text-primary">{{ $unit->available_quantity }}/{{ $unit->quantity }}</td>
                                    <td class="px-5 py-4">
                                        <span class="sig-badge border {{ $statusClasses[$unit->status] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ $formatLabel($unit->status) }}</span>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <a href="{{ route('admin.heavy-equipment-units.show', $unit) }}" class="sig-button sig-button-ghost px-2.5 py-1.5">Lihat</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-10 text-center text-sm text-slate-500">Belum ada unit alat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <section class="sig-card p-5">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-bold text-primary">Ketersediaan Per Pos</h3>
                    <p class="mt-1 text-sm text-slate-500">Ringkasan cepat untuk membaca pos yang punya unit respons.</p>
                </div>
                <a href="{{ route('admin.heavy-equipment-posts.index') }}" class="sig-button sig-button-outline">Kelola Pos</a>
            </div>
            <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($postSummaries as $post)
                    @php
                        $postTotal = $post->units->sum('quantity');
                        $postAvailable = $post->units->sum('available_quantity');
                    @endphp
                    <article class="rounded-xl border border-slate-200 bg-white p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="font-semibold text-primary">{{ $post->name }}</p>
                                <p class="mt-1 text-sm text-slate-500">{{ $post->district ?: '-' }}</p>
                            </div>
                            <span class="font-technical rounded-lg bg-yellow-50 px-2.5 py-1 text-sm font-semibold text-yellow-700">{{ $postAvailable }}/{{ $postTotal }}</span>
                        </div>
                        <p class="mt-3 text-xs leading-5 text-slate-500">{{ $post->units->count() }} catatan unit alat</p>
                    </article>
                @empty
                    <p class="text-sm text-slate-500">Belum ada pos alat berat.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
