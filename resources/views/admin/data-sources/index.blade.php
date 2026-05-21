@extends('layouts.admin')

@section('title', 'Sumber Data')
@section('eyebrow', 'Tata Kelola Dataset')
@section('page-title', 'Sumber Data')

@section('content')
    @php
        $summary = [
            ['label' => 'Data Nyata', 'value' => '18', 'tone' => 'bg-teal-50 text-teal-700 border-teal-100'],
            ['label' => 'Data Dummy', 'value' => '22', 'tone' => 'bg-yellow-50 text-yellow-700 border-yellow-100'],
            ['label' => 'Data Simulasi', 'value' => '09', 'tone' => 'bg-blue-50 text-blue-700 border-blue-100'],
            ['label' => 'Perlu Validasi', 'value' => '06', 'tone' => 'bg-red-50 text-red-700 border-red-100'],
        ];
        $rows = [
            ['dataset' => 'flood_risk_points', 'type' => 'observasi', 'ref' => 'Catatan akademik', 'status' => 'simulasi', 'verified' => 'false', 'updated' => '20 Mei 2026', 'notes' => 'Koordinat perlu validasi'],
            ['dataset' => 'flood_events', 'type' => 'admin_input', 'ref' => 'Skenario demo', 'status' => 'simulasi', 'verified' => 'false', 'updated' => '20 Mei 2026', 'notes' => 'Untuk demo UI'],
            ['dataset' => 'evacuation_points', 'type' => 'observasi', 'ref' => 'Fasilitas publik', 'status' => 'dummy', 'verified' => 'false', 'updated' => '19 Mei 2026', 'notes' => 'Kapasitas estimasi'],
            ['dataset' => 'heavy_equipment_posts', 'type' => 'dummy', 'ref' => 'Tidak resmi', 'status' => 'dummy', 'verified' => 'false', 'updated' => '19 Mei 2026', 'notes' => 'Pos realistis untuk demo'],
        ];
    @endphp

    <div class="space-y-6">
        <section class="sig-card p-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <span class="sig-badge bg-blue-50 text-blue-700">Status Dataset</span>
                    <h2 class="mt-3 text-xl font-bold text-primary">Sumber Data dan Validasi</h2>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                        Pantau perbedaan data nyata, dummy, dan simulasi agar demo akademik tetap transparan.
                    </p>
                </div>
                <span class="sig-badge border border-amber-100 bg-amber-50 text-amber-700">Perlu validasi koordinat</span>
            </div>
        </section>

        <section class="grid gap-4 md:grid-cols-4">
            @foreach ($summary as $item)
                <article class="sig-card p-4">
                    <span class="sig-badge border {{ $item['tone'] }}">{{ $item['label'] }}</span>
                    <p class="font-technical mt-4 text-3xl font-semibold text-primary">{{ $item['value'] }}</p>
                    <p class="mt-2 text-sm text-slate-500">Pratinjau status dataset</p>
                </article>
            @endforeach
        </section>

        <section class="rounded-2xl border border-blue-100 bg-blue-50 p-4 text-sm leading-6 text-blue-800">
            Data dummy dan simulasi digunakan untuk kebutuhan demo akademik dan tidak diklaim sebagai data resmi.
        </section>

        <section class="sig-card overflow-hidden">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-lg font-bold text-primary">Daftar Dataset Aktif</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-surface-gray text-xs font-bold uppercase tracking-[0.08em] text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Dataset</th>
                            <th class="px-5 py-3">Tipe Sumber</th>
                            <th class="px-5 py-3">Referensi Sumber</th>
                            <th class="px-5 py-3">Status Data</th>
                            <th class="px-5 py-3">Terverifikasi</th>
                            <th class="px-5 py-3">Diperbarui</th>
                            <th class="px-5 py-3">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach ($rows as $row)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-5 py-4 font-technical text-xs font-semibold text-primary">{{ $row['dataset'] }}</td>
                                <td class="px-5 py-4">{{ $row['type'] }}</td>
                                <td class="px-5 py-4">{{ $row['ref'] }}</td>
                                <td class="px-5 py-4">
                                    <span class="sig-badge border {{ $row['status'] === 'dummy' ? 'border-yellow-100 bg-yellow-50 text-yellow-700' : 'border-blue-100 bg-blue-50 text-blue-700' }}">{{ $row['status'] }}</span>
                                </td>
                                <td class="px-5 py-4 font-technical text-xs">{{ $row['verified'] }}</td>
                                <td class="px-5 py-4 font-technical text-xs text-slate-500">{{ $row['updated'] }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ $row['notes'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
