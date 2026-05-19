@extends('layouts.app')

@section('title', 'Peta Banjir Bandar Lampung')

@section('content')
    <section class="mx-auto grid min-h-[calc(100vh-73px)] max-w-7xl gap-4 px-4 py-6 sm:px-6 lg:grid-cols-[360px_1fr] lg:px-8">
        <aside class="rounded-lg border border-slate-200 bg-white p-4">
            <div class="mb-5">
                <p class="text-xs font-semibold uppercase text-civic-blue">Public Map Explorer</p>
                <h1 class="mt-2 text-2xl font-semibold text-slate-950">Peta Respons Banjir Bandar Lampung</h1>
                <p class="mt-2 text-sm leading-6 text-slate-600">Placeholder awal untuk eksplorasi layer banjir, titik evakuasi, dan pos alat berat.</p>
            </div>

            <div class="space-y-3">
                <div class="rounded-md border border-slate-200 p-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Titik rawan banjir</span>
                        <span class="rounded-full bg-amber-50 px-2 py-1 text-xs font-medium text-amber-700">Layer</span>
                    </div>
                </div>
                <div class="rounded-md border border-slate-200 p-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Kejadian banjir</span>
                        <span class="rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700">Layer</span>
                    </div>
                </div>
                <div class="rounded-md border border-slate-200 p-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Titik evakuasi</span>
                        <span class="rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700">Layer</span>
                    </div>
                </div>
                <div class="rounded-md border border-slate-200 p-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Pos alat berat</span>
                        <span class="rounded-full bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-700">Layer</span>
                    </div>
                </div>
            </div>

            <div class="mt-5 rounded-md bg-slate-50 p-3 text-sm text-slate-600">
                <p class="font-medium text-slate-800">Status setup</p>
                <p class="mt-1">GeoJSON, Leaflet final, dan data PostGIS akan ditambahkan pada phase berikutnya.</p>
            </div>
        </aside>

        <div class="relative min-h-[520px] overflow-hidden rounded-lg border border-slate-200 bg-white">
            <div class="absolute inset-0 bg-[linear-gradient(90deg,#e2e8f0_1px,transparent_1px),linear-gradient(180deg,#e2e8f0_1px,transparent_1px)] bg-[size:32px_32px]"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-white/90 via-white/55 to-blue-50/70"></div>

            <div class="relative flex h-full min-h-[520px] items-center justify-center p-6">
                <div class="max-w-md rounded-lg border border-slate-200 bg-white/95 p-5 text-center shadow-sm">
                    <p class="font-technical text-xs font-medium text-slate-500">SRID 4326 · GeoJSON · Leaflet</p>
                    <h2 class="mt-2 text-xl font-semibold text-slate-950">Map canvas placeholder</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Area ini disiapkan untuk peta Leaflet. Integrasi layer GeoJSON belum dikerjakan pada Phase 1.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
