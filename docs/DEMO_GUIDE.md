# Panduan Demo SIGAP Banjir

Dokumen ini adalah panduan praktis untuk mempresentasikan MVP SIGAP Banjir.

## A. Persiapan Sebelum Demo

1. Pastikan Postgres.app atau PostgreSQL lokal berjalan.
2. Pastikan database `sigap-banjir` dapat diakses.
3. Pastikan PostGIS aktif pada database.
4. Pastikan dependency sudah terpasang:

```bash
composer install
npm install
```

5. Jalankan build asset jika belum:

```bash
npm run build
```

6. Jalankan Laravel:

```bash
php artisan serve --host=127.0.0.1 --port=8001
```

7. Buka browser:

```text
http://127.0.0.1:8001
```

## B. Login Admin

URL:

```text
http://127.0.0.1:8001/admin/login
```

Akun demo lokal:

```text
Email: hibbanrdn@gmail.com
Password: admin123
```

Akun ini hanya untuk demo lokal dan kebutuhan akademik. Jangan presentasikan sebagai credential production.

## C. Urutan Demo yang Direkomendasikan

### 1. Public Map Explorer

Buka:

```text
/peta
```

Tunjukkan:

- peta Leaflet;
- basemap Standar, Humanitarian, dan Satelit;
- marker pin kategori;
- legend;
- layer kejadian banjir, titik rawan, titik evakuasi, dan pos alat berat;
- popup marker;
- filter dan toggle layer.

Narasi singkat:

```text
Halaman ini membaca data spasial dari endpoint GeoJSON internal Laravel. Geometry berasal dari PostGIS dan ditampilkan oleh Leaflet.
```

### 2. Pilih Kejadian Banjir

Klik salah satu kejadian banjir pada daftar atau marker.

Tunjukkan:

- popup kejadian;
- detail singkat;
- tombol rekomendasi resource.

Narasi singkat:

```text
Saat satu kejadian dipilih, sistem dapat mengambil rekomendasi titik evakuasi dan pos alat berat terdekat dari API analisis spasial.
```

### 3. Tampilkan Rute Evakuasi

Klik tombol rute evakuasi.

Tunjukkan:

- route LineString pada peta;
- jarak;
- durasi;
- provider OSRM;
- notice bahwa rute hanya referensi.

Narasi singkat:

```text
Rute dibuat oleh backend melalui OSRM demo server. Rute ini referensi akademik, bukan rute darurat resmi.
```

### 4. Login Admin

Buka:

```text
/admin/login
```

Login dengan akun demo lokal.

### 5. Dashboard Admin

Buka:

```text
/admin/dashboard
```

Tunjukkan:

- statistik banjir aktif;
- titik rawan;
- titik evakuasi aktif;
- unit alat tersedia;
- data perlu validasi;
- kejadian terbaru;
- ketersediaan alat;
- quick action.

Narasi singkat:

```text
Dashboard ini tidak lagi dummy UI. Angka berasal dari database PostgreSQL/PostGIS.
```

### 6. CRUD Data Inti

Buka secara singkat:

```text
/admin/flood-events
/admin/flood-risks
/admin/evacuation-points
/admin/heavy-equipment-posts
/admin/equipment
```

Tunjukkan:

- daftar data real dari database;
- tombol detail/edit;
- status data;
- form koordinat jika membuka create/edit.

Narasi singkat:

```text
CRUD ini mengelola data spasial inti. Input longitude dan latitude dikonversi menjadi kolom PostGIS geom.
```

### 7. Detail Kejadian Decision Support

Buka salah satu detail kejadian, misalnya:

```text
/admin/flood-events/9
```

Jika ID berbeda, ambil dari daftar kejadian banjir.

Tunjukkan:

- metadata kejadian;
- status data simulasi/dummy;
- mini map Leaflet;
- rekomendasi titik evakuasi;
- rekomendasi pos alat berat;
- tombol `Lihat di Peta`;
- tombol `Tampilkan Rute`;
- panel rute referensi.

Narasi singkat:

```text
Halaman detail ini menjadi decision support untuk satu kejadian banjir. Analisis jarak tetap dihitung oleh PostGIS, sedangkan rute diambil melalui backend routing API.
```

### 8. Sumber Data & Validasi

Buka:

```text
/admin/data-sources
```

Tunjukkan:

- notice transparansi dataset;
- total data spasial;
- jumlah simulasi, dummy, nyata, dan perlu validasi;
- filter modul, status data, sumber, verifikasi, kecamatan, dan search;
- link detail/edit record.

Narasi singkat:

```text
Halaman ini memperjelas bahwa data simulasi dan dummy dipakai untuk demo akademik dan tidak diklaim sebagai data resmi.
```

## D. Poin Narasi Akademik

- PostGIS digunakan untuk menyimpan geometry dan menghitung jarak.
- GeoJSON digunakan sebagai format transfer data spasial ke Leaflet.
- Leaflet digunakan untuk visualisasi peta interaktif.
- OSRM digunakan untuk rute evakuasi referensi tanpa token.
- Data dummy dan simulasi diberi label agar transparan.
- Sistem ini adalah MVP akademik SIG, bukan sistem operasional kebencanaan produksi.

## E. Hal yang Jangan Diklaim

Jangan mengklaim:

- data sebagai data resmi BPBD/pemerintah;
- rute sebagai jalur evakuasi resmi atau pasti aman;
- sistem sebagai production-ready;
- sistem sudah mempertimbangkan jalan tertutup, banjir aktual, atau lalu lintas;
- sudah ada route history;
- sudah ada workflow verifikasi massal;
- sudah ada upload dokumen bukti sumber.

## F. Troubleshooting Singkat

### Database tidak connect

Cek:

```bash
php artisan migrate:status
```

Pastikan:

- Postgres.app berjalan;
- database `sigap-banjir` ada;
- `.env` mengarah ke PostgreSQL yang benar.

### PostGIS belum aktif

Jalankan di database:

```sql
CREATE EXTENSION IF NOT EXISTS postgis;
```

### Tile peta tidak muncul

Kemungkinan:

- koneksi internet tidak tersedia;
- tile provider lambat;
- browser memblokir request eksternal.

Solusi demo:

- refresh halaman;
- coba basemap lain;
- jelaskan bahwa data marker tetap berasal dari API internal.

### OSRM sementara gagal

OSRM demo server adalah layanan eksternal gratis.

Solusi demo:

- coba ulang beberapa saat;
- tetap tunjukkan Spatial Analysis API dan rekomendasi resource;
- jelaskan bahwa error provider sudah ditangani oleh aplikasi.

### Login gagal

Pastikan memakai akun demo lokal:

```text
hibbanrdn@gmail.com
admin123
```

Jika masih gagal, cek data admin:

```bash
php artisan tinker --execute="echo App\\Models\\User::where('email', 'hibbanrdn@gmail.com')->count();"
```

## G. Checklist Cepat Sebelum Presentasi

- Database `sigap-banjir` aktif.
- `php artisan migrate:status` menunjukkan migration inti `Ran`.
- `npm run build` sukses.
- `php artisan serve --host=127.0.0.1 --port=8001` berjalan.
- `/peta` tampil.
- `/admin/login` bisa login.
- `/admin/dashboard` tampil.
- Detail kejadian bisa membuka mini map dan rekomendasi.
- `/admin/data-sources` menampilkan transparansi data.
