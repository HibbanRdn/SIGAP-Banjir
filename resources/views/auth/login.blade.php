@extends('layouts.app')

@section('title', 'Login Admin - SIGAP Banjir')

@section('content')
    <section class="relative flex min-h-[calc(100vh-73px)] items-center justify-center overflow-hidden px-4 py-10 sm:px-6 lg:px-8">
        <div class="absolute inset-0 sig-grid-bg bg-surface-gray"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(0,88,190,0.12),transparent_34%),radial-gradient(circle_at_bottom_right,rgba(45,212,191,0.10),transparent_30%)]"></div>

        <div class="relative w-full max-w-md">
            <div class="mb-7 text-center">
                <div class="mx-auto flex justify-center">
                    <span class="rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-soft">
                        <img src="{{ asset('assets/brand/logo-utama.png') }}" alt="SIGAP Banjir Bandar Lampung" class="h-14 w-auto object-contain">
                    </span>
                </div>
                <h1 class="mt-5 text-2xl font-bold tracking-tight text-primary">SIGAP Banjir</h1>
                <p class="mx-auto mt-2 max-w-sm text-sm leading-6 text-slate-600">
                    Sistem Informasi Geografis Antisipasi dan Penanganan Banjir Bandar Lampung
                </p>
            </div>

            <div class="sig-card p-6 sm:p-8">
                <div class="mb-6">
                    <span class="sig-badge bg-blue-50 text-blue-700">Area Admin</span>
                    <h2 class="mt-3 text-xl font-bold text-primary">Login Admin</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Masuk untuk mengelola data demo, rekomendasi spasial, dan halaman administrasi SIGAP Banjir.
                    </p>
                </div>

                @if (session('status'))
                    <div class="mb-5 rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-5 rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm leading-6 text-red-800">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700">Email Admin</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            placeholder="example@gmail.com"
                            autocomplete="email"
                            autofocus
                            class="sig-input mt-2 @error('email') border-red-300 focus:border-red-400 focus:ring-red-100 @enderror"
                        >
                        @error('email')
                            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            placeholder="Masukkan password admin"
                            autocomplete="current-password"
                            class="sig-input mt-2 @error('password') border-red-300 focus:border-red-400 focus:ring-red-100 @enderror"
                        >
                        @error('password')
                            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <label class="flex items-center gap-2 text-sm font-medium text-slate-600">
                        <input name="remember" type="checkbox" value="1" class="h-4 w-4 rounded border-slate-300 text-secondary focus:ring-secondary/30">
                        Ingat sesi admin
                    </label>
                    <button type="submit" class="sig-button w-full justify-center bg-secondary text-white hover:bg-primary">
                        Masuk ke Dashboard
                    </button>
                </form>

                <div class="mt-5 rounded-xl border border-blue-100 bg-blue-50 p-3 text-sm leading-6 text-blue-800">
                    Autentikasi menggunakan session Laravel. Route admin akan diarahkan ke halaman ini jika belum login.
                </div>
            </div>

            <div class="mt-5 text-center">
                <a href="{{ route('map') }}" class="text-sm font-semibold text-secondary transition hover:text-primary">Kembali ke Peta Publik</a>
            </div>
        </div>
    </section>
@endsection
