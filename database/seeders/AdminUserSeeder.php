<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = 'hibbanrdn@gmail.com';
        $now = now();

        $payload = [
            'name' => 'M. Hibban Ramadhan',
            'email' => $email,
            'email_verified_at' => null,
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'remember_token' => null,
            'updated_at' => $now,
        ];

        if (DB::table('users')->where('email', $email)->exists()) {
            DB::table('users')->where('email', $email)->update($payload);

            return;
        }

        DB::table('users')->insert([
            ...$payload,
            'created_at' => $now,
        ]);
    }
}
