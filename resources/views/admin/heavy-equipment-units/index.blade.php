@extends('layouts.admin')

@section('title', 'Unit Alat Berat')
@section('eyebrow', 'Inventaris Unit')
@section('page-title', 'Unit Alat Berat')

@section('content')
    @php
        $statusClasses = [
            'tersedia' => 'border-teal-100 bg-teal-50 text-teal-700',
            'digunakan' => 'border-blue-100 bg-blue-50 text-blue-700',
            'perawatan' => 'border-amber-100 bg-amber-50 text-amber-700',
            'tidak_aktif' => 'border-slate-200 bg-slate-100 text-slate-600',
        ];
        $activeFilterCount = collect($filters)->filter(fn ($value) => $value !== null && $value !== '')->count();
        $formatLabel = fn ($value) => ucfirst(str_replace('_', ' ', (string) $value));
    @endphp

    <div class="space-y-6">
        <section class="sig-card p-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <span class="sig-badge bg-yellow-50 text-yellow-700">Unit Per Pos</span>
                    <h2 class="mt-3 text-xl font-bold text-primary">Manajemen Unit Alat Berat</h2>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                        Kelola jumlah total dan ketersediaan alat berat pada setiap pos respons.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.equipment.index') }}" class="sig-button sig-button-outline">Ringkasan Inventaris</a>
                    <a href="{{ route('admin.heavy-equipment-units.create') }}" class="sig-button sig-button-primary">Tambah Unit Alat</a>
                </div>
            </div>
        </section>

        <section class="sig-card overflow-hidden">
            <form method="GET" action="{{ route('admin.heavy-equipment-units.index') }}" class="border-b border-slate-200 px-5 py-4">
                <div class="grid gap-3 lg:grid-cols-[1fr_.8fr_.8fr_.8fr_auto]">
                    <select name="post_id" class="sig-input">
                        <option value="">Semua pos</option>
                        @foreach ($posts as $post)
                            <option value="{{ $post->id }}" @selected((string) ($filters['post_id'] ?? '') === (string) $post->id)>{{ $post->name }}</option>
                        @endforeach
                    </select>
                    <select name="equipment_type_id" class="sig-input">
                        <option value="">Semua jenis</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->id }}" @selected((string) ($filters['equipment_type_id'] ?? '') === (string) $type->id)>{{ $type->name }}</option>
                        @endforeach
                    </select>
                    <select name="status" class="sig-input">
                        <option value="">Semua status</option>
                        @foreach (\App\Models\HeavyEquipmentUnit::STATUSES as $status)
                            <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ $formatLabel($status) }}</option>
                        @endforeach
                    </select>
                    <select name="available_only" class="sig-input">
                        <option value="">Semua ketersediaan</option>
                        <option value="1" @selected(($filters['available_only'] ?? '') === '1')>Tersedia saja</option>
                    </select>
                    <div class="flex gap-2">
                        <button type="submit" class="sig-button sig-button-outline whitespace-nowrap">Terapkan</button>
                        @if ($activeFilterCount > 0)
                            <a href="{{ route('admin.heavy-equipment-units.index') }}" class="sig-button sig-button-ghost whitespace-nowrap">Reset</a>
                        @endif
                    </div>
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-surface-gray text-xs font-bold uppercase tracking-[0.08em] text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Pos Alat Berat</th>
                            <th class="px-5 py-3">Jenis Alat</th>
                            <th class="px-5 py-3">Quantity</th>
                            <th class="px-5 py-3">Available</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Notes</th>
                            <th class="px-5 py-3">Updated At</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($units as $unit)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-5 py-4 font-semibold text-primary">{{ $unit->post?->name ?: '-' }}</td>
                                <td class="px-5 py-4 font-technical text-xs font-semibold text-slate-700">{{ $unit->type?->name ?: '-' }}</td>
                                <td class="px-5 py-4 font-technical font-semibold text-primary">{{ $unit->quantity }}</td>
                                <td class="px-5 py-4">
                                    <span class="font-technical font-semibold {{ $unit->available_quantity === 0 ? 'text-amber-700' : 'text-teal-700' }}">{{ $unit->available_quantity }}</span>
                                </td>
                                <td class="px-5 py-4"><span class="sig-badge border {{ $statusClasses[$unit->status] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ $formatLabel($unit->status) }}</span></td>
                                <td class="px-5 py-4 text-slate-600">{{ $unit->notes ?: '-' }}</td>
                                <td class="px-5 py-4 font-technical text-xs text-slate-500">{{ $unit->updated_at?->diffForHumans() }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-1">
                                        <a href="{{ route('admin.heavy-equipment-units.show', $unit) }}" class="sig-button sig-button-ghost px-2.5 py-1.5">Lihat</a>
                                        <a href="{{ route('admin.heavy-equipment-units.edit', $unit) }}" class="sig-button sig-button-ghost px-2.5 py-1.5">Edit</a>
                                        <form method="POST" action="{{ route('admin.heavy-equipment-units.destroy', $unit) }}" onsubmit="return confirm('Hapus unit alat ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="sig-button sig-button-ghost px-2.5 py-1.5 text-red-600 hover:bg-red-50 hover:text-red-700">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-12 text-center text-sm text-slate-500">Tidak ada unit alat sesuai filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($units->hasPages())
                <div class="border-t border-slate-200 px-5 py-4">
                    {{ $units->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection
