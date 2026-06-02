# Audit Validasi Spasial Dataset SIGAP Banjir

## 1. Metode Validasi Lokasi

Audit ini dilakukan terhadap seluruh record spasial pada tabel `flood_events`, `flood_risk_points`, `evacuation_points`, dan `heavy_equipment_posts`. Pemeriksaan dilakukan dengan dua pendekatan: validasi internal koordinat terhadap wilayah studi Kota Bandar Lampung dan validasi eksternal tingkat area menggunakan reverse geocoding OpenStreetMap/Nominatim.

Validasi ini tidak mengubah status operasional data menjadi resmi. Hasil audit hanya menunjukkan bahwa koordinat berada pada area yang dapat ditelusuri melalui peta publik. Status kejadian banjir, tingkat risiko, fungsi titik evakuasi, dan keberadaan pos alat berat tetap dinyatakan sebagai skenario simulasi/dummy sampai ada sumber resmi atau dokumen verifikasi lapangan.

## 2. Sumber Peta Publik

| Sumber | Penggunaan | Tanggal Akses |
| --- | --- | --- |
| OpenStreetMap | Referensi peta publik dan nama area | 2 Juni 2026 |
| Nominatim OpenStreetMap | Reverse geocoding koordinat longitude/latitude | 2 Juni 2026 |

## 3. Ringkasan Hasil Audit

| Dataset | Jumlah Record Diaudit |
| --- | ---: |
| Kejadian Banjir | 8 |
| Titik Rawan Banjir | 12 |
| Titik Evakuasi | 10 |
| Pos Alat Berat | 6 |
| **Total** | **36** |

| Status Validasi Lokasi | Jumlah |
| --- | ---: |
| tervalidasi_peta_publik_level_area | 36 |

Seluruh record final berada pada area yang terbaca sebagai Kota Bandar Lampung atau area administrasi yang relevan pada hasil reverse geocoding Nominatim. Beberapa titik memiliki nama detail jalan/kelurahan yang berbeda dari atribut aplikasi karena batas administrasi dan detail pemetaan OSM tidak selalu identik dengan pembagian kecamatan/kelurahan yang digunakan pada skenario akademik.

## 4. Inkonsistensi yang Ditemukan

Inkonsistensi utama yang ditemukan adalah record `Pos Alat Berat Panjang` yang sebelumnya berstatus `nyata`, memiliki `source_type = pemerintah`, dan `is_verified = true`, tetapi `source_reference` masih menunjukkan bahwa data berasal dari seeder demo. Karena tidak tersedia dokumen resmi atau URL sumber yang membuktikan record tersebut sebagai data operasional resmi, status record dikoreksi menjadi `dummy`, `source_type = dummy`, dan `is_verified = false`.

Selain itu, beberapa titik awal yang sebelumnya hanya berada pada rentang koordinat Bandar Lampung dikoreksi agar lebih sesuai dengan hasil peta publik, terutama pada area Way Halim, Sukarame, Panjang Utara, Rajabasa, Bumi Waras, Enggal, dan Kemiling Permai.

## 5. Record yang Dikoreksi

| Dataset | ID | Nama Final | Koordinat Final | Status Data Final | Catatan Koreksi |
| --- | ---: | --- | --- | --- | --- |
| flood_events | 10 | Genangan Way Halim | 105.2746909, -5.3823404 | simulasi | Dikoreksi: longitude: 105.2869000 -> 105.2746909; latitude: -5.3867000 -> -5.3823404. Koordinat berada pada wilayah Kota Bandar Lampung menurut reverse geocoding Nominatim; validasi ini hanya validasi lokasi/area, bukan verifikasi operasional resmi. |
| flood_events | 11 | Banjir Sukarame | 105.2946540, -5.3974767 | simulasi | Dikoreksi: name: Banjir Korpri Sukarame -> Banjir Sukarame; address: Genangan Korpri Raya Sukarame -> Genangan area Sukarame; subdistrict: Korpri Raya -> Sukarame; longitude: 105.3045000 -> 105.2946540; latitude: -5.3832000 -> -5.3974767. Koordinat berada p... |
| flood_events | 12 | Genangan Panjang Utara | 105.3229645, -5.4721335 | simulasi | Dikoreksi: longitude: 105.3278000 -> 105.3229645; latitude: -5.4658000 -> -5.4721335. Koordinat berada pada wilayah Kota Bandar Lampung menurut reverse geocoding Nominatim; validasi ini hanya validasi lokasi/area, bukan verifikasi operasional resmi. |
| flood_events | 13 | Banjir Rajabasa | 105.2297280, -5.3627526 | simulasi | Dikoreksi: name: Banjir Rajabasa Nunyai -> Banjir Rajabasa; address: Genangan permukiman Rajabasa Nunyai -> Genangan permukiman Rajabasa; subdistrict: Rajabasa Nunyai -> Rajabasa; longitude: 105.2294000 -> 105.2297280; latitude: -5.3716000 -> -5.3627526. Ko... |
| flood_events | 15 | Banjir Bumi Waras | 105.2706967, -5.4486092 | simulasi | Dikoreksi: longitude: 105.2702000 -> 105.2706967; latitude: -5.4352000 -> -5.4486092. Koordinat berada pada wilayah Kota Bandar Lampung menurut reverse geocoding Nominatim; validasi ini hanya validasi lokasi/area, bukan verifikasi operasional resmi. |
| flood_events | 16 | Genangan Enggal | 105.2608294, -5.4198497 | simulasi | Dikoreksi: longitude: 105.2639000 -> 105.2608294; latitude: -5.4206000 -> -5.4198497. Koordinat berada pada wilayah Kota Bandar Lampung menurut reverse geocoding Nominatim; validasi ini hanya validasi lokasi/area, bukan verifikasi operasional resmi. |
| flood_risk_points | 13 | Rawan Banjir Way Halim | 105.2746909, -5.3823404 | simulasi | Dikoreksi: longitude: 105.2886000 -> 105.2746909; latitude: -5.3897000 -> -5.3823404. Koordinat berada pada wilayah Kota Bandar Lampung menurut reverse geocoding Nominatim; validasi ini hanya validasi lokasi/area, bukan verifikasi operasional resmi. |
| flood_risk_points | 15 | Rawan Banjir Panjang Utara | 105.3229645, -5.4721335 | simulasi | Dikoreksi: longitude: 105.3284000 -> 105.3229645; latitude: -5.4661000 -> -5.4721335. Koordinat berada pada wilayah Kota Bandar Lampung menurut reverse geocoding Nominatim; validasi ini hanya validasi lokasi/area, bukan verifikasi operasional resmi. |
| flood_risk_points | 16 | Rawan Banjir Rajabasa | 105.2297280, -5.3627526 | simulasi | Dikoreksi: name: Rawan Banjir Rajabasa Nunyai -> Rawan Banjir Rajabasa; address: Permukiman sekitar Rajabasa Nunyai -> Permukiman sekitar Rajabasa; subdistrict: Rajabasa Nunyai -> Rajabasa; longitude: 105.2302000 -> 105.2297280; latitude: -5.3722000 -> -5.3... |
| flood_risk_points | 17 | Rawan Banjir Sukarame | 105.2946540, -5.3974767 | simulasi | Dikoreksi: name: Rawan Banjir Korpri Sukarame -> Rawan Banjir Sukarame; address: Area Korpri Raya Sukarame -> Area Sukarame; subdistrict: Korpri Raya -> Sukarame; longitude: 105.3052000 -> 105.2946540; latitude: -5.3838000 -> -5.3974767. Koordinat berada pa... |
| flood_risk_points | 21 | Rawan Banjir Bumi Waras | 105.2706967, -5.4486092 | simulasi | Dikoreksi: longitude: 105.2701000 -> 105.2706967; latitude: -5.4364000 -> -5.4486092. Koordinat berada pada wilayah Kota Bandar Lampung menurut reverse geocoding Nominatim; validasi ini hanya validasi lokasi/area, bukan verifikasi operasional resmi. |
| flood_risk_points | 23 | Rawan Banjir Kemiling Permai | 105.2224429, -5.3760226 | simulasi | Dikoreksi: longitude: 105.2149000 -> 105.2224429; latitude: -5.3970000 -> -5.3760226. Koordinat berada pada wilayah Kota Bandar Lampung menurut reverse geocoding Nominatim; validasi ini hanya validasi lokasi/area, bukan verifikasi operasional resmi. |
| flood_risk_points | 24 | Rawan Banjir Teluk Betung Timur | 105.2452182, -5.4698660 | simulasi | Dikoreksi: longitude: 105.2787000 -> 105.2452182; latitude: -5.4555000 -> -5.4698660. Koordinat berada pada wilayah Kota Bandar Lampung menurut reverse geocoding Nominatim; validasi ini hanya validasi lokasi/area, bukan verifikasi operasional resmi. |
| evacuation_points | 11 | Masjid Al-Furqon Lungsir | 105.2615707, -5.4291549 | simulasi | Dikoreksi: longitude: 105.2635000 -> 105.2615707; latitude: -5.4230000 -> -5.4291549. Koordinat berada pada wilayah Kota Bandar Lampung menurut reverse geocoding Nominatim; validasi ini hanya validasi lokasi/area, bukan verifikasi operasional resmi. |
| evacuation_points | 14 | Kandidat Evakuasi Panjang Utara - Simulasi | 105.3229645, -5.4721335 | simulasi | Dikoreksi: name: Aula Kecamatan Panjang -> Kandidat Evakuasi Panjang Utara - Simulasi; address: Area Kecamatan Panjang -> Area Panjang Utara sekitar Jalan Yos Soedarso; longitude: 105.3314000 -> 105.3229645; latitude: -5.4682000 -> -5.4721335. Koordinat ber... |
| evacuation_points | 15 | Kandidat Evakuasi Sukarame - Simulasi | 105.2946540, -5.3974767 | simulasi | Dikoreksi: name: Puskesmas Sukarame -> Kandidat Evakuasi Sukarame - Simulasi; address: Area layanan kesehatan Sukarame -> Area Sukarame; subdistrict: Sukarame Baru -> Sukarame; longitude: 105.3025000 -> 105.2946540; latitude: -5.3775000 -> -5.3974767. Koord... |
| evacuation_points | 16 | SDN Simulasi Way Halim | 105.2746909, -5.3823404 | simulasi | Dikoreksi: longitude: 105.2878000 -> 105.2746909; latitude: -5.3890000 -> -5.3823404. Koordinat berada pada wilayah Kota Bandar Lampung menurut reverse geocoding Nominatim; validasi ini hanya validasi lokasi/area, bukan verifikasi operasional resmi. |
| evacuation_points | 18 | Balai Warga Rajabasa - Simulasi | 105.2297280, -5.3627526 | simulasi | Dikoreksi: name: Balai Warga Rajabasa Nunyai -> Balai Warga Rajabasa - Simulasi; address: Area Rajabasa Nunyai -> Area Rajabasa; subdistrict: Rajabasa Nunyai -> Rajabasa; longitude: 105.2299000 -> 105.2297280; latitude: -5.3710000 -> -5.3627526. Koordinat b... |
| evacuation_points | 19 | Masjid Simulasi Kemiling | 105.2224429, -5.3760226 | simulasi | Dikoreksi: longitude: 105.2157000 -> 105.2224429; latitude: -5.3971000 -> -5.3760226. Koordinat berada pada wilayah Kota Bandar Lampung menurut reverse geocoding Nominatim; validasi ini hanya validasi lokasi/area, bukan verifikasi operasional resmi. |
| heavy_equipment_posts | 7 | Pos Alat Berat Panjang | 105.3262000, -5.4669000 | dummy | Dikoreksi: status awal Panjang: source_type=pemerintah, data_status=nyata, is_verified=True -> source_type=dummy, data_status=dummy, is_verified=False. Koordinat berada pada wilayah Kota Bandar Lampung menurut reverse geocoding Nominatim; validasi ini hanya... |
| heavy_equipment_posts | 9 | Pos Alat Berat Rajabasa | 105.2297280, -5.3627526 | dummy | Dikoreksi: address: Area operasional sekitar Rajabasa Nunyai -> Area operasional sekitar Rajabasa; subdistrict: Rajabasa Nunyai -> Rajabasa; longitude: 105.2290000 -> 105.2297280; latitude: -5.3729000 -> -5.3627526. Koordinat berada pada wilayah Kota Bandar Lampung menurut reverse geocoding Nominatim; validasi ini hanya validasi lokasi/area, b... |
| heavy_equipment_posts | 10 | Pos Alat Berat Way Halim | 105.2746909, -5.3823404 | dummy | Dikoreksi: longitude: 105.2896000 -> 105.2746909; latitude: -5.3912000 -> -5.3823404. Koordinat berada pada wilayah Kota Bandar Lampung menurut reverse geocoding Nominatim; validasi ini hanya validasi lokasi/area, bukan verifikasi operasional resmi. |
| heavy_equipment_posts | 11 | Pos Alat Berat Sukarame | 105.2946540, -5.3974767 | dummy | Dikoreksi: address: Area operasional sekitar Korpri Sukarame -> Area operasional Sukarame; subdistrict: Korpri Raya -> Sukarame; longitude: 105.3068000 -> 105.2946540; latitude: -5.3812000 -> -5.3974767. Koordinat berada pada wilayah Kota Bandar Lampung men... |
| heavy_equipment_posts | 12 | Pos Alat Berat Kemiling | 105.2224429, -5.3760226 | dummy | Dikoreksi: longitude: 105.2140000 -> 105.2224429; latitude: -5.3965000 -> -5.3760226. Koordinat berada pada wilayah Kota Bandar Lampung menurut reverse geocoding Nominatim; validasi ini hanya validasi lokasi/area, bukan verifikasi operasional resmi. |

## 6. Tabel Audit Seluruh Record Spasial

| Dataset | ID | Nama Final | Kecamatan | Kelurahan/Area | Longitude | Latitude | Status Validasi Lokasi | Status Data | Verifikasi Operasional |
| --- | ---: | --- | --- | --- | ---: | ---: | --- | --- | --- |
| flood_events | 9 | Banjir Teluk Betung Selatan | Teluk Betung Selatan | Pesawahan | 105.2607000 | -5.4478000 | tervalidasi_peta_publik_level_area | simulasi | belum_terverifikasi_operasional |
| flood_events | 10 | Genangan Way Halim | Way Halim | Way Halim Permai | 105.2746909 | -5.3823404 | tervalidasi_peta_publik_level_area | simulasi | belum_terverifikasi_operasional |
| flood_events | 11 | Banjir Sukarame | Sukarame | Sukarame | 105.2946540 | -5.3974767 | tervalidasi_peta_publik_level_area | simulasi | belum_terverifikasi_operasional |
| flood_events | 12 | Genangan Panjang Utara | Panjang | Panjang Utara | 105.3229645 | -5.4721335 | tervalidasi_peta_publik_level_area | simulasi | belum_terverifikasi_operasional |
| flood_events | 13 | Banjir Rajabasa | Rajabasa | Rajabasa | 105.2297280 | -5.3627526 | tervalidasi_peta_publik_level_area | simulasi | belum_terverifikasi_operasional |
| flood_events | 14 | Genangan Kedamaian | Kedamaian | Kedamaian | 105.2828000 | -5.4082000 | tervalidasi_peta_publik_level_area | simulasi | belum_terverifikasi_operasional |
| flood_events | 15 | Banjir Bumi Waras | Bumi Waras | Bumi Waras | 105.2706967 | -5.4486092 | tervalidasi_peta_publik_level_area | simulasi | belum_terverifikasi_operasional |
| flood_events | 16 | Genangan Enggal | Enggal | Enggal | 105.2608294 | -5.4198497 | tervalidasi_peta_publik_level_area | simulasi | belum_terverifikasi_operasional |
| flood_risk_points | 13 | Rawan Banjir Way Halim | Way Halim | Way Halim Permai | 105.2746909 | -5.3823404 | tervalidasi_peta_publik_level_area | simulasi | belum_terverifikasi_operasional |
| flood_risk_points | 14 | Rawan Banjir Teluk Betung Selatan | Teluk Betung Selatan | Pesawahan | 105.2608000 | -5.4469000 | tervalidasi_peta_publik_level_area | simulasi | belum_terverifikasi_operasional |
| flood_risk_points | 15 | Rawan Banjir Panjang Utara | Panjang | Panjang Utara | 105.3229645 | -5.4721335 | tervalidasi_peta_publik_level_area | simulasi | belum_terverifikasi_operasional |
| flood_risk_points | 16 | Rawan Banjir Rajabasa | Rajabasa | Rajabasa | 105.2297280 | -5.3627526 | tervalidasi_peta_publik_level_area | simulasi | belum_terverifikasi_operasional |
| flood_risk_points | 17 | Rawan Banjir Sukarame | Sukarame | Sukarame | 105.2946540 | -5.3974767 | tervalidasi_peta_publik_level_area | simulasi | belum_terverifikasi_operasional |
| flood_risk_points | 18 | Rawan Banjir Kedamaian | Kedamaian | Kedamaian | 105.2816000 | -5.4095000 | tervalidasi_peta_publik_level_area | simulasi | belum_terverifikasi_operasional |
| flood_risk_points | 19 | Rawan Banjir Labuhan Ratu | Labuhan Ratu | Labuhan Ratu Raya | 105.2448000 | -5.3756000 | tervalidasi_peta_publik_level_area | simulasi | belum_terverifikasi_operasional |
| flood_risk_points | 20 | Rawan Banjir Tanjung Karang Timur | Tanjung Karang Timur | Kota Baru | 105.2779000 | -5.4163000 | tervalidasi_peta_publik_level_area | simulasi | belum_terverifikasi_operasional |
| flood_risk_points | 21 | Rawan Banjir Bumi Waras | Bumi Waras | Bumi Waras | 105.2706967 | -5.4486092 | tervalidasi_peta_publik_level_area | simulasi | belum_terverifikasi_operasional |
| flood_risk_points | 22 | Rawan Banjir Enggal | Enggal | Enggal | 105.2597000 | -5.4189000 | tervalidasi_peta_publik_level_area | simulasi | belum_terverifikasi_operasional |
| flood_risk_points | 23 | Rawan Banjir Kemiling Permai | Kemiling | Kemiling Permai | 105.2224429 | -5.3760226 | tervalidasi_peta_publik_level_area | simulasi | belum_terverifikasi_operasional |
| flood_risk_points | 24 | Rawan Banjir Teluk Betung Timur | Teluk Betung Timur | Keteguhan | 105.2452182 | -5.4698660 | tervalidasi_peta_publik_level_area | simulasi | belum_terverifikasi_operasional |
| evacuation_points | 11 | Masjid Al-Furqon Lungsir | Tanjung Karang Pusat | Lungsir | 105.2615707 | -5.4291549 | tervalidasi_peta_publik_level_area | simulasi | belum_terverifikasi_operasional |
| evacuation_points | 12 | GOR Saburai | Enggal | Enggal | 105.2598000 | -5.4218000 | tervalidasi_peta_publik_level_area | simulasi | belum_terverifikasi_operasional |
| evacuation_points | 13 | Kantor Kecamatan Teluk Betung Selatan | Teluk Betung Selatan | Pesawahan | 105.2591000 | -5.4485000 | tervalidasi_peta_publik_level_area | simulasi | belum_terverifikasi_operasional |
| evacuation_points | 14 | Kandidat Evakuasi Panjang Utara - Simulasi | Panjang | Panjang Utara | 105.3229645 | -5.4721335 | tervalidasi_peta_publik_level_area | simulasi | belum_terverifikasi_operasional |
| evacuation_points | 15 | Kandidat Evakuasi Sukarame - Simulasi | Sukarame | Sukarame | 105.2946540 | -5.3974767 | tervalidasi_peta_publik_level_area | simulasi | belum_terverifikasi_operasional |
| evacuation_points | 16 | SDN Simulasi Way Halim | Way Halim | Way Halim Permai | 105.2746909 | -5.3823404 | tervalidasi_peta_publik_level_area | simulasi | belum_terverifikasi_operasional |
| evacuation_points | 17 | Lapangan Enggal | Enggal | Enggal | 105.2593000 | -5.4175000 | tervalidasi_peta_publik_level_area | simulasi | belum_terverifikasi_operasional |
| evacuation_points | 18 | Balai Warga Rajabasa - Simulasi | Rajabasa | Rajabasa | 105.2297280 | -5.3627526 | tervalidasi_peta_publik_level_area | simulasi | belum_terverifikasi_operasional |
| evacuation_points | 19 | Masjid Simulasi Kemiling | Kemiling | Kemiling Permai | 105.2224429 | -5.3760226 | tervalidasi_peta_publik_level_area | simulasi | belum_terverifikasi_operasional |
| evacuation_points | 20 | Balai Warga Kedamaian | Kedamaian | Kedamaian | 105.2815000 | -5.4100000 | tervalidasi_peta_publik_level_area | simulasi | belum_terverifikasi_operasional |
| heavy_equipment_posts | 7 | Pos Alat Berat Panjang | Panjang | Panjang Utara | 105.3262000 | -5.4669000 | tervalidasi_peta_publik_level_area | dummy | belum_terverifikasi_operasional |
| heavy_equipment_posts | 8 | Pos Alat Berat Teluk Betung | Teluk Betung Selatan | Pesawahan | 105.2590000 | -5.4442000 | tervalidasi_peta_publik_level_area | dummy | belum_terverifikasi_operasional |
| heavy_equipment_posts | 9 | Pos Alat Berat Rajabasa | Rajabasa | Rajabasa | 105.2297280 | -5.3627526 | tervalidasi_peta_publik_level_area | dummy | belum_terverifikasi_operasional |
| heavy_equipment_posts | 10 | Pos Alat Berat Way Halim | Way Halim | Way Halim Permai | 105.2746909 | -5.3823404 | tervalidasi_peta_publik_level_area | dummy | belum_terverifikasi_operasional |
| heavy_equipment_posts | 11 | Pos Alat Berat Sukarame | Sukarame | Sukarame | 105.2946540 | -5.3974767 | tervalidasi_peta_publik_level_area | dummy | belum_terverifikasi_operasional |
| heavy_equipment_posts | 12 | Pos Alat Berat Kemiling | Kemiling | Kemiling Permai | 105.2224429 | -5.3760226 | tervalidasi_peta_publik_level_area | dummy | belum_terverifikasi_operasional |

## 7. Batasan Audit

Validasi koordinat melalui peta publik tidak berarti data telah menjadi data resmi pemerintah, data kejadian faktual, titik evakuasi resmi, atau fasilitas pos alat berat resmi. Dataset final tetap digunakan sebagai dataset awal pengembangan dan simulasi spasial untuk menguji fungsi peta, GeoJSON, analisis jarak PostGIS, rekomendasi resource, dan rute referensi OSRM.

## 8. File Pendukung

- `spatial_validation_audit.csv`: tabel audit seluruh record spasial.
- `audit_backups/heavy_equipment_posts_panjang_before_correction.json`: backup record Pos Alat Berat Panjang sebelum koreksi status.
- `audit_backups/spatial_records_final.json`: snapshot record spasial final setelah koreksi.
- `audit_backups/nominatim_reverse_geocode_results_final.json`: hasil reverse geocoding Nominatim untuk record final.
