@extends('layouts.admin')

@section('title', 'Status UI')
@section('eyebrow', 'Pratinjau Internal')
@section('page-title', 'Status dan Komponen')

@section('content')
    <div class="grid gap-6 lg:grid-cols-2">
        <section class="sig-card p-5">
            <h2 class="text-lg font-bold text-primary">State Kosong</h2>
            <div class="mt-4 rounded-xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center">
                <p class="font-semibold text-primary">Belum ada kejadian banjir</p>
                <p class="mt-2 text-sm text-slate-500">Data akan tampil setelah admin menambahkan kejadian banjir.</p>
                <button type="button" class="sig-button sig-button-primary mt-4">Tambah Kejadian</button>
            </div>
        </section>

        <section class="sig-card p-5">
            <h2 class="text-lg font-bold text-primary">State Memuat</h2>
            <div class="mt-4 space-y-3">
                <div class="h-4 w-2/3 animate-pulse rounded bg-slate-200"></div>
                <div class="h-4 w-full animate-pulse rounded bg-slate-200"></div>
                <div class="h-4 w-5/6 animate-pulse rounded bg-slate-200"></div>
                <button type="button" class="sig-button border border-slate-200 bg-slate-200 text-slate-500">Memuat...</button>
            </div>
        </section>

        <section class="rounded-2xl border border-red-100 bg-red-50 p-5">
            <h2 class="text-lg font-bold text-primary">State Error</h2>
            <p class="mt-2 text-sm leading-6 text-red-800">Provider rute tidak merespons. Periksa koneksi atau coba kembali beberapa saat lagi.</p>
        </section>

        <section class="sig-card p-5">
            <h2 class="text-lg font-bold text-primary">Contoh Badge</h2>
            <div class="mt-4 flex flex-wrap gap-2">
                <span class="sig-badge border border-red-100 bg-red-50 text-red-700">Kritis</span>
                <span class="sig-badge border border-amber-100 bg-amber-50 text-amber-700">Sedang</span>
                <span class="sig-badge border border-teal-100 bg-teal-50 text-teal-700">Aktif</span>
                <span class="sig-badge border border-yellow-100 bg-yellow-50 text-yellow-700">dummy</span>
                <span class="sig-badge border border-slate-200 bg-slate-50 text-slate-600">simulasi</span>
            </div>
        </section>
    </div>
@endsection
