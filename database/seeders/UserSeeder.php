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
            'password' => Hash::make('password'),
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

        // Create Reviewer 1
        User::create([
            'email' => 'reviewer1@reviewer.com',
            'password' => Hash::make('password'),
            'role' => 'reviewer',
            'name' => 'Reviewer 1',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1985-01-01',
            'nik' => '2234567890123456',
            'nidn_nuptk' => '2234567890',
            'jabatan_akademik' => 'Lektor Kepala',
            'bidang_keahlian' => 'Sistem Informasi',
            'program_studi' => 'Sistem Informasi',
            'alamat_domisili' => 'Bandung',
            'no_hp' => '082234567890'
        ]);

        // Create Reviewer 2
        User::create([
            'email' => 'reviewer2@reviewer.com',
            'password' => Hash::make('password'),
            'role' => 'reviewer',
            'name' => 'Reviewer 2',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1987-05-15',
            'nik' => '3234567890123456',
            'nidn_nuptk' => '3234567890',
            'jabatan_akademik' => 'Lektor Kepala',
            'bidang_keahlian' => 'Manajemen',
            'program_studi' => 'Manajemen',
            'alamat_domisili' => 'Surabaya',
            'no_hp' => '083234567890'
        ]);

        // Create Regular User
        for ($i = 1; $i <= 3; $i++) {
            $nik = fake()->unique()->numerify('###############') . fake()->randomDigit();
            User::create([
                'email' => "user{$i}@user.com",
                'password' => Hash::make('password'),
                'role' => 'user',
                'name' => fake()->name(),
                'tempat_lahir' => fake()->city(),
                'tanggal_lahir' => fake()->date(),
                'nik' => $nik,
                'nidn_nuptk' => fake()->unique()->numerify('##########'),
                'jabatan_akademik' => fake()->randomElement([
                    'Asisten Ahli',
                    'Lektor',
                    'Lektor Kepala',
                    'Guru Besar',
                    'Belum Memiliki Jabatan Akademik'
                ]),
                'bidang_keahlian' => fake()->randomElement([
                    'Teknologi Informasi',
                    'Sistem Informasi',
                    'Manajemen',
                    'Akuntansi',
                    'Ekonomi'
                ]),
                'program_studi' => fake()->randomElement([
                    'Teknik Informatika',
                    'Sistem Informasi',
                    'Manajemen',
                    'Akuntansi',
                    'Ekonomi'
                ]),
                'alamat_domisili' => fake()->address(),
                'no_hp' => fake()->numerify('08##########')
            ]);
        }
    }
}