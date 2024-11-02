<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin
        User::create([
            'email' => 'admin@admin.com',
            'password' => Hash::make('Stikparmantap0123'),
            'role' => 'admin',
            'name' => 'Admin',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'nik' => '1234567890123456',
            'nidn_nuptk' => '1234567890',
            'jabatan_akademik' => 'Lektor',
            'bidang_keahlian' => 'Teknologi Informasi',
            'program_studi' => 'Teknik Informatika',
            'alamat_domisili' => 'Jakarta Pusat',
            'no_hp' => '081234567890'
        ]);
    }
}