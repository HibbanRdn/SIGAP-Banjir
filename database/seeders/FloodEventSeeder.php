<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class FloodEventSeeder extends Seeder
{
    private const ADMIN_EMAIL = 'hibbanrdn@gmail.com';

    private const DETIK_ANTARA_URL = 'https://news.detik.com/berita/d-8445003/banjir-terjang-16-kecamatan-di-bandar-lampung-1-orang-meninggal';

    private const RILIS_34_TITIK_URL = 'https://lampung.rilis.id/Breaking%20News/Berita/banjir-rendam-34-titik-di-bandar-lampung-1-warga-36kR?page=1';

    private const RILIS_KEDATON_URL = 'https://lampung.rilis.id/Peristiwa/Berita/bandar-lampung-dikepung-banjir-di-kedaton-kEg1';

    private const RILIS_KEDATON_PAGE_2_URL = 'https://lampung.rilis.id/Peristiwa/Berita/bandar-lampung-dikepung-banjir-di-kedaton-kEg1?page=2';

    private const BONGKAR_POST_URL = 'https://bacabongkarpost.com/banjir-rendam-bandar-lampung-akibat-hujan-deras-aktivitas-warga-terganggu/';

    private const COORDINATE_NOTE = 'Kejadian banjir merupakan data nyata berbasis pemberitaan media. Koordinat merupakan hasil geocoding/plotting lokasi jalan atau area yang disebut dalam berita, sehingga presisi titik masih dapat disempurnakan dengan survei GPS lapangan.';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminId = DB::table('users')->where('email', self::ADMIN_EMAIL)->value('id');

        if (! $adminId) {
            throw new RuntimeException('Admin utama belum tersedia. Jalankan AdminUserSeeder terlebih dahulu.');
        }

        DB::table('flood_events')
            ->whereIn('data_status', ['dummy', 'simulasi'])
            ->delete();

        $now = now();

        foreach ($this->events() as $event) {
            $longitude = $event['longitude'];
            $latitude = $event['latitude'];
            unset($event['longitude'], $event['latitude']);

            $this->upsertPoint('flood_events', [
                ...$event,
                'source_type' => 'berita',
                'is_verified' => true,
                'data_status' => 'nyata',
                'created_by' => $adminId,
                'created_at' => $event['created_at'] ?? $now,
                'updated_at' => $now,
            ], $longitude, $latitude);
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function events(): array
    {
        return [
            [
                'name' => 'Banjir Depan RSUD Abdul Moeloek',
                'address' => 'Depan RSUD Abdul Moeloek / jalan protokol',
                'district' => 'Enggal',
                'subdistrict' => 'Enggal',
                'severity_level' => 'tinggi',
                'water_depth_cm' => 60,
                'status' => 'surut',
                'description' => 'Genangan tinggi dilaporkan di depan RSUD Abdul Moeloek saat banjir 14 April 2026. '.self::COORDINATE_NOTE,
                'source_reference' => implode(' | ', [self::DETIK_ANTARA_URL, self::RILIS_KEDATON_URL, self::BONGKAR_POST_URL]),
                'occurred_at' => '2026-04-14 18:30:00',
                'reported_at' => '2026-04-15 10:00:00',
                'longitude' => 105.2601,
                'latitude' => -5.4160,
            ],
            [
                'name' => 'Banjir Jalan Dokter Sutomo Penengahan',
                'address' => 'Jalan Dokter Sutomo, Kelurahan Penengahan RT 005 LK 01',
                'district' => 'Kedaton',
                'subdistrict' => 'Penengahan',
                'severity_level' => 'kritis',
                'water_depth_cm' => 120,
                'status' => 'surut',
                'description' => 'Warga dilaporkan terjebak di rumah akibat debit air meningkat cepat, dengan ketinggian air mencapai dada orang dewasa. '.self::COORDINATE_NOTE,
                'source_reference' => self::RILIS_KEDATON_URL,
                'occurred_at' => '2026-04-14 18:30:00',
                'reported_at' => '2026-04-14 23:49:00',
                'longitude' => 105.2638,
                'latitude' => -5.3948,
            ],
            [
                'name' => 'Genangan Jalan Pangeran Antasari - Transmart',
                'address' => 'Koridor Jalan Pangeran Antasari hingga kawasan Transmart',
                'district' => 'Way Halim',
                'subdistrict' => 'Way Halim Permai',
                'severity_level' => 'tinggi',
                'water_depth_cm' => 50,
                'status' => 'surut',
                'description' => 'Genangan dilaporkan terjadi di sepanjang Jalan Pangeran Antasari hingga kawasan Transmart. '.self::COORDINATE_NOTE,
                'source_reference' => implode(' | ', [self::RILIS_KEDATON_PAGE_2_URL, self::BONGKAR_POST_URL]),
                'occurred_at' => '2026-04-14 18:30:00',
                'reported_at' => '2026-04-14 23:49:00',
                'longitude' => 105.2878,
                'latitude' => -5.3987,
            ],
            [
                'name' => 'Banjir Kaliawi - Kelapa Tiga',
                'address' => 'Kaliawi / Kelapa Tiga',
                'district' => 'Tanjung Karang Barat',
                'subdistrict' => 'Kaliawi / Kelapa Tiga',
                'severity_level' => 'tinggi',
                'water_depth_cm' => 70,
                'status' => 'surut',
                'description' => 'Kaliawi dan Kelapa Tiga disebut sebagai kawasan terdampak banjir 14 April 2026. Koordinat merupakan titik representatif area. '.self::COORDINATE_NOTE,
                'source_reference' => implode(' | ', [self::RILIS_KEDATON_URL, self::BONGKAR_POST_URL]),
                'occurred_at' => '2026-04-14 18:30:00',
                'reported_at' => '2026-04-14 23:49:00',
                'longitude' => 105.2460,
                'latitude' => -5.4108,
            ],
            [
                'name' => 'Genangan Jalan Teuku Umar',
                'address' => 'Jalan Teuku Umar',
                'district' => 'Kedaton',
                'subdistrict' => 'Kedaton',
                'severity_level' => 'sedang',
                'water_depth_cm' => 40,
                'status' => 'surut',
                'description' => 'Jalan Teuku Umar disebut sebagai salah satu jalan protokol yang tergenang saat banjir 14 April 2026. '.self::COORDINATE_NOTE,
                'source_reference' => self::BONGKAR_POST_URL,
                'occurred_at' => '2026-04-14 18:30:00',
                'reported_at' => '2026-04-14 22:00:00',
                'longitude' => 105.2578,
                'latitude' => -5.3865,
            ],
            [
                'name' => 'Genangan Jalan Ki Maja',
                'address' => 'Jalan Ki Maja',
                'district' => 'Way Halim',
                'subdistrict' => 'Way Halim Permai',
                'severity_level' => 'sedang',
                'water_depth_cm' => 40,
                'status' => 'surut',
                'description' => 'Jalan Ki Maja disebut sebagai salah satu ruas terdampak genangan saat banjir 14 April 2026. '.self::COORDINATE_NOTE,
                'source_reference' => self::BONGKAR_POST_URL,
                'occurred_at' => '2026-04-14 18:30:00',
                'reported_at' => '2026-04-14 22:00:00',
                'longitude' => 105.2795,
                'latitude' => -5.3848,
            ],
            [
                'name' => 'Genangan Jalan Antasari',
                'address' => 'Jalan Pangeran Antasari',
                'district' => 'Kedamaian',
                'subdistrict' => 'Kedamaian',
                'severity_level' => 'sedang',
                'water_depth_cm' => 50,
                'status' => 'surut',
                'description' => 'Jalan Antasari disebut sebagai salah satu ruas jalan protokol terdampak genangan. '.self::COORDINATE_NOTE,
                'source_reference' => self::BONGKAR_POST_URL,
                'occurred_at' => '2026-04-14 18:30:00',
                'reported_at' => '2026-04-14 22:00:00',
                'longitude' => 105.2930,
                'latitude' => -5.4075,
            ],
            [
                'name' => 'Banjir Jalan Pandawa Garuntang',
                'address' => 'Jalan Pandawa, Kelurahan Garuntang',
                'district' => 'Bumi Waras',
                'subdistrict' => 'Garuntang',
                'severity_level' => 'kritis',
                'water_depth_cm' => null,
                'status' => 'surut',
                'description' => 'Lokasi warga yang dilaporkan meninggal pada kejadian banjir 14 April 2026. Koordinat merupakan titik representatif area Jalan Pandawa, Garuntang. '.self::COORDINATE_NOTE,
                'source_reference' => self::RILIS_34_TITIK_URL,
                'occurred_at' => '2026-04-14 18:30:00',
                'reported_at' => '2026-04-15 10:28:00',
                'longitude' => 105.2800,
                'latitude' => -5.4305,
            ],
            [
                'name' => 'Banjir Sukarame',
                'address' => 'Wilayah Sukarame terdampak banjir',
                'district' => 'Sukarame',
                'subdistrict' => 'Sukarame',
                'severity_level' => 'sedang',
                'water_depth_cm' => null,
                'status' => 'surut',
                'description' => 'Sukarame disebut sebagai salah satu kecamatan/kawasan terdampak banjir 14 April 2026. '.self::COORDINATE_NOTE,
                'source_reference' => implode(' | ', [self::DETIK_ANTARA_URL, self::RILIS_34_TITIK_URL, self::BONGKAR_POST_URL]),
                'occurred_at' => '2026-04-14 18:30:00',
                'reported_at' => '2026-04-15 11:54:00',
                'longitude' => 105.3130,
                'latitude' => -5.3850,
            ],
            [
                'name' => 'Banjir Rajabasa',
                'address' => 'Wilayah Rajabasa terdampak banjir',
                'district' => 'Rajabasa',
                'subdistrict' => 'Rajabasa',
                'severity_level' => 'sedang',
                'water_depth_cm' => null,
                'status' => 'surut',
                'description' => 'Rajabasa disebut sebagai salah satu kawasan terdampak banjir 14 April 2026. '.self::COORDINATE_NOTE,
                'source_reference' => implode(' | ', [self::DETIK_ANTARA_URL, self::BONGKAR_POST_URL]),
                'occurred_at' => '2026-04-14 18:30:00',
                'reported_at' => '2026-04-15 11:54:00',
                'longitude' => 105.2325,
                'latitude' => -5.3660,
            ],
            [
                'name' => 'Banjir Teluk Betung',
                'address' => 'Wilayah Teluk Betung terdampak banjir',
                'district' => 'Teluk Betung Selatan',
                'subdistrict' => 'Teluk Betung',
                'severity_level' => 'sedang',
                'water_depth_cm' => null,
                'status' => 'surut',
                'description' => 'Teluk Betung disebut sebagai salah satu kawasan terdampak banjir 14 April 2026. '.self::COORDINATE_NOTE,
                'source_reference' => implode(' | ', [self::DETIK_ANTARA_URL, self::RILIS_34_TITIK_URL, self::BONGKAR_POST_URL]),
                'occurred_at' => '2026-04-14 18:30:00',
                'reported_at' => '2026-04-15 11:54:00',
                'longitude' => 105.2580,
                'latitude' => -5.4450,
            ],
            [
                'name' => 'Banjir Panjang',
                'address' => 'Wilayah Panjang terdampak banjir',
                'district' => 'Panjang',
                'subdistrict' => 'Panjang',
                'severity_level' => 'sedang',
                'water_depth_cm' => null,
                'status' => 'surut',
                'description' => 'Panjang disebut sebagai salah satu kecamatan terdampak hujan dan banjir pada 14 April 2026. '.self::COORDINATE_NOTE,
                'source_reference' => implode(' | ', [self::DETIK_ANTARA_URL, self::RILIS_34_TITIK_URL]),
                'occurred_at' => '2026-04-14 18:30:00',
                'reported_at' => '2026-04-15 11:54:00',
                'longitude' => 105.3210,
                'latitude' => -5.4680,
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
