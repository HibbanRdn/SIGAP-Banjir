@php
    $isEdit = strtoupper($method) !== 'POST';
@endphp

<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if ($isEdit)
        @method($method)
    @endif

    <section class="sig-card p-5">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <span class="sig-badge bg-yellow-50 text-yellow-700">{{ $isEdit ? 'Edit jenis alat' : 'Input jenis alat' }}</span>
                <h2 class="mt-3 text-xl font-bold text-primary">{{ $isEdit ? 'Ubah Jenis Alat' : 'Tambah Jenis Alat Baru' }}</h2>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                    Nama jenis alat disimpan sebagai kode ringkas, misalnya <span class="font-technical">pompa_air</span> atau <span class="font-technical">dump_truck</span>.
                </p>
            </div>
            <a href="{{ route('admin.equipment-types.index') }}" class="sig-button sig-button-outline">Kembali</a>
        </div>
    </section>

    <section class="sig-card p-5">
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="block text-sm font-semibold text-slate-700" for="name">Nama Jenis</label>
                <input id="name" name="name" class="sig-input font-technical mt-2 @error('name') border-red-300 @enderror" type="text" value="{{ old('name', $equipmentType->name) }}" placeholder="contoh: pompa_air">
                <p class="mt-2 text-xs text-slate-500">Spasi dan tanda hubung akan dinormalisasi menjadi underscore.</p>
                @error('name') <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700" for="description">Deskripsi</label>
                <textarea id="description" name="description" class="sig-input mt-2 min-h-32 @error('description') border-red-300 @enderror" placeholder="Fungsi alat dalam respons banjir">{{ old('description', $equipmentType->description) }}</textarea>
                @error('description') <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>
    </section>

    <div class="sticky bottom-4 z-10 rounded-2xl border border-slate-200 bg-white/95 p-4 shadow-soft backdrop-blur">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-slate-500">Jenis alat yang sudah dipakai unit tidak bisa dihapus sampai unitnya dipindahkan atau dihapus.</p>
            <div class="flex gap-2">
                <a href="{{ $isEdit ? route('admin.equipment-types.show', $equipmentType) : route('admin.equipment-types.index') }}" class="sig-button sig-button-outline">Batal</a>
                <button type="submit" class="sig-button sig-button-primary">{{ $submitLabel }}</button>
            </div>
        </div>
    </div>
</form>
