<?php

namespace Database\Seeders;

use App\Models\JenisDokumen;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JenisDokumenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisDokumen = [
            [
                'nama' => 'Pengajuan Proposal Penelitian',
            ],
            [
                'nama' => 'Pengajuan Proposal PkM',
            ],
        ];

        foreach ($jenisDokumen as $jenis) {
            JenisDokumen::create($jenis);
        }
    }
}
