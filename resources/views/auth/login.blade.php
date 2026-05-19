@extends('layouts.app')

@section('title', 'Login Admin')

@section('content')
    <section class="mx-auto flex min-h-[calc(100vh-73px)] max-w-7xl items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
        <div class="w-full max-w-md rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-6">
                <img src="{{ asset('assets/brand/logo-utama.png') }}" alt="SIGAP Banjir Bandar Lampung" class="mb-6 h-12 w-auto object-contain">
                <p class="text-xs font-semibold uppercase text-civic-blue">Admin Area</p>
                <h1 class="mt-2 text-2xl font-semibold text-slate-950">Login Admin</h1>
                <p class="mt-2 text-sm leading-6 text-slate-600">Placeholder autentikasi. Proses login final akan dibuat pada phase admin authentication.</p>
            </div>

            <form class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                    <input id="email" type="email" placeholder="admin@example.com" disabled class="mt-2 w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-500">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                    <input id="password" type="password" placeholder="Belum aktif" disabled class="mt-2 w-full rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-500">
                </div>
                <button type="button" disabled class="w-full rounded-md bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-500">Autentikasi belum dikonfigurasi</button>
            </form>

            <div class="mt-5 rounded-md bg-blue-50 p-3 text-sm text-blue-800">
                Gunakan halaman dashboard placeholder untuk mengecek layout admin sementara.
            </div>
        </div>
    </section>
@endsection
