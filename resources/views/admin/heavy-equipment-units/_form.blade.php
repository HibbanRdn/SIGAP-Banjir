@php
    $isEdit = strtoupper($method) !== 'POST';
    $formatLabel = fn ($value) => ucfirst(str_replace('_', ' ', (string) $value));
@endphp

<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if ($isEdit)
        @method($method)
    @endif

    <section class="sig-card p-5">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <span class="sig-badge bg-yellow-50 text-yellow-700">{{ $isEdit ? 'Edit unit alat' : 'Input unit alat' }}</span>
                <h2 class="mt-3 text-xl font-bold text-primary">{{ $isEdit ? 'Ubah Unit Alat' : 'Tambah Unit Alat Baru' }}</h2>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                    Unit alat menghubungkan pos alat berat dengan jenis alat dan jumlah ketersediaannya.
                </p>
            </div>
            <a href="{{ route('admin.heavy-equipment-units.index') }}" class="sig-button sig-button-outline">Kembali</a>
        </div>
    </section>

    <div class="grid gap-6 xl:grid-cols-[1fr_360px]">
        <section class="sig-card p-5">
            <h3 class="text-lg font-bold text-primary">Informasi Unit</h3>
            <div class="mt-5 grid gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-slate-700" for="post_id">Pos Alat Berat</label>
                    <select id="post_id" name="post_id" class="sig-input mt-2 @error('post_id') border-red-300 @enderror">
                        <option value="">Pilih pos</option>
                        @foreach ($posts as $post)
                            <option value="{{ $post->id }}" @selected((string) old('post_id', $unit->post_id) === (string) $post->id)>{{ $post->name }}{{ $post->district ? ' - '.$post->district : '' }}</option>
                        @endforeach
                    </select>
                    @error('post_id') <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700" for="equipment_type_id">Jenis Alat</label>
                    <select id="equipment_type_id" name="equipment_type_id" class="sig-input mt-2 @error('equipment_type_id') border-red-300 @enderror">
                        <option value="">Pilih jenis alat</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->id }}" @selected((string) old('equipment_type_id', $unit->equipment_type_id) === (string) $type->id)>{{ $type->name }}</option>
                        @endforeach
                    </select>
                    @error('equipment_type_id') <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700" for="quantity">Quantity</label>
                    <input id="quantity" name="quantity" class="sig-input font-technical mt-2 @error('quantity') border-red-300 @enderror" type="number" min="0" value="{{ old('quantity', $unit->quantity) }}">
                    @error('quantity') <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700" for="available_quantity">Available Quantity</label>
                    <input id="available_quantity" name="available_quantity" class="sig-input font-technical mt-2 @error('available_quantity') border-red-300 @enderror" type="number" min="0" value="{{ old('available_quantity', $unit->available_quantity) }}">
                    <p class="mt-2 text-xs text-slate-500">Tidak boleh melebihi quantity.</p>
                    @error('available_quantity') <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700" for="status">Status Unit</label>
                    <select id="status" name="status" class="sig-input mt-2 @error('status') border-red-300 @enderror">
                        @foreach (\App\Models\HeavyEquipmentUnit::STATUSES as $status)
                            <option value="{{ $status }}" @selected(old('status', $unit->status) === $status)>{{ $formatLabel($status) }}</option>
                        @endforeach
                    </select>
                    <p class="mt-2 text-xs text-slate-500">Jika status tidak aktif, jumlah tersedia harus 0.</p>
                    @error('status') <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700" for="notes">Catatan</label>
                    <textarea id="notes" name="notes" class="sig-input mt-2 min-h-28 @error('notes') border-red-300 @enderror" placeholder="Catatan kondisi unit, perawatan, atau ketersediaan">{{ old('notes', $unit->notes) }}</textarea>
                    @error('notes') <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </section>

        <aside class="sig-card p-5">
            <h3 class="text-lg font-bold text-primary">Aturan Validasi</h3>
            <div class="mt-4 space-y-3 text-sm leading-6 text-slate-600">
                <p>Quantity dan available quantity tidak boleh negatif.</p>
                <p><span class="font-technical">available_quantity</span> wajib lebih kecil atau sama dengan <span class="font-technical">quantity</span>.</p>
                <p>Jenis alat yang sama tidak boleh duplikat pada pos yang sama.</p>
            </div>
        </aside>
    </div>

    <div class="sticky bottom-4 z-10 rounded-2xl border border-slate-200 bg-white/95 p-4 shadow-soft backdrop-blur">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-slate-500">Perubahan unit akan memengaruhi ringkasan ketersediaan alat pada pos terkait.</p>
            <div class="flex gap-2">
                <a href="{{ $isEdit ? route('admin.heavy-equipment-units.show', $unit) : route('admin.heavy-equipment-units.index') }}" class="sig-button sig-button-outline">Batal</a>
                <button type="submit" class="sig-button sig-button-primary">{{ $submitLabel }}</button>
            </div>
        </div>
    </div>
</form>
