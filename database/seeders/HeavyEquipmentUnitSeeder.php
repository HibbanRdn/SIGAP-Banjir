<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class HeavyEquipmentUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $posts = DB::table('heavy_equipment_posts')
            ->whereIn('name', [
                'Pos Alat Berat Panjang',
                'Pos Alat Berat Teluk Betung',
                'Pos Alat Berat Rajabasa',
                'Pos Alat Berat Way Halim',
                'Pos Alat Berat Sukarame',
                'Pos Alat Berat Kemiling',
            ])
            ->pluck('id', 'name');

        $types = DB::table('equipment_types')->pluck('id', 'name');

        DB::table('heavy_equipment_units')
            ->whereIn('post_id', $posts->values())
            ->delete();

        $units = [
            ['Pos Alat Berat Panjang', 'pompa_air', 3, 2, 'tersedia', 'Prioritas genangan wilayah Panjang dan Bumi Waras.'],
            ['Pos Alat Berat Panjang', 'dump_truck', 2, 1, 'digunakan', 'Satu unit diasumsikan sedang mendukung pembersihan material.'],
            ['Pos Alat Berat Panjang', 'excavator', 1, 1, 'tersedia', 'Standby untuk pembersihan sedimentasi.'],
            ['Pos Alat Berat Teluk Betung', 'pompa_air', 2, 2, 'tersedia', 'Standby untuk genangan pesisir Teluk Betung.'],
            ['Pos Alat Berat Teluk Betung', 'pickup_operasional', 2, 1, 'tersedia', 'Kendaraan lapangan untuk petugas.'],
            ['Pos Alat Berat Rajabasa', 'excavator', 1, 1, 'tersedia', 'Dukungan pembukaan akses dan material.'],
            ['Pos Alat Berat Rajabasa', 'wheel_loader', 1, 0, 'perawatan', 'Unit sedang perawatan berkala.'],
            ['Pos Alat Berat Rajabasa', 'dump_truck', 2, 2, 'tersedia', 'Dukungan angkut material.'],
            ['Pos Alat Berat Way Halim', 'dump_truck', 3, 2, 'tersedia', 'Respons area perkotaan dan jalan utama.'],
            ['Pos Alat Berat Way Halim', 'pompa_air', 2, 1, 'digunakan', 'Satu unit diasumsikan sedang dipakai.'],
            ['Pos Alat Berat Sukarame', 'pickup_operasional', 2, 2, 'tersedia', 'Mobilitas petugas wilayah Sukarame.'],
            ['Pos Alat Berat Sukarame', 'mobil_tangki', 1, 1, 'tersedia', 'Dukungan air bersih pascabanjir.'],
            ['Pos Alat Berat Sukarame', 'pompa_air', 2, 2, 'tersedia', 'Dukungan genangan Korpri dan Kedamaian.'],
            ['Pos Alat Berat Kemiling', 'pickup_operasional', 1, 0, 'tidak_aktif', 'Pos tidak aktif pada skenario demo.'],
            ['Pos Alat Berat Kemiling', 'wheel_loader', 1, 0, 'tidak_aktif', 'Unit tidak tersedia karena pos tidak aktif.'],
        ];

        foreach ($units as [$postName, $typeName, $quantity, $availableQuantity, $status, $notes]) {
            if (! isset($posts[$postName], $types[$typeName])) {
                throw new RuntimeException("Data pos atau jenis alat belum tersedia: {$postName} / {$typeName}");
            }

            DB::table('heavy_equipment_units')->insert([
                'post_id' => $posts[$postName],
                'equipment_type_id' => $types[$typeName],
                'quantity' => $quantity,
                'available_quantity' => $availableQuantity,
                'status' => $status,
                'notes' => $notes,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
