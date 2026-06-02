# UI PROGRESS DESIGN SIGAP BANJIR

## 1. Konsep UI

Konsep UI SIGAP Banjir adalah:

```text
Civic Flood Response Map Explorer with Modern Component System
```

Karakter visualnya calm, structured, trustworthy, civic, akademik, map-first, dan data-oriented. UI tidak diposisikan sebagai landing page startup, tetapi sebagai aplikasi SIG yang bisa dipakai untuk demonstrasi pemetaan, analisis jarak, dan pengelolaan data spasial.

## 2. Public Map Explorer `/peta`

| Elemen | Implementasi Aktual |
|---|---|
| Layout | Panel kiri dan peta Leaflet dominan |
| Filter | Search, status, severity, kecamatan |
| Layer toggle | Kejadian banjir, titik rawan, titik evakuasi, pos alat berat |
| Basemap selector | Standar, Humanitarian, Satelit |
| Legend | Marker banjir, rawan, evakuasi, pos alat berat, rute |
| Marker | Custom div icon per kategori |
| Popup | Ringkas, menampilkan atribut utama |
| Rekomendasi | Tombol cari evakuasi, alat berat, dan resource |
| Routing | Tombol tampilkan rute evakuasi referensi |

Halaman ini adalah layar utama aplikasi. Data peta dikonsumsi dari GeoJSON API dan digambar oleh Leaflet. Analisis resource dan routing tetap dipanggil melalui API backend.

## 3. Login Admin `/admin/login`

| Elemen | Implementasi Aktual |
|---|---|
| Layout | Full-screen split layout pada desktop |
| Sisi kiri | Hero civic spatial dengan brand, narasi, visual peta/resource/rute |
| Sisi kanan | Form login admin |
| Navigasi | Link kembali ke peta publik |
| Tone | Formal, terbatas untuk administrator |

Halaman login tidak menampilkan tombol admin di halaman publik. Admin masuk melalui URL khusus `/admin/login`, sehingga peta publik tetap fokus pada eksplorasi data.

## 4. Dashboard Admin `/admin/dashboard`

Dashboard admin membaca data real dari database. Komponen yang sudah berjalan:

1. Hero dashboard SIGAP Banjir.
2. Statistik ringkasan data respons.
3. Kejadian banjir terbaru.
4. Ketersediaan alat berat.
5. Transparansi dataset.
6. Ringkasan layer peta.
7. Validasi data per dataset.
8. Quick action ke modul inti.

Dashboard tidak hanya menjadi tabel CRUD, tetapi memberi gambaran progres data dan kondisi sistem.

## 5. Detail Kejadian Banjir

Halaman detail kejadian banjir berfungsi sebagai decision support untuk satu kejadian.

Komponen utama:

1. Header kejadian dengan badge status, severity, data status, dan verifikasi.
2. Notice jika data simulasi/dummy.
3. Mini map Leaflet.
4. Panel rekomendasi titik evakuasi terdekat.
5. Panel rekomendasi pos alat berat terdekat.
6. Panel rute evakuasi referensi.
7. Informasi kejadian, alamat, koordinat, sumber, dan status data.

Analisis jarak dihitung backend menggunakan PostGIS. Rute diambil dari Routing API backend dengan OSRM demo server.

## 6. CRUD Data Spasial

Modul CRUD yang tersedia:

| Modul | Fungsi UI |
|---|---|
| Kejadian Banjir | Index/list, create/edit, detail decision support |
| Titik Rawan Banjir | Index/list, create/edit, detail dengan mini map |
| Titik Evakuasi | Index/list, create/edit, detail dengan mini map |
| Pos Alat Berat | Index/list, create/edit, detail pos dan unit |
| Jenis Alat | Master data jenis alat |
| Unit Alat | Pengelolaan quantity dan available quantity per pos |

Form data spasial memakai input longitude dan latitude yang dikonversi ke `geom` oleh backend. Validasi koordinat menjaga titik berada di sekitar Bandar Lampung.

## 7. Sumber Data & Validasi

Halaman `/admin/data-sources` menjadi bagian penting transparansi dataset.

Fitur UI yang tersedia:

1. Notice bahwa data simulasi/dummy dipakai untuk demo akademik.
2. Statistik status data.
3. Cakupan validasi per modul.
4. Filter modul, data status, source type, verifikasi, kecamatan, dan search.
5. Tabel daftar data spasial.
6. Link detail/edit per record.
7. Empty state jika filter tidak menemukan data.

Halaman ini membantu menjelaskan kepada dosen bahwa project tidak menyamarkan data simulasi sebagai data resmi.

## 8. Design System

| Elemen | Arahan |
|---|---|
| Font utama | Plus Jakarta Sans |
| Font teknis | JetBrains Mono untuk angka, koordinat, jarak, durasi, ID |
| Civic navy | Anchor visual, sidebar, hero dashboard, login |
| Civic blue | Action utama, route, focus state |
| Red/coral | Marker kejadian banjir dan severity tinggi/kritis |
| Teal/green | Titik evakuasi dan status aman |
| Amber/gold | Titik rawan dan pos alat berat |
| Card | Border halus, radius konsisten, shadow minimal |
| Table | Header muted, row hover, badge status |
| Marker | Custom Leaflet div icon, bukan marker default mentah |
| Motion | CSS transition/keyframes ringan dengan `prefers-reduced-motion` |

## 9. Catatan Visual Aktual

Implementasi UI aktual tidak menggunakan GSAP atau Three.js. Motion yang ada berupa transisi CSS ringan, reveal dashboard, progress bar, dan state hover/focus. Hal ini sesuai scope MVP karena aplikasi lebih membutuhkan kejelasan data dan peta daripada efek visual kompleks.

## 10. Keterbatasan UI

1. Data sumber masih perlu validasi lanjutan.
2. Rute OSRM masih bersifat referensi.
3. Belum ada workflow verifikasi massal.
4. Belum ada upload bukti sumber.
5. Belum ada multi-role kompleks.
6. Public map belum menjadi sistem kebencanaan operasional resmi.
