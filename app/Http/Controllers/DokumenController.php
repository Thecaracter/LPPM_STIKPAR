<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use Illuminate\Support\Str;
use App\Models\JenisDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DokumenController extends Controller
{
    /**
     * Generate unique filename with date and random string
     */
    private function generateUniqueFilename($file, $prefix = '')
    {
        $date = now()->format('Ymd_His');
        $random = Str::random(10);
        $extension = $file->getClientOriginalExtension();
        $sanitizedOriginalName = preg_replace('/[^A-Za-z0-9\-_.]/', '', $file->getClientOriginalName());
        $originalNameWithoutExt = pathinfo($sanitizedOriginalName, PATHINFO_FILENAME);

        return $prefix . $date . '_' . $random . '_' . Str::slug($originalNameWithoutExt) . '.' . $extension;
    }

    public function index()
    {
        try {
            $user = Auth::user();


            $baseQuery = Dokumen::with(['user', 'reviewer', 'jenisDokumen'])
                ->whereNotIn('status', ['ditolak', 'berhasil']);


            $dokumen = match ($user->role) {
                'admin' => $baseQuery->latest()->paginate(10),

                'reviewer' => $baseQuery->byReviewer($user->id)
                    ->latest()
                    ->paginate(10),

                default => $baseQuery->byUser($user->id)
                    ->latest()
                    ->paginate(10),
            };

            $jenisDokumen = JenisDokumen::all();

            return view('pages.dokumen', compact('dokumen', 'jenisDokumen'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memuat data: ' . $e->getMessage());
        }
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                'judul_penelitian' => 'required|string|max:255',
                'abstrak_penelitian' => 'required|string',
                'metode_penelitian' => 'required|string',
                'total_anggaran' => 'required|numeric|min:0',
                'sumber_dana' => 'required|string|max:255',
                'lokasi_penelitian' => 'required|string|max:255',
                'waktu_mulai' => 'required|date',
                'waktu_selesai' => 'required|date|after:waktu_mulai',
                'spesifikasi_outcome' => 'required|string',
                'file_proposal_pdf' => 'required|mimes:pdf|max:10240',
                'file_proposal_word' => 'required|mimes:doc,docx|max:10240',
                'jenis_dokumen_id' => 'required|exists:jenis_dokumen,id'
            ]);

            $dataToStore = $request->except(['file_proposal_pdf', 'file_proposal_word']);


            if ($request->hasFile('file_proposal_pdf')) {
                $pdfFile = $request->file('file_proposal_pdf');
                $pdfName = $this->generateUniqueFilename($pdfFile, 'PDF_');
                $pdfFile->move(public_path('PdfDokumen'), $pdfName);
                $dataToStore['file_proposal_pdf'] = $pdfName;
            }


            if ($request->hasFile('file_proposal_word')) {
                $wordFile = $request->file('file_proposal_word');
                $wordName = $this->generateUniqueFilename($wordFile, 'DOC_');
                $wordFile->move(public_path('WordDokumen'), $wordName);
                $dataToStore['file_proposal_word'] = $wordName;
            }

            $dataToStore['user_id'] = Auth::id();
            $dataToStore['status'] = Dokumen::STATUS_SUBMITTED;
            $dataToStore['tanggal_submit'] = now();

            $dokumen = Dokumen::create($dataToStore);

            return response()->json([
                'status' => 'success',
                'message' => 'Dokumen berhasil disimpan'
            ]);
        } catch (\Exception $e) {

            if (isset($pdfName)) {
                $pdfPath = public_path('PdfDokumen/' . $pdfName);
                if (file_exists($pdfPath)) {
                    unlink($pdfPath);
                }
            }
            if (isset($wordName)) {
                $wordPath = public_path('WordDokumen/' . $wordName);
                if (file_exists($wordPath)) {
                    unlink($wordPath);
                }
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $dokumen = Dokumen::findOrFail($id);

            \Log::info('Start update dokumen', [
                'dokumen_id' => $id,
                'current_dokumen' => $dokumen->toArray(),
                'request_data' => $request->all()
            ]);

            $request->validate([
                'judul_penelitian' => 'required|string|max:255',
                'abstrak_penelitian' => 'required|string',
                'metode_penelitian' => 'required|string',
                'total_anggaran' => 'required|numeric|min:0',
                'sumber_dana' => 'required|string|max:255',
                'lokasi_penelitian' => 'required|string|max:255',
                'waktu_mulai' => 'required|date',
                'waktu_selesai' => 'required|date|after:waktu_mulai',
                'spesifikasi_outcome' => 'required|string',
                'file_proposal_pdf' => 'nullable|mimes:pdf|max:10240',
                'file_proposal_word' => 'nullable|mimes:doc,docx|max:10240',
                'jenis_dokumen_id' => 'required|exists:jenis_dokumen,id'
            ]);

            \Log::info('Validation passed');

            $dataToUpdate = [
                'judul_penelitian' => $request->judul_penelitian,
                'abstrak_penelitian' => $request->abstrak_penelitian,
                'metode_penelitian' => $request->metode_penelitian,
                'total_anggaran' => $request->total_anggaran,
                'sumber_dana' => $request->sumber_dana,
                'lokasi_penelitian' => $request->lokasi_penelitian,
                'waktu_mulai' => $request->waktu_mulai,
                'waktu_selesai' => $request->waktu_selesai,
                'spesifikasi_outcome' => $request->spesifikasi_outcome,
                'jenis_dokumen_id' => $request->jenis_dokumen_id,
                'status' => 'submitted',
            ];

            $newPdfName = null;
            $newWordName = null;

            // Process PDF file if uploaded
            if ($request->hasFile('file_proposal_pdf')) {
                $pdfFile = $request->file('file_proposal_pdf');
                $newPdfName = $this->generateUniqueFilename($pdfFile, 'PDF_');

                $pdfFile->move(public_path('PdfDokumen'), $newPdfName);

                // Delete old PDF file if exists
                if ($dokumen->file_proposal_pdf) {
                    $oldPdfPath = public_path('PdfDokumen/' . $dokumen->file_proposal_pdf);
                    if (file_exists($oldPdfPath)) {
                        unlink($oldPdfPath);
                    }
                }

                $dataToUpdate['file_proposal_pdf'] = $newPdfName;
            }

            // Process Word file if uploaded
            if ($request->hasFile('file_proposal_word')) {
                $wordFile = $request->file('file_proposal_word');
                $newWordName = $this->generateUniqueFilename($wordFile, 'DOC_');

                $wordFile->move(public_path('WordDokumen'), $newWordName);

                // Delete old Word file if exists
                if ($dokumen->file_proposal_word) {
                    $oldWordPath = public_path('WordDokumen/' . $dokumen->file_proposal_word);
                    if (file_exists($oldWordPath)) {
                        unlink($oldWordPath);
                    }
                }

                $dataToUpdate['file_proposal_word'] = $newWordName;
            }

            // Update dokumen
            $dokumen->update($dataToUpdate);

            return response()->json([
                'status' => 'success',
                'message' => 'Dokumen berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error updating dokumen', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Delete uploaded files if error occurs
            if ($newPdfName) {
                $newPdfPath = public_path('PdfDokumen/' . $newPdfName);
                if (file_exists($newPdfPath)) {
                    unlink($newPdfPath);
                }
            }
            if ($newWordName) {
                $newWordPath = public_path('WordDokumen/' . $newWordName);
                if (file_exists($newWordPath)) {
                    unlink($newWordPath);
                }
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            \Log::info('Starting delete process for dokumen', ['id' => $id]);

            $dokumen = Dokumen::findOrFail($id);
            \Log::info('Dokumen found', ['dokumen' => $dokumen->toArray()]);


            $pdfName = $dokumen->file_proposal_pdf;
            $wordName = $dokumen->file_proposal_word;


            $deleted = $dokumen->delete();
            \Log::info('Database record deleted', ['success' => $deleted]);

            if ($deleted) {

                if ($pdfName) {
                    $pdfPath = public_path('PdfDokumen/' . $pdfName);
                    if (file_exists($pdfPath)) {
                        unlink($pdfPath);
                        \Log::info('PDF file deleted', ['path' => $pdfPath]);
                    } else {
                        \Log::warning('PDF file not found', ['path' => $pdfPath]);
                    }
                }


                if ($wordName) {
                    $wordPath = public_path('WordDokumen/' . $wordName);
                    if (file_exists($wordPath)) {
                        unlink($wordPath);
                        \Log::info('Word file deleted', ['path' => $wordPath]);
                    } else {
                        \Log::warning('Word file not found', ['path' => $wordPath]);
                    }
                }

                \Log::info('Delete process completed successfully');
                return redirect()->back()->with('success', 'Dokumen berhasil dihapus');
            }

            throw new \Exception('Failed to delete document from database');

        } catch (\Exception $e) {
            \Log::error('Error in delete process', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus dokumen: ' . $e->getMessage());
        }
    }
}
