<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;

class InitialAdminSeeder extends Seeder
{
    public function run(): void
    {
        Pengguna::firstOrCreate(
            ['username' => '0111'],
            [
                'nama' => 'Siti Aminah',
                'password' => Hash::make('tu123'),
                'role' => 'tu',
                'no_telp' => '081234567891',
                'nip' => '197808152005012002',
            ]
        );

        Pengguna::firstOrCreate(
            ['username' => '0222'],
            [
                'nama' => 'Dr. Ahmad Wijaya',
                'password' => Hash::make('kepsek123'),
                'role' => 'kepsek',
                'no_telp' => '081234567890',
                'nip' => '196505101990031001',
            ]
        );

        Pengguna::firstOrCreate(
            ['username' => '0333'],
            [
                'nama' => 'Maya Sari',
                'password' => Hash::make('guru123'),
                'role' => 'guru',
                'no_telp' => '081234567892',
                'nip' => '198512152010012004',
            ]
        );

        Pengguna::firstOrCreate(
            ['username' => '0444'],
            [
                'nama' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'no_telp' => null,
                'nip' => null,
            ]
        );
    }
}
