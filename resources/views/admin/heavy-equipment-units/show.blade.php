@extends('layouts.admin')

@section('title', 'Detail Unit Alat')
@section('eyebrow', 'Detail Unit Alat')
@section('page-title', 'Detail Unit Alat')

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
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <span class="sig-badge border {{ $statusClasses[$unit->status] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ $formatLabel($unit->status) }}</span>
                    <h2 class="mt-4 text-2xl font-bold tracking-tight text-primary">{{ $unit->post?->name ?: 'Pos tidak ditemukan' }}</h2>
                    <p class="font-technical mt-2 text-sm font-semibold text-slate-600">{{ $unit->type?->name ?: 'Jenis alat tidak ditemukan' }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.heavy-equipment-units.edit', $unit) }}" class="sig-button sig-button-primary">Edit Data</a>
                    <a href="{{ route('admin.heavy-equipment-units.index') }}" class="sig-button sig-button-outline">Kembali</a>
                </div>
            </div>
        </section>

        <div class="grid gap-4 md:grid-cols-3">
            <section class="sig-card p-5">
                <p class="text-sm text-slate-500">Quantity</p>
                <p class="font-technical mt-2 text-3xl font-bold text-primary">{{ $unit->quantity }}</p>
            </section>
            <section class="sig-card p-5">
                <p class="text-sm text-slate-500">Available Quantity</p>
                <p class="font-technical mt-2 text-3xl font-bold {{ $unit->available_quantity === 0 ? 'text-amber-700' : 'text-teal-700' }}">{{ $unit->available_quantity }}</p>
            </section>
            <section class="sig-card p-5">
                <p class="text-sm text-slate-500">Status</p>
                <p class="mt-2"><span class="sig-badge border {{ $statusClasses[$unit->status] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">{{ $formatLabel($unit->status) }}</span></p>
            </section>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <section class="sig-card p-5">
                <h3 class="text-lg font-bold text-primary">Pos Alat Berat</h3>
                <dl class="mt-4 space-y-3 text-sm">
                    <div><dt class="text-slate-500">Nama Pos</dt><dd class="mt-1 font-semibold text-slate-700">{{ $unit->post?->name ?: '-' }}</dd></div>
                    <div><dt class="text-slate-500">Kecamatan</dt><dd class="mt-1 text-slate-700">{{ $unit->post?->district ?: '-' }}</dd></div>
                    <div><dt class="text-slate-500">Kontak</dt><dd class="font-technical mt-1 text-slate-700">{{ $unit->post?->contact_phone ?: '-' }}</dd></div>
                </dl>
            </section>
            <section class="sig-card p-5">
                <h3 class="text-lg font-bold text-primary">Catatan Unit</h3>
                <p class="mt-3 text-sm leading-6 text-slate-600">{{ $unit->notes ?: 'Belum ada catatan unit.' }}</p>
            </section>
        </div>
    </div>
@endsection
