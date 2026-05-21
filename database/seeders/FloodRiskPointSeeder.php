<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class FloodRiskPointSeeder extends Seeder
{
    private const ADMIN_EMAIL = 'hibbanrdn@gmail.com';

    private const SOURCE_REFERENCE = 'Seeder demo SIGAP Banjir';

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
            ->where('source_reference', self::SOURCE_REFERENCE)
            ->delete();

        $now = now();

        $points = [
            ['Rawan Banjir Way Halim', 'Koridor permukiman Way Halim Permai', 'Way Halim', 'Way Halim Permai', 'tinggi', 'Area simulasi rawan genangan saat hujan deras dan drainase meluap.', 105.2886, -5.3897],
            ['Rawan Banjir Teluk Betung Selatan', 'Sekitar Pesawahan dan akses pesisir', 'Teluk Betung Selatan', 'Pesawahan', 'tinggi', 'Area simulasi rawan banjir rob dan genangan hujan intensitas tinggi.', 105.2608, -5.4469],
            ['Rawan Banjir Panjang Utara', 'Koridor akses Pelabuhan Panjang', 'Panjang', 'Panjang Utara', 'tinggi', 'Area simulasi rawan genangan pada akses jalan rendah.', 105.3284, -5.4661],
            ['Rawan Banjir Rajabasa Nunyai', 'Permukiman sekitar Rajabasa Nunyai', 'Rajabasa', 'Rajabasa Nunyai', 'sedang', 'Area simulasi dengan potensi limpasan saat hujan deras.', 105.2302, -5.3722],
            ['Rawan Banjir Korpri Sukarame', 'Area Korpri Raya Sukarame', 'Sukarame', 'Korpri Raya', 'tinggi', 'Area simulasi rawan genangan pada permukiman padat.', 105.3052, -5.3838],
            ['Rawan Banjir Kedamaian', 'Koridor Kedamaian dan sekitarnya', 'Kedamaian', 'Kedamaian', 'sedang', 'Area simulasi rawan genangan lokal di jalan penghubung.', 105.2816, -5.4095],
            ['Rawan Banjir Labuhan Ratu', 'Area Labuhan Ratu Raya', 'Labuhan Ratu', 'Labuhan Ratu Raya', 'sedang', 'Area simulasi dengan potensi genangan pada titik rendah.', 105.2448, -5.3756],
            ['Rawan Banjir Tanjung Karang Timur', 'Koridor jalan permukiman Tanjung Karang Timur', 'Tanjung Karang Timur', 'Kota Baru', 'sedang', 'Area simulasi rawan limpasan dari saluran perkotaan.', 105.2779, -5.4163],
            ['Rawan Banjir Bumi Waras', 'Area Bumi Waras dekat pesisir', 'Bumi Waras', 'Bumi Waras', 'tinggi', 'Area simulasi rawan genangan pesisir dan drainase padat.', 105.2701, -5.4364],
            ['Rawan Banjir Enggal', 'Area pusat kota Enggal', 'Enggal', 'Enggal', 'rendah', 'Area simulasi rawan genangan ringan saat hujan puncak.', 105.2597, -5.4189],
            ['Rawan Banjir Kemiling Permai', 'Permukiman Kemiling Permai', 'Kemiling', 'Kemiling Permai', 'rendah', 'Area simulasi pemantauan genangan lokal.', 105.2149, -5.3970],
            ['Rawan Banjir Teluk Betung Timur', 'Koridor akses Teluk Betung Timur', 'Teluk Betung Timur', 'Keteguhan', 'sedang', 'Area simulasi rawan limpasan menuju wilayah pesisir.', 105.2787, -5.4555],
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
