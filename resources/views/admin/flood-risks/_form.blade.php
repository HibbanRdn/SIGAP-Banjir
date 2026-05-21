@php
    $isEdit = strtoupper($method) !== 'POST';
    $longitudeValue = old('longitude', $floodRisk->longitude !== null ? number_format((float) $floodRisk->longitude, 6, '.', '') : '');
    $latitudeValue = old('latitude', $floodRisk->latitude !== null ? number_format((float) $floodRisk->latitude, 6, '.', '') : '');
@endphp

<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if ($isEdit)
        @method($method)
    @endif

    <section class="sig-card p-5">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <span class="sig-badge bg-amber-50 text-amber-700">{{ $isEdit ? 'Edit data risiko' : 'Input titik rawan' }}</span>
                <h2 class="mt-3 text-xl font-bold text-primary">{{ $isEdit ? 'Ubah Titik Rawan' : 'Catat Titik Rawan Baru' }}</h2>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                    Titik rawan banjir disimpan sebagai <span class="font-technical">geometry(Point, 4326)</span>. Longitude dan latitude hanya menjadi input bantu.
                </p>
            </div>
            <a href="{{ route('admin.flood-risks.index') }}" class="sig-button sig-button-outline">Kembali</a>
        </div>
    </section>

    <div class="grid gap-6 xl:grid-cols-[1fr_380px]">
        <div class="space-y-6">
            <section class="sig-card p-5">
                <h3 class="text-lg font-bold text-primary">Informasi Lokasi</h3>
                <p class="mt-1 text-sm text-slate-500">Nama lokasi, alamat, kecamatan, dan kelurahan titik rawan.</p>
                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700" for="name">Nama Lokasi</label>
                        <input id="name" name="name" class="sig-input mt-2 @error('name') border-red-300 @enderror" type="text" value="{{ old('name', $floodRisk->name) }}" placeholder="Contoh: Rawan Banjir Way Halim">
                        @error('name') <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700" for="district">Kecamatan</label>
                        <input id="district" name="district" list="district_options" class="sig-input mt-2 @error('district') border-red-300 @enderror" type="text" value="{{ old('district', $floodRisk->district) }}" placeholder="Contoh: Way Halim">
                        <datalist id="district_options">
                            @foreach ($districts as $district)
                                <option value="{{ $district }}"></option>
                            @endforeach
                        </datalist>
                        @error('district') <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700" for="subdistrict">Kelurahan</label>
                        <input id="subdistrict" name="subdistrict" class="sig-input mt-2 @error('subdistrict') border-red-300 @enderror" type="text" value="{{ old('subdistrict', $floodRisk->subdistrict) }}" placeholder="Contoh: Perumnas Way Halim">
                        @error('subdistrict') <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700" for="address">Alamat / Deskripsi Lokasi</label>
                        <textarea id="address" name="address" class="sig-input mt-2 min-h-24 @error('address') border-red-300 @enderror" placeholder="Tuliskan alamat atau patokan lokasi rawan banjir">{{ old('address', $floodRisk->address) }}</textarea>
                        @error('address') <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
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
                        <p class="mt-2 text-xs text-slate-500">Contoh latitude: <span class="font-technical">-5.389700</span></p>
                        @error('latitude') <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700" for="longitude">Longitude</label>
                        <input id="longitude" name="longitude" class="sig-input font-technical mt-2 @error('longitude') border-red-300 @enderror" type="text" value="{{ $longitudeValue }}" placeholder="105.xxxx">
                        <p class="mt-2 text-xs text-slate-500">Contoh longitude: <span class="font-technical">105.288600</span></p>
                        @error('longitude') <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="mt-4 rounded-xl border border-blue-100 bg-blue-50 p-3 text-sm leading-6 text-blue-800">
                    PostGIS memakai urutan <span class="font-semibold">longitude, latitude</span>: <span class="font-technical">ST_SetSRID(ST_MakePoint(longitude, latitude), 4326)</span>.
                </div>
                <div class="relative mt-5 min-h-72 overflow-hidden rounded-xl border border-slate-200 bg-slate-100">
                    <div class="absolute inset-0 sig-grid-bg"></div>
                    <div class="map-marker map-marker-risk map-marker-selected left-[52%] top-[46%] h-5 w-5"></div>
                    <div class="absolute inset-x-0 bottom-0 bg-white/90 p-3 text-sm text-slate-600 backdrop-blur">Placeholder pemilih peta. Integrasi klik peta akan dikerjakan pada fase Leaflet final.</div>
                </div>
            </section>

            <section class="sig-card p-5">
                <h3 class="text-lg font-bold text-primary">Catatan Tambahan</h3>
                <textarea id="description" name="description" class="sig-input mt-4 min-h-28 @error('description') border-red-300 @enderror" placeholder="Catatan kondisi kawasan, penyebab genangan, atau riwayat banjir">{{ old('description', $floodRisk->description) }}</textarea>
                @error('description') <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
            </section>
        </div>

        <aside class="space-y-6">
            <section class="sig-card p-5">
                <h3 class="text-lg font-bold text-primary">Tingkat Risiko</h3>
                <div class="mt-5">
                    <label class="block text-sm font-semibold text-slate-700" for="risk_level">Risk Level</label>
                    <select id="risk_level" name="risk_level" class="sig-input mt-2 @error('risk_level') border-red-300 @enderror">
                        @foreach (\App\Models\FloodRiskPoint::RISK_LEVELS as $riskLevel)
                            <option value="{{ $riskLevel }}" @selected(old('risk_level', $floodRisk->risk_level) === $riskLevel)>{{ ucfirst($riskLevel) }}</option>
                        @endforeach
                    </select>
                    @error('risk_level') <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                </div>
            </section>

            <section class="sig-card p-5">
                <h3 class="text-lg font-bold text-primary">Sumber Data</h3>
                <div class="mt-5 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700" for="source_type">Tipe Sumber</label>
                        <select id="source_type" name="source_type" class="sig-input mt-2 @error('source_type') border-red-300 @enderror">
                            @foreach (\App\Models\FloodRiskPoint::SOURCE_TYPES as $sourceType)
                                <option value="{{ $sourceType }}" @selected(old('source_type', $floodRisk->source_type) === $sourceType)>{{ $sourceType }}</option>
                            @endforeach
                        </select>
                        @error('source_type') <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700" for="data_status">Status Data</label>
                        <select id="data_status" name="data_status" class="sig-input mt-2 @error('data_status') border-red-300 @enderror">
                            @foreach (\App\Models\FloodRiskPoint::DATA_STATUSES as $dataStatus)
                                <option value="{{ $dataStatus }}" @selected(old('data_status', $floodRisk->data_status) === $dataStatus)>{{ $dataStatus }}</option>
                            @endforeach
                        </select>
                        @error('data_status') <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700" for="source_reference">Referensi Sumber</label>
                        <input id="source_reference" name="source_reference" class="sig-input mt-2 @error('source_reference') border-red-300 @enderror" type="text" value="{{ old('source_reference', $floodRisk->source_reference) }}" placeholder="URL/catatan sumber jika data nyata">
                        @error('source_reference') <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <label class="flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm font-semibold text-slate-700">
                        <input name="is_verified" type="checkbox" value="1" class="h-4 w-4 rounded border-slate-300 text-secondary focus:ring-secondary/30" @checked(old('is_verified', $floodRisk->is_verified))>
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
                <a href="{{ $isEdit ? route('admin.flood-risks.show', $floodRisk) : route('admin.flood-risks.index') }}" class="sig-button sig-button-outline">Batal</a>
                <button type="submit" class="sig-button sig-button-primary">{{ $submitLabel }}</button>
            </div>
        </div>
    </div>
</form>
