<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class FloodRiskPointSeeder extends Seeder
{
    private const ADMIN_EMAIL = 'hibbanrdn@gmail.com';

    private const SOURCE_REFERENCE = 'Dataset simulasi pengembangan SIGAP Banjir; lokasi area diverifikasi melalui peta publik OpenStreetMap/Nominatim, tingkat risiko merupakan skenario uji aplikasi.';

    private const LEGACY_SOURCE_REFERENCES = [
        'Seeder demo SIGAP Banjir',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminId = DB::table('users')->where('email', self::ADMIN_EMAIL)->value('id');

        if (! $adminId) {
            throw new RuntimeException('Admin utama belum tersedia. Jalankan AdminUserSeeder terlebih dahulu.');
        }

        DB::table('flood_risk_points')
            ->whereIn('source_reference', array_merge([self::SOURCE_REFERENCE], self::LEGACY_SOURCE_REFERENCES))
            ->delete();

        $now = now();

        $points = [
            ['Rawan Banjir Way Halim', 'Koridor permukiman Way Halim Permai', 'Way Halim', 'Way Halim Permai', 'tinggi', 'Area simulasi rawan genangan saat hujan deras dan drainase meluap.', 105.2746909, -5.3823404],
            ['Rawan Banjir Teluk Betung Selatan', 'Sekitar Pesawahan dan akses pesisir', 'Teluk Betung Selatan', 'Pesawahan', 'tinggi', 'Area simulasi rawan banjir rob dan genangan hujan intensitas tinggi.', 105.2608, -5.4469],
            ['Rawan Banjir Panjang Utara', 'Koridor akses Pelabuhan Panjang', 'Panjang', 'Panjang Utara', 'tinggi', 'Area simulasi rawan genangan pada akses jalan rendah.', 105.3229645, -5.4721335],
            ['Rawan Banjir Rajabasa', 'Permukiman sekitar Rajabasa', 'Rajabasa', 'Rajabasa', 'sedang', 'Area simulasi dengan potensi limpasan saat hujan deras.', 105.2297280, -5.3627526],
            ['Rawan Banjir Sukarame', 'Area Sukarame', 'Sukarame', 'Sukarame', 'tinggi', 'Area simulasi rawan genangan pada permukiman padat.', 105.2946540, -5.3974767],
            ['Rawan Banjir Kedamaian', 'Koridor Kedamaian dan sekitarnya', 'Kedamaian', 'Kedamaian', 'sedang', 'Area simulasi rawan genangan lokal di jalan penghubung.', 105.2816, -5.4095],
            ['Rawan Banjir Labuhan Ratu', 'Area Labuhan Ratu Raya', 'Labuhan Ratu', 'Labuhan Ratu Raya', 'sedang', 'Area simulasi dengan potensi genangan pada titik rendah.', 105.2448, -5.3756],
            ['Rawan Banjir Tanjung Karang Timur', 'Koridor jalan permukiman Tanjung Karang Timur', 'Tanjung Karang Timur', 'Kota Baru', 'sedang', 'Area simulasi rawan limpasan dari saluran perkotaan.', 105.2779, -5.4163],
            ['Rawan Banjir Bumi Waras', 'Area Bumi Waras dekat pesisir', 'Bumi Waras', 'Bumi Waras', 'tinggi', 'Area simulasi rawan genangan pesisir dan drainase padat.', 105.2706967, -5.4486092],
            ['Rawan Banjir Enggal', 'Area pusat kota Enggal', 'Enggal', 'Enggal', 'rendah', 'Area simulasi rawan genangan ringan saat hujan puncak.', 105.2597, -5.4189],
            ['Rawan Banjir Kemiling Permai', 'Permukiman Kemiling Permai', 'Kemiling', 'Kemiling Permai', 'rendah', 'Area simulasi pemantauan genangan lokal.', 105.2224429, -5.3760226],
            ['Rawan Banjir Teluk Betung Timur', 'Koridor akses Teluk Betung Timur', 'Teluk Betung Timur', 'Keteguhan', 'sedang', 'Area simulasi rawan limpasan menuju wilayah pesisir.', 105.2452182, -5.4698660],
        ];

        foreach ($points as [$name, $address, $district, $subdistrict, $riskLevel, $description, $longitude, $latitude]) {
            $this->insertPoint('flood_risk_points', [
                'name' => $name,
                'address' => $address,
                'district' => $district,
                'subdistrict' => $subdistrict,
                'risk_level' => $riskLevel,
                'description' => $description,
                'source_type' => 'admin_input',
                'source_reference' => self::SOURCE_REFERENCE,
                'is_verified' => false,
                'data_status' => 'simulasi',
                'created_by' => $adminId,
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
