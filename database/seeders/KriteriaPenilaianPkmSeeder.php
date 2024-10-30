<?php

namespace Database\Seeders;

use App\Models\JenisDokumen;
use App\Models\KriteriaPenilaian;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class KriteriaPenilaianPkmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            // Mendapatkan ID jenis dokumen PkM
            $jenisDokumenPkm = JenisDokumen::where('nama', 'Pengajuan Proposal PkM')->first();

            if (!$jenisDokumenPkm) {
                $this->command->error('Jenis Dokumen PkM tidak ditemukan! Pastikan JenisDokumenSeeder sudah dijalankan.');
                return;
            }

            // Hapus data lama jika ada
            KriteriaPenilaian::where('jenis_dokumen_id', $jenisDokumenPkm->id)->delete();

            $kriteriaPenilaian = [
                [
                    'jenis_dokumen_id' => $jenisDokumenPkm->id,
                    'nama_kriteria' => 'Relevansi usulan PkM terhadap bidang fokus, tema, dan topik dengan tema PkM STIKPAR Toraja',
                    'bobot' => 7.5,
                ],
                [
                    'jenis_dokumen_id' => $jenisDokumenPkm->id,
                    'nama_kriteria' => 'Sasaran Manfaat PkM yang dilaksanakan',
                    'bobot' => 37.5,
                ],
                [
                    'jenis_dokumen_id' => $jenisDokumenPkm->id,
                    'nama_kriteria' => 'Keterkaitan usulan PkM terhadap hasil PkM yang didapat sebelumnya dan rencana kedepan (roadmap PkM)',
                    'bobot' => 12.5,
                ],
                [
                    'jenis_dokumen_id' => $jenisDokumenPkm->id,
                    'nama_kriteria' => 'Kesesuaian kompetensi tim PkM dan pembagian tugas',
                    'bobot' => 7.5,
                ],
                [
                    'jenis_dokumen_id' => $jenisDokumenPkm->id,
                    'nama_kriteria' => 'Kewajaran metode tahapan target capaian luaran wajib PkM',
                    'bobot' => 12.5,
                ],
                [
                    'jenis_dokumen_id' => $jenisDokumenPkm->id,
                    'nama_kriteria' => 'Kekinian dan sumber primer pengacuan pustaka',
                    'bobot' => 12.5,
                ],
                [
                    'jenis_dokumen_id' => $jenisDokumenPkm->id,
                    'nama_kriteria' => 'Penulisan usulan sesuai panduan (jumlah kata per bagian, isi dokumen pendukung)',
                    'bobot' => 10.0,
                ],
            ];

            $totalBobot = collect($kriteriaPenilaian)->sum('bobot');
            if ($totalBobot != 100) {
                $this->command->error("Total bobot kriteria ($totalBobot%) tidak sama dengan 100%!");
                return;
            }

            foreach ($kriteriaPenilaian as $kriteria) {
                KriteriaPenilaian::create($kriteria);
            }

        } catch (\Exception $e) {
            Log::error('Error seeding kriteria penilaian: ' . $e->getMessage());
            $this->command->error('Gagal melakukan seed kriteria penilaian: ' . $e->getMessage());
        }
    }
}