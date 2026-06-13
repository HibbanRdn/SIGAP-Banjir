<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class FloodRiskPointSeeder extends Seeder
{
    private const ADMIN_EMAIL = 'hibbanrdn@gmail.com';

    private const SOURCE_REFERENCE = 'Agustri & Asbi (2020), Tingkat Risiko Bencana Banjir di Kota Bandar Lampung dan Upaya Pengurangannya Berbasis Penataan Ruang';

    private const CENTROID_NOTE = 'Titik merupakan representasi/centroid area kelurahan berdasarkan hasil kajian risiko banjir, bukan batas polygon risiko resmi.';

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
            ->whereIn('data_status', ['dummy', 'simulasi'])
            ->delete();

        $now = now();

        foreach ($this->points() as $point) {
            $longitude = $point['longitude'];
            $latitude = $point['latitude'];
            unset($point['longitude'], $point['latitude']);

            $this->upsertPoint('flood_risk_points', [
                ...$point,
                'source_type' => 'jurnal',
                'source_reference' => self::SOURCE_REFERENCE,
                'is_verified' => true,
                'data_status' => 'nyata',
                'created_by' => $adminId,
                'created_at' => $point['created_at'] ?? $now,
                'updated_at' => $now,
            ], $longitude, $latitude);
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function points(): array
    {
        return [
            [
                'name' => 'Risiko Banjir Way Kandis',
                'address' => 'Kelurahan Way Kandis',
                'district' => 'Tanjung Senang',
                'subdistrict' => 'Way Kandis',
                'risk_level' => 'tinggi',
                'description' => 'Area permukiman risiko tinggi berdasarkan jurnal, luasan sekitar 143,19 ha. '.self::CENTROID_NOTE,
                'longitude' => 105.2920,
                'latitude' => -5.3608,
            ],
            [
                'name' => 'Risiko Banjir Sukabumi',
                'address' => 'Kelurahan Sukabumi',
                'district' => 'Sukabumi',
                'subdistrict' => 'Sukabumi',
                'risk_level' => 'tinggi',
                'description' => 'Area permukiman risiko tinggi berdasarkan jurnal, luasan sekitar 136,80 ha. '.self::CENTROID_NOTE,
                'longitude' => 105.3110,
                'latitude' => -5.4105,
            ],
            [
                'name' => 'Risiko Banjir Bumi Kedamaian',
                'address' => 'Kelurahan Bumi Kedamaian',
                'district' => 'Kedamaian',
                'subdistrict' => 'Bumi Kedamaian',
                'risk_level' => 'tinggi',
                'description' => 'Area sempadan sungai dan permukiman risiko tinggi; terkait Sungai Kalibalok menurut pembahasan jurnal. '.self::CENTROID_NOTE,
                'longitude' => 105.2860,
                'latitude' => -5.3940,
            ],
            [
                'name' => 'Risiko Banjir Rajabasa Jaya',
                'address' => 'Kelurahan Rajabasa Jaya',
                'district' => 'Rajabasa',
                'subdistrict' => 'Rajabasa Jaya',
                'risk_level' => 'tinggi',
                'description' => 'Disebut dalam jurnal sebagai kelurahan dengan tingkat risiko tinggi terluas. '.self::CENTROID_NOTE,
                'longitude' => 105.2350,
                'latitude' => -5.3585,
            ],
            [
                'name' => 'Risiko Banjir Bumi Waras',
                'address' => 'Kelurahan Bumi Waras',
                'district' => 'Bumi Waras',
                'subdistrict' => 'Bumi Waras',
                'risk_level' => 'tinggi',
                'description' => 'Area sempadan pantai risiko tinggi berdasarkan jurnal. '.self::CENTROID_NOTE,
                'longitude' => 105.2680,
                'latitude' => -5.4395,
            ],
            [
                'name' => 'Risiko Banjir Kangkung',
                'address' => 'Kelurahan Kangkung',
                'district' => 'Bumi Waras',
                'subdistrict' => 'Kangkung',
                'risk_level' => 'tinggi',
                'description' => 'Area sempadan pantai risiko tinggi berdasarkan jurnal. '.self::CENTROID_NOTE,
                'longitude' => 105.2600,
                'latitude' => -5.4470,
            ],
            [
                'name' => 'Risiko Banjir Way Tataan',
                'address' => 'Kelurahan Way Tataan',
                'district' => 'Teluk Betung Timur',
                'subdistrict' => 'Way Tataan',
                'risk_level' => 'tinggi',
                'description' => 'Area sempadan pantai risiko tinggi berdasarkan jurnal. '.self::CENTROID_NOTE,
                'longitude' => 105.2910,
                'latitude' => -5.4730,
            ],
            [
                'name' => 'Risiko Banjir Gedong Pakuan',
                'address' => 'Kelurahan Gedong Pakuan',
                'district' => 'Teluk Betung Selatan',
                'subdistrict' => 'Gedong Pakuan',
                'risk_level' => 'tinggi',
                'description' => 'Area sempadan sungai risiko tinggi berdasarkan jurnal. '.self::CENTROID_NOTE,
                'longitude' => 105.2560,
                'latitude' => -5.4380,
            ],
            [
                'name' => 'Risiko Banjir Pesawahan',
                'address' => 'Kelurahan Pesawahan',
                'district' => 'Teluk Betung Selatan',
                'subdistrict' => 'Pesawahan',
                'risk_level' => 'tinggi',
                'description' => 'Area sempadan sungai/pantai risiko tinggi berdasarkan jurnal. '.self::CENTROID_NOTE,
                'longitude' => 105.2490,
                'latitude' => -5.4370,
            ],
            [
                'name' => 'Risiko Banjir Sumberejo Sejahtera',
                'address' => 'Kelurahan Sumberejo Sejahtera',
                'district' => 'Kemiling',
                'subdistrict' => 'Sumberejo Sejahtera',
                'risk_level' => 'tinggi',
                'description' => 'Area permukiman risiko tinggi berdasarkan jurnal. '.self::CENTROID_NOTE,
                'longitude' => 105.2230,
                'latitude' => -5.3930,
            ],
            [
                'name' => 'Risiko Banjir Kampung Baru',
                'address' => 'Kelurahan Kampung Baru',
                'district' => 'Labuhan Ratu',
                'subdistrict' => 'Kampung Baru',
                'risk_level' => 'tinggi',
                'description' => 'Area permukiman risiko tinggi; jurnal juga membahas Kampung Baru sebagai permukiman berkepadatan tinggi. '.self::CENTROID_NOTE,
                'longitude' => 105.2450,
                'latitude' => -5.3720,
            ],
            [
                'name' => 'Risiko Banjir Kota Karang Raya',
                'address' => 'Kelurahan Kota Karang Raya',
                'district' => 'Teluk Betung Timur',
                'subdistrict' => 'Kota Karang Raya',
                'risk_level' => 'tinggi',
                'description' => 'Area sempadan pantai dan permukiman risiko tinggi berdasarkan jurnal. '.self::CENTROID_NOTE,
                'longitude' => 105.2550,
                'latitude' => -5.4630,
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function upsertPoint(string $table, array $row, float $longitude, float $latitude): void
    {
        $existingId = DB::table($table)->where('name', $row['name'])->value('id');

        if ($existingId) {
            $columns = array_keys($row);
            $setSql = collect($columns)
                ->map(fn (string $column): string => "\"{$column}\" = ?")
                ->implode(', ');

            DB::update(
                "UPDATE {$table} SET {$setSql}, geom = ST_SetSRID(ST_MakePoint(?, ?), 4326) WHERE id = ?",
                [...array_values($row), $longitude, $latitude, $existingId],
            );

            return;
        }

        $columns = array_keys($row);
        $columnSql = collect($columns)->map(fn (string $column): string => "\"{$column}\"")->implode(', ');
        $placeholders = collect($columns)->map(fn (): string => '?')->implode(', ');

        DB::insert(
            "INSERT INTO {$table} ({$columnSql}, geom) VALUES ({$placeholders}, ST_SetSRID(ST_MakePoint(?, ?), 4326))",
            [...array_values($row), $longitude, $latitude],
        );
    }
}
