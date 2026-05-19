@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-lg border border-slate-200 bg-white p-4">
            <p class="text-sm font-medium text-slate-500">Banjir Aktif</p>
            <p class="font-technical mt-3 text-3xl font-semibold text-slate-950">0</p>
            <p class="mt-2 text-sm text-slate-500">Belum tersambung ke database.</p>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-4">
            <p class="text-sm font-medium text-slate-500">Titik Evakuasi</p>
            <p class="font-technical mt-3 text-3xl font-semibold text-slate-950">0</p>
            <p class="mt-2 text-sm text-slate-500">Dataset akan disiapkan setelah Phase 2.</p>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-4">
            <p class="text-sm font-medium text-slate-500">Pos Alat Berat</p>
            <p class="font-technical mt-3 text-3xl font-semibold text-slate-950">0</p>
            <p class="mt-2 text-sm text-slate-500">Data dummy realistis masuk fase dataset.</p>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-4">
            <p class="text-sm font-medium text-slate-500">Status Sistem</p>
            <p class="mt-3 text-lg font-semibold text-civic-blue">Setup awal</p>
            <p class="mt-2 text-sm text-slate-500">Laravel, Blade, dan Tailwind siap dirapikan.</p>
        </div>
    </div>

    <div class="mt-6 grid gap-4 lg:grid-cols-[1.2fr_.8fr]">
        <section class="rounded-lg border border-slate-200 bg-white p-5">
            <p class="text-sm font-semibold text-slate-950">Prioritas implementasi berikutnya</p>
            <div class="mt-4 space-y-3">
                <div class="rounded-md border border-slate-200 p-3">
                    <p class="text-sm font-medium text-slate-800">Phase 2 - Database and PostGIS Setup</p>
                    <p class="mt-1 text-sm text-slate-500">Menunggu PostgreSQL aktif sebelum migration dan query spasial.</p>
                </div>
                <div class="rounded-md border border-slate-200 p-3">
                    <p class="text-sm font-medium text-slate-800">Phase 3 - Dataset and Seeder</p>
                    <p class="mt-1 text-sm text-slate-500">Menyiapkan data nyata, dummy, dan simulasi setelah skema stabil.</p>
                </div>
            </div>
        </section>

        <section class="rounded-lg border border-slate-200 bg-white p-5">
            <p class="text-sm font-semibold text-slate-950">Catatan Phase 1</p>
            <p class="mt-3 text-sm leading-6 text-slate-600">Halaman ini hanya placeholder dashboard. Statistik, rekomendasi, GeoJSON, dan routing belum dihubungkan karena belum masuk fase implementasi fitur.</p>
        </section>
    </div>
@endsection
