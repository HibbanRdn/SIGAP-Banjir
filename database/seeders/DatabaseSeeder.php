<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            EquipmentTypeSeeder::class,
            HeavyEquipmentPostSeeder::class,
            HeavyEquipmentUnitSeeder::class,
            EvacuationPointSeeder::class,
            FloodRiskPointSeeder::class,
            FloodEventSeeder::class,
        ]);
    }
}
