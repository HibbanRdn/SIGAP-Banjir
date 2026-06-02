# VIDEO SCRIPT PROGRESS SIGAP BANJIR

Durasi target: 5-8 menit.

Pembicara dapat diganti sesuai anggota kelompok:

```text
[Pembicara 1]
[Pembicara 2]
[Pembicara 3]
```

| Durasi | Tampilan Layar | Narasi |
|---|---|---|
| 00:00-00:30 | Tampilkan DOCX laporan final atau halaman `/peta` | Assalamu'alaikum/selamat pagi. Kami dari kelompok [isi nama kelompok] akan mempresentasikan progres aplikasi GIS bernama SIGAP Banjir, yaitu Sistem Informasi Geografis Antisipasi dan Penanganan Banjir Bandar Lampung. Aplikasi ini dikembangkan sebagai project akademik GIS untuk memetakan kejadian banjir, titik rawan banjir, titik evakuasi, dan pos alat berat. |
| 00:30-01:00 | Buka `LAPORAN_PROGRESS_SIGAP_BANJIR_FINAL.docx` atau folder `docs/progress-report/dataset` | Pada progres ini, fokus utama yang dilaporkan adalah rancangan dataset, rancangan layer peta, dan rancangan UI aplikasi. Dataset yang digunakan merupakan dataset awal pengembangan berbasis lokasi nyata di wilayah Bandar Lampung yang telah diaudit secara spasial, tetapi skenario operasionalnya tetap simulasi/dummy dan belum diklaim sebagai data resmi pemerintah. |
| 01:00-01:40 | Tampilkan `DATASET_SUMMARY.md` atau CSV `flood_events.csv` | Dataset spasial utama terdiri dari 8 kejadian banjir, 12 titik rawan banjir, 10 titik evakuasi, dan 6 pos alat berat. Selain itu terdapat 15 data unit alat berat dan 6 jenis alat. Status final data spasial adalah 30 simulasi, 6 dummy, 0 nyata, dan seluruh 36 record masih perlu validasi operasional resmi. |
| 01:40-02:10 | Tampilkan `SPATIAL_VALIDATION_AUDIT.md` atau `spatial_validation_audit.csv` | Validasi lokasi dilakukan menggunakan OpenStreetMap/Nominatim pada tingkat area. Validasi ini memastikan koordinat berada di wilayah studi Bandar Lampung, tetapi tidak berarti kejadian banjir, titik evakuasi, atau pos alat berat sudah menjadi data resmi operasional. |
| 02:10-02:35 | Zoom kolom longitude/latitude pada CSV | Longitude dan latitude pada file CSV diekspor dari kolom PostGIS `geom` menggunakan `ST_X` dan `ST_Y`. Untuk perhitungan jarak, sistem menggunakan `ST_Distance(geom::geography, geom::geography)` sehingga hasil jarak dapat dibaca dalam meter. |
| 02:35-03:00 | Buka halaman `/admin/data-sources` | Sistem memiliki halaman Sumber Data dan Validasi. Halaman ini menunjukkan 36 total data spasial, 30 data simulasi, 6 data dummy, 0 data nyata, dan 36 data yang masih perlu validasi operasional. Dengan begitu, aplikasi tidak menyembunyikan status dataset pengembangan akademik. |
| 03:00-03:35 | Buka `/peta`, tampilkan semua marker dan legend | Berikutnya adalah rancangan layer. Pada peta publik, terdapat empat layer utama: kejadian banjir, titik rawan banjir, titik evakuasi, dan pos alat berat. Setiap layer memiliki marker berbeda agar pengguna dapat membedakan kategori data secara visual. |
| 03:35-04:00 | Klik/toggle layer dan basemap selector | Pengguna dapat mengaktifkan atau menonaktifkan layer, memilih basemap Standar, Humanitarian, atau Satelit, serta melihat legend. Data layer diambil dari GeoJSON API Laravel dan divisualisasikan dengan Leaflet. |
| 04:00-04:30 | Pilih satu kejadian banjir, contoh `Genangan Way Halim` atau `Banjir Teluk Betung Selatan` | Saat satu kejadian banjir dipilih, panel kiri menampilkan kejadian terpilih. Dari sini pengguna dapat meminta rekomendasi titik evakuasi, rekomendasi pos alat berat, atau resource gabungan. Analisis rekomendasi dilakukan di backend menggunakan PostGIS. |
| 04:30-05:00 | Klik `Cari Resource` lalu sorot hasil rekomendasi | Hasil rekomendasi menampilkan titik evakuasi dan pos alat berat terdekat berdasarkan jarak spasial. Marker rekomendasi juga diberi tampilan berbeda pada peta agar lebih mudah ditemukan. |
| 05:00-05:30 | Klik `Tampilkan Rute Evakuasi` | Sistem dapat menampilkan rute evakuasi referensi. Backend mengambil koordinat kejadian dan titik evakuasi dari database, lalu memanggil OSRM demo server. Rute ditampilkan sebagai LineString pada Leaflet. Rute ini bersifat referensi, bukan rute resmi kebencanaan. |
| 05:30-06:00 | Buka `/admin/login` | Untuk bagian UI admin, aplikasi memiliki halaman login full-screen split layout. Halaman ini memisahkan akses admin dari peta publik agar pengguna umum tetap fokus pada eksplorasi peta. |
| 06:00-06:35 | Login dan tampilkan `/admin/dashboard` | Setelah login, admin masuk ke dashboard. Dashboard menampilkan statistik data aktual dari database, kejadian terbaru, ketersediaan alat berat, status dataset, dan quick action ke modul utama. |
| 06:35-07:10 | Buka `/admin/flood-events/10` | Detail kejadian banjir menjadi halaman decision support. Di sini admin dapat melihat metadata kejadian, mini map, rekomendasi titik evakuasi, rekomendasi pos alat berat, dan rute referensi. Halaman ini menunjukkan nilai GIS karena menggabungkan data spasial, analisis jarak, dan rute. |
| 07:10-07:35 | Buka CRUD, misalnya `/admin/flood-events` atau `/admin/evacuation-points` | Admin juga dapat mengelola data inti melalui CRUD. Data spasial seperti kejadian, titik rawan, evakuasi, dan pos alat berat dapat ditambah atau diedit dengan input longitude dan latitude yang dikonversi menjadi geometry PostGIS. |
| 07:35-07:55 | Kembali ke `/admin/data-sources` | Terakhir, halaman Sumber Data dan Validasi digunakan untuk memastikan dataset tetap transparan. Data simulasi dan dummy ditampilkan apa adanya, dan data yang belum memiliki verifikasi operasional tetap ditandai perlu validasi. |
| 07:55-08:15 | Tampilkan laporan final DOCX/PDF atau peta publik | Kesimpulannya, progres SIGAP Banjir sudah mencakup rancangan dataset, rancangan layer, rancangan UI, serta implementasi inti GIS seperti PostGIS, GeoJSON, Leaflet, rekomendasi resource terdekat, dan rute referensi. Pengembangan berikutnya adalah validasi dataset menggunakan sumber resmi atau sumber terverifikasi. Terima kasih. |

## Catatan Saat Rekaman

1. Jangan menyebut dataset sebagai data resmi pemerintah.
2. Gunakan istilah `dataset awal pengembangan`, `dataset simulasi spasial`, dan `rancangan data operasional`.
3. Jelaskan bahwa validasi yang sudah dilakukan adalah validasi lokasi/area melalui peta publik, bukan validasi operasional resmi.
4. Saat menunjukkan rute, sebutkan bahwa rute bersifat referensi.
5. Saat menunjukkan pos alat berat, sebutkan bahwa data resource masih dummy realistis dan bukan fasilitas resmi.
6. Jangan menampilkan password terlalu lama saat login.

## Urutan Demo Praktis

1. Tampilkan laporan final `LAPORAN_PROGRESS_SIGAP_BANJIR_FINAL.docx`.
2. Buka folder dataset dan tunjukkan CSV final.
3. Buka `/peta`.
4. Tampilkan layer dan legend.
5. Pilih kejadian banjir.
6. Klik `Cari Resource`.
7. Klik `Tampilkan Rute Evakuasi`.
8. Buka `/admin/login`.
9. Login admin.
10. Buka dashboard.
11. Buka detail kejadian.
12. Buka halaman Sumber Data & Validasi.
