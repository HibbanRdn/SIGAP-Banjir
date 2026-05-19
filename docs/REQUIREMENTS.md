# REQUIREMENTS.md

# Sistem Informasi Geografis Mitigasi dan Respons Banjir Kota Bandar Lampung

## 1. Ringkasan Project

Project ini adalah Sistem Informasi Geografis berbasis web untuk membantu pemetaan titik rawan banjir, pencatatan kejadian banjir, serta rekomendasi titik evakuasi dan pos alat berat terdekat di Kota Bandar Lampung.

Sistem ini ditujukan sebagai project akademik mata kuliah Sistem Informasi Geografis, sehingga fokus utamanya bukan hanya CRUD data, tetapi pemanfaatan data spasial, visualisasi peta, dan analisis lokasi menggunakan PostgreSQL + PostGIS.

## 2. Scope Final MVP

MVP project ini berfokus pada:

1. Menampilkan peta interaktif Kota Bandar Lampung.
2. Menampilkan layer titik rawan banjir.
3. Menampilkan layer titik kejadian banjir.
4. Menampilkan layer titik evakuasi.
5. Menampilkan layer pos alat berat.
6. Admin dapat menambahkan titik kejadian banjir dari peta.
7. Admin dapat mengelola data kejadian banjir, titik evakuasi, dan pos alat berat.
8. Sistem dapat mencari titik evakuasi terdekat dari lokasi banjir menggunakan analisis spasial PostGIS.
9. Sistem dapat mencari pos alat berat terdekat dari lokasi banjir menggunakan analisis spasial PostGIS.
10. Sistem dapat menampilkan rute evakuasi sederhana dari lokasi banjir ke titik evakuasi.
11. Sistem dapat menampilkan data spasial dalam bentuk GeoJSON untuk divisualisasikan pada peta.

## 3. Fitur yang Ditunda

Fitur berikut tidak termasuk MVP dan dapat dikembangkan setelah fitur utama selesai:

1. Laporan banjir dari pengguna umum.
2. Validasi laporan oleh multi-role.
3. Notifikasi WhatsApp, email, atau SMS.
4. Tracking alat berat secara real-time.
5. Prediksi banjir berbasis cuaca atau sensor.
6. Integrasi data BMKG atau IoT.
7. Routing berbasis pgRouting.
8. Simulasi jalan tertutup akibat banjir.
9. Dashboard prioritas wilayah yang kompleks.
10. Aplikasi mobile petugas lapangan.
11. Manajemen instansi atau role BPBD yang detail.

## 4. Rumusan Masalah

1. Bagaimana merancang sistem informasi geografis untuk memetakan titik rawan banjir dan titik kejadian banjir di Kota Bandar Lampung?
2. Bagaimana menyimpan dan mengelola data lokasi banjir, titik evakuasi, dan pos alat berat menggunakan PostgreSQL dan PostGIS?
3. Bagaimana sistem dapat menentukan titik evakuasi terdekat dari lokasi banjir berdasarkan jarak spasial?
4. Bagaimana sistem dapat menentukan pos alat berat terdekat dari lokasi banjir berdasarkan jarak spasial?
5. Bagaimana sistem dapat menampilkan rute evakuasi sederhana dari lokasi banjir menuju titik evakuasi?
6. Bagaimana visualisasi peta dapat membantu proses mitigasi dan respons awal terhadap kejadian banjir?

## 5. Tujuan Sistem

### 5.1 Tujuan Utama

1. Membangun SIG berbasis web untuk pemetaan dan respons awal banjir di Kota Bandar Lampung.
2. Menyediakan visualisasi spasial titik rawan banjir, titik kejadian banjir, titik evakuasi, dan pos alat berat.
3. Menggunakan PostGIS untuk mendukung analisis lokasi, terutama pencarian titik terdekat.
4. Membantu admin atau pengambil keputusan menentukan titik evakuasi dan pos alat berat yang paling relevan berdasarkan lokasi kejadian banjir.

### 5.2 Tujuan Pendukung

1. Menyediakan antarmuka peta yang mudah digunakan.
2. Menyediakan fitur input titik kejadian banjir langsung dari peta.
3. Menyediakan informasi severity atau tingkat keparahan banjir.
4. Menyediakan data spasial dalam format GeoJSON.
5. Menyediakan dasar dokumentasi akademik untuk analisis SIG.

## 6. Batasan Masalah

1. Wilayah studi dibatasi pada Kota Bandar Lampung.
2. Data kejadian banjir dapat berasal dari data nyata, berita, sumber pemerintah, dan data simulasi realistis.
3. Data alat berat boleh berupa data dummy, tetapi harus masuk akal secara geografis.
4. Sistem tidak menangani prediksi banjir.
5. Sistem tidak menangani sensor tinggi air atau data real-time.
6. Routing pada MVP menggunakan layanan eksternal seperti OSRM atau OpenRouteService.
7. Analisis spasial utama dibatasi pada pencarian titik terdekat, perhitungan jarak, dan visualisasi GeoJSON.
8. Sistem hanya membutuhkan role admin dan pengguna umum pada versi MVP.
9. Petugas lapangan dan BPBD digunakan sebagai konteks aktor, tetapi tidak wajib memiliki modul login terpisah.
10. Sistem tidak menggantikan sistem resmi BPBD, melainkan prototype akademik berbasis SIG.

## 7. Aktor Sistem

### 7.1 Pengguna Umum

Pengguna umum dapat melihat peta banjir, titik rawan banjir, titik evakuasi, dan informasi dasar lokasi.

### 7.2 Admin

Admin dapat login dan mengelola data utama sistem, termasuk kejadian banjir, titik evakuasi, dan pos alat berat. Admin juga dapat menjalankan fitur rekomendasi titik evakuasi dan pos alat berat terdekat.

### 7.3 Petugas Lapangan

Petugas lapangan menjadi aktor konseptual yang memberikan informasi kejadian banjir. Pada MVP, perannya masih diwakili oleh admin.

### 7.4 Pengambil Keputusan/BPBD

BPBD menjadi aktor konseptual yang menggunakan informasi peta dan rekomendasi sistem untuk membantu respons awal banjir. Pada MVP, BPBD tidak memerlukan role login khusus.

## 8. Fitur MVP

### 8.1 Peta Publik

- Menampilkan peta dasar.
- Menampilkan marker titik rawan banjir.
- Menampilkan marker titik kejadian banjir.
- Menampilkan marker titik evakuasi.
- Menampilkan marker pos alat berat.
- Menyediakan kontrol layer.
- Menampilkan popup informasi singkat pada setiap marker.

### 8.2 Login Admin

- Admin dapat login ke sistem.
- Admin dapat mengakses halaman dashboard dan manajemen data.
- Autentikasi dibuat sederhana untuk kebutuhan project akademik.

### 8.3 Manajemen Titik Kejadian Banjir

- Admin dapat menambah titik kejadian banjir.
- Admin dapat memilih lokasi banjir langsung dari peta.
- Admin dapat mengisi nama lokasi, deskripsi, waktu kejadian, tingkat keparahan, dan status.
- Admin dapat mengedit dan menghapus data kejadian banjir.

### 8.4 Manajemen Titik Rawan Banjir

- Admin dapat melihat data titik rawan banjir.
- Admin dapat menambah, mengedit, dan menghapus titik rawan banjir.
- Data memiliki atribut tingkat risiko.

### 8.5 Manajemen Titik Evakuasi

- Admin dapat mengelola titik evakuasi.
- Data titik evakuasi memuat nama tempat, alamat, kapasitas, fasilitas, status, dan lokasi spasial.

### 8.6 Manajemen Pos Alat Berat

- Admin dapat mengelola pos alat berat.
- Data alat berat memuat nama pos, jenis alat, status, keterangan, dan lokasi spasial.
- Data alat berat pada MVP boleh dummy tetapi harus realistis.

### 8.7 Rekomendasi Titik Evakuasi Terdekat

- Admin memilih satu titik kejadian banjir.
- Sistem menghitung titik evakuasi terdekat menggunakan PostGIS.
- Sistem menampilkan nama titik evakuasi, jarak, kapasitas, dan status.
- Sistem menampilkan marker titik evakuasi yang direkomendasikan pada peta.

### 8.8 Rekomendasi Alat Berat Terdekat

- Admin memilih satu titik kejadian banjir.
- Sistem menghitung pos alat berat terdekat menggunakan PostGIS.
- Sistem menampilkan nama pos alat berat, jenis alat, status, dan jarak.
- Sistem mengurutkan rekomendasi dari jarak terdekat.

### 8.9 Rute Evakuasi Sederhana

- Sistem mengambil koordinat kejadian banjir dan titik evakuasi yang direkomendasikan.
- Sistem meminta rute ke layanan routing eksternal seperti OSRM atau OpenRouteService.
- Sistem menampilkan rute sebagai garis pada peta.
- Rute hanya bersifat referensi dan belum mempertimbangkan kondisi jalan tertutup.

## 9. Analisis Spasial yang Digunakan

Analisis spasial utama pada MVP:

1. Menyimpan data lokasi menggunakan tipe geometry/geography PostGIS.
2. Mengambil data spasial sebagai GeoJSON.
3. Menghitung jarak antara kejadian banjir dan titik evakuasi.
4. Menghitung jarak antara kejadian banjir dan pos alat berat.
5. Mengurutkan titik terdekat berdasarkan jarak.
6. Menampilkan hasil analisis pada peta.

Analisis tambahan yang boleh dibuat setelah MVP:

1. Radius terdampak berdasarkan tingkat keparahan.
2. Filter titik berdasarkan kecamatan.
3. Jumlah kejadian banjir per kecamatan.
4. Skor prioritas wilayah sederhana.

## 10. Stack Teknologi Final

Stack final yang direkomendasikan:

- Backend: Laravel
- Database: PostgreSQL
- Spatial Extension: PostGIS
- Frontend: Blade + Tailwind CSS
- Peta: Leaflet.js
- Basemap: OpenStreetMap
- Routing: OSRM atau OpenRouteService
- Format data spasial: GeoJSON

Alasan pemilihan stack:

1. Laravel mempercepat pembuatan CRUD, autentikasi, validasi, dan struktur project.
2. PostgreSQL + PostGIS memberikan nilai akademik SIG yang kuat.
3. Leaflet ringan, mudah digunakan, dan cocok untuk peta interaktif.
4. OSRM atau OpenRouteService membuat fitur routing realistis tanpa membebani MVP.
5. Stack ini cukup profesional tetapi tetap realistis untuk project kuliah.

## 11. Data yang Dibutuhkan

### 11.1 Data Nyata

1. Nama wilayah dan lokasi rawan banjir di Kota Bandar Lampung.
2. Titik kejadian banjir historis dari berita atau sumber pemerintah.
3. Lokasi fasilitas yang dapat digunakan sebagai titik evakuasi.
4. Batas administrasi kecamatan atau kelurahan jika tersedia.

### 11.2 Data Dummy

1. Lokasi pos alat berat.
2. Jenis alat berat.
3. Status alat berat.
4. Kapasitas titik evakuasi jika data nyata tidak tersedia.
5. Tingkat keparahan banjir untuk data simulasi.

### 11.3 Data Spasial

1. Titik rawan banjir.
2. Titik kejadian banjir.
3. Titik evakuasi.
4. Pos alat berat.
5. Rute evakuasi.
6. Batas wilayah administratif jika tersedia.

### 11.4 Data Non-Spasial

1. Nama lokasi.
2. Alamat.
3. Deskripsi.
4. Tingkat risiko.
5. Tingkat keparahan.
6. Status kejadian.
7. Kapasitas evakuasi.
8. Jenis alat berat.
9. Status alat berat.
10. Waktu kejadian.

## 12. Rancangan Modul Sistem

1. Modul peta publik.
2. Modul autentikasi admin.
3. Modul dashboard admin.
4. Modul manajemen titik rawan banjir.
5. Modul manajemen titik kejadian banjir.
6. Modul manajemen titik evakuasi.
7. Modul manajemen pos alat berat.
8. Modul rekomendasi titik evakuasi terdekat.
9. Modul rekomendasi pos alat berat terdekat.
10. Modul routing evakuasi sederhana.
11. Modul API GeoJSON.

## 13. Rancangan Halaman

### 13.1 Halaman Publik

1. Halaman peta banjir.
2. Halaman detail kejadian banjir.
3. Halaman daftar titik evakuasi sederhana.

### 13.2 Halaman Admin

1. Login admin.
2. Dashboard admin.
3. Manajemen titik kejadian banjir.
4. Manajemen titik rawan banjir.
5. Manajemen titik evakuasi.
6. Manajemen pos alat berat.
7. Detail kejadian banjir dan hasil rekomendasi.

## 14. Alur Utama Sistem

### 14.1 Pengguna Melihat Peta

1. Pengguna membuka halaman peta.
2. Sistem mengambil data GeoJSON dari server.
3. Sistem menampilkan layer kejadian banjir, titik rawan banjir, titik evakuasi, dan pos alat berat.
4. Pengguna dapat membuka popup informasi pada marker.

### 14.2 Admin Menambahkan Kejadian Banjir

1. Admin login.
2. Admin membuka halaman manajemen kejadian banjir.
3. Admin klik lokasi pada peta.
4. Sistem mengambil koordinat lokasi.
5. Admin mengisi data kejadian banjir.
6. Sistem menyimpan data ke database PostGIS.
7. Kejadian banjir baru muncul pada peta.

### 14.3 Admin Mencari Titik Evakuasi Terdekat

1. Admin membuka detail kejadian banjir.
2. Admin memilih fitur rekomendasi evakuasi.
3. Sistem menjalankan query jarak di PostGIS.
4. Sistem menampilkan titik evakuasi terdekat beserta jaraknya.
5. Sistem menandai titik evakuasi tersebut pada peta.

### 14.4 Admin Mencari Alat Berat Terdekat

1. Admin membuka detail kejadian banjir.
2. Admin memilih fitur rekomendasi pos alat berat.
3. Sistem menghitung jarak dari kejadian banjir ke pos alat berat yang tersedia.
4. Sistem menampilkan daftar pos alat berat terdekat.
5. Admin dapat melihat pos alat berat paling efisien berdasarkan jarak.

### 14.5 Sistem Menampilkan Rute Evakuasi

1. Sistem mengambil koordinat kejadian banjir.
2. Sistem mengambil koordinat titik evakuasi terdekat.
3. Sistem meminta data rute ke OSRM atau OpenRouteService.
4. Sistem menampilkan polyline rute pada peta.
5. Sistem menampilkan estimasi jarak dan durasi jika tersedia.

## 15. Kriteria Keberhasilan MVP

MVP dianggap berhasil jika:

1. Data spasial tersimpan di PostgreSQL dengan PostGIS.
2. Peta dapat menampilkan minimal empat layer utama.
3. Admin dapat menambahkan kejadian banjir dari peta.
4. Sistem dapat menghitung titik evakuasi terdekat.
5. Sistem dapat menghitung pos alat berat terdekat.
6. Sistem dapat menampilkan jarak hasil rekomendasi.
7. Sistem dapat menampilkan rute evakuasi sederhana.
8. Sistem dapat membedakan data berdasarkan status atau tingkat keparahan.
9. Sistem memiliki dokumentasi database, dataset, dan fitur.
10. Sistem dapat didemonstrasikan sebagai project SIG, bukan hanya web CRUD.

## 16. Struktur Dokumen Project

Dokumen yang disarankan untuk repository:

1. `REQUIREMENTS.md`
   - Berisi scope, tujuan, batasan, aktor, fitur MVP, dan kriteria keberhasilan.

2. `DATASET.md`
   - Berisi daftar data nyata, data dummy, sumber data, atribut, dan status kelengkapan data.

3. `DATABASE.md`
   - Berisi rancangan tabel, kolom, relasi, tipe data spasial, dan query PostGIS utama.

4. `UI.md`
   - Berisi rancangan halaman, komponen peta, layer, marker, legenda, dan alur interaksi.

5. `API.md`
   - Berisi rancangan endpoint untuk data GeoJSON, CRUD, rekomendasi evakuasi, rekomendasi pos alat berat, dan routing.

6. `TASKS.md`
   - Berisi daftar pekerjaan bertahap dari setup project sampai testing dan dokumentasi.

7. `AGENTS.md`
   - Berisi aturan kerja project agar implementasi tetap konsisten, termasuk stack, scope, dan larangan memperluas fitur di luar MVP.

Urutan pembuatan dokumen:

1. `REQUIREMENTS.md`
2. `DATASET.md`
3. `DATABASE.md`
4. `UI.md`
5. `API.md`
6. `TASKS.md`
7. `AGENTS.md`

## 17. Keputusan Final MVP

Versi MVP final adalah:

Sistem Informasi Geografis berbasis web untuk Kota Bandar Lampung yang menampilkan titik rawan banjir, titik kejadian banjir, titik evakuasi, dan pos alat berat; memungkinkan admin menambah kejadian banjir dari peta; serta menyediakan rekomendasi titik evakuasi dan pos alat berat terdekat menggunakan PostGIS, dengan rute evakuasi sederhana menggunakan layanan routing eksternal.

Stack final:

Laravel + Blade + Tailwind CSS + PostgreSQL + PostGIS + Leaflet + OSRM/OpenRouteService.

Fokus utama project:

1. Pemetaan banjir.
2. Manajemen data spasial.
3. Analisis titik terdekat.
4. Visualisasi rute evakuasi.
5. Dokumentasi akademik SIG yang rapi.
