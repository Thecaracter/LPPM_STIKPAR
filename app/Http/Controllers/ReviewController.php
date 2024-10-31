<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\KriteriaPenilaian;
use App\Models\PenilaianDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReviewController extends Controller
{
    public function index()
    {
        try {
            $dokumen = Dokumen::with(['user', 'jenisDokumen'])
                ->whereIn('status', ['submitted'])
                ->latest('tanggal_submit')
                ->paginate(10);

            return view('pages.review', compact('dokumen'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data: ' . $e->getMessage());
        }
    }

    public function getKriteria($id)
    {
        try {
            $dokumen = Dokumen::findOrFail($id);
            $kriteria = KriteriaPenilaian::where('jenis_dokumen_id', $dokumen->jenis_dokumen_id)
                ->with([
                    'penilaian' => function ($query) use ($id) {
                        $query->where('dokumen_id', $id);
                    }
                ])
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $kriteria
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            // Base validation rules
            $rules = [
                'status' => 'required|in:revisi,ditolak,berhasil',
                'catatan_reviewer' => 'required|string|min:10',
            ];

            // Add penilaian validation rules only if status is 'berhasil'
            if ($request->status === 'berhasil') {
                $rules['penilaian'] = 'required|array';
                $rules['penilaian.*.kriteria_id'] = 'required|exists:kriteria_penilaian,id';
                $rules['penilaian.*.skor'] = 'required|numeric|min:0|max:10';
                $rules['penilaian.*.justifikasi'] = 'required|string|min:3';
            }

            $request->validate($rules, [
                'status.required' => 'Status review harus dipilih',
                'status.in' => 'Status review tidak valid',
                'catatan_reviewer.required' => 'Catatan reviewer harus diisi',
                'catatan_reviewer.min' => 'Catatan reviewer minimal 10 karakter',
                'penilaian.required' => 'Penilaian harus diisi untuk status Berhasil',
                'penilaian.*.skor.required' => 'Skor harus diisi',
                'penilaian.*.skor.numeric' => 'Skor harus berupa angka',
                'penilaian.*.skor.min' => 'Skor minimal 0',
                'penilaian.*.skor.max' => 'Skor maksimal 10',
                'penilaian.*.justifikasi.required' => 'Justifikasi harus diisi',
                'penilaian.*.justifikasi.min' => 'Justifikasi minimal 10 karakter'
            ]);

            $dokumen = Dokumen::findOrFail($id);

            // Log start review
            \Log::info('Start review dokumen', [
                'dokumen_id' => $id,
                'reviewer_id' => auth()->id(),
                'request_data' => $request->all()
            ]);

            $dokumen->status = $request->status;
            $dokumen->catatan_reviewer = $request->catatan_reviewer;
            $dokumen->reviewer_id = auth()->id();
            $dokumen->tanggal_review = Carbon::now();

            // Only process penilaian if status is 'berhasil'
            if ($request->status === 'berhasil') {
                $totalNilai = 0;
                $totalBobot = 0;

                foreach ($request->penilaian as $nilai) {
                    $kriteria = KriteriaPenilaian::find($nilai['kriteria_id']);
                    $nilaiTerbobot = ($nilai['skor'] * $kriteria->bobot) / 10;
                    $totalNilai += $nilaiTerbobot;
                    $totalBobot += $kriteria->bobot;

                    PenilaianDokumen::updateOrCreate(
                        [
                            'dokumen_id' => $dokumen->id,
                            'kriteria_penilaian_id' => $nilai['kriteria_id'],
                        ],
                        [
                            'skor' => $nilai['skor'],
                            'nilai' => $nilaiTerbobot,
                            'justifikasi' => $nilai['justifikasi']
                        ]
                    );
                }

                $nilaiAkhir = ($totalBobot > 0) ? round(($totalNilai / $totalBobot) * 100) : 0;
                $dokumen->nilai = $nilaiAkhir;

                \Log::info('Penilaian berhasil dihitung', [
                    'dokumen_id' => $id,
                    'total_nilai' => $totalNilai,
                    'total_bobot' => $totalBobot,
                    'nilai_akhir' => $nilaiAkhir
                ]);
            } else {
                // Reset nilai if status is not 'berhasil'
                $dokumen->nilai = null;
                // Delete any existing penilaian
                PenilaianDokumen::where('dokumen_id', $dokumen->id)->delete();

                \Log::info('Penilaian direset karena status bukan berhasil', [
                    'dokumen_id' => $id,
                    'status' => $request->status
                ]);
            }

            $dokumen->save();

            DB::commit();

            \Log::info('Review dokumen berhasil disimpan', [
                'dokumen_id' => $id,
                'status' => $dokumen->status,
                'nilai_akhir' => $dokumen->nilai
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Review berhasil disimpan',
                'data' => [
                    'nilai_akhir' => $dokumen->nilai,
                    'status' => $dokumen->getStatusLabel()
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            \Log::error('Validation error pada review dokumen', [
                'dokumen_id' => $id,
                'errors' => $e->errors()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error pada review dokumen', [
                'dokumen_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}