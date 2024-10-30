<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AnggotaTim;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AnggotaTimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::where('role', 'user')->get()->each(function ($user) {

            for ($i = 0; $i < 10; $i++) {
                AnggotaTim::create([
                    'id_tim' => $user->id,
                    'nama' => fake()->name(),
                ]);
            }
        });
    }
}
