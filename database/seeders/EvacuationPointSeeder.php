<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EvacuationPointSeeder extends Seeder
{
    private const SOURCE_REFERENCE = 'Dataset simulasi pengembangan SIGAP Banjir; lokasi fasilitas/area diverifikasi melalui peta publik OpenStreetMap/Nominatim, fungsi evakuasi merupakan skenario uji aplikasi.';

    private const LEGACY_SOURCE_REFERENCES = [
        'Seeder demo SIGAP Banjir',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('evacuation_points')
            ->whereIn('source_reference', array_merge([self::SOURCE_REFERENCE], self::LEGACY_SOURCE_REFERENCES))
            ->delete();

        $now = now();

        $points = [
            ['Masjid Al-Furqon Lungsir', 'masjid', 'Area Lungsir, Tanjung Karang Pusat', 'Tanjung Karang Pusat', 'Lungsir', 300, 'aula,toilet,tempat ibadah,parkir', 'Pengurus Masjid', '0812-7300-2101', 'aktif', 'Titik evakuasi simulasi yang dekat dengan pusat kota.', 105.2615707, -5.4291549],
            ['GOR Saburai', 'aula', 'Kompleks GOR Saburai, Enggal', 'Enggal', 'Enggal', 600, 'aula luas,toilet,parkir,pos kesehatan', 'Koordinator GOR', '0812-7300-2102', 'aktif', 'Titik evakuasi simulasi berkapasitas besar untuk area pusat kota.', 105.2598, -5.4218],
            ['Kantor Kecamatan Teluk Betung Selatan', 'gedung_pemerintah', 'Area kantor kecamatan Teluk Betung Selatan', 'Teluk Betung Selatan', 'Pesawahan', 180, 'aula,toilet,ruang koordinasi', 'Petugas Kecamatan', '0812-7300-2103', 'aktif', 'Titik evakuasi simulasi untuk warga sekitar Teluk Betung Selatan.', 105.2591, -5.4485],
            ['Kandidat Evakuasi Panjang Utara - Simulasi', 'aula', 'Area Panjang Utara sekitar Jalan Yos Soedarso', 'Panjang', 'Panjang Utara', 220, 'aula,toilet,dapur umum', 'Koordinator Titik Evakuasi', '0812-7300-2104', 'aktif', 'Kandidat titik evakuasi simulasi untuk wilayah Panjang dan sekitar pelabuhan.', 105.3229645, -5.4721335],
            ['Kandidat Evakuasi Sukarame - Simulasi', 'aula', 'Area Sukarame', 'Sukarame', 'Sukarame', 120, 'aula,toilet,ruang tunggu', 'Koordinator Titik Evakuasi', '0812-7300-2105', 'aktif', 'Kandidat titik evakuasi simulasi untuk area Sukarame.', 105.2946540, -5.3974767],
            ['SDN Simulasi Way Halim', 'sekolah', 'Area sekolah Way Halim Permai', 'Way Halim', 'Way Halim Permai', 250, 'ruang kelas,toilet,halaman', 'Koordinator Sekolah', '0812-7300-2106', 'aktif', 'Titik evakuasi simulasi untuk area permukiman Way Halim.', 105.2746909, -5.3823404],
            ['Lapangan Enggal', 'lapangan', 'Area lapangan terbuka Enggal', 'Enggal', 'Enggal', 500, 'area terbuka,parkir,posko tenda', 'Petugas Lapangan', '0812-7300-2107', 'aktif', 'Titik kumpul simulasi untuk evakuasi sementara.', 105.2593, -5.4175],
            ['Balai Warga Rajabasa - Simulasi', 'gedung_pemerintah', 'Area Rajabasa', 'Rajabasa', 'Rajabasa', 160, 'aula,toilet,ruang logistik', 'Petugas Kelurahan', '0812-7300-2108', 'penuh', 'Titik evakuasi simulasi dengan status penuh untuk variasi demo.', 105.2297280, -5.3627526],
            ['Masjid Simulasi Kemiling', 'masjid', 'Area Kemiling Permai', 'Kemiling', 'Kemiling Permai', 200, 'aula,toilet,tempat ibadah', 'Pengurus Masjid', '0812-7300-2109', 'aktif', 'Titik evakuasi simulasi untuk area Kemiling.', 105.2224429, -5.3760226],
            ['Balai Warga Kedamaian', 'gedung_pemerintah', 'Area Balai Warga Kedamaian', 'Kedamaian', 'Kedamaian', 170, 'aula,toilet,dapur umum', 'Petugas Kelurahan', '0812-7300-2110', 'aktif', 'Titik evakuasi simulasi untuk skenario genangan Kedamaian.', 105.2815, -5.4100],
        ];

        foreach ($points as [$name, $type, $address, $district, $subdistrict, $capacity, $facilities, $contactPerson, $contactPhone, $status, $description, $longitude, $latitude]) {
            $this->insertPoint('evacuation_points', [
                'name' => $name,
                'type' => $type,
                'address' => $address,
                'district' => $district,
                'subdistrict' => $subdistrict,
                'capacity' => $capacity,
                'facilities' => $facilities,
                'contact_person' => $contactPerson,
                'contact_phone' => $contactPhone,
                'status' => $status,
                'description' => $description,
                'source_type' => 'admin_input',
                'source_reference' => self::SOURCE_REFERENCE,
                'is_verified' => false,
                'data_status' => 'simulasi',
                'created_at' => $now,
                'updated_at' => $now,
            ], $longitude, $latitude);
        }
    }

    private function insertPoint(string $table, array $row, float $longitude, float $latitude): void
    {
        $columns = array_keys($row);
        $columnSql = collect($columns)->map(fn (string $column): string => "\"{$column}\"")->implode(', ');
        $placeholders = collect($columns)->map(fn (): string => '?')->implode(', ');

        DB::insert(
            "INSERT INTO {$table} ({$columnSql}, geom) VALUES ({$placeholders}, ST_SetSRID(ST_MakePoint(?, ?), 4326))",
            [...array_values($row), $longitude, $latitude],
        );
    }
}
