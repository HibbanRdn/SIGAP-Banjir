@extends('layouts.admin')

@section('title', 'Jenis & Unit Alat')
@section('eyebrow', 'Inventaris Alat')
@section('page-title', 'Jenis & Unit Alat')

@section('content')
    @php
        $types = [
            ['name' => 'excavator', 'desc' => 'Pembersihan material berat dan pengerukan sedimen', 'qty' => '08'],
            ['name' => 'dump_truck', 'desc' => 'Pengangkutan material lumpur atau puing', 'qty' => '18'],
            ['name' => 'pompa_air', 'desc' => 'Penyedotan genangan pada area rendah', 'qty' => '12'],
            ['name' => 'wheel_loader', 'desc' => 'Pemindahan material dan akses jalan', 'qty' => '05'],
            ['name' => 'mobil_tangki', 'desc' => 'Distribusi air bersih dan dukungan lapangan', 'qty' => '04'],
            ['name' => 'pickup_operasional', 'desc' => 'Mobilitas petugas dan logistik ringan', 'qty' => '18'],
        ];
        $units = [
            ['post' => 'Pos Alat Berat Panjang', 'type' => 'excavator', 'available' => '02', 'total' => '03', 'status' => 'tersedia'],
            ['post' => 'Gudang Logistik Rajabasa', 'type' => 'dump_truck', 'available' => '03', 'total' => '05', 'status' => 'tersedia'],
            ['post' => 'Pos Pembantu Kemiling', 'type' => 'wheel_loader', 'available' => '01', 'total' => '01', 'status' => 'digunakan'],
            ['post' => 'Pos Teluk Betung', 'type' => 'pompa_air', 'available' => '00', 'total' => '02', 'status' => 'perawatan'],
        ];
    @endphp

    <div class="space-y-6">
        <section class="sig-card p-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <span class="sig-badge bg-yellow-50 text-yellow-700">Pratinjau Inventaris</span>
                    <h2 class="mt-3 text-xl font-bold text-primary">Jenis dan Unit Alat Berat</h2>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Pratinjau layout dua kolom untuk master jenis alat dan unit per pos.</p>
                </div>
                <button type="button" class="sig-button sig-button-primary">Tambah Jenis Alat</button>
            </div>
        </section>

        <div class="grid gap-6 xl:grid-cols-[.85fr_1.15fr]">
            <section class="sig-card overflow-hidden">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h3 class="text-lg font-bold text-primary">Daftar Jenis Alat</h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach ($types as $type)
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="font-technical text-sm font-semibold text-primary">{{ $type['name'] }}</p>
                                    <p class="mt-1 text-sm leading-6 text-slate-600">{{ $type['desc'] }}</p>
                                </div>
                                <span class="font-technical rounded-lg bg-yellow-50 px-2.5 py-1 text-sm font-semibold text-yellow-700">{{ $type['qty'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="sig-card overflow-hidden">
                <div class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <h3 class="text-lg font-bold text-primary">Unit per Pos</h3>
                    <button type="button" class="sig-button sig-button-outline">Tambah Unit</button>
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
                            @foreach ($units as $unit)
                                <tr class="transition hover:bg-slate-50">
                                    <td class="px-5 py-4 font-semibold text-primary">{{ $unit['post'] }}</td>
                                    <td class="px-5 py-4 font-technical text-xs text-slate-700">{{ $unit['type'] }}</td>
                                    <td class="px-5 py-4 font-technical text-sm font-semibold text-primary">{{ $unit['available'] }}/{{ $unit['total'] }}</td>
                                    <td class="px-5 py-4">
                                        <span class="sig-badge border {{ $unit['status'] === 'tersedia' ? 'border-teal-100 bg-teal-50 text-teal-700' : ($unit['status'] === 'digunakan' ? 'border-blue-100 bg-blue-50 text-blue-700' : 'border-amber-100 bg-amber-50 text-amber-700') }}">{{ $unit['status'] }}</span>
                                    </td>
                                    <td class="px-5 py-4 text-right"><button type="button" class="sig-button sig-button-ghost px-2.5 py-1.5">Edit</button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
@endsection
