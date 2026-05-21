@php
    $isEdit = strtoupper($method) !== 'POST';
    $longitudeValue = old('longitude', $post->longitude !== null ? number_format((float) $post->longitude, 6, '.', '') : '');
    $latitudeValue = old('latitude', $post->latitude !== null ? number_format((float) $post->latitude, 6, '.', '') : '');
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
                <span class="sig-badge bg-yellow-50 text-yellow-700">{{ $isEdit ? 'Edit pos alat berat' : 'Input pos alat berat' }}</span>
                <h2 class="mt-3 text-xl font-bold text-primary">{{ $isEdit ? 'Ubah Pos Alat Berat' : 'Catat Pos Alat Berat Baru' }}</h2>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                    Lokasi pos disimpan sebagai <span class="font-technical">geometry(Point, 4326)</span>. Unit alat berat dikelola pada modul terpisah.
                </p>
            </div>
            <a href="{{ route('admin.heavy-equipment-posts.index') }}" class="sig-button sig-button-outline">Kembali</a>
        </div>
    </section>

    <div class="grid gap-6 xl:grid-cols-[1fr_380px]">
        <div class="space-y-6">
            <section class="sig-card p-5">
                <h3 class="text-lg font-bold text-primary">Identitas Pos</h3>
                <p class="mt-1 text-sm text-slate-500">Nama pos, alamat, kecamatan, dan kelurahan.</p>
                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700" for="name">Nama Pos</label>
                        <input id="name" name="name" class="sig-input mt-2 @error('name') border-red-300 @enderror" type="text" value="{{ old('name', $post->name) }}" placeholder="Contoh: Pos Alat Berat Panjang">
                        @error('name') <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700" for="district">Kecamatan</label>
                        <input id="district" name="district" list="district_options" class="sig-input mt-2 @error('district') border-red-300 @enderror" type="text" value="{{ old('district', $post->district) }}" placeholder="Contoh: Panjang">
                        <datalist id="district_options">
                            @foreach ($districts as $district)
                                <option value="{{ $district }}"></option>
                            @endforeach
                        </datalist>
                        @error('district') <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700" for="subdistrict">Kelurahan</label>
                        <input id="subdistrict" name="subdistrict" class="sig-input mt-2 @error('subdistrict') border-red-300 @enderror" type="text" value="{{ old('subdistrict', $post->subdistrict) }}" placeholder="Contoh: Panjang Utara">
                        @error('subdistrict') <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700" for="address">Alamat / Patokan</label>
                        <textarea id="address" name="address" class="sig-input mt-2 min-h-24 @error('address') border-red-300 @enderror" placeholder="Tuliskan alamat atau patokan pos alat berat">{{ old('address', $post->address) }}</textarea>
                        @error('address') <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <section class="sig-card p-5">
                <h3 class="text-lg font-bold text-primary">Kontak Penanggung Jawab</h3>
                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700" for="contact_person">Penanggung Jawab</label>
                        <input id="contact_person" name="contact_person" class="sig-input mt-2 @error('contact_person') border-red-300 @enderror" type="text" value="{{ old('contact_person', $post->contact_person) }}" placeholder="Nama koordinator pos">
                        @error('contact_person') <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700" for="contact_phone">Nomor Kontak</label>
                        <input id="contact_phone" name="contact_phone" class="sig-input font-technical mt-2 @error('contact_phone') border-red-300 @enderror" type="text" value="{{ old('contact_phone', $post->contact_phone) }}" placeholder="08xxxxxxxxxx">
                        @error('contact_phone') <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <section class="sig-card p-5">
                <h3 class="text-lg font-bold text-primary">Lokasi dan Koordinat</h3>
                <p class="mt-1 text-sm text-slate-500">Koordinat wajib berada di sekitar Bandar Lampung.</p>
                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700" for="latitude">Latitude</label>
                        <input id="latitude" name="latitude" class="sig-input font-technical mt-2 @error('latitude') border-red-300 @enderror" type="text" value="{{ $latitudeValue }}" placeholder="-5.xxxx">
                        <p class="mt-2 text-xs text-slate-500">Contoh latitude: <span class="font-technical">-5.466900</span></p>
                        @error('latitude') <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700" for="longitude">Longitude</label>
                        <input id="longitude" name="longitude" class="sig-input font-technical mt-2 @error('longitude') border-red-300 @enderror" type="text" value="{{ $longitudeValue }}" placeholder="105.xxxx">
                        <p class="mt-2 text-xs text-slate-500">Contoh longitude: <span class="font-technical">105.326200</span></p>
                        @error('longitude') <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="mt-4 rounded-xl border border-blue-100 bg-blue-50 p-3 text-sm leading-6 text-blue-800">
                    PostGIS memakai urutan <span class="font-semibold">longitude, latitude</span>: <span class="font-technical">ST_SetSRID(ST_MakePoint(longitude, latitude), 4326)</span>.
                </div>
                <div class="relative mt-5 min-h-72 overflow-hidden rounded-xl border border-slate-200 bg-slate-100">
                    <div class="absolute inset-0 sig-grid-bg"></div>
                    <div class="map-marker map-marker-equipment map-marker-selected left-[50%] top-[46%] h-5 w-5"></div>
                    <div class="absolute inset-x-0 bottom-0 bg-white/90 p-3 text-sm text-slate-600 backdrop-blur">Placeholder pemilih peta. Integrasi klik peta akan dikerjakan pada fase Leaflet final.</div>
                </div>
            </section>

            <section class="sig-card p-5">
                <h3 class="text-lg font-bold text-primary">Catatan Tambahan</h3>
                <textarea id="description" name="description" class="sig-input mt-4 min-h-28 @error('description') border-red-300 @enderror" placeholder="Catatan operasional pos, jam layanan, atau batasan akses">{{ old('description', $post->description) }}</textarea>
                @error('description') <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
            </section>
        </div>

        <aside class="space-y-6">
            <section class="sig-card p-5">
                <h3 class="text-lg font-bold text-primary">Status dan Sumber Data</h3>
                <div class="mt-5 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700" for="status">Status Pos</label>
                        <select id="status" name="status" class="sig-input mt-2 @error('status') border-red-300 @enderror">
                            @foreach (\App\Models\HeavyEquipmentPost::STATUSES as $status)
                                <option value="{{ $status }}" @selected(old('status', $post->status) === $status)>{{ $formatLabel($status) }}</option>
                            @endforeach
                        </select>
                        @error('status') <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700" for="source_type">Tipe Sumber</label>
                        <select id="source_type" name="source_type" class="sig-input mt-2 @error('source_type') border-red-300 @enderror">
                            @foreach (\App\Models\HeavyEquipmentPost::SOURCE_TYPES as $sourceType)
                                <option value="{{ $sourceType }}" @selected(old('source_type', $post->source_type) === $sourceType)>{{ $sourceType }}</option>
                            @endforeach
                        </select>
                        @error('source_type') <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700" for="data_status">Status Data</label>
                        <select id="data_status" name="data_status" class="sig-input mt-2 @error('data_status') border-red-300 @enderror">
                            @foreach (\App\Models\HeavyEquipmentPost::DATA_STATUSES as $dataStatus)
                                <option value="{{ $dataStatus }}" @selected(old('data_status', $post->data_status) === $dataStatus)>{{ $dataStatus }}</option>
                            @endforeach
                        </select>
                        @error('data_status') <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700" for="source_reference">Referensi Sumber</label>
                        <input id="source_reference" name="source_reference" class="sig-input mt-2 @error('source_reference') border-red-300 @enderror" type="text" value="{{ old('source_reference', $post->source_reference) }}" placeholder="URL/catatan sumber jika data nyata">
                        @error('source_reference') <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <label class="flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm font-semibold text-slate-700">
                        <input name="is_verified" type="checkbox" value="1" class="h-4 w-4 rounded border-slate-300 text-secondary focus:ring-secondary/30" @checked(old('is_verified', $post->is_verified))>
                        Data sudah diverifikasi
                    </label>
                </div>
            </section>
        </aside>
    </div>

    <div class="sticky bottom-4 z-10 rounded-2xl border border-slate-200 bg-white/95 p-4 shadow-soft backdrop-blur">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-slate-500">Pastikan koordinat tidak tertukar. Longitude sekitar <span class="font-technical">105.x</span>, latitude sekitar <span class="font-technical">-5.x</span>.</p>
            <div class="flex gap-2">
                <a href="{{ $isEdit ? route('admin.heavy-equipment-posts.show', $post) : route('admin.heavy-equipment-posts.index') }}" class="sig-button sig-button-outline">Batal</a>
                <button type="submit" class="sig-button sig-button-primary">{{ $submitLabel }}</button>
            </div>
        </div>
    </div>
</form>
