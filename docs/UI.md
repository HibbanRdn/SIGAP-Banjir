# UI.md

# Panduan UI/UX Sistem Informasi Geografis Mitigasi dan Respons Banjir Kota Bandar Lampung

## Status Implementasi UI Aktual

MVP final sudah memiliki:

- public map explorer `/peta` berbasis Leaflet;
- basemap selector gratis: Standar, Humanitarian, dan Satelit;
- marker pin kategori dengan ikon untuk kejadian banjir, titik rawan, titik evakuasi, dan pos alat berat;
- route LineString referensi dengan gaya civic blue dan outline;
- dashboard admin berbasis data database;
- detail kejadian banjir admin sebagai decision support dengan mini map, rekomendasi resource, dan rute OSRM;
- halaman `Sumber Data & Validasi` untuk transparansi status dummy/simulasi/verifikasi.

Setiap pengembangan setelah MVP harus mempertahankan gaya civic, calm, map-first, dan transparan ini.

## A. Ringkasan Arah UI

UI sistem ini bukan hanya admin CRUD, tetapi platform eksplorasi peta banjir berbasis SIG. Fokus utama sistem adalah membantu pengguna melihat kondisi spasial banjir, titik evakuasi, dan pos alat berat secara cepat, jelas, dan dapat dipercaya.

Arah utama UI:

- Map-first
- Data-oriented
- Modern
- Calm
- Civic
- Akademik
- Mudah digunakan
- Tidak kaku
- Tidak terlalu dekoratif
- Tidak AI slop

Vibe utama:

```text
Civic Flood Response Map Explorer with Modern Component System
```

UI harus mendukung dua pengalaman utama:

1. Pengguna umum melihat peta, titik rawan banjir, kejadian banjir, titik evakuasi, dan pos alat berat.
2. Admin mengelola data serta menjalankan rekomendasi evakuasi dan alat berat terdekat.

Sistem harus terasa seperti produk SIG kebencanaan yang dirancang serius, bukan sekadar template admin dengan tabel CRUD.

## B. Design Personality

Personality desain:

- Calm, structured, and trustworthy.
- Civic but not bureaucratic.
- Academic but not outdated.
- Modern but not trendy.
- Friendly but not playful.
- Data-oriented but not cold.
- Map-first but not visually empty.
- Operational but not intimidating.
- Emergency-aware but not panic-driven.

Sistem harus terasa seperti:

- Platform informasi publik modern.
- Dashboard respons banjir berbasis peta.
- Admin panel kebencanaan yang efisien.
- Produk akademik yang tetap terlihat serius.
- Bukan template AI generik.
- Bukan admin panel default yang kaku.
- Bukan landing page startup yang terlalu marketing.

UI tidak boleh terlalu dramatis. Karena konteksnya banjir, warna merah dan warning harus digunakan hati-hati agar memberi sinyal, bukan menciptakan rasa panik.

## C. Prinsip Modern Component System

UI mengambil prinsip modern component system seperti shadcn-style, tetapi tidak meniru shadcn/ui secara literal dan tidak menggunakan komponen shadcn asli.

Prinsip umum:

1. Komponen harus reusable dan konsisten.
2. Gunakan border halus, bukan shadow berat.
3. Gunakan radius konsisten, idealnya 6px sampai 8px.
4. Gunakan muted background untuk area sekunder.
5. Gunakan typography hierarchy yang jelas.
6. Gunakan spacing yang terukur.
7. Gunakan state visual yang lengkap.
8. Jangan membuat semua elemen mencolok.
9. Visual interest muncul dari spacing, hierarchy, ikon, subtle motion, microcopy, dan state.
10. Hindari gradasi warna berlebihan.
11. Komponen harus terasa dirancang, bukan sekadar aman.

Setiap komponen penting harus memiliki state:

- Default
- Hover
- Active
- Disabled
- Focus-visible
- Loading jika relevan
- Empty state jika relevan
- Error state jika relevan

Komponen utama yang harus distandarkan:

- Button
- Input
- Select
- Textarea
- Badge
- Card
- Table
- Sidebar item
- Alert
- Empty state
- Modal/dialog jika diperlukan
- Dropdown jika diperlukan
- Filter chip
- Map popup
- Statistic card
- Layer control
- Search result item
- Recommendation card
- Route information panel
- Status indicator

## D. Font dan Tipografi

Font utama sistem:

```text
Plus Jakarta Sans
```

Digunakan untuk:

- Heading
- Subheading
- Body text
- Navigasi
- Sidebar
- Tombol
- Label form
- Tabel
- Badge
- Popup peta
- Card
- Microcopy
- Seluruh elemen UI umum

Font teknis:

```text
JetBrains Mono
```

Digunakan hanya untuk:

- Angka statistik
- Koordinat latitude dan longitude
- Jarak dalam meter/kilometer
- Durasi rute
- ID data
- Kode wilayah jika ada
- Metadata teknis
- Nilai teknis seperti SRID, GeoJSON, status internal, dan response API jika ditampilkan

Aturan penggunaan font:

1. Jangan menggunakan JetBrains Mono untuk seluruh UI.
2. Jangan menggunakan font default browser.
3. Jangan mencampur terlalu banyak font.
4. Plus Jakarta Sans harus menjadi identitas tipografi utama.
5. JetBrains Mono hanya menjadi aksen teknis.
6. Semua halaman publik dan admin harus mengikuti aturan font ini.
7. Jika menggunakan Tailwind CSS, font family harus didefinisikan konsisten di konfigurasi atau stylesheet global saat implementasi.

Hierarchy tipografi:

| Elemen | Arah Style |
|---|---|
| Page title | Besar, tegas, calm |
| Section title | Medium, jelas |
| Card label | Kecil, muted, boleh uppercase ringan |
| Body text | Readable, tidak terlalu kecil |
| Metadata | Ukuran kecil, warna muted |
| Angka statistik | Lebih tebal, boleh JetBrains Mono |
| Map popup | Ringkas, tidak panjang |

Hindari terlalu banyak ukuran font yang tidak konsisten.

## E. Tone Warna yang Mature

Komposisi warna:

```text
70% neutral
20% navy/blue
10% accent status
```

Warna inti:

| Fungsi | Arah Warna |
|---|---|
| Civic Navy | Anchor visual dan identitas |
| Civic Blue | Action utama dan focus state |
| Civic Red | Marker banjir, danger, severity tinggi/kritis |
| Green | Evakuasi dan status aman |
| Amber/Gold | Alat berat, warning, dan sumber daya respons |
| Background | Abu sangat muda |
| Card | Putih |
| Text | Slate/neutral gelap |
| Muted text | Slate/neutral sedang |

Arahan penggunaan:

1. Warna utama tidak harus muncul di semua tempat.
2. Banyak komponen cukup memakai neutral palette.
3. Biru hanya untuk aksi penting dan focus state.
4. Merah hanya untuk marker, danger, dan severity.
5. Navy menjadi anchor visual, bukan memenuhi seluruh halaman.
6. Gunakan warna muted agar tampilan calm.
7. Hindari saturasi berlebihan.
8. Hindari gradasi generik.
9. Gunakan warna status secara konsisten.

Pemetaan warna status:

| Data | Warna |
|---|---|
| Banjir aktif | Red |
| Banjir surut | Slate/gray |
| Banjir ditangani | Blue |
| Risiko tinggi | Orange/red |
| Risiko sedang | Amber |
| Risiko rendah | Muted green |
| Evakuasi aktif | Green/blue |
| Evakuasi penuh | Amber/red |
| Pos alat berat aktif | Amber |
| Pos alat berat tidak aktif | Gray |
| Data simulasi/dummy | Muted slate/amber |

## F. Ikon yang Terarah

Ikon adalah bagian dari UX, bukan dekorasi random.

Rekomendasi:

1. Gunakan satu icon library saja.
2. Pilihan utama: Lucide Icons.
3. Heroicons boleh dipakai jika lebih mudah, tetapi jangan dicampur dengan Lucide.
4. Gunakan ikon line-style.
5. Stroke harus konsisten.
6. Ukuran umum: 16px, 18px, atau 20px.
7. Ikon mengikuti `currentColor`.
8. Ikon aktif mengikuti warna action/navy.
9. Jangan memakai ikon warna-warni.
10. Jangan memakai ikon 3D.
11. Jangan memakai emoji sebagai ikon UI.

Contoh ikon:

| Fungsi | Ikon |
|---|---|
| Dashboard | LayoutDashboard |
| Peta | Map |
| Kejadian banjir | Waves atau MapPin |
| Titik rawan | AlertTriangle |
| Evakuasi | ShieldCheck atau Home |
| Alat berat | Truck atau Construction |
| Rute | Route |
| Layer | Layers |
| Search | Search |
| Filter | SlidersHorizontal |
| Tambah | Plus |
| Edit | Pencil |
| Hapus | Trash2 |
| Detail | Eye |
| Simpan | Save |
| Logout | LogOut |
| Koordinat | Crosshair atau MapPinned |
| Status | BadgeCheck atau CircleAlert |
| Jarak | Ruler |
| Durasi | Clock |

### Brand Assets

Asset logo project ditempatkan di struktur publik Laravel agar mudah dipakai di Blade:

| Asset | Path | Penggunaan |
|---|---|---|
| Logo utama | `public/assets/brand/logo-utama.png` | Header publik, login page, sidebar/topbar, README, dan area yang membutuhkan logo lengkap |
| Logo icon | `public/assets/brand/logo-icon.png` | Favicon, app icon, sidebar compact, dan logo kecil |
| Favicon PNG | `public/favicon.png` | Favicon utama berbasis PNG |
| Favicon ICO | `public/favicon.ico` | Fallback favicon untuk browser yang membutuhkan `.ico` |

Aturan penggunaan logo:

1. Gunakan `w-auto` atau `object-contain` agar logo tidak terdistorsi.
2. Gunakan alt text yang jelas, misalnya `SIGAP Banjir Bandar Lampung`.
3. Jangan memberi shadow, glow, atau efek dekoratif berlebihan.
4. Jika logo dipakai pada background gelap, gunakan container terang sederhana agar tetap terbaca.

## G. Layout Utama Sistem

Sistem memiliki dua area utama:

1. Public Map Explorer
2. Admin Dashboard

### Public Map Explorer

Karakter:

- Map-first layout.
- Peta dominan.
- Panel kiri untuk search, filter, layer, dan daftar hasil.
- Area kanan/utama untuk Leaflet map.
- Popup marker ringkas dan informatif.
- Filter tidak boleh menutup peta terlalu banyak.
- Pada mobile, panel berubah menjadi bottom sheet atau collapsible panel.

Struktur desktop:

```text
[Panel eksplorasi 320-380px] [Peta Leaflet full height]
```

Struktur mobile:

```text
[Peta full screen]
[Bottom sheet untuk search/filter/detail]
```

### Admin Dashboard

Karakter:

- Sidebar navigasi.
- Header/topbar ringan.
- Area konten utama.
- Grid card statistik.
- Section aksi cepat.
- Section status data.
- Tabel data.
- Form dengan section card.
- Detail kejadian banjir dengan rekomendasi evakuasi dan alat berat.

Admin dashboard tidak boleh hanya berupa tabel putih dan sidebar navy. Harus ada hierarchy, card yang rapi, status yang terbaca, dan akses peta yang jelas.

## H. Struktur Halaman yang Harus Ada

### Halaman Publik

| Halaman | Tujuan | Komponen Utama | API/Data | State | Catatan UX |
|---|---|---|---|---|---|
| Peta Banjir | Eksplorasi semua layer peta | Leaflet map, panel filter, layer toggle, list hasil, legend | GeoJSON flood-risks, flood-events, evacuation-points, heavy-equipment-posts | Loading peta, empty hasil, error GeoJSON | Peta harus menjadi fokus utama |
| Detail Kejadian Banjir | Melihat detail satu kejadian | Header detail, badge severity, lokasi, metadata, mini map | Detail flood event | Loading, not found | Tampilkan status data nyata/simulasi |
| Detail Titik Evakuasi | Melihat detail evakuasi | Nama, tipe, kapasitas, fasilitas, status, mini map | Detail evacuation point | Loading, not found | Kapasitas dan status harus jelas |
| Detail Pos Alat Berat | Melihat detail pos alat berat | Nama pos, status, unit tersedia, mini map | Detail heavy equipment post | Loading, not found | Cocok jika popup terlalu sempit |

### Halaman Admin

| Halaman | Tujuan | Komponen Utama | API/Data | State | Catatan UX |
|---|---|---|---|---|---|
| Login Admin | Autentikasi admin | Form email/password, info mode demo | Admin login | Loading, error credential | Sederhana, formal, tidak terlalu marketing |
| Dashboard Admin | Ringkasan sistem | Statistik, aksi cepat, status data, kejadian terbaru | Dashboard summary | Loading, empty, error | Lebih luwes dari dashboard CRUD biasa |
| Manajemen Titik Rawan | Kelola titik rawan banjir | Tabel, filter, search, button tambah | Admin flood-risks | Empty, loading, error | Tabel harus punya akses lihat di peta |
| Manajemen Kejadian Banjir | Kelola kejadian banjir | Tabel, badge severity, status, aksi | Admin flood-events | Empty, loading, error | Prioritas tinggi/kritis harus mudah terbaca |
| Form Tambah/Edit Kejadian | Input data banjir | Section form, peta koordinat, status sumber | Admin flood-events | Validation error, saving | Klik peta untuk koordinat saat implementasi |
| Manajemen Titik Evakuasi | Kelola evakuasi | Tabel, filter status/type, aksi | Admin evacuation-points | Empty, loading, error | Kapasitas dan status penting |
| Form Tambah/Edit Evakuasi | Input titik evakuasi | Section identitas, kapasitas, fasilitas, koordinat | Admin evacuation-points | Validation error, saving | Fasilitas bisa berupa tag/chip |
| Manajemen Pos Alat Berat | Kelola pos alat berat | Tabel pos, unit ringkas, status | Admin heavy-equipment-posts | Empty, loading, error | Pos harus bisa dilihat di peta |
| Manajemen Jenis dan Unit Alat | Kelola master alat | Tabel jenis, unit per pos | Equipment types, units | Empty, validation error | Jangan terlalu kompleks |
| Detail Kejadian Banjir | Pusat analisis | Detail banjir, map, rekomendasi, route panel | Detail, nearest, routing | Loading, empty, error | Halaman paling penting untuk SIG |
| Rekomendasi Evakuasi | Analisis titik evakuasi | Ranked cards, jarak, kapasitas, map highlight | nearest-evacuation | Loading, empty, error | Bisa berupa section di detail banjir |
| Rekomendasi Alat Berat | Analisis pos alat berat | Ranked cards, unit tersedia, jarak | nearest-equipment | Loading, empty, error | Bisa berupa section di detail banjir |
| Peta Admin | Eksplorasi dan input data | Map, layer, klik tambah titik | GeoJSON endpoints | Loading, error | Boleh digabung dengan public map plus tools admin |

## I. Sidebar Admin yang Modern dan Tidak Kaku

Sidebar harus terasa seperti navigasi product dashboard.

Arahan:

1. Brand area rapi.
2. Logo mark sederhana boleh berupa simbol peta, gelombang, atau mitigasi.
3. Menu memakai ikon + label.
4. Active state jelas.
5. Hover state halus.
6. Section spacing jelas.
7. Logout dipisah di bagian bawah.
8. Decorative shape boleh ada, tetapi subtle.
9. Sidebar jangan hanya blok navy polos.
10. Nav item height konsisten.
11. Border/line divider subtle boleh digunakan.
12. Sidebar harus readable.

Style sidebar:

| State | Style |
|---|---|
| Default | Text muted, background transparent |
| Hover | Background halus, text lebih gelap |
| Active | Background blue-tinted/navy-tinted, text jelas |
| Icon | Mengikuti warna teks |
| Badge | Kecil untuk jumlah banjir aktif jika ada |

Menu admin:

1. Dashboard
2. Peta
3. Titik Rawan Banjir
4. Kejadian Banjir
5. Titik Evakuasi
6. Pos Alat Berat
7. Jenis Alat
8. Analisis Spasial
9. Sumber Data atau Pengaturan jika diperlukan
10. Logout

## J. Header dan Topbar

Topbar harus ringan dan fungsional.

Komponen yang boleh ada:

- Judul halaman.
- Breadcrumb sederhana.
- Search kecil jika relevan.
- User menu admin.
- Status sistem/data, misalnya “Mode Demo Akademik”.
- Tombol aksi utama halaman, misalnya “Tambah Kejadian”.

Arahan:

1. Jangan membuat topbar terlalu tinggi.
2. Jangan menaruh terlalu banyak aksi.
3. Gunakan border-bottom subtle.
4. Gunakan background putih atau translucent ringan.
5. Breadcrumb tidak wajib jika halaman masih sederhana.
6. Primary action sebaiknya berada di kanan atas halaman.

## K. Dashboard Admin Lebih Luwes

Dashboard admin tidak boleh hanya statistik dan card intro.

Section dashboard:

1. Ringkasan Data
2. Aksi Cepat
3. Status Data
4. Kejadian Banjir Terbaru
5. Shortcut Analisis
6. Ketersediaan Alat Berat

Statistik card:

- Ikon kecil.
- Label.
- Angka.
- Hint pendek.
- Angka boleh memakai JetBrains Mono.
- Border lembut.
- Shadow minimal.
- Hover hanya jika card clickable.

Contoh statistik:

1. Banjir Aktif
2. Titik Rawan
3. Titik Evakuasi Aktif
4. Pos Alat Berat Aktif
5. Unit Alat Tersedia
6. Data Perlu Validasi

Quick action:

1. Tambah Kejadian Banjir
2. Tambah Titik Evakuasi
3. Tambah Pos Alat Berat
4. Buka Peta
5. Lihat Rekomendasi

Status data:

1. Data nyata
2. Data simulasi
3. Data dummy
4. Data belum terverifikasi

Chart kompleks tidak wajib untuk MVP. Rekap sederhana sudah cukup.

## L. Public Map Explorer Lebih Product-Like

Halaman peta publik adalah layar utama yang harus paling terasa sebagai produk SIG.

Layout:

- Split layout.
- Panel kiri untuk eksplorasi.
- Peta kanan besar dan dominan.
- Panel bisa collapse.
- Pada mobile, panel menjadi bottom sheet.

Panel kiri:

1. Search bar.
2. Filter chips.
3. Layer toggles.
4. Result count.
5. List card.
6. Empty state.
7. Reset filter.

Layer:

1. Titik rawan banjir.
2. Kejadian banjir.
3. Titik evakuasi.
4. Pos alat berat.
5. Rute evakuasi jika tersedia.
6. Radius terdampak jika opsional dipakai.
7. Batas kecamatan jika tersedia.

Card hasil:

- Clickable.
- Hover menyorot state.
- Active card punya border/action color.
- Menampilkan nama, tipe, status, kecamatan, dan metadata penting.
- Tidak terlalu padat.

Interaksi:

1. Saat memilih item, peta melakukan smooth pan/zoom.
2. Marker aktif berubah visual.
3. Popup terbuka.
4. Panel menampilkan detail ringkas.
5. Filter aktif terlihat jelas.
6. Empty result punya ikon dan tombol reset filter.
7. Loading list memakai skeleton.

## M. Komponen Peta Leaflet

### Basemap

- Gunakan OpenStreetMap.
- Tampilan peta harus bersih.
- Jangan menampilkan terlalu banyak overlay saat awal dibuka.
- Layer default yang disarankan: kejadian banjir aktif, titik rawan, evakuasi aktif.

### Marker

Marker harus dibedakan berdasarkan kategori.

Kategori marker:

1. Flood risk point
2. Flood event
3. Evacuation point
4. Heavy equipment post
5. Selected point
6. Recommended point

Arah marker:

- Jangan hanya memakai marker default Leaflet jika bisa dibuat lebih polished.
- Gunakan custom div icon sederhana dengan warna dan ikon.
- Marker harus tetap terbaca pada zoom berbeda.
- Marker selected boleh memiliki ring/border.
- Marker recommended boleh memiliki highlight khusus yang tetap subtle.

Marker state:

| State | Arah Visual |
|---|---|
| Default | Warna kategori, ukuran normal |
| Hover | Sedikit lebih kontras |
| Active/selected | Ring atau border biru/navy |
| Disabled/inactive | Gray/muted |
| Recommended | Accent ring atau badge kecil |

### Popup

Popup harus ringkas seperti mini card.

Isi popup:

- Title.
- Badge status.
- Kecamatan.
- Metadata penting.
- CTA kecil.

CTA contoh:

- Lihat Detail
- Cari Evakuasi
- Cari Alat Berat
- Lihat di Peta

Jangan menampilkan semua atribut panjang di popup. Detail lengkap masuk halaman detail atau panel.

### Legend

Legend:

- Ringkas.
- Mudah dibaca.
- Bisa collapsible.
- Tidak menutupi area peta penting.
- Gunakan label teks, bukan hanya warna.

### Layer Control

Layer control:

- Toggle jelas.
- State active terlihat.
- Jangan memakai kontrol Leaflet default tanpa styling jika memungkinkan.
- Layer yang aktif harus mudah dikenali.

## N. Detail Kejadian Banjir

Halaman detail kejadian banjir adalah pusat analisis SIG.

Komponen:

1. Header detail dengan nama kejadian.
2. Badge status dan severity.
3. Ringkasan lokasi.
4. Mini map atau map section.
5. Kedalaman air.
6. Waktu kejadian.
7. Waktu laporan.
8. Metadata sumber data.
9. Tombol aksi.
10. Panel rekomendasi evakuasi.
11. Panel rekomendasi alat berat.
12. Panel rute jika sudah diambil.
13. Alert jika data dummy/simulasi.

Tombol aksi:

- Cari Titik Evakuasi Terdekat
- Cari Pos Alat Berat Terdekat
- Tampilkan Rute Evakuasi
- Edit Data

Style:

1. Severity harus terlihat, tetapi tidak membuat halaman terasa panik.
2. Gunakan badge dan border accent.
3. Jarak dan durasi memakai JetBrains Mono.
4. Rekomendasi ditampilkan sebagai ranked card/list.
5. Posisi rekomendasi di peta harus terlihat jelas.
6. Data simulasi harus diberi label jelas.

## O. Recommendation UI

### Nearest Evacuation

Card rekomendasi utama memuat:

- Nama titik evakuasi.
- Jenis tempat.
- Kapasitas.
- Status.
- Jarak.
- Tombol lihat rute.
- Tombol lihat di peta.

Jarak sebaiknya ditampilkan dengan JetBrains Mono.

### Nearest Equipment

Daftar pos alat berat terdekat memuat:

- Urutan berdasarkan jarak.
- Nama pos.
- Status pos.
- Jenis alat tersedia.
- Jumlah tersedia.
- Jarak.
- Badge “Terdekat” untuk peringkat pertama.

Jangan hanya menampilkan tabel. Gunakan card/list yang lebih mudah dibaca untuk keputusan operasional.

State:

| State | UI |
|---|---|
| Loading | Skeleton card atau spinner kecil |
| Empty evakuasi | “Tidak ada titik evakuasi aktif” |
| Empty alat berat | “Tidak ada alat berat tersedia” |
| Error | Alert dengan penyebab |
| Success | Ranked card/list |

## P. Form UX yang Nyaman

Form admin harus dibagi menjadi section agar tidak terasa seperti form panjang biasa.

Arahan:

1. Form dibagi menjadi card/section.
2. Tiap section punya title dan deskripsi singkat.
3. Ikon kecil boleh digunakan untuk section.
4. Input punya helper text jika field rawan salah.
5. Error message dekat field.
6. Sticky action bar boleh digunakan untuk form panjang.
7. Tombol simpan/batal konsisten.
8. Focus state jelas.
9. Peta kecil pada form punya border dan label jelas.
10. Admin bisa klik peta untuk mengambil koordinat saat implementasi.

Section form kejadian banjir:

1. Informasi Kejadian
2. Lokasi dan Koordinat
3. Status dan Severity
4. Sumber Data
5. Catatan Tambahan

Hint koordinat:

- Latitude contoh: `-5.xxxx`
- Longitude contoh: `105.xxxx`
- PostGIS memakai urutan longitude, latitude.
- Koordinat harus berada di sekitar Bandar Lampung.

Section form titik evakuasi:

1. Identitas Lokasi
2. Kapasitas dan Fasilitas
3. Kontak
4. Koordinat
5. Status Data

Section form pos alat berat:

1. Identitas Pos
2. Kontak
3. Koordinat
4. Unit Alat
5. Status Data

## Q. Button Style Modern

Varian button:

| Varian | Fungsi |
|---|---|
| Primary | Aksi utama |
| Secondary | Aksi pendukung |
| Outline | Navigasi, filter, aksi netral |
| Ghost | Icon button atau action kecil |
| Destructive | Hapus atau aksi berbahaya |
| Link | Navigasi teks |

State:

- Hover
- Active
- Focus-visible
- Disabled
- Loading

Gaya:

1. Radius sedang.
2. Height konsisten.
3. Icon + label jika perlu.
4. Tidak memakai shadow besar.
5. Hover cukup dengan perubahan background/border halus.
6. Focus ring harus jelas.
7. Loading state mencegah double-submit.
8. Tombol destructive tidak boleh terlalu dominan kecuali dalam konfirmasi hapus.

Contoh label tombol:

- Tambah Kejadian
- Simpan Perubahan
- Terapkan Filter
- Reset Filter
- Cari Evakuasi Terdekat
- Cari Pos Alat Berat
- Tampilkan Rute
- Lihat di Peta
- Batal

## R. Card Style Polished

Card tidak boleh terasa seperti kotak putih kosong.

Arahan:

1. Card punya header, content, dan footer/action jika relevan.
2. Gunakan border lembut.
3. Gunakan subtle shadow hanya bila perlu.
4. Gunakan small label/eyebrow text untuk konteks.
5. Gunakan ikon kecil untuk statistik atau section.
6. Hover hanya pada card clickable.
7. Card non-clickable tidak perlu hover berlebihan.
8. Spacing harus rapi.
9. Jangan membuat card terlalu padat.
10. Jangan menaruh card di dalam card tanpa kebutuhan jelas.

Untuk dashboard:

- Statistik card punya ikon kecil, label, angka, dan hint pendek.
- Quick action card punya ikon, title, deskripsi singkat, dan arrow kecil.
- Intro card boleh memiliki decorative shape subtle.

Untuk peta:

- Result card berisi status, lokasi, dan metadata.
- Selected card punya border biru/navy.
- Rekomendasi card menampilkan jarak secara jelas.

## S. Badge, Status, dan Severity

Badge digunakan untuk status penting.

Jenis badge:

1. Status kejadian banjir.
2. Severity.
3. Risk level.
4. Data status.
5. Is verified.
6. Status titik evakuasi.
7. Status pos alat berat.
8. Available quantity.
9. Recommended/terdekat.

Arahan:

1. Badge harus readable.
2. Jangan memakai warna terlalu banyak.
3. Gunakan warna status secara konsisten.
4. Badge penting boleh pakai warna.
5. Badge sekunder pakai muted.
6. Severity kritis/tinggi boleh merah, tetapi controlled.
7. Data dummy/simulasi harus terlihat, tetapi tidak mengganggu.

Contoh badge:

| Status | Arah Warna |
|---|---|
| aktif | Red untuk banjir, green untuk evakuasi/pos |
| surut | Gray |
| ditangani | Blue |
| kritis | Red |
| tinggi | Red/orange |
| sedang | Amber |
| rendah | Green muted |
| dummy | Slate/amber muted |
| simulasi | Blue/slate muted |
| belum terverifikasi | Amber muted |

## T. Table UX Modern

Tabel admin harus rapi dan tidak terlalu padat.

Arahan:

1. Header tabel muted.
2. Row hover lembut.
3. Badge untuk status/severity.
4. Action button memakai ghost/outline dengan ikon.
5. Empty state jika data kosong.
6. Search/filter sederhana disiapkan secara visual.
7. Pada mobile gunakan horizontal scroll.
8. Jangan membuat semua tombol aksi terlalu mencolok.
9. Tabel tidak boleh menjadi satu-satunya cara melihat data spasial.
10. Selalu sediakan akses “Lihat di Peta” untuk data spasial.

Kolom umum:

- Nama
- Kecamatan
- Status
- Severity/Risk
- Data Status
- Verifikasi
- Terakhir Diperbarui
- Aksi

## U. Filter dan Search UX

Filter harus ringan dan mudah dipakai.

Filter publik:

- Status banjir
- Severity
- Risk level
- Kecamatan
- Jenis layer
- Data status

Filter admin:

- Status
- Data status
- Kecamatan
- Source type
- Verified/unverified
- Equipment type
- Availability

Style:

1. Filter chip punya active/hover/focus state.
2. Dropdown tidak terlalu besar.
3. Tombol reset filter tersedia.
4. Active filter count boleh ditampilkan.
5. Search bar punya ikon.
6. Empty state muncul jika hasil kosong.
7. Filter chip di mobile bisa horizontal scroll.

## V. Empty State dan Loading State

### Empty State

Empty state memakai:

- Ikon kecil.
- Judul singkat.
- Deskripsi singkat.
- Aksi relevan.

Contoh:

- Belum ada kejadian banjir
- Tidak ada titik sesuai filter
- Belum ada titik evakuasi aktif
- Tidak ada alat berat tersedia
- Koordinat belum tersedia
- Rute belum dibuat

Empty state tidak boleh terlalu dramatis.

### Loading State

Loading memakai:

- Skeleton untuk list, card, dan table.
- Spinner kecil hanya untuk aksi tombol.
- Button loading state untuk mencegah double-submit.
- Overlay kecil untuk loading peta jika diperlukan.
- Hindari full-screen loading kecuali benar-benar perlu.

## W. Alert dan Error UX

Jenis alert:

- Success
- Warning
- Error
- Info

Contoh alert:

- Data berhasil disimpan.
- Koordinat berada di luar wilayah Bandar Lampung.
- Tidak ada titik evakuasi aktif.
- Provider routing tidak merespons.
- Data ini adalah simulasi akademik.
- Data belum diverifikasi.

Arahan:

1. Alert harus membantu, bukan hanya memberi warna merah.
2. Error message form harus dekat field.
3. Alert global hanya untuk informasi global.
4. Jangan memakai alert besar untuk hal kecil.
5. Beri solusi jika memungkinkan.

Contoh solusi:

```text
Periksa koordinat atau pilih ulang titik di peta.
```

## X. Motion dan Interaction

Motion harus terasa seperti modern app, bukan presentasi.

Gunakan transition pada:

- Button
- Nav item
- Card clickable
- Input
- Dropdown
- Alert
- Panel
- Filter chip
- Popup
- Sidebar collapse jika ada

Durasi:

| Elemen | Durasi |
|---|---|
| Button/nav/input | 120-160ms |
| Card/panel | 160-220ms |
| Page content | 180-260ms |
| Map pan | Mengikuti Leaflet smooth/default |

Easing:

- `ease-out` untuk enter
- `ease-in` untuk exit
- `ease-in-out` untuk hover umum

Boleh digunakan:

- Page content fade-in ringan.
- Dashboard card stagger sangat ringan.
- Panel result slide/fade ringan.
- Alert fade/slide kecil.
- Skeleton pulse subtle.
- Smooth pan/zoom saat memilih marker.

Tidak boleh:

- Bounce.
- Parallax.
- Glow/neon.
- Scale besar.
- Animasi looping tidak perlu.
- Motion yang mengganggu peta.
- Efek terlalu template AI.

Wajib menghormati `prefers-reduced-motion`.

## Y. Microcopy

Bahasa UI menggunakan Bahasa Indonesia yang jelas, singkat, dan membantu.

Prinsip:

1. Hindari istilah teknis yang tidak perlu untuk pengguna umum.
2. Istilah teknis boleh muncul di admin.
3. Label jangan terlalu panjang.
4. Tombol harus spesifik.
5. Error harus menjelaskan masalah.
6. Helper text harus membantu.

Contoh microcopy:

- Tambah Kejadian Banjir
- Simpan Perubahan
- Terapkan Filter
- Reset Filter
- Cari Evakuasi Terdekat
- Cari Pos Alat Berat
- Tampilkan Rute Evakuasi
- Lihat di Peta
- Data simulasi akademik
- Koordinat perlu divalidasi
- Tidak ada titik evakuasi aktif
- Provider rute tidak merespons

Gunakan istilah yang hati-hati:

| Hindari | Gunakan |
|---|---|
| Rute resmi | Rute referensi |
| Keputusan final | Rekomendasi berdasarkan jarak spasial |
| Radius terdampak resmi | Radius terdampak simulasi |
| Data pasti | Data terverifikasi / data simulasi |

Data dummy, simulasi, dan nyata harus selalu jelas.

## Z. Responsiveness

UI harus nyaman di desktop dan tetap bisa digunakan di mobile.

### Desktop

- Sidebar admin tetap terlihat.
- Map explorer split layout.
- Tabel tampil penuh.
- Dashboard memakai grid.

### Tablet

- Sidebar bisa compact.
- Map panel lebih sempit.
- Filter bisa collapse.

### Mobile

- Sidebar menjadi drawer.
- Map explorer menggunakan bottom sheet.
- Tabel horizontal scroll.
- Form satu kolom.
- Action bar sticky bawah boleh digunakan.
- Popup peta tidak boleh terlalu besar.
- Filter chip bisa scroll horizontal.

## AA. Accessibility

Guideline aksesibilitas:

1. Kontras teks harus cukup.
2. Focus state harus terlihat.
3. Button icon-only harus punya `aria-label`.
4. Form label harus jelas.
5. Error message harus terbaca.
6. Jangan hanya mengandalkan warna untuk status.
7. Gunakan badge teks untuk status.
8. Interactive area cukup besar.
9. Keyboard navigation minimal untuk form dan tombol.
10. Map interaction harus punya alternatif list/panel.

## AB. Anti AI-Slop, tetapi Jangan Terlalu Aman

Anti AI-slop bukan berarti tanpa kreativitas.

Boleh:

- Memakai ikon.
- Memakai motion subtle.
- Memakai empty state.
- Memakai decorative shape subtle.
- Memakai layout map explorer yang lebih berani.
- Memakai card rekomendasi yang polished.
- Memakai komponen modern ala shadcn-style.
- Memakai state visual lengkap.
- Memakai detail interaksi kecil yang terasa premium.

Dilarang:

- Gradasi generik berlebihan.
- Warna random.
- Shadow berat.
- Spacing kacau.
- Animasi tidak berguna.
- Dekorasi kebencanaan yang terlalu dramatis.
- UI terlalu statis.
- Halaman hanya tabel dan card putih polos.
- Semua halaman terlihat sama tanpa hierarchy.
- Interaction state seadanya.
- Marker peta default tanpa pemikiran visual.
- Dashboard seperti template admin lama.
- Landing page startup yang terlalu marketing.

## AC. Mapping UI dengan API

Mapping UI dengan endpoint API:

| UI | Endpoint |
|---|---|
| Public Map Explorer | `/api/v1/geojson/flood-risks` |
| Public Map Explorer | `/api/v1/geojson/flood-events` |
| Public Map Explorer | `/api/v1/geojson/evacuation-points` |
| Public Map Explorer | `/api/v1/geojson/heavy-equipment-posts` |
| Dashboard Admin | `/api/v1/admin/dashboard/summary` |
| Tabel Titik Rawan | `/api/v1/admin/flood-risks` |
| Tabel Kejadian Banjir | `/api/v1/admin/flood-events` |
| Tabel Titik Evakuasi | `/api/v1/admin/evacuation-points` |
| Tabel Pos Alat Berat | `/api/v1/admin/heavy-equipment-posts` |
| Detail Kejadian Banjir | `/api/v1/flood-events/{id}` |
| Rekomendasi Evakuasi | `/api/v1/analysis/flood-events/{id}/nearest-evacuation` |
| Rekomendasi Alat Berat | `/api/v1/analysis/flood-events/{id}/nearest-equipment` |
| Rute Evakuasi | `/api/v1/routing/flood-events/{id}/to-nearest-evacuation` |
| Form select | `/api/v1/master/*` |

Catatan:

1. UI tidak boleh hardcode enum jika master endpoint tersedia.
2. GeoJSON endpoint digunakan untuk Leaflet layer.
3. CRUD endpoint digunakan untuk admin.
4. Spatial analysis endpoint digunakan untuk recommendation card.
5. Routing endpoint digunakan untuk route information panel dan polyline peta.

## AD. Prioritas Implementasi UI

Urutan implementasi UI yang realistis:

1. Layout dasar admin.
2. Login admin.
3. Dashboard admin.
4. CRUD kejadian banjir.
5. CRUD titik rawan banjir.
6. CRUD titik evakuasi.
7. CRUD pos dan unit alat berat.
8. Endpoint GeoJSON ditampilkan di peta.
9. Public Map Explorer.
10. Detail kejadian banjir.
11. Rekomendasi evakuasi.
12. Rekomendasi alat berat.
13. Rute evakuasi.
14. Polish UI state, loading, empty, error, hover, focus.
15. Responsive refinement.

Alasan urutan ini lebih aman:

1. Admin layout dan auth menjadi fondasi navigasi.
2. CRUD data utama dibutuhkan sebelum peta bisa hidup.
3. GeoJSON layer lebih mudah diuji setelah data tersedia.
4. Analisis spasial bergantung pada data banjir, evakuasi, dan alat berat.
5. Routing sebaiknya dibuat setelah rekomendasi evakuasi stabil.
6. Polish UI dilakukan setelah alur utama berjalan.

## AE. Checklist UI.md

- [ ] Design personality sudah jelas.
- [ ] Style modern component system sudah jelas.
- [ ] Font sudah ditentukan.
- [ ] Plus Jakarta Sans menjadi font utama.
- [ ] JetBrains Mono hanya untuk data teknis.
- [ ] Palet warna sudah matang.
- [ ] Ikon sudah diarahkan.
- [ ] Layout publik dan admin sudah jelas.
- [ ] Halaman utama sudah dirancang.
- [ ] Map explorer sudah dirancang.
- [ ] Marker, popup, legend, dan layer control sudah diarahkan.
- [ ] Dashboard admin sudah diarahkan.
- [ ] Form UX sudah diarahkan.
- [ ] Table UX sudah diarahkan.
- [ ] Button/card/badge state sudah lengkap.
- [ ] Empty/loading/error state sudah diarahkan.
- [ ] Motion guideline sudah jelas.
- [ ] Microcopy sudah diarahkan.
- [ ] Responsive dan accessibility sudah dibahas.
- [ ] Anti AI-slop sudah jelas.
- [ ] Mapping UI dengan API sudah jelas.
- [ ] Siap lanjut ke `TASKS.md`.

## Keputusan Akhir UI

### 1. Ringkasan Arah UI Final

Arah UI final adalah:

```text
Civic Flood Response Map Explorer with Modern Component System
```

Sistem harus map-first, calm, civic, modern, data-oriented, dan tetap akademik. Tampilan harus terasa seperti platform SIG kebencanaan yang serius, bukan admin panel default atau landing page startup.

### 2. Halaman Wajib MVP

Halaman wajib:

1. Login Admin
2. Dashboard Admin
3. Public Map Explorer
4. Peta Admin
5. Manajemen Titik Rawan Banjir
6. Manajemen Kejadian Banjir
7. Form Tambah/Edit Kejadian Banjir
8. Manajemen Titik Evakuasi
9. Form Tambah/Edit Titik Evakuasi
10. Manajemen Pos Alat Berat
11. Manajemen Jenis dan Unit Alat Berat
12. Detail Kejadian Banjir
13. Section rekomendasi evakuasi
14. Section rekomendasi alat berat
15. Section rute evakuasi

### 3. Komponen yang Wajib Distandarkan

Komponen wajib:

1. Button
2. Input
3. Select
4. Textarea
5. Badge
6. Card
7. Table
8. Sidebar item
9. Alert
10. Empty state
11. Filter chip
12. Map popup
13. Statistic card
14. Layer control
15. Recommendation card
16. Route information panel
17. Status indicator

### 4. Bagian UI yang Boleh Dipoles Setelah MVP

Boleh dipoles setelah MVP:

1. Animasi panel peta.
2. Sidebar collapse.
3. Skeleton loading lebih detail.
4. Dashboard visual tambahan.
5. Radius terdampak.
6. Layer batas kecamatan.
7. Filter advanced.
8. Halaman sumber data.
9. Riwayat rute.
10. Riwayat dispatch alat berat.

### 5. Bagian yang Sebaiknya Jangan Dibuat Dulu

Jangan dibuat dulu:

1. Landing page marketing.
2. Dark mode.
3. Chart kompleks.
4. Multi-role UI kompleks.
5. Upload foto.
6. Notifikasi real-time.
7. Tracking alat berat real-time.
8. UI laporan warga publik.
9. Wizard onboarding.
10. Animasi hero dekoratif.

### 6. Dokumen Berikutnya

Dokumen berikutnya yang harus dibuat:

```text
TASKS.md
```

Alasannya, setelah requirements, database, dataset, API, dan UI sudah jelas, langkah berikutnya adalah menyusun daftar pekerjaan teknis bertahap agar implementasi tidak melebar dari MVP.
