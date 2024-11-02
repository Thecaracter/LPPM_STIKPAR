<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Dokumen;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DokumenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan direktori untuk file ada
        if (!File::exists(public_path('PdfDokumen'))) {
            File::makeDirectory(public_path('PdfDokumen'));
        }
        if (!File::exists(public_path('WordDokumen'))) {
            File::makeDirectory(public_path('WordDokumen'));
        }

        // Ambil semua user id dengan role user
        $userIds = DB::table('users')->where('role', 'user')->pluck('id');
        // Ambil semua jenis dokumen id
        $jenisDokumenIds = DB::table('jenis_dokumen')->pluck('id');

        for ($i = 1; $i <= 30; $i++) {
            $date = fake()->dateTimeBetween('-1 year', '+1 year');
            $startDate = Carbon::parse($date);
            $endDate = Carbon::parse($date)->addMonths(rand(6, 12));

            // Generate nama file 
            $pdfName = 'PDF_' . date('Ymd_His') . '_' . fake()->bothify('???###') . '_sample.pdf';
            $wordName = 'DOC_' . date('Ymd_His') . '_' . fake()->bothify('???###') . '_sample.docx';

            // Copy sample file jika ada
            if (File::exists(database_path('seeders/samples/sample.pdf'))) {
                File::copy(
                    database_path('seeders/samples/sample.pdf'),
                    public_path('PdfDokumen/' . $pdfName)
                );
            }
            if (File::exists(database_path('seeders/samples/sample.docx'))) {
                File::copy(
                    database_path('seeders/samples/sample.docx'),
                    public_path('WordDokumen/' . $wordName)
                );
            }

            Dokumen::create([
                'judul_penelitian' => fake()->sentence(5),
                'abstrak_penelitian' => fake()->paragraph(3),
                'metode_penelitian' => fake()->paragraph(2),
                'total_anggaran' => fake()->numberBetween(10000000, 100000000),
                'sumber_dana' => fake()->randomElement(['Internal', 'DIPA', 'Mandiri', 'Hibah']),
                'lokasi_penelitian' => fake()->city(),
                'waktu_mulai' => $startDate,
                'waktu_selesai' => $endDate,
                'spesifikasi_outcome' => fake()->paragraph(),
                'file_proposal_pdf' => $pdfName,
                'file_proposal_word' => $wordName,
                'jenis_dokumen_id' => $jenisDokumenIds->random(),
                'user_id' => $userIds->random(),
                'status' => 'submitted',
                'tanggal_submit' => now(),
            ]);

            // Delay sedikit agar nama file tidak bentrok
            usleep(100000);
        }
    }
}