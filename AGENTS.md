# AGENTS.md

## 1. Project Context

Repository ini adalah project:

```text
Sistem Informasi Geografis Mitigasi dan Respons Banjir Kota Bandar Lampung
```

Project ini adalah MVP akademik untuk mata kuliah Sistem Informasi Geografis. Sistem berfokus pada:

- pemetaan titik rawan banjir;
- pemetaan kejadian banjir;
- pengelolaan titik evakuasi;
- pengelolaan pos alat berat;
- rekomendasi titik evakuasi terdekat;
- rekomendasi pos alat berat terdekat;
- rute evakuasi sederhana;
- data spasial berbasis PostgreSQL + PostGIS;
- peta interaktif berbasis Leaflet.

Project ini bukan sistem BPBD produksi. Jangan memperlakukannya sebagai sistem kebencanaan skala penuh. Fokusnya adalah MVP akademik SIG yang realistis, rapi, bisa didemonstrasikan, dan menunjukkan nilai analisis spasial.

## 2. Source of Truth

Sebelum mengerjakan task apa pun, agent wajib membaca dokumen acuan sesuai kebutuhan:

- `docs/REQUIREMENTS.md` untuk scope, fitur, batasan, aktor, dan kriteria keberhasilan.
- `docs/DATABASE.md` untuk struktur database, relasi, PostGIS, SRID, index, dan query spasial.
- `docs/DATASET.md` untuk data nyata, dummy, simulasi, seed data, koordinat, dan validasi dataset.
- `docs/API.md` untuk rancangan endpoint, response JSON, GeoJSON, validasi, status code, dan error handling.
- `docs/UI.md` untuk UI/UX, layout, style, font, warna, map explorer, interaction state, dan design system.
- `docs/TASKS.md` untuk urutan pengerjaan dan roadmap implementasi.

Aturan:

1. Jika ada konflik antara dokumen dan permintaan implementasi, berhenti dan jelaskan konfliknya terlebih dahulu.
2. Jangan mengambil keputusan besar tanpa merujuk dokumen.
3. Jangan mengubah keputusan desain tanpa alasan jelas.
4. Jangan memperluas scope tanpa persetujuan user.
5. Jika dokumen belum cukup menjawab suatu keputusan teknis, pilih solusi paling konservatif dan paling sesuai MVP.

## 3. MVP Scope

Fitur yang termasuk MVP:

1. Admin login.
2. CRUD titik rawan banjir.
3. CRUD kejadian banjir.
4. CRUD titik evakuasi.
5. CRUD pos alat berat.
6. CRUD jenis alat berat.
7. CRUD unit alat berat.
8. Endpoint GeoJSON untuk layer peta.
9. Peta publik berbasis Leaflet.
10. Dashboard admin.
11. Rekomendasi titik evakuasi terdekat berbasis PostGIS.
12. Rekomendasi pos alat berat terdekat berbasis PostGIS.
13. Rute evakuasi sederhana menggunakan OSRM/OpenRouteService.
14. Seed data demo yang membedakan data nyata, dummy, dan simulasi.

Nilai SIG utama MVP ada pada:

- penyimpanan data spasial di PostGIS;
- visualisasi layer peta;
- response GeoJSON;
- query jarak spasial;
- rekomendasi sumber daya respons terdekat, yaitu titik evakuasi dan pos alat berat;
- visualisasi rute referensi.

## 4. Out of Scope

Fitur berikut tidak boleh dikerjakan sebelum MVP selesai:

- laporan publik;
- upload foto;
- validasi multi-role;
- role BPBD kompleks;
- role petugas lapangan kompleks;
- tracking alat berat real-time;
- prediksi banjir;
- integrasi BMKG;
- integrasi IoT/sensor;
- pgRouting;
- simulasi jalan tertutup;
- aplikasi mobile;
- dashboard prioritas wilayah yang terlalu kompleks.

Jika user meminta fitur tersebut sebelum MVP selesai, tandai sebagai backlog dan jelaskan bahwa fitur tersebut berada di luar scope MVP. Jangan langsung mengerjakannya kecuali user secara eksplisit meminta perubahan scope.

## 5. Tech Stack Rules

Stack final project:

- Backend: Laravel.
- View: Blade.
- Styling: Tailwind CSS.
- Database: PostgreSQL.
- Spatial extension: PostGIS.
- Map: Leaflet.
- Routing sederhana: OSRM atau OpenRouteService.

Aturan stack:

1. Backend tetap Laravel.
2. View tetap Blade.
3. Styling tetap Tailwind CSS.
4. Database tetap PostgreSQL.
5. Spatial extension tetap PostGIS.
6. Map tetap Leaflet.
7. Routing sederhana menggunakan OSRM atau OpenRouteService.
8. Jangan mengubah project menjadi React/Next.js.
9. Jangan menggunakan shadcn/ui asli.
10. Jangan mengganti stack tanpa instruksi eksplisit dari user.

## 6. Laravel Coding Rules

Aturan Laravel:

1. Ikuti struktur Laravel yang rapi.
2. Pisahkan controller, model, service, request validation, dan view.
3. Jangan membuat controller terlalu penuh.
4. Query spasial sebaiknya diletakkan di service khusus.
5. Gunakan Form Request atau validasi terstruktur jika memungkinkan.
6. Gunakan route name yang konsisten.
7. Gunakan migration yang jelas.
8. Gunakan seeder untuk data demo.
9. Jangan hardcode data utama di view.
10. Jangan menyimpan API key di frontend.
11. Jangan menampilkan data sensitif di response.
12. Jangan menaruh logika SIG penting di JavaScript frontend.

Service yang disarankan:

- `GeoJsonService`
- `SpatialQueryService`
- `NearestEvacuationService`
- `NearestEquipmentService`
- `RoutingService`

Controller yang umum dipakai dapat mengikuti `docs/API.md`, misalnya:

- `GeoJsonController`
- `FloodRiskPointController`
- `FloodEventController`
- `EvacuationPointController`
- `HeavyEquipmentPostController`
- `EquipmentTypeController`
- `HeavyEquipmentUnitController`
- `SpatialAnalysisController`
- `RoutingController`
- `DashboardController`

## 7. Database and PostGIS Rules

Aturan database:

1. Gunakan PostgreSQL + PostGIS.
2. Kolom spasial utama bernama `geom`.
3. Gunakan `geometry(Point, 4326)` untuk titik.
4. Gunakan SRID 4326.
5. Longitude dan latitude boleh digunakan untuk input form, tetapi analisis spasial memakai `geom`.
6. Saat membuat titik PostGIS, urutan wajib longitude, latitude.
7. Gunakan `ST_SetSRID(ST_MakePoint(longitude, latitude), 4326)`.
8. Untuk jarak dalam meter, gunakan `ST_Distance(geom::geography, geom::geography)`.
9. Gunakan `ST_AsGeoJSON` untuk response peta.
10. Buat spatial index GiST pada kolom `geom`.
11. Jangan menukar latitude dan longitude.
12. Validasi koordinat agar berada di sekitar Bandar Lampung.

Tabel inti:

- `users`
- `flood_risk_points`
- `flood_events`
- `evacuation_points`
- `heavy_equipment_posts`
- `equipment_types`
- `heavy_equipment_units`

Tabel opsional:

- `districts`
- `data_sources`
- `route_histories`
- `equipment_dispatch_logs`

Tabel opsional tidak boleh memblokir MVP awal. Buat hanya jika waktu cukup atau fitur terkait benar-benar dibutuhkan.

## 8. Dataset Rules

Aturan dataset:

1. Data nyata harus memiliki `source_reference`.
2. Data dummy tidak boleh diklaim sebagai data resmi.
3. Data simulasi harus diberi label `simulasi`.
4. Gunakan kolom `source_type`, `source_reference`, `data_status`, dan `is_verified`.
5. Data alat berat boleh dummy, tetapi harus realistis.
6. Titik evakuasi boleh memakai fasilitas publik yang bisa diverifikasi.
7. Kapasitas evakuasi boleh estimasi akademik jika tidak ada data resmi.
8. Semua koordinat harus divalidasi.
9. Jangan membuat koordinat asal-asalan untuk data yang diklaim nyata.
10. Jika lokasi tidak jelas, beri label perlu validasi atau gunakan status simulasi.

Jenis `data_status`:

- `nyata`
- `dummy`
- `simulasi`

Jenis `source_type`:

- `pemerintah`
- `berita`
- `jurnal`
- `observasi`
- `admin_input`
- `dummy`

Minimal data demo sesuai `docs/DATASET.md`:

- 10 titik rawan banjir;
- 5 kejadian banjir;
- 8 titik evakuasi;
- 5 pos alat berat;
- 5 jenis alat berat;
- unit alat per pos secukupnya.

## 9. API Rules

Aturan API:

1. Ikuti `docs/API.md`.
2. Gunakan base path `/api/v1`.
3. Pisahkan endpoint publik dan admin.
4. Endpoint GeoJSON harus mengembalikan `FeatureCollection`.
5. Endpoint GeoJSON tidak perlu dibungkus `success/message` jika dipakai langsung oleh Leaflet.
6. Response JSON biasa memakai `success`, `message`, dan `data`.
7. Error response harus konsisten.
8. Spatial analysis dilakukan di backend, bukan frontend.
9. Routing provider dipanggil dari backend.
10. Jangan expose API key OpenRouteService di frontend.
11. Pastikan coordinates selalu `[longitude, latitude]`.

Endpoint penting:

- `/api/v1/geojson/flood-risks`
- `/api/v1/geojson/flood-events`
- `/api/v1/geojson/evacuation-points`
- `/api/v1/geojson/heavy-equipment-posts`
- `/api/v1/analysis/flood-events/{id}/nearest-evacuation`
- `/api/v1/analysis/flood-events/{id}/nearest-equipment`
- `/api/v1/analysis/flood-events/{id}/nearest-resources`
- `/api/v1/routing/flood-events/{id}/to-nearest-evacuation`
- `/api/v1/routing/flood-events/{id}/to-evacuation/{evacuation_id}`

Status code, error handling, dan bentuk response harus mengikuti `docs/API.md`.

## 10. UI/UX Rules

Konsep utama UI:

```text
Civic Flood Response Map Explorer with Modern Component System
```

Arah UI:

- calm;
- structured;
- trustworthy;
- civic but not bureaucratic;
- academic but not outdated;
- modern but not trendy;
- map-first;
- data-oriented;
- tidak kaku;
- tidak AI slop.

Stack UI:

- Laravel Blade;
- Tailwind CSS;
- Leaflet.

Font:

1. Plus Jakarta Sans sebagai font utama seluruh UI.
2. JetBrains Mono hanya untuk data teknis seperti angka, koordinat, jarak, durasi, ID, kode, dan metadata teknis.
3. Jangan memakai JetBrains Mono untuk semua teks.
4. Jangan memakai font default browser.
5. Jangan mencampur terlalu banyak font.

Style:

1. Gunakan clean component system.
2. Gunakan border halus.
3. Gunakan radius konsisten.
4. Gunakan spacing rapi.
5. Hover/focus state harus jelas.
6. Card harus polished.
7. Table harus modern.
8. Empty/loading/error state harus ada.
9. Ikon harus konsisten menggunakan satu icon library, disarankan Lucide Icons.
10. Jangan menggunakan emoji.
11. Jangan menggunakan warna random.
12. Jangan menggunakan shadow berat.
13. Jangan membuat UI terlalu ramai seperti landing page startup.
14. Jangan membuat UI terlalu kaku seperti admin panel default.

## 11. Map and Leaflet Rules

Aturan peta:

1. Peta adalah bagian utama sistem.
2. Gunakan Leaflet.
3. Gunakan OpenStreetMap sebagai basemap.
4. Data peta dikonsumsi dari endpoint GeoJSON.
5. Jangan hitung analisis spasial di frontend.

Layer utama:

- titik rawan banjir;
- kejadian banjir;
- titik evakuasi;
- pos alat berat;
- rute evakuasi jika tersedia.

Aturan marker dan interaksi:

1. Marker harus dibedakan berdasarkan kategori.
2. Popup harus ringkas.
3. Detail panjang jangan dimasukkan semua ke popup.
4. Gunakan legend/layer control.
5. Klik item list harus bisa mengarah ke marker jika fitur sudah dibuat.
6. Marker aktif/rekomendasi harus terlihat berbeda.
7. Jangan memakai marker default tanpa pertimbangan visual jika bisa dibuat lebih baik.
8. Coordinates pada GeoJSON harus `[longitude, latitude]`. Jika memakai API Leaflet yang meminta format LatLng, ikuti format Leaflet tanpa mengubah format GeoJSON atau PostGIS.

## 12. Routing Rules

Aturan routing:

1. Default MVP menggunakan OSRM jika ingin cepat tanpa API key.
2. OpenRouteService boleh digunakan jika API key disimpan di backend.
3. Rute adalah referensi, bukan rute resmi.
4. Rute belum mempertimbangkan jalan tertutup.
5. Backend mengambil koordinat dari database.
6. Backend memanggil provider routing.
7. Frontend menerima GeoJSON LineString.
8. Handle error provider dengan jelas.
9. Jangan expose API key routing di frontend.

Jika provider routing gagal, response harus menjelaskan kegagalan dan UI harus menampilkan error yang membantu.

## 13. Validation Rules

Validasi umum:

1. `name` wajib.
2. `longitude` wajib untuk data spasial.
3. `latitude` wajib untuk data spasial.
4. `longitude` dan `latitude` harus numeric.
5. Koordinat harus di sekitar Bandar Lampung.
6. Enum status harus valid.
7. `capacity` tidak boleh negatif.
8. `quantity` tidak boleh negatif.
9. `available_quantity` tidak boleh melebihi `quantity`.
10. `source_reference` wajib jika `data_status = nyata`.
11. Data dummy/simulasi harus jelas.
12. Error message harus informatif.
13. Error form harus dekat field jika sedang mengerjakan UI.

Validasi khusus geospasial:

- jangan menukar latitude dan longitude;
- jangan menerima titik tanpa `geom`;
- pastikan `geom` memakai SRID 4326;
- pastikan data spasial bisa diubah menjadi GeoJSON.

## 14. Testing Rules

Setelah mengerjakan fitur, agent harus mengecek:

1. Fitur bisa diakses.
2. Validasi berjalan.
3. Tidak ada error utama.
4. Endpoint mengembalikan response sesuai `docs/API.md`.
5. GeoJSON valid.
6. Leaflet bisa membaca GeoJSON.
7. Query PostGIS berjalan.
8. Jarak terdekat masuk akal.
9. Routing provider error ditangani.
10. UI mengikuti `docs/UI.md`.
11. Data dummy/nyata/simulasi terlihat jelas.
12. Halaman responsif minimal tidak rusak.
13. Endpoint admin tidak bisa diakses tanpa login.

Jika automated test belum dibuat, lakukan manual test dan tuliskan hasilnya. Jangan mengklaim fitur sudah selesai jika belum divalidasi.

## 15. Git and Change Management

Aturan perubahan:

1. Jangan mengubah banyak phase sekaligus.
2. Jangan menghapus file penting tanpa alasan.
3. Jangan melakukan refactor besar tanpa diminta.
4. Jangan mengubah dokumen acuan tanpa instruksi.
5. Jangan menjalankan perintah destruktif tanpa instruksi eksplisit.
6. Jangan mengubah stack atau scope sebagai efek samping task.

Setelah perubahan, jelaskan:

1. file yang diubah;
2. alasan perubahan;
3. cara test;
4. hasil test;
5. risiko atau sisa pekerjaan.

Jika ada error, jelaskan error dan perbaikan yang dilakukan. Jika tidak bisa menyelesaikan semua, selesaikan bagian yang aman dan jelaskan sisanya.

## 16. Task Execution Rules

Aturan eksekusi task:

1. Ikuti urutan `docs/TASKS.md`.
2. Mulai dari `Phase 0 - Project Preparation`.
3. Jangan lompat ke fitur besar sebelum setup dan database siap.
4. Selesaikan satu task kecil sebelum lanjut.
5. Jangan mengerjakan fitur di backlog sebelum MVP selesai.
6. Jika user meminta task spesifik, tetap cek apakah task tersebut sesuai MVP dan dokumen.
7. Jika task terlalu besar, pecah menjadi langkah kecil.
8. Jangan bertanya ulang jika informasi sudah ada di docs.
9. Jangan mulai routing sebelum data, GeoJSON, dan nearest evacuation stabil.
10. Jangan mulai polish UI besar sebelum alur utama berjalan.

## 17. Communication Rules

Gunakan Bahasa Indonesia. Jangan gunakan emoji.

Setelah menyelesaikan task, agent harus memberi ringkasan dengan format:

1. Ringkasan perubahan.
2. File yang diubah.
3. Cara menjalankan/test.
4. Hasil validasi.
5. Catatan risiko/sisa pekerjaan.
6. Rekomendasi task berikutnya.

Jawaban harus ringkas tetapi jelas. Jika ada bagian yang belum bisa dikerjakan, sebutkan dengan jujur.

## 18. Anti AI-Slop Rules

Aturan anti AI-slop:

1. Jangan membuat UI generik tanpa hierarchy.
2. Jangan membuat semua halaman berupa card putih polos dan tabel default.
3. Jangan menggunakan gradient berlebihan.
4. Jangan menggunakan warna random.
5. Jangan menggunakan animasi tidak berguna.
6. Jangan memakai icon campur-campur.
7. Jangan menambahkan dekorasi kebencanaan yang terlalu dramatis.
8. Jangan menambah fitur hanya agar terlihat ramai.
9. Jangan membuat marker peta tanpa pertimbangan visual.
10. Jangan membuat dashboard seperti template admin lama.

UI boleh modern dan kreatif, tetapi harus tetap fungsional, civic, akademik, map-first, dan mendukung pengambilan keputusan berbasis lokasi.

## 19. Done Criteria

Task dianggap selesai jika:

1. Sesuai dokumen.
2. Sesuai scope MVP.
3. Tidak menambah fitur liar.
4. Bisa dijalankan atau divalidasi.
5. Tidak merusak fitur lain.
6. UI sesuai `docs/UI.md`.
7. API sesuai `docs/API.md`.
8. Database sesuai `docs/DATABASE.md`.
9. Data sesuai `docs/DATASET.md`.
10. Perubahan dilaporkan dengan jelas.
11. Risiko atau sisa pekerjaan disebutkan.

Jika ada poin yang belum terpenuhi, task belum benar-benar selesai.

## 20. Final Reminder

Ini adalah project akademik SIG.

Nilai utama project ada pada:

- data spasial;
- PostgreSQL + PostGIS;
- peta interaktif;
- GeoJSON;
- analisis titik terdekat;
- rekomendasi evakuasi;
- rekomendasi alat berat;
- visualisasi rute referensi.

Jangan mengubahnya menjadi CRUD biasa. Jangan mengubahnya menjadi sistem kebencanaan skala produksi. Jaga MVP tetap realistis, rapi, dan bisa dipresentasikan.
