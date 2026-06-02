<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class FloodEventSeeder extends Seeder
{
    private const ADMIN_EMAIL = 'hibbanrdn@gmail.com';

    private const SOURCE_REFERENCE = 'Dataset simulasi pengembangan SIGAP Banjir; lokasi area diverifikasi melalui peta publik OpenStreetMap/Nominatim, kejadian banjir merupakan skenario uji aplikasi.';

    private const LEGACY_SOURCE_REFERENCES = [
        'Skenario demo SIGAP Banjir',
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

        DB::table('flood_events')
            ->whereIn('source_reference', array_merge([self::SOURCE_REFERENCE], self::LEGACY_SOURCE_REFERENCES))
            ->delete();

        $now = now();

        $events = [
            ['Banjir Teluk Betung Selatan', 'Genangan sekitar akses Pesawahan dan Teluk Betung Selatan', 'Teluk Betung Selatan', 'Pesawahan', 'kritis', 95, 'aktif', 'Skenario banjir aktif dengan genangan tinggi dan kebutuhan rekomendasi evakuasi cepat.', '2026-05-21 07:10:00', '2026-05-21 07:25:00', 105.2607, -5.4478],
            ['Genangan Way Halim', 'Genangan permukiman Way Halim Permai', 'Way Halim', 'Way Halim Permai', 'tinggi', 60, 'aktif', 'Skenario genangan aktif pada area perkotaan dengan kebutuhan pompa air.', '2026-05-21 08:05:00', '2026-05-21 08:20:00', 105.2746909, -5.3823404],
            ['Banjir Sukarame', 'Genangan area Sukarame', 'Sukarame', 'Sukarame', 'tinggi', 70, 'ditangani', 'Skenario kejadian sedang ditangani oleh pos terdekat.', '2026-05-20 17:40:00', '2026-05-20 18:00:00', 105.2946540, -5.3974767],
            ['Genangan Panjang Utara', 'Genangan akses logistik Panjang Utara', 'Panjang', 'Panjang Utara', 'sedang', 45, 'aktif', 'Skenario genangan pada akses jalan dekat kawasan pelabuhan.', '2026-05-21 06:30:00', '2026-05-21 06:50:00', 105.3229645, -5.4721335],
            ['Banjir Rajabasa', 'Genangan permukiman Rajabasa', 'Rajabasa', 'Rajabasa', 'sedang', 38, 'surut', 'Skenario kejadian yang mulai surut setelah hujan mereda.', '2026-05-20 15:15:00', '2026-05-20 15:35:00', 105.2297280, -5.3627526],
            ['Genangan Kedamaian', 'Genangan lokal pada koridor Kedamaian', 'Kedamaian', 'Kedamaian', 'rendah', 25, 'ditangani', 'Skenario genangan ringan yang memerlukan pemantauan.', '2026-05-20 10:05:00', '2026-05-20 10:30:00', 105.2828, -5.4082],
            ['Banjir Bumi Waras', 'Genangan rendah sekitar Bumi Waras', 'Bumi Waras', 'Bumi Waras', 'tinggi', 80, 'arsip', 'Skenario historis/arsip untuk variasi status kejadian banjir.', '2026-05-18 19:20:00', '2026-05-18 19:45:00', 105.2706967, -5.4486092],
            ['Genangan Enggal', 'Genangan ringan pusat kota Enggal', 'Enggal', 'Enggal', 'rendah', 20, 'surut', 'Skenario genangan ringan yang sudah surut.', '2026-05-19 14:00:00', '2026-05-19 14:15:00', 105.2608294, -5.4198497],
        ];

        foreach ($events as [$name, $address, $district, $subdistrict, $severityLevel, $waterDepth, $status, $description, $occurredAt, $reportedAt, $longitude, $latitude]) {
            $this->insertPoint('flood_events', [
                'name' => $name,
                'address' => $address,
                'district' => $district,
                'subdistrict' => $subdistrict,
                'severity_level' => $severityLevel,
                'water_depth_cm' => $waterDepth,
                'status' => $status,
                'description' => $description,
                'source_type' => 'admin_input',
                'source_reference' => self::SOURCE_REFERENCE,
                'occurred_at' => $occurredAt,
                'reported_at' => $reportedAt,
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
