<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EvacuationPointSeeder extends Seeder
{
    private const SOURCE_REFERENCE = 'Seeder demo SIGAP Banjir';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('evacuation_points')
            ->where('source_reference', self::SOURCE_REFERENCE)
            ->delete();

        $now = now();

        $points = [
            ['Masjid Al-Furqon Lungsir', 'masjid', 'Area Lungsir, Tanjung Karang Pusat', 'Tanjung Karang Pusat', 'Lungsir', 300, 'aula,toilet,tempat ibadah,parkir', 'Pengurus Masjid', '0812-7300-2101', 'aktif', 'Titik evakuasi simulasi yang dekat dengan pusat kota.', 105.2635, -5.4230],
            ['GOR Saburai', 'aula', 'Kompleks GOR Saburai, Enggal', 'Enggal', 'Enggal', 600, 'aula luas,toilet,parkir,pos kesehatan', 'Koordinator GOR', '0812-7300-2102', 'aktif', 'Titik evakuasi simulasi berkapasitas besar untuk area pusat kota.', 105.2598, -5.4218],
            ['Kantor Kecamatan Teluk Betung Selatan', 'gedung_pemerintah', 'Area kantor kecamatan Teluk Betung Selatan', 'Teluk Betung Selatan', 'Pesawahan', 180, 'aula,toilet,ruang koordinasi', 'Petugas Kecamatan', '0812-7300-2103', 'aktif', 'Titik evakuasi simulasi untuk warga sekitar Teluk Betung Selatan.', 105.2591, -5.4485],
            ['Aula Kecamatan Panjang', 'aula', 'Area Kecamatan Panjang', 'Panjang', 'Panjang Utara', 220, 'aula,toilet,dapur umum', 'Petugas Kecamatan', '0812-7300-2104', 'aktif', 'Titik evakuasi simulasi untuk wilayah Panjang dan sekitar pelabuhan.', 105.3314, -5.4682],
            ['Puskesmas Sukarame', 'puskesmas', 'Area layanan kesehatan Sukarame', 'Sukarame', 'Sukarame Baru', 120, 'layanan kesehatan,toilet,ruang tunggu', 'Petugas Puskesmas', '0812-7300-2105', 'aktif', 'Titik evakuasi simulasi dengan dukungan kesehatan dasar.', 105.3025, -5.3775],
            ['SDN Simulasi Way Halim', 'sekolah', 'Area sekolah Way Halim Permai', 'Way Halim', 'Way Halim Permai', 250, 'ruang kelas,toilet,halaman', 'Koordinator Sekolah', '0812-7300-2106', 'aktif', 'Titik evakuasi simulasi untuk area permukiman Way Halim.', 105.2878, -5.3890],
            ['Lapangan Enggal', 'lapangan', 'Area lapangan terbuka Enggal', 'Enggal', 'Enggal', 500, 'area terbuka,parkir,posko tenda', 'Petugas Lapangan', '0812-7300-2107', 'aktif', 'Titik kumpul simulasi untuk evakuasi sementara.', 105.2593, -5.4175],
            ['Balai Warga Rajabasa Nunyai', 'gedung_pemerintah', 'Area Rajabasa Nunyai', 'Rajabasa', 'Rajabasa Nunyai', 160, 'aula,toilet,ruang logistik', 'Petugas Kelurahan', '0812-7300-2108', 'penuh', 'Titik evakuasi simulasi dengan status penuh untuk variasi demo.', 105.2299, -5.3710],
            ['Masjid Simulasi Kemiling', 'masjid', 'Area Kemiling Permai', 'Kemiling', 'Kemiling Permai', 200, 'aula,toilet,tempat ibadah', 'Pengurus Masjid', '0812-7300-2109', 'aktif', 'Titik evakuasi simulasi untuk area Kemiling.', 105.2157, -5.3971],
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
