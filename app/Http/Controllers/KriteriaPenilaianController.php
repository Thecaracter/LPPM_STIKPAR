<?php

namespace App\Http\Controllers;

use App\Models\JenisDokumen;
use Illuminate\Http\Request;
use App\Models\KriteriaPenilaian;
use Illuminate\Support\Facades\Validator;

class KriteriaPenilaianController extends Controller
{
    public function index($jenis_dokumen_id)
    {
        try {
            $jenisDokumen = JenisDokumen::findOrFail($jenis_dokumen_id);
            $kriteriaPenilaian = KriteriaPenilaian::where('jenis_dokumen_id', $jenis_dokumen_id)->get();

            return view('pages.kriteria-penilaian', compact('kriteriaPenilaian', 'jenisDokumen'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengambil data kriteria penilaian: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'jenis_dokumen_id' => 'required|exists:jenis_dokumen,id',
                'nama_kriteria' => 'required|string|max:255',
                'bobot' => 'required|numeric|min:0'  // Hapus max:100
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            KriteriaPenilaian::create([
                'jenis_dokumen_id' => $request->jenis_dokumen_id,
                'nama_kriteria' => $request->nama_kriteria,
                'bobot' => $request->bobot
            ]);

            return redirect()->back()->with('success', 'Kriteria penilaian berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan kriteria penilaian: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $kriteriaPenilaian = KriteriaPenilaian::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nama_kriteria' => 'required|string|max:255',
                'bobot' => 'required|numeric|min:0'  // Hapus max:100
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $kriteriaPenilaian->update([
                'nama_kriteria' => $request->nama_kriteria,
                'bobot' => $request->bobot
            ]);

            return redirect()->back()->with('success', 'Kriteria penilaian berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui kriteria penilaian: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $kriteriaPenilaian = KriteriaPenilaian::findOrFail($id);
            $kriteriaPenilaian->delete();

            return redirect()->back()->with('success', 'Kriteria penilaian berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus kriteria penilaian: ' . $e->getMessage());
        }
    }
}