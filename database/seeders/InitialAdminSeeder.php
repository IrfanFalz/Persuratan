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
            ['username' => 'admin'],
            [
                'nama' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'no_telp' => null,
                'nip' => null,
            ]
        );

        Pengguna::firstOrCreate(
            ['username' => 'kepsek'],
            [
                'nama' => 'Kepala Sekolah',
                'password' => Hash::make('kepsek123'),
                'role' => 'kepsek',
                'no_telp' => '081234567890',
                'nip' => '196505101990031001',
            ]
        );

        Pengguna::firstOrCreate(
            ['username' => 'tu'],
            [
                'nama' => 'Tata Usaha',
                'password' => Hash::make('tu123'),
                'role' => 'tu',
                'no_telp' => '081234567891',
                'nip' => '197808152005012002',
            ]
        );

        Pengguna::firstOrCreate(
            ['username' => 'ktu'],
            [
                'nama' => 'Ketua Tata Usaha',
                'password' => Hash::make('ktu123'),
                'role' => 'ktu',
                'no_telp' => '081234567892',
                'nip' => '197808152005012003',
            ]
        );
    }
}
