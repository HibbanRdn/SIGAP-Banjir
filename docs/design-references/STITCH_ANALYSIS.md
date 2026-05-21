# STITCH_ANALYSIS.md

# Analisis Referensi Desain Stitch

Project: Sistem Informasi Geografis Mitigasi dan Respons Banjir Kota Bandar Lampung

Dokumen ini merangkum hasil analisis visual dari export Stitch yang berada di `docs/design-references/stitch`. Tujuannya adalah membantu implementasi UI Laravel Blade + Tailwind CSS agar tetap konsisten dengan arah desain project, tanpa menyalin kode HTML Stitch secara mentah.

## 1. Ringkasan Umum

Desain Stitch digunakan sebagai referensi visual, bukan kode final.

Implementasi final tetap mengikuti stack project:

- Laravel untuk backend.
- Blade untuk view.
- Tailwind CSS untuk styling.
- Leaflet untuk peta.
- PostgreSQL + PostGIS untuk database spasial.
- OSRM/OpenRouteService untuk rute evakuasi sederhana.

Export Stitch membantu memberi acuan visual untuk:

- layout admin dashboard;
- public map explorer;
- tabel manajemen data;
- form tambah/edit;
- detail kejadian banjir;
- rekomendasi evakuasi dan alat berat;
- state kosong, loading, error;
- style warna, spacing, border, radius, card, button, badge, dan form.

Kode `code.html` dari Stitch tidak boleh di-copy-paste langsung ke Laravel karena:

- masih berupa HTML statis;
- beberapa screen masih memakai branding placeholder seperti `Civic GIS`;
- sebagian label masih berbahasa Inggris;
- beberapa data dan koordinat masih generic;
- Stitch memakai Tailwind CDN dan script statis, sedangkan project Laravel harus memakai Vite + Tailwind;
- icon dari Stitch hanya referensi, sedangkan project merekomendasikan satu icon library konsisten, yaitu Lucide Icons;
- peta final harus memakai Leaflet dan endpoint GeoJSON, bukan peta statis atau dummy.

Kesimpulan: desain Stitch layak dipakai sebagai visual direction, tetapi implementasi harus dibuat ulang secara bersih dalam Blade + Tailwind CSS sesuai `docs/UI.md`, `docs/API.md`, `docs/TASKS.md`, dan `AGENTS.md`.

## 2. Daftar Screen yang Tersedia

Folder yang ditemukan di `docs/design-references/stitch`:

| Nama Folder | `screen.png` | `code.html` / Referensi | Fungsi Screen | Kategori |
|---|---|---|---|---|
| `add_edit_flood_event` | `docs/design-references/stitch/add_edit_flood_event/screen.png` | `docs/design-references/stitch/add_edit_flood_event/code.html` | Form tambah/edit kejadian banjir | Add/edit form |
| `admin_analisis_spasial` | `docs/design-references/stitch/admin_analisis_spasial/screen.png` | `docs/design-references/stitch/admin_analisis_spasial/code.html` | Halaman analisis spasial dan rekomendasi sumber daya | Recommendation panel / spatial analysis |
| `admin_dashboard` | `docs/design-references/stitch/admin_dashboard/screen.png` | `docs/design-references/stitch/admin_dashboard/code.html` | Dashboard admin dengan statistik, kejadian terbaru, dan inventaris alat | Admin dashboard |
| `admin_login` | `docs/design-references/stitch/admin_login/screen.png` | `docs/design-references/stitch/admin_login/code.html` | Halaman login admin | Login page |
| `evacuation_route_view` | `docs/design-references/stitch/evacuation_route_view/screen.png` | `docs/design-references/stitch/evacuation_route_view/code.html` | Tampilan rute evakuasi dan informasi perjalanan | Route view |
| `flood_event_detail` | `docs/design-references/stitch/flood_event_detail/screen.png` | `docs/design-references/stitch/flood_event_detail/code.html` | Detail kejadian banjir dengan rekomendasi evakuasi dan alat berat | Detail page |
| `flood_event_management` | `docs/design-references/stitch/flood_event_management/screen.png` | `docs/design-references/stitch/flood_event_management/code.html` | Tabel manajemen kejadian banjir | Management table |
| `jenis_unit_alat_management` | `docs/design-references/stitch/jenis_unit_alat_management/screen.png` | `docs/design-references/stitch/jenis_unit_alat_management/code.html` | Manajemen jenis dan unit alat berat | Management table / inventory |
| `pos_alat_berat_management` | `docs/design-references/stitch/pos_alat_berat_management/screen.png` | `docs/design-references/stitch/pos_alat_berat_management/code.html` | Manajemen pos alat berat | Management table / resource cards |
| `public_map_explorer` | `docs/design-references/stitch/public_map_explorer/screen.png` | `docs/design-references/stitch/public_map_explorer/code.html` | Peta publik dengan panel eksplorasi, layer, marker, dan popup | Public map explorer |
| `sigap_banjir` | Tidak ada | `docs/design-references/stitch/sigap_banjir/DESIGN.md` | Token desain, identitas, warna, font, spacing, dan logo usage | Design token reference |
| `states_components_summary` | `docs/design-references/stitch/states_components_summary/screen.png` | `docs/design-references/stitch/states_components_summary/code.html` | Ringkasan komponen state kosong, loading, error, dan form state | State/loading/empty |
| `sumber_data_status` | `docs/design-references/stitch/sumber_data_status/screen.png` | `docs/design-references/stitch/sumber_data_status/code.html` | Ringkasan status sumber data nyata, dummy, simulasi, dan perlu validasi | Data status page |
| `titik_evakuasi_management` | `docs/design-references/stitch/titik_evakuasi_management/screen.png` | `docs/design-references/stitch/titik_evakuasi_management/code.html` | Manajemen titik evakuasi | Management table |
| `titik_rawan_banjir_management` | `docs/design-references/stitch/titik_rawan_banjir_management/screen.png` | `docs/design-references/stitch/titik_rawan_banjir_management/code.html` | Manajemen titik rawan banjir | Management table |

Catatan:

- `sigap_banjir/DESIGN.md` bukan screen, tetapi penting sebagai referensi token desain.
- Semua `screen.png` dan `code.html` tetap disimpan sebagai referensi desain, bukan aset implementasi final.
- Tidak ada file Stitch yang perlu dipindahkan atau dihapus.

## 3. Analisis Visual per Screen

### 3.1 `add_edit_flood_event`

Fungsi screen:

- Menjadi referensi untuk form tambah/edit kejadian banjir.
- Cocok dipetakan ke halaman Laravel Blade `resources/views/admin/flood-events/create.blade.php`, `resources/views/admin/flood-events/edit.blade.php`, atau partial form seperti `resources/views/admin/flood-events/_form.blade.php`.

Layout utama:

- Admin layout dengan sidebar dan konten utama.
- Header halaman dengan judul besar.
- Form dibagi menjadi beberapa section.
- Ada area input informasi kejadian, deskripsi, lokasi, koordinat, severity/status, dan sumber data.
- Ada area map picker atau placeholder peta untuk memilih koordinat.
- Ada tombol aksi simpan/batal yang sebaiknya dibuat konsisten.

Komponen utama:

- Form section card.
- Input text.
- Textarea.
- Select.
- Coordinate fields.
- Map picker panel.
- Helper text.
- Button primary dan secondary.
- Status/severity selector.

Style yang menonjol:

- Form terlihat lebih product-like daripada form admin default.
- Spacing antar section cukup lega.
- Border card halus.
- Label dan helper text cukup jelas.

Bagian yang cocok diadaptasi:

- Pembagian form menjadi section.
- Area koordinat dengan helper text.
- Map picker sebagai bagian khusus, bukan input koordinat biasa saja.
- Action bar simpan/batal yang jelas.

Bagian yang perlu disesuaikan:

- Brand `Civic GIS` harus diganti menjadi `SIGAP Banjir`.
- Judul `Record Flood Event` diganti menjadi `Tambah Kejadian Banjir` atau `Edit Kejadian Banjir`.
- Placeholder contoh koordinat generic seperti `-122.4194` dan `37.7749` harus diganti ke konteks Bandar Lampung.
- Field harus mengikuti `flood_events`: `name`, `address`, `district`, `subdistrict`, `severity_level`, `water_depth_cm`, `status`, `description`, `occurred_at`, `reported_at`, `source_type`, `source_reference`, `data_status`, `is_verified`, `longitude`, `latitude`.
- Urutan PostGIS harus ditegaskan: longitude, latitude.

Bagian yang tidak boleh di-copy mentah:

- HTML form statis.
- Tailwind CDN.
- Placeholder data English/generic.
- Script map statis.

### 3.2 `admin_analisis_spasial`

Fungsi screen:

- Menjadi referensi untuk halaman analisis spasial admin.
- Cocok untuk rekomendasi evakuasi terdekat dan pos alat berat terdekat.

Layout utama:

- Admin shell dengan sidebar.
- Area filter atau pemilihan kejadian banjir.
- Panel hasil analisis.
- Rekomendasi evakuasi dan alat berat ditampilkan sebagai card/list.
- Ada informasi jarak dan kemungkinan konteks rute.

Komponen utama:

- Search/select kejadian banjir.
- Recommendation card.
- Ranked result list.
- Distance metadata.
- Badge status.
- Action button seperti lihat peta atau tampilkan rute.

Style yang menonjol:

- Hasil rekomendasi tidak sekadar tabel.
- Informasi jarak dan ranking terlihat lebih mudah dipahami.
- Ada pembeda visual antara evakuasi dan alat berat.

Bagian yang cocok diadaptasi:

- Card rekomendasi evakuasi.
- Card pos alat berat.
- Ranking dan label `Terdekat`.
- Jarak memakai font teknis.

Bagian yang perlu disesuaikan:

- Harus mengikuti endpoint API:
  - `/api/v1/analysis/flood-events/{id}/nearest-evacuation`
  - `/api/v1/analysis/flood-events/{id}/nearest-equipment`
  - `/api/v1/analysis/flood-events/{id}/nearest-resources`
- Hasil analisis harus berasal dari PostGIS, bukan data dummy di frontend.
- Label harus Bahasa Indonesia.
- Equipment harus konsisten sebagai `pos alat berat` dan `unit alat berat`.

Bagian yang tidak boleh di-copy mentah:

- Dummy recommendation data.
- Script interaksi statis.
- Kalkulasi jarak di frontend.

### 3.3 `admin_dashboard`

Fungsi screen:

- Referensi utama untuk dashboard admin.
- Cocok dipetakan ke `resources/views/admin/dashboard.blade.php`.

Layout utama:

- Sidebar product-like.
- Topbar ringan.
- Grid statistik.
- Section kejadian banjir terbaru.
- Section inventaris alat.
- Quick overview yang tidak terlalu berat.

Komponen utama:

- Statistic cards.
- Recent flood event list.
- Equipment inventory section.
- Badge status.
- Quick action button.
- Sidebar navigation.

Style yang menonjol:

- Dashboard terasa modern, calm, dan tidak seperti template admin lama.
- Card memakai border dan shadow ringan.
- Angka statistik cukup menonjol.

Bagian yang cocok diadaptasi:

- Struktur dashboard sebagai halaman pertama admin.
- Pola statistic card.
- Recent events.
- Equipment availability summary.
- Sidebar dan topbar.

Bagian yang perlu disesuaikan:

- `Civic GIS` dan `Civic Flood Dashboard` harus diganti.
- Statistik harus disesuaikan dengan MVP:
  - Banjir Aktif.
  - Titik Rawan.
  - Titik Evakuasi Aktif.
  - Pos Alat Berat Aktif.
  - Unit Alat Tersedia.
  - Data Perlu Validasi.
- Data card harus berasal dari endpoint/dashboard backend, bukan angka statis.

Bagian yang tidak boleh di-copy mentah:

- Label brand English.
- Dummy dashboard values sebagai data final.
- Icon library campuran.

### 3.4 `admin_login`

Fungsi screen:

- Referensi untuk halaman login admin.
- Cocok dipetakan ke `resources/views/auth/login.blade.php`.

Layout utama:

- Login card di tengah halaman.
- Background netral.
- Brand di bagian atas.
- Input credential dan tombol login.

Komponen utama:

- Brand logo.
- Input username/email.
- Input password.
- Button primary.
- Error state login.

Style yang menonjol:

- Login tidak terlalu ramai.
- Cocok dengan style civic modern.
- Card login punya spacing rapi.

Bagian yang cocok diadaptasi:

- Layout card login.
- Logo di bagian atas.
- Tombol login full-width.
- Error message dekat form.

Bagian yang perlu disesuaikan:

- Gunakan logo project:
  - `public/assets/brand/logo-utama.png`
  - `public/assets/brand/logo-icon.png`
- Placeholder seperti `adm_12345` harus disesuaikan jika login memakai email atau username.
- Auth final mengikuti Laravel MVP, bukan logic statis.

Bagian yang tidak boleh di-copy mentah:

- Credential dummy.
- Script login statis.
- Placeholder yang tidak cocok dengan implementasi Laravel.

### 3.5 `evacuation_route_view`

Fungsi screen:

- Referensi untuk tampilan rute evakuasi.
- Cocok untuk panel rute pada detail kejadian banjir atau halaman khusus rute.

Layout utama:

- Header route.
- Informasi origin dan destination.
- Panel distance/duration.
- Area peta/rute.
- Informasi rute referensi.

Komponen utama:

- Route information panel.
- Origin/destination card.
- Distance and duration metric.
- Route line map.
- Warning/info alert.

Style yang menonjol:

- Rute terasa sebagai informasi operasional, bukan hanya garis pada peta.
- Jarak dan durasi menonjol dengan typography teknis.

Bagian yang cocok diadaptasi:

- Card asal dan tujuan.
- Metric jarak dan durasi.
- Panel catatan bahwa rute adalah referensi.
- Garis rute pada peta Leaflet.

Bagian yang perlu disesuaikan:

- Rute harus berasal dari endpoint:
  - `/api/v1/routing/flood-events/{id}/to-nearest-evacuation`
  - `/api/v1/routing/flood-events/{id}/to-evacuation/{evacuation_id}`
- Rute harus berupa GeoJSON LineString.
- Tampilkan label `Rute referensi`, bukan `rute resmi`.
- Jangan klaim mempertimbangkan jalan tertutup.

Bagian yang tidak boleh di-copy mentah:

- Route dummy.
- Koordinat generic.
- Script peta statis.

### 3.6 `flood_event_detail`

Fungsi screen:

- Referensi untuk halaman detail kejadian banjir.
- Cocok dipetakan ke `resources/views/admin/flood-events/show.blade.php`.

Layout utama:

- Header dengan nama kejadian.
- Badge status dan severity.
- Metadata kejadian.
- Area peta.
- Panel rekomendasi evakuasi.
- Panel rekomendasi alat berat.
- CTA rute evakuasi.

Komponen utama:

- Detail header.
- Severity badge.
- Status badge.
- Metadata card.
- Mini map/map section.
- Recommendation card.
- Route CTA.
- Source/data status alert.

Style yang menonjol:

- Informasi operasional dan spasial berada dalam satu halaman.
- Rekomendasi ditampilkan sebagai kartu yang mudah dipindai.

Bagian yang cocok diadaptasi:

- Struktur detail sebagai pusat workflow admin.
- Rekomendasi evakuasi dan alat berat dalam card/list.
- Jarak dan durasi memakai JetBrains Mono.
- Alert untuk data dummy/simulasi.

Bagian yang perlu disesuaikan:

- Judul dan data seperti `Lower Basin Sector 4 Breach` harus diganti dengan data Bandar Lampung.
- `Northside Civic Center` dan `Depot 4` harus diganti dengan titik evakuasi dan pos alat berat dari database.
- Search placeholder generic harus disesuaikan.

Bagian yang tidak boleh di-copy mentah:

- Data lokasi generic.
- Recommendation dummy.
- Brand `Civic GIS`.

### 3.7 `flood_event_management`

Fungsi screen:

- Referensi untuk tabel manajemen kejadian banjir.
- Cocok dipetakan ke `resources/views/admin/flood-events/index.blade.php`.

Layout utama:

- Admin shell.
- Header halaman.
- Search/filter area.
- Button tambah.
- Tabel data.
- Badge status/severity.
- Action buttons.

Komponen utama:

- Search input.
- Filter button.
- Add button.
- Table.
- Badge.
- Row action.
- Pagination jika diperlukan.

Style yang menonjol:

- Tabel tidak terlihat default.
- Header tabel muted.
- Row actions cukup ringan.
- Status dan severity mudah dibaca.

Bagian yang cocok diadaptasi:

- Pola search/filter/add.
- Tabel dengan badge status dan severity.
- Row hover.
- Action icon buttons.

Bagian yang perlu disesuaikan:

- Judul `Flood Events` menjadi `Kejadian Banjir`.
- Search placeholder menjadi `Cari kejadian, kecamatan, atau status...`.
- Kolom mengikuti `flood_events`.
- Status harus konsisten: `aktif`, `surut`, `ditangani`, `arsip`.
- Severity: `rendah`, `sedang`, `tinggi`, `kritis`.

Bagian yang tidak boleh di-copy mentah:

- Text English.
- ID/dummy rows.
- Action handler statis.

### 3.8 `jenis_unit_alat_management`

Fungsi screen:

- Referensi untuk manajemen jenis dan unit alat berat.
- Cocok untuk:
  - `resources/views/admin/equipment-types/index.blade.php`
  - `resources/views/admin/heavy-equipment-units/index.blade.php`

Layout utama:

- Header manajemen alat.
- Search area.
- Tabel jenis alat.
- Tabel atau section unit alat.
- Badge status ketersediaan.

Komponen utama:

- Inventory cards.
- Equipment type list.
- Unit availability table.
- Badge status.
- Quantity display.

Style yang menonjol:

- Inventory terasa seperti resource management, bukan tabel polos.
- Unit dan jenis alat bisa dibedakan secara visual.

Bagian yang cocok diadaptasi:

- Pemisahan `jenis alat` dan `unit alat`.
- Badge availability.
- Quantity dengan font teknis.
- List unit per pos.

Bagian yang perlu disesuaikan:

- Jenis alat mengikuti DATASET.md:
  - `excavator`
  - `dump_truck`
  - `wheel_loader`
  - `pompa_air`
  - `crane_kecil`
  - `mobil_tangki`
  - `pickup_operasional`
- Status unit: `tersedia`, `digunakan`, `perawatan`, `tidak_aktif`.
- Jangan memasukkan resource di luar MVP tanpa keputusan.

Bagian yang tidak boleh di-copy mentah:

- Data inventory dummy sebagai seed final.
- Class HTML langsung.
- Struktur yang membuat relasi pos-unit-type tidak jelas.

### 3.9 `pos_alat_berat_management`

Fungsi screen:

- Referensi untuk manajemen pos alat berat.
- Cocok dipetakan ke `resources/views/admin/heavy-equipment-posts/index.blade.php`.

Layout utama:

- Header halaman.
- Search/filter.
- List/table pos alat berat.
- Card ringkas untuk pos tertentu.
- Informasi status pos dan unit tersedia.

Komponen utama:

- Search input.
- Pos card.
- Status badge.
- Unit summary.
- Action buttons.
- Map/location indicator jika tersedia.

Style yang menonjol:

- Pos alat berat tampil sebagai resource yang punya konteks lokasi.
- Ada pembeda visual resource dengan warna amber/gold.

Bagian yang cocok diadaptasi:

- Pos card untuk informasi ringkas.
- Badge `aktif`/`tidak_aktif`.
- Ringkasan unit alat di tiap pos.
- Action `Lihat`, `Edit`, `Hapus`.

Bagian yang perlu disesuaikan:

- Pos harus berada di wilayah relevan Bandar Lampung.
- Data alat berat boleh dummy, tetapi harus diberi `data_status = dummy`.
- Status pos hanya `aktif` dan `tidak_aktif`.

Bagian yang tidak boleh di-copy mentah:

- Nama pos jika tidak sesuai dataset.
- Jumlah unit yang terlalu berlebihan.
- Koordinat tanpa validasi.

### 3.10 `public_map_explorer`

Fungsi screen:

- Referensi utama untuk peta publik.
- Cocok dipetakan ke `resources/views/pages/home.blade.php` atau `resources/views/pages/map.blade.php`.

Layout utama:

- Split layout.
- Panel kiri untuk search, layer, filter, dan result list.
- Area kanan untuk peta dominan.
- Marker dan popup.
- Legend/layer control.

Komponen utama:

- Search bar.
- Layer toggle.
- Filter chip.
- Result card.
- Map container.
- Marker visual.
- Popup mini card.
- Legend.

Style yang menonjol:

- Map-first dan product-like.
- Panel kiri membuat peta terasa eksploratif.
- Marker dan popup punya identitas visual.

Bagian yang cocok diadaptasi:

- Split layout.
- Result list yang terhubung dengan marker.
- Layer toggle:
  - Titik Rawan Banjir.
  - Kejadian Banjir.
  - Titik Evakuasi.
  - Pos Alat Berat.
- Popup ringkas.
- Legend.

Bagian yang perlu disesuaikan:

- Label generic seperti `Sector 4 Riverbank`, `Civic Hall Shelter Alpha`, dan `Mobile Pump Unit P-02` harus diganti dengan data database.
- Peta harus Leaflet + GeoJSON endpoint:
  - `/api/v1/geojson/flood-risks`
  - `/api/v1/geojson/flood-events`
  - `/api/v1/geojson/evacuation-points`
  - `/api/v1/geojson/heavy-equipment-posts`
- Search `coordinates or address` boleh dipertahankan secara konsep, tetapi implementasi MVP bisa dimulai dari search nama/kecamatan.

Bagian yang tidak boleh di-copy mentah:

- Peta statis atau dummy.
- Marker statis tanpa data GeoJSON.
- Koordinat generic.

### 3.11 `states_components_summary`

Fungsi screen:

- Referensi untuk state UI: empty, loading, error, dan form state.

Layout utama:

- Kumpulan contoh state dalam section.
- Empty state untuk data kosong.
- Loading skeleton/spinner.
- Error alert.
- Input state.

Komponen utama:

- Empty state card.
- Loading skeleton.
- Error alert.
- Form validation state.
- Disabled/loading button.

Style yang menonjol:

- State terasa dirancang, bukan hanya pesan teks polos.
- Copywriting sudah sebagian berbahasa Indonesia.

Bagian yang cocok diadaptasi:

- Empty state:
  - `Belum ada kejadian banjir`
  - `Tidak ada titik evakuasi aktif`
  - `Pos alat berat tidak tersedia`
- Error state untuk provider rute.
- Loading skeleton untuk list/table/card.

Bagian yang perlu disesuaikan:

- Pastikan semua state mengikuti microcopy UI.md.
- Error harus memberi solusi praktis jika memungkinkan.
- Jangan menggunakan alert merah besar untuk error kecil.

Bagian yang tidak boleh di-copy mentah:

- Markup statis component showcase.
- State yang tidak relevan dengan MVP.

### 3.12 `sumber_data_status`

Fungsi screen:

- Referensi untuk status sumber data.
- Bisa dipakai untuk dashboard data quality atau halaman opsional.

Layout utama:

- Ringkasan data nyata, dummy, simulasi, dan perlu validasi.
- Daftar dataset aktif.
- Badge status sumber.

Komponen utama:

- Data status card.
- Dataset list/table.
- Badge `nyata`, `dummy`, `simulasi`.
- Verification indicator.

Style yang menonjol:

- Pembedaan data nyata/dummy/simulasi terlihat jelas.
- Cocok untuk kebutuhan akademik agar sumber data transparan.

Bagian yang cocok diadaptasi:

- Badge data status.
- Card ringkasan kualitas data.
- Alert data belum diverifikasi.

Bagian yang perlu disesuaikan:

- Halaman ini tidak wajib sebagai MVP awal jika waktu terbatas.
- Bisa dipecah menjadi section kecil di dashboard admin.
- Jika tabel `data_sources` belum dibuat, jangan paksakan halaman penuh.

Bagian yang tidak boleh di-copy mentah:

- Dataset statis.
- Klaim data resmi tanpa sumber.

### 3.13 `titik_evakuasi_management`

Fungsi screen:

- Referensi untuk manajemen titik evakuasi.
- Cocok dipetakan ke `resources/views/admin/evacuation-points/index.blade.php`.

Layout utama:

- Header halaman.
- Search/filter.
- List/table titik evakuasi.
- Card/detail ringkas.
- Badge status dan kapasitas.

Komponen utama:

- Search input.
- Filter status/type.
- Table/card.
- Capacity metric.
- Facilities tag.
- Status badge.
- Action buttons.

Style yang menonjol:

- Titik evakuasi terasa sebagai aset publik, bukan data tabel saja.
- Kapasitas dan status mudah dilihat.

Bagian yang cocok diadaptasi:

- List titik evakuasi dengan kapasitas.
- Badge `aktif`, `penuh`, `tidak_aktif`.
- Facilities chips.
- Contact metadata.

Bagian yang perlu disesuaikan:

- Jenis titik evakuasi mengikuti DATASET.md:
  - sekolah;
  - masjid;
  - gedung pemerintah;
  - aula;
  - lapangan;
  - puskesmas.
- Kapasitas boleh estimasi akademik jika tidak ada data resmi, tetapi harus jelas sumber/statusnya.

Bagian yang tidak boleh di-copy mentah:

- Data fasilitas tanpa validasi.
- Kapasitas sebagai klaim resmi jika hanya estimasi.

### 3.14 `titik_rawan_banjir_management`

Fungsi screen:

- Referensi untuk manajemen titik rawan banjir.
- Cocok dipetakan ke `resources/views/admin/flood-risks/index.blade.php`.

Layout utama:

- Header halaman.
- Search/filter.
- Tabel/list titik rawan.
- Badge risk level.
- Status data dan verifikasi.

Komponen utama:

- Search input.
- Filter risk level.
- Table.
- Risk badge.
- Data status badge.
- Action buttons.

Style yang menonjol:

- Risk level terlihat jelas.
- Cocok untuk data akademik yang membedakan nyata/dummy/simulasi.

Bagian yang cocok diadaptasi:

- Tabel titik rawan dengan risk level.
- Search berdasarkan nama/kecamatan.
- Badge `rendah`, `sedang`, `tinggi`.
- Data status badge.

Bagian yang perlu disesuaikan:

- Kolom mengikuti `flood_risk_points`.
- Jangan mencampur kejadian banjir aktif ke tabel titik rawan.
- Risk level berbeda dari severity kejadian banjir.

Bagian yang tidak boleh di-copy mentah:

- Generic risk rows.
- Data sumber yang tidak jelas.

### 3.15 `sigap_banjir/DESIGN.md`

Fungsi referensi:

- Menjadi sumber token desain dari export Stitch.
- Berisi identitas, warna, font, radius, spacing, dan logo usage.

Token penting yang ditemukan:

- Nama desain: `SIGAP Banjir`.
- Full name: `Sistem Informasi Geografis Antisipasi dan Penanganan Banjir Bandar Lampung`.
- Primary: `#091426`.
- Secondary: `#0058be`.
- Danger coral: `#F87171`.
- Safe teal: `#2DD4BF`.
- Resource amber: `#FBBF24`.
- Surface: `#fbf8fa`.
- Surface gray: `#F8FAFC`.
- Text main: `#334155`.
- Text muted: `#94A3B8`.
- Font utama: Plus Jakarta Sans.
- Font teknis: JetBrains Mono.
- Radius base: 8px.
- Sidebar width reference: 260px.
- Explorer panel width reference: 400px.

Bagian yang cocok diadaptasi:

- Color direction.
- Typography usage.
- Spacing/radius prinsip.
- Logo usage.

Bagian yang perlu disesuaikan:

- Token bisa diterjemahkan ke Tailwind config atau stylesheet global saat implementasi.
- Jangan membuat token terlalu rumit jika belum dibutuhkan MVP.
- Pastikan token final tetap sesuai `docs/UI.md`.

## 4. Komponen Visual Utama yang Bisa Diadaptasi

### Sidebar

Bentuk visual:

- Sidebar product-like dengan brand area, nav item berikon, active state, dan spacing jelas.
- Tidak sekadar blok navy polos.

Kapan dipakai:

- Layout admin.

Style Tailwind konseptual:

- Background netral/white atau navy yang terkontrol.
- Border kanan halus.
- Nav item tinggi konsisten.
- Active state dengan background blue-tinted/navy-tinted.

State:

- default;
- hover;
- active;
- focus;
- disabled jika menu belum tersedia.

Catatan:

- Gunakan logo project.
- Gunakan Lucide Icons saat implementasi, bukan campuran icon library.

### Topbar

Bentuk visual:

- Header ringan dengan judul halaman, breadcrumb opsional, user/action area.

Kapan dipakai:

- Admin dashboard dan halaman manajemen.

Style Tailwind konseptual:

- Border-bottom halus.
- Background putih atau surface.
- Height tidak terlalu tinggi.

State:

- action button hover;
- dropdown hover/focus jika ada;
- loading state untuk data ringkas jika dibutuhkan.

### Search Input

Bentuk visual:

- Input dengan ikon search, border halus, placeholder muted.

Kapan dipakai:

- Tabel manajemen.
- Public map explorer.
- Filter daftar hasil.

Style Tailwind konseptual:

- Rounded-lg.
- Border outline-variant.
- Focus ring civic blue.
- Background white.

State:

- default;
- hover;
- focus;
- disabled;
- error jika search terkait validasi khusus.

### Filter Button dan Filter Chip

Bentuk visual:

- Button outline/ghost untuk membuka filter.
- Chip kecil untuk filter aktif.

Kapan dipakai:

- Public map explorer.
- Tabel admin.

Style Tailwind konseptual:

- Border halus.
- Background muted ketika aktif.
- Text compact.

State:

- default;
- hover;
- active;
- focus;
- disabled.

### Table

Bentuk visual:

- Header muted.
- Row dengan hover lembut.
- Badge status di dalam cell.
- Action icon button di kanan.

Kapan dipakai:

- Kejadian banjir.
- Titik rawan banjir.
- Titik evakuasi.
- Pos alat berat.
- Jenis dan unit alat.

Style Tailwind konseptual:

- White card container.
- Border rounded-lg.
- Text body-md.
- Header background surface-gray.

State:

- loading skeleton.
- empty state.
- row hover.
- selected row jika diperlukan.
- error state jika gagal load.

### Card

Bentuk visual:

- Border halus.
- Shadow minimal.
- Header/content/action area jelas.

Kapan dipakai:

- Statistik dashboard.
- Quick action.
- Result list map.
- Recommendation card.
- Detail metadata.

Style Tailwind konseptual:

- `rounded-lg`.
- `border`.
- `bg-white`.
- `shadow-sm` hanya bila perlu.

State:

- default.
- hover untuk clickable card.
- active/selected.
- loading skeleton.
- empty.

### Statistic Card

Bentuk visual:

- Label kecil.
- Angka besar.
- Ikon kecil.
- Hint pendek.

Kapan dipakai:

- Dashboard admin.

Style Tailwind konseptual:

- Angka memakai JetBrains Mono.
- Icon muted atau sesuai kategori.
- Border lembut.

State:

- loading.
- empty/zero state.
- hover hanya jika clickable.

### Badge

Bentuk visual:

- Pill kecil atau rounded badge.
- Warna status konsisten.

Kapan dipakai:

- Status kejadian banjir.
- Severity.
- Risk level.
- Data status.
- Availability alat.
- Recommended/terdekat.

Style Tailwind konseptual:

- Text kecil.
- Background muted.
- Border opsional.
- Warna terkontrol.

State:

- tidak perlu hover kecuali clickable.
- disabled/muted untuk arsip atau tidak aktif.

### Alert

Bentuk visual:

- Card kecil dengan ikon, judul, dan pesan pendek.

Kapan dipakai:

- Provider rute gagal.
- Data simulasi akademik.
- Koordinat perlu validasi.
- Tidak ada evakuasi aktif.

Style Tailwind konseptual:

- Border sesuai status.
- Background sangat muted.
- Tidak memakai warna merah dominan untuk semua error.

State:

- success.
- warning.
- error.
- info.

### Form Section

Bentuk visual:

- Section card dengan title, deskripsi singkat, dan field group.

Kapan dipakai:

- Form kejadian banjir.
- Form titik evakuasi.
- Form pos alat berat.
- Form titik rawan banjir.

Style Tailwind konseptual:

- Card putih.
- Header section.
- Grid responsive.
- Helper text muted.

State:

- default.
- focus.
- error.
- disabled.
- loading/submit.

### Input, Textarea, Select

Bentuk visual:

- Border halus.
- Rounded-lg.
- Focus ring civic blue.
- Error message dekat field.

Kapan dipakai:

- Semua form admin.

Style Tailwind konseptual:

- `bg-white`.
- `border-outline-variant`.
- `focus:ring-secondary`.
- Text Plus Jakarta Sans.

State:

- default.
- hover.
- focus.
- disabled.
- error.

### Button dan Icon Button

Bentuk visual:

- Primary solid blue/navy.
- Secondary muted.
- Outline dengan border.
- Ghost untuk aksi kecil.
- Destructive untuk hapus.

Kapan dipakai:

- Simpan.
- Tambah.
- Edit.
- Hapus.
- Tampilkan rute.
- Lihat di peta.

Style Tailwind konseptual:

- Height konsisten.
- Radius 8px.
- Icon 16-20px.
- Loading state.

State:

- default.
- hover.
- active.
- focus-visible.
- disabled.
- loading.

### Map Panel

Bentuk visual:

- Container peta dominan.
- Panel kiri atau overlay kontrol.
- Legend/layer control rapi.

Kapan dipakai:

- Public map explorer.
- Detail kejadian banjir.
- Route view.

Style Tailwind konseptual:

- Full height area.
- Border container jika dalam admin detail.
- Tidak terlalu banyak overlay.

State:

- loading map data.
- empty layer.
- selected marker.
- recommended marker.
- route visible.

### Map Popup

Bentuk visual:

- Mini card ringkas.
- Title, badge, metadata, CTA kecil.

Kapan dipakai:

- Marker banjir.
- Marker evakuasi.
- Marker pos alat berat.

Style Tailwind konseptual:

- Popup content ringkas.
- No paragraph panjang.
- Button/link kecil.

State:

- default.
- selected.
- recommended.

### Layer Control

Bentuk visual:

- Toggle list dengan label layer dan status aktif.

Kapan dipakai:

- Public map explorer.
- Admin map.

Style Tailwind konseptual:

- Checkbox/toggle dengan label.
- Badge/count kecil jika tersedia.

State:

- active.
- inactive.
- disabled/loading.

### Recommendation Card

Bentuk visual:

- Card ranking dengan nama, jarak, status, dan CTA.

Kapan dipakai:

- Nearest evacuation.
- Nearest equipment.

Style Tailwind konseptual:

- Badge `Terdekat` untuk rank pertama.
- Distance memakai JetBrains Mono.
- Border accent sesuai kategori.

State:

- loading.
- empty.
- error.
- success.

### Route Information Panel

Bentuk visual:

- Panel jarak, durasi, origin, destination, dan catatan rute referensi.

Kapan dipakai:

- Detail kejadian.
- Route view.

Style Tailwind konseptual:

- Metric card kecil.
- LineString map layer.
- Warning/info alert.

State:

- loading route.
- route found.
- provider error.
- no route.

### Empty State

Bentuk visual:

- Ikon kecil, judul singkat, pesan pendek, action opsional.

Kapan dipakai:

- Tabel kosong.
- Filter tidak menemukan data.
- Tidak ada rekomendasi.

Style Tailwind konseptual:

- Muted border/card.
- Tidak terlalu dramatis.

State:

- data kosong.
- filter kosong.
- resource tidak tersedia.

### Loading State

Bentuk visual:

- Skeleton untuk card/table/list.
- Spinner kecil untuk button.

Kapan dipakai:

- Load GeoJSON.
- Load table.
- Submit form.
- Hitung rekomendasi.
- Ambil rute.

Style Tailwind konseptual:

- Pulse subtle.
- Overlay kecil untuk map loading.

State:

- initial loading.
- button loading.
- partial loading.

## 5. Layout yang Bisa Dipakai

### Public Map Explorer

Layout Stitch yang bisa diadaptasi:

- Split layout.
- Panel kiri untuk search, filter, layer, result count, dan result cards.
- Area kanan untuk peta Leaflet.
- Popup marker ringkas.
- Legend/layer control.
- Route layer jika rute tersedia.

Implementasi final:

- Panel kiri desktop.
- Bottom sheet/collapsible panel pada mobile.
- Data berasal dari endpoint GeoJSON.
- Marker dibuat dari data Leaflet, bukan HTML statis.

### Admin Dashboard

Layout Stitch yang bisa diadaptasi:

- Sidebar.
- Topbar.
- Statistic cards.
- Recent events.
- Equipment inventory.
- Quick actions.

Implementasi final:

- Dashboard menampilkan ringkasan dari database.
- Card statistik memakai JetBrains Mono untuk angka.
- Quick action mengarah ke halaman admin yang sudah ada.
- Jangan membuat chart kompleks di MVP awal.

### Management Table

Layout Stitch yang bisa diadaptasi:

- Header halaman.
- Search area.
- Filter button.
- Add button.
- Table header muted.
- Row hover.
- Badge status.
- Action buttons.
- Pagination jika data bertambah.

Implementasi final:

- Gunakan untuk `flood_risk_points`, `flood_events`, `evacuation_points`, `heavy_equipment_posts`, `equipment_types`, dan `heavy_equipment_units`.
- Tetap sediakan link ke peta/detail untuk data spasial.

### Add/Edit Form

Layout Stitch yang bisa diadaptasi:

- Sectioned form.
- Basic information section.
- Location and coordinate section.
- Map picker area.
- Data source section.
- Sticky action bar jika form panjang.
- Save/cancel button.

Implementasi final:

- Error message dekat field.
- Helper text koordinat.
- Longitude dan latitude harus jelas.
- `geom` dibuat di backend.

### Detail Page

Layout Stitch yang bisa diadaptasi:

- Event title.
- Severity/status badges.
- Metadata.
- Spatial context map.
- Recommendation cards.
- Route information.

Implementasi final:

- Detail kejadian banjir menjadi pusat alur analisis.
- Tampilkan rekomendasi evakuasi dan pos alat berat berdasarkan PostGIS.
- Rute dari backend routing service.

## 6. Warna dan Tone Visual

Tone visual Stitch selaras dengan `docs/UI.md`:

- Neutral background untuk halaman umum.
- White cards untuk konten.
- Navy/dark sebagai anchor visual.
- Blue sebagai primary action dan focus state.
- Muted red/coral untuk banjir, danger, severity tinggi/kritis.
- Teal/green untuk evakuasi dan status aman.
- Amber/gold untuk alat berat dan resource.
- Muted gray untuk metadata.
- Border halus untuk struktur.

Token warna yang ditemukan dari `sigap_banjir/DESIGN.md`:

| Token | Nilai | Pemakaian |
|---|---:|---|
| `primary` | `#091426` | Civic navy, brand, heading, sidebar anchor |
| `secondary` | `#0058be` | Primary action, focus state |
| `danger-coral` | `#F87171` | Banjir aktif, danger, severity |
| `safe-teal` | `#2DD4BF` | Evakuasi, aman, status aktif |
| `resource-amber` | `#FBBF24` | Pos alat berat, resource, warning |
| `surface` | `#fbf8fa` | Background halaman |
| `surface-gray` | `#F8FAFC` | Area sekunder, header tabel |
| `text-main` | `#334155` | Body text |
| `text-muted` | `#94A3B8` | Metadata |
| `outline-variant` | `#c5c6cd` | Border halus |

Rekomendasi:

- Gunakan rasio 70% neutral, 20% navy/blue, 10% accent.
- Jangan overuse merah.
- Jangan memakai neon, gradient berlebihan, atau warna random.
- Gunakan warna status secara konsisten.

## 7. Typography

Aturan font final tetap mengikuti `docs/UI.md`:

- Plus Jakarta Sans sebagai font utama UI.
- JetBrains Mono hanya untuk data teknis.

Plus Jakarta Sans digunakan untuk:

- heading;
- subheading;
- body text;
- navigasi;
- sidebar;
- tombol;
- label form;
- tabel;
- badge;
- popup peta;
- card;
- microcopy;
- seluruh elemen UI umum.

JetBrains Mono hanya digunakan untuk:

- angka statistik;
- koordinat latitude dan longitude;
- jarak meter/kilometer;
- durasi rute;
- ID data;
- kode wilayah jika ada;
- metadata teknis;
- SRID, GeoJSON, status internal, atau response API jika ditampilkan.

Catatan dari Stitch:

- Banyak screen sudah memakai Plus Jakarta Sans dan JetBrains Mono.
- Beberapa screen masih memakai label English/generic.
- Implementasi final tidak boleh memakai font default browser.
- Jangan memakai JetBrains Mono untuk seluruh UI.

## 8. Spacing, Border, Radius, dan Shadow

Pola Stitch yang cocok:

- Spacing halaman sekitar 24px.
- Gap grid sekitar 16px.
- Sidebar sekitar 260px.
- Panel explorer sekitar 400px.
- Radius base sekitar 8px.
- Card dan input banyak memakai radius `rounded-lg`.
- Shadow sangat ringan, lebih mengandalkan border.
- Divider halus untuk memisahkan section.

Rekomendasi implementasi:

- Gunakan spacing konsisten antar section.
- Gunakan border halus pada card, table, input, dan panel.
- Gunakan shadow minimal seperti `shadow-sm` hanya jika perlu.
- Gunakan radius 8px sebagai default button/card/input.
- Gunakan radius lebih besar hanya untuk panel besar jika konsisten.
- Hindari halaman terlalu padat.
- Hindari halaman terlalu kosong.
- Pastikan table, form, dan card tetap readable di desktop dan mobile.

## 9. Button Style

Button dari Stitch bisa dipetakan ke design system berikut:

| Varian | Visual | Dipakai Untuk |
|---|---|---|
| Primary | Solid blue/navy, text putih | Aksi utama seperti `Tambah Kejadian Banjir`, `Simpan Perubahan` |
| Secondary | Background muted, text primary | Aksi pendukung |
| Outline | Border halus, background putih | Filter, navigasi, aksi non-primer |
| Ghost | Background transparan, hover subtle | Icon button, action kecil |
| Destructive | Red controlled, tidak terlalu dominan | Hapus data |
| Link | Text action tanpa box | Navigasi detail kecil |

State yang harus disiapkan:

- default;
- hover;
- active;
- focus-visible;
- disabled;
- loading.

Label tombol final harus Bahasa Indonesia, misalnya:

- `Tambah Kejadian Banjir`
- `Simpan Perubahan`
- `Terapkan Filter`
- `Reset Filter`
- `Cari Evakuasi Terdekat`
- `Cari Pos Alat Berat`
- `Tampilkan Rute Evakuasi`
- `Lihat di Peta`
- `Batal`

Catatan:

- Jangan memakai semua button sebagai primary.
- Button destructive hanya menonjol pada konteks konfirmasi hapus.
- Button loading harus mencegah double-submit.

## 10. Table Style

Pola tabel Stitch yang cocok:

- Table berada dalam card/container putih.
- Header tabel muted.
- Row hover halus.
- Badge untuk status/severity/risk.
- Action button menggunakan ghost/outline icon.
- Search/filter berada di atas tabel.
- Add button berada di area header.

Tabel yang akan memakai pola ini:

- Kejadian Banjir.
- Titik Rawan Banjir.
- Titik Evakuasi.
- Pos Alat Berat.
- Jenis Alat.
- Unit Alat.

Kolom umum:

- Nama.
- Kecamatan.
- Status.
- Severity/Risk.
- Data Status.
- Verifikasi.
- Terakhir Diperbarui.
- Aksi.

Rekomendasi:

- Pada mobile gunakan horizontal scroll.
- Jangan menampilkan semua atribut panjang dalam tabel.
- Detail panjang masuk halaman detail.
- Jika data kosong, tampilkan empty state, bukan tabel kosong polos.

## 11. Form Style

Pola form Stitch yang cocok:

- Section card.
- Label jelas.
- Helper text untuk field rawan salah.
- Input, textarea, select dengan border halus.
- Coordinate block.
- Map picker area.
- Sticky action bar jika form panjang.

Form final harus mendukung:

- `longitude`;
- `latitude`;
- catatan bahwa PostGIS memakai urutan longitude, latitude;
- error message dekat field;
- `source_type`;
- `source_reference`;
- `data_status`;
- `is_verified`.

Section form kejadian banjir:

1. Informasi Kejadian.
2. Lokasi dan Koordinat.
3. Status dan Severity.
4. Sumber Data.
5. Catatan Tambahan.

Section form titik evakuasi:

1. Identitas Lokasi.
2. Kapasitas dan Fasilitas.
3. Kontak.
4. Koordinat.
5. Status Data.

Section form pos alat berat:

1. Identitas Pos.
2. Kontak.
3. Koordinat.
4. Unit Alat.
5. Status Data.

Catatan:

- Jangan membuat form panjang tanpa pengelompokan.
- Jangan menyembunyikan error validasi.
- Jangan mengubah `geom` secara manual dari frontend; backend yang membentuk `geom`.

## 12. Map Panel dan Marker Style

Pola map explorer Stitch:

- Map container dominan.
- Panel kiri untuk eksplorasi.
- Marker dibedakan berdasarkan kategori.
- Popup ringkas.
- Active/recommended marker lebih menonjol.
- Legend/layer control tersedia.
- Route line ditampilkan jika rute tersedia.

Implementasi final:

- Gunakan Leaflet.
- Gunakan OpenStreetMap sebagai basemap.
- Data layer berasal dari endpoint GeoJSON.
- Coordinates GeoJSON harus `[longitude, latitude]`.
- Jika memakai Leaflet LatLng API, konversi format sesuai kebutuhan Leaflet tanpa mengubah format GeoJSON/PostGIS.

Layer wajib:

- Titik rawan banjir.
- Kejadian banjir.
- Titik evakuasi.
- Pos alat berat.

Layer opsional:

- Rute evakuasi.
- Radius terdampak simulasi.
- Batas kecamatan jika data tersedia.

Marker style yang disarankan:

- Flood risk point: amber/orange/red sesuai risk level.
- Flood event: red/coral sesuai severity/status.
- Evacuation point: teal/green/blue.
- Heavy equipment post: amber/gold.
- Selected point: outline/ring biru/navy.
- Recommended point: ring atau badge visual khusus.

Popup:

- Title.
- Badge status.
- Kecamatan/kelurahan.
- Metadata penting.
- CTA kecil: `Lihat Detail`, `Cari Evakuasi`, `Cari Alat Berat`, atau `Tampilkan Rute` sesuai konteks.

Catatan:

- Jangan memakai marker default Leaflet tanpa pertimbangan visual jika waktu memungkinkan.
- Jangan memasukkan semua atribut panjang ke popup.
- Jangan memakai peta statis dari Stitch.

## 13. Branding yang Harus Disesuaikan

Brand placeholder dari Stitch yang harus diganti:

| Dari Stitch | Menjadi |
|---|---|
| `Civic GIS` | `SIGAP Banjir` |
| `Civic Flood Dashboard` | `SIGAP Banjir` atau `Dashboard Admin` |
| `Flood Mitigation Unit` | `Sistem Informasi Geografis Antisipasi dan Penanganan Banjir Bandar Lampung` |
| Generic city/sector | Konteks Bandar Lampung |
| `Sector 4 Riverbank` | Nama lokasi banjir dari database |
| `Civic Hall Shelter Alpha` | Nama titik evakuasi dari database |
| `Mobile Pump Unit P-02` | Pos/unit alat berat dari database |

Asset logo project yang harus digunakan:

- `public/assets/brand/logo-utama.png`
- `public/assets/brand/logo-icon.png`
- `public/favicon.png`

Aturan branding:

- Gunakan logo utama pada login, sidebar/topbar, dan halaman publik.
- Gunakan logo icon untuk favicon atau sidebar compact.
- Jangan memakai brand placeholder dari Stitch.
- Jangan memakai logo/image dari Stitch jika tidak berasal dari asset project.

## 14. Istilah yang Harus Diindonesiakan

Istilah English/generic dari Stitch harus diganti ke Bahasa Indonesia yang konsisten:

| Dari Stitch | Istilah Final |
|---|---|
| Flood Events | Kejadian Banjir |
| Incident Reports | Kejadian Banjir |
| Risk Analysis | Analisis Risiko / Analisis Spasial |
| Asset Inventory | Inventaris Alat / Pos Alat Berat |
| Data Management | Manajemen Data |
| New Event | Tambah Kejadian |
| Record Flood Event | Tambah Kejadian Banjir |
| Search events | Cari kejadian |
| Filter | Filter |
| Active | Aktif |
| Critical | Kritis |
| Resolved | Surut atau Arsip, sesuai konteks |
| Equipment | Alat Berat |
| Evacuation | Evakuasi |
| Shelter | Titik Evakuasi |
| Dispatch | Kirim / Rekomendasikan / Tampilkan Rute, sesuai konteks |
| Route | Rute Evakuasi |
| Data Source | Sumber Data |

Istilah final yang harus dipakai:

- titik rawan banjir;
- kejadian banjir;
- titik evakuasi;
- pos alat berat;
- unit alat berat;
- jenis alat berat;
- rekomendasi evakuasi terdekat;
- rekomendasi alat berat terdekat;
- rute evakuasi;
- data nyata;
- data dummy;
- data simulasi;
- data spasial;
- GeoJSON;
- PostGIS;
- Leaflet.

## 15. Hal yang Cocok Dipakai

Elemen Stitch yang layak dipakai sebagai acuan:

- Sidebar product-like.
- Dashboard card yang calm dan polished.
- Statistic card dengan ikon kecil.
- Clean table dengan search/filter/action.
- Form section yang rapi.
- Coordinate/map picker block.
- Sticky action bar untuk form panjang.
- Public map split layout.
- Result card pada map explorer.
- Marker/popup yang lebih dipikirkan daripada default.
- Recommendation card.
- Route information panel.
- Muted border.
- Badge style.
- Loading/empty/error state.
- Data status card.
- Typography hierarchy.
- Warna civic navy/blue dengan aksen coral, teal, amber.

## 16. Hal yang Perlu Disesuaikan

Hal yang harus disesuaikan sebelum implementasi:

- Branding `Civic GIS` menjadi `SIGAP Banjir`.
- Bahasa Inggris menjadi Bahasa Indonesia.
- Nama menu mengikuti dokumen project.
- Data dummy/generic diganti dataset project.
- Peta generic menjadi konteks Bandar Lampung.
- Koordinat contoh harus berada di Bandar Lampung jika ditampilkan.
- Istilah `Civic` jangan dipakai sebagai brand utama.
- Resource seperti rescue boats atau resource lain di luar MVP tidak perlu dibuat.
- Action label harus sesuai API dan fitur MVP.
- Icon Material Symbols dari Stitch diganti ke icon library final jika project memilih Lucide Icons.
- Tailwind CDN Stitch diganti dengan Tailwind via Vite.
- Komponen harus dibuat ulang sebagai Blade/partial/component yang rapi.
- Data status harus jelas: `nyata`, `dummy`, `simulasi`.
- `source_type`, `source_reference`, `data_status`, dan `is_verified` harus tetap terlihat pada halaman yang relevan.

## 17. Hal yang Tidak Boleh Di-copy Mentah

Jangan melakukan hal berikut:

- Jangan copy HTML mentah Stitch ke Blade.
- Jangan copy script yang tidak relevan.
- Jangan copy data dummy sebagai data final.
- Jangan copy koordinat generic.
- Jangan copy brand `Civic GIS`.
- Jangan copy peta statis.
- Jangan copy class Tailwind tanpa dirapikan.
- Jangan memasukkan Tailwind CDN ke project Laravel final.
- Jangan memasukkan dependency yang tidak sesuai stack.
- Jangan mengubah project menjadi React.
- Jangan menggunakan shadcn/ui asli.
- Jangan memakai data Stitch sebagai seed final.
- Jangan mengklaim data dummy sebagai data resmi.
- Jangan membuat fitur di luar MVP hanya karena muncul di desain.

## 18. Rekomendasi Implementasi ke Laravel Blade + Tailwind CSS

Urutan implementasi UI yang direkomendasikan saat sudah masuk fase UI:

1. Rapikan base layout admin.
2. Rapikan base layout public.
3. Buat partial/sidebar admin.
4. Buat partial/topbar admin.
5. Standarkan komponen dasar:
   - button;
   - card;
   - badge;
   - table;
   - form field;
   - alert;
   - empty state.
6. Implementasikan dashboard admin.
7. Implementasikan management table kejadian banjir.
8. Implementasikan form tambah/edit kejadian banjir.
9. Implementasikan management table lain:
   - titik rawan banjir;
   - titik evakuasi;
   - pos alat berat;
   - jenis dan unit alat.
10. Implementasikan public map explorer.
11. Implementasikan detail kejadian banjir.
12. Implementasikan recommendation panel.
13. Implementasikan route information panel.
14. Polish state, responsive, hover, focus, empty, loading, dan error.

Halaman pertama yang paling aman diimplementasikan:

- `admin_dashboard` setelah base admin layout siap.

Alasannya:

- Dashboard membantu mengunci visual system lebih awal.
- Tidak membutuhkan interaksi peta yang kompleks.
- Bisa dimulai dengan data placeholder terkontrol sebelum endpoint statistik final.
- Komponen yang dibuat akan dipakai ulang di halaman berikutnya.

Setelah dashboard:

1. `flood_event_management`.
2. `add_edit_flood_event`.
3. `public_map_explorer`.
4. `flood_event_detail`.
5. `admin_analisis_spasial`.
6. `evacuation_route_view`.

## 19. Mapping Screen Stitch ke File Blade

Mapping konseptual ke struktur Blade:

| Screen Stitch | File Blade yang Cocok | Catatan |
|---|---|---|
| `admin_login` | `resources/views/auth/login.blade.php` | Login admin, gunakan logo utama |
| `admin_dashboard` | `resources/views/admin/dashboard.blade.php` | Dashboard admin dan card statistik |
| `flood_event_management` | `resources/views/admin/flood-events/index.blade.php` | Tabel kejadian banjir |
| `add_edit_flood_event` | `resources/views/admin/flood-events/create.blade.php`, `resources/views/admin/flood-events/edit.blade.php`, atau `_form.blade.php` | Form sectioned untuk kejadian banjir |
| `flood_event_detail` | `resources/views/admin/flood-events/show.blade.php` | Detail kejadian, rekomendasi, rute |
| `admin_analisis_spasial` | `resources/views/admin/analysis/index.blade.php` atau section di detail kejadian | Analisis nearest evacuation/equipment |
| `evacuation_route_view` | `resources/views/admin/routes/show.blade.php` atau partial route panel | Rute evakuasi referensi |
| `public_map_explorer` | `resources/views/pages/home.blade.php` atau `resources/views/pages/map.blade.php` | Peta publik Leaflet |
| `titik_rawan_banjir_management` | `resources/views/admin/flood-risks/index.blade.php` | Tabel titik rawan banjir |
| `titik_evakuasi_management` | `resources/views/admin/evacuation-points/index.blade.php` | Tabel titik evakuasi |
| `pos_alat_berat_management` | `resources/views/admin/heavy-equipment-posts/index.blade.php` | Tabel/card pos alat berat |
| `jenis_unit_alat_management` | `resources/views/admin/equipment-types/index.blade.php` dan `resources/views/admin/heavy-equipment-units/index.blade.php` | Manajemen jenis dan unit alat |
| `sumber_data_status` | `resources/views/admin/data-status/index.blade.php` atau section dashboard | Opsional, jangan memblokir MVP awal |
| `states_components_summary` | Referensi component partials | Bukan halaman produk utama |
| `sigap_banjir/DESIGN.md` | Tailwind config/stylesheet global saat implementasi | Sumber token visual |

Catatan:

- Mapping ini konseptual. Nama file final boleh disesuaikan dengan struktur Laravel yang dipilih, selama tetap konsisten dan mudah dipahami.
- Jangan membuat file Blade dari mapping ini sampai user meminta implementasi UI.

## 20. Checklist Kesiapan Implementasi UI

- [ ] Semua screen Stitch terdaftar.
- [ ] Branding placeholder sudah dicatat.
- [ ] Komponen visual sudah diekstrak.
- [ ] Layout utama sudah dipetakan.
- [ ] Warna dan typography sudah disesuaikan.
- [ ] Mapping ke Blade sudah dibuat.
- [ ] Hal yang tidak boleh di-copy sudah jelas.
- [ ] Halaman pertama untuk implementasi sudah direkomendasikan.
- [ ] Implementasi final tetap Laravel Blade + Tailwind CSS.
- [ ] Peta final tetap Leaflet.
- [ ] Data peta final tetap dari endpoint GeoJSON.
- [ ] Analisis spasial final tetap dari PostGIS backend.
- [ ] Tidak ada HTML Stitch yang dijadikan kode final.

