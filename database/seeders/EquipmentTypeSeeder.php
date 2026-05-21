<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquipmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $equipmentTypes = [
            [
                'name' => 'excavator',
                'description' => 'Alat untuk pembersihan material, lumpur, dan sedimentasi setelah banjir.',
            ],
            [
                'name' => 'dump_truck',
                'description' => 'Kendaraan untuk mengangkut material, sampah, atau lumpur dari lokasi terdampak.',
            ],
            [
                'name' => 'wheel_loader',
                'description' => 'Alat untuk memindahkan material berat dalam volume besar.',
            ],
            [
                'name' => 'pompa_air',
                'description' => 'Alat untuk membantu penyedotan genangan pada area rendah.',
            ],
            [
                'name' => 'mobil_tangki',
                'description' => 'Kendaraan pendukung distribusi air bersih atau penyemprotan pascabanjir.',
            ],
            [
                'name' => 'pickup_operasional',
                'description' => 'Kendaraan operasional lapangan untuk petugas dan logistik ringan.',
            ],
        ];

        foreach ($equipmentTypes as $equipmentType) {
            DB::table('equipment_types')->updateOrInsert(
                ['name' => $equipmentType['name']],
                [
                    'description' => $equipmentType['description'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            );
        }
    }
}
