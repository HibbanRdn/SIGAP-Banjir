<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HeavyEquipmentPostSeeder extends Seeder
{
    private const SOURCE_REFERENCE = 'Seeder demo SIGAP Banjir';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('heavy_equipment_posts')
            ->where('source_reference', self::SOURCE_REFERENCE)
            ->delete();

        $now = now();

        $posts = [
            [
                'name' => 'Pos Alat Berat Panjang',
                'address' => 'Area operasional sekitar Pelabuhan Panjang',
                'district' => 'Panjang',
                'subdistrict' => 'Panjang Utara',
                'contact_person' => 'Koordinator Pos Panjang',
                'contact_phone' => '0812-7300-1101',
                'status' => 'aktif',
                'description' => 'Pos dummy realistis untuk dukungan respons banjir wilayah Panjang dan sekitarnya.',
                'longitude' => 105.3262,
                'latitude' => -5.4669,
            ],
            [
                'name' => 'Pos Alat Berat Teluk Betung',
                'address' => 'Area operasional Teluk Betung Selatan',
                'district' => 'Teluk Betung Selatan',
                'subdistrict' => 'Pesawahan',
                'contact_person' => 'Koordinator Pos Teluk Betung',
                'contact_phone' => '0812-7300-1102',
                'status' => 'aktif',
                'description' => 'Pos dummy realistis untuk wilayah pesisir dan pusat Teluk Betung.',
                'longitude' => 105.2590,
                'latitude' => -5.4442,
            ],
            [
                'name' => 'Pos Alat Berat Rajabasa',
                'address' => 'Area operasional sekitar Rajabasa Nunyai',
                'district' => 'Rajabasa',
                'subdistrict' => 'Rajabasa Nunyai',
                'contact_person' => 'Koordinator Pos Rajabasa',
                'contact_phone' => '0812-7300-1103',
                'status' => 'aktif',
                'description' => 'Pos dummy realistis untuk respons wilayah Rajabasa dan Labuhan Ratu.',
                'longitude' => 105.2290,
                'latitude' => -5.3729,
            ],
            [
                'name' => 'Pos Alat Berat Way Halim',
                'address' => 'Area operasional Way Halim Permai',
                'district' => 'Way Halim',
                'subdistrict' => 'Way Halim Permai',
                'contact_person' => 'Koordinator Pos Way Halim',
                'contact_phone' => '0812-7300-1104',
                'status' => 'aktif',
                'description' => 'Pos dummy realistis untuk dukungan respons genangan perkotaan.',
                'longitude' => 105.2896,
                'latitude' => -5.3912,
            ],
            [
                'name' => 'Pos Alat Berat Sukarame',
                'address' => 'Area operasional sekitar Korpri Sukarame',
                'district' => 'Sukarame',
                'subdistrict' => 'Korpri Raya',
                'contact_person' => 'Koordinator Pos Sukarame',
                'contact_phone' => '0812-7300-1105',
                'status' => 'aktif',
                'description' => 'Pos dummy realistis untuk wilayah Sukarame dan Kedamaian.',
                'longitude' => 105.3068,
                'latitude' => -5.3812,
            ],
            [
                'name' => 'Pos Alat Berat Kemiling',
                'address' => 'Area operasional Kemiling Permai',
                'district' => 'Kemiling',
                'subdistrict' => 'Kemiling Permai',
                'contact_person' => 'Koordinator Pos Kemiling',
                'contact_phone' => '0812-7300-1106',
                'status' => 'tidak_aktif',
                'description' => 'Pos dummy realistis dengan status tidak aktif untuk variasi demo.',
                'longitude' => 105.2140,
                'latitude' => -5.3965,
            ],
        ];

        foreach ($posts as $post) {
            $longitude = $post['longitude'];
            $latitude = $post['latitude'];
            unset($post['longitude'], $post['latitude']);

            $this->insertPoint('heavy_equipment_posts', [
                ...$post,
                'source_type' => 'dummy',
                'source_reference' => self::SOURCE_REFERENCE,
                'is_verified' => false,
                'data_status' => 'dummy',
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
