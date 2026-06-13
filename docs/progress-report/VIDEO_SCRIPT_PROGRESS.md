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
| 00:00-00:30 | Tampilkan DOCX laporan final atau halaman `/peta` | Assalamu'alaikum/selamat pagi. Kami dari kelompok [isi nama kelompok] akan mempresentasikan progres aplikasi GIS bernama SIGAP Banjir, yaitu Sistem Informasi Geografis Antisipasi dan Penanganan Banjir Bandar Lampung. Aplikasi ini dikembangkan sebagai project akademik GIS untuk memetakan kejadian banjir, titik rawan banjir, titik evakuasi, pos alat berat, dan intensitas kejadian per kecamatan. |
| 00:30-01:00 | Buka `LAPORAN_PROGRESS_SIGAP_BANJIR_FINAL.docx` atau folder `docs/progress-report/dataset` | Pada progres ini, fokus utama yang dilaporkan adalah rancangan dataset, rancangan layer peta, dan rancangan UI aplikasi. Dataset kejadian banjir sudah menggunakan data nyata berbasis berita, sedangkan titik rawan banjir menggunakan data nyata berbasis jurnal. Titik evakuasi dan pos alat berat tetap dataset pengembangan untuk kebutuhan demo rekomendasi respons. |
| 01:00-01:40 | Tampilkan `DATASET_SUMMARY.md` atau CSV `flood_events.csv` | Dataset spasial utama terdiri dari 12 kejadian banjir, 12 titik rawan banjir, 10 titik evakuasi, dan 6 pos alat berat. Status final data spasial adalah 24 nyata, 10 simulasi, 6 dummy, dan 16 record masih perlu validasi operasional. |
| 01:40-02:10 | Tampilkan `REAL_SOURCE_DATA_AUDIT.md` atau `real_source_flood_dataset.csv` | Layer kejadian banjir menggunakan data nyata dari berita lokal/nasional terkait banjir Bandar Lampung, sedangkan koordinat ditentukan berdasarkan lokasi jalan, kelurahan, kecamatan, atau landmark yang disebutkan dalam berita. Layer titik rawan menggunakan jurnal Agustri dan Asbi tahun 2020 sebagai dasar literatur risiko banjir. |
| 02:10-02:35 | Zoom kolom longitude/latitude pada CSV | Longitude dan latitude pada file CSV diekspor dari kolom PostGIS `geom` menggunakan `ST_X` dan `ST_Y`. Untuk perhitungan jarak, sistem menggunakan `ST_Distance(geom::geography, geom::geography)` sehingga hasil jarak dapat dibaca dalam meter. |
| 02:35-03:00 | Buka halaman `/admin/data-sources` | Sistem memiliki halaman Sumber Data dan Validasi. Halaman ini menunjukkan data kejadian sebagai sumber berita, status nyata, dan terverifikasi. Titik rawan banjir tampil sebagai sumber jurnal, status nyata, dan terverifikasi. Dengan begitu, aplikasi transparan membedakan data nyata, simulasi, dan dummy. |
| 03:00-03:35 | Buka `/peta`, tampilkan marker dan legend | Berikutnya adalah rancangan layer. Pada peta publik, terdapat layer kejadian banjir, titik rawan banjir, intensitas kecamatan, titik evakuasi, dan pos alat berat. Setiap layer memiliki tampilan berbeda agar pengguna dapat membedakan kategori data secara visual. |
| 03:35-04:00 | Klik/toggle layer dan basemap selector | Pengguna dapat mengaktifkan atau menonaktifkan layer, memilih basemap Standar, Humanitarian, atau Satelit, serta melihat legend. Data layer diambil dari GeoJSON API Laravel dan divisualisasikan dengan Leaflet. |
| 04:00-04:30 | Aktifkan layer `Intensitas Kecamatan` | Layer intensitas kecamatan menampilkan polygon kecamatan dengan warna transparan berdasarkan jumlah kejadian banjir pada tabel `flood_events`. Karena data saat ini tersebar rendah, kecamatan terdampak berada pada kategori 1-4 kejadian. |
| 04:30-05:00 | Pilih satu kejadian banjir, contoh `Banjir Jalan Dokter Sutomo Penengahan` | Saat satu kejadian banjir dipilih, popup menampilkan status, severity, kecamatan, sumber data berita, dan status data nyata. Dari sini pengguna dapat meminta rekomendasi titik evakuasi, rekomendasi pos alat berat, atau resource gabungan. Analisis rekomendasi dilakukan di backend menggunakan PostGIS. |
| 05:00-05:30 | Klik `Cari Resource` lalu sorot hasil rekomendasi | Hasil rekomendasi menampilkan titik evakuasi dan pos alat berat terdekat berdasarkan jarak spasial. Marker rekomendasi juga diberi tampilan berbeda pada peta agar lebih mudah ditemukan. |
| 05:30-06:00 | Klik `Tampilkan Rute Evakuasi` | Sistem dapat menampilkan rute evakuasi referensi. Backend mengambil koordinat kejadian dan titik evakuasi dari database, lalu memanggil OSRM demo server. Rute ditampilkan sebagai LineString pada Leaflet. Rute ini bersifat referensi, bukan rute resmi kebencanaan. |
| 06:00-06:30 | Buka `/admin/login` dan `/admin/dashboard` | Untuk bagian UI admin, aplikasi memiliki login terpisah dari peta publik. Setelah login, dashboard menampilkan statistik data aktual dari database, kejadian terbaru, ketersediaan alat berat, status dataset, dan quick action ke modul utama. |
| 06:30-07:00 | Buka detail salah satu kejadian banjir | Detail kejadian banjir menjadi halaman decision support. Di sini admin dapat melihat metadata kejadian, mini map, rekomendasi titik evakuasi, rekomendasi pos alat berat, dan rute referensi. |
| 07:00-07:30 | Buka CRUD dan halaman Sumber Data & Validasi | Admin dapat mengelola data inti melalui CRUD. Halaman Sumber Data dan Validasi memastikan data berbasis berita dan jurnal tidak disamakan dengan data dummy, sementara titik evakuasi dan pos alat berat tetap diberi status pengembangan yang jelas. |
| 07:30-08:00 | Tampilkan laporan final DOCX/PDF atau peta publik | Kesimpulannya, progres SIGAP Banjir sudah mencakup rancangan dataset, rancangan layer, rancangan UI, serta implementasi inti GIS seperti PostGIS, GeoJSON, Leaflet, rekomendasi resource terdekat, layer intensitas kecamatan, dan rute referensi. Terima kasih. |

## Catatan Saat Rekaman

1. Jangan menyebut dataset sebagai data resmi pemerintah.
2. Sebutkan bahwa kejadian banjir adalah data nyata berbasis berita.
3. Sebutkan bahwa titik rawan banjir adalah data nyata berbasis jurnal/literatur.
4. Jelaskan bahwa koordinat berita adalah hasil geocoding/plotting lokasi jalan, kelurahan, kecamatan, atau landmark yang disebut dalam berita.
5. Jelaskan bahwa titik risiko jurnal adalah representasi/centroid area kelurahan, bukan batas polygon risiko resmi.
6. Saat menunjukkan rute, sebutkan bahwa rute bersifat referensi.
7. Saat menunjukkan pos alat berat, sebutkan bahwa data resource masih dummy realistis dan bukan fasilitas resmi.
8. Jangan menampilkan password terlalu lama saat login.

## Urutan Demo Praktis

1. Tampilkan laporan final `LAPORAN_PROGRESS_SIGAP_BANJIR_FINAL.docx`.
2. Buka folder dataset dan tunjukkan CSV final.
3. Buka `/peta`.
4. Tampilkan layer dan legend.
5. Aktifkan layer intensitas kecamatan.
6. Pilih kejadian banjir.
7. Klik `Cari Resource`.
8. Klik `Tampilkan Rute Evakuasi`.
9. Buka `/admin/login`.
10. Login admin.
11. Buka dashboard.
12. Buka detail kejadian.
13. Buka halaman Sumber Data & Validasi.
