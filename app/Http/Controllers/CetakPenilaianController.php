<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use Illuminate\Http\Request;

class CetakPenilaianController extends Controller
{
    public function show($id)
    {
        try {
            $dokumen = Dokumen::with([
                'user.anggotaTim',  // Tambahkan relasi anggotaTim
                'reviewer',
                'jenisDokumen',
                'penilaian' => function ($query) {
                    $query->with('kriteria')->orderBy('created_at', 'asc');
                }
            ])->findOrFail($id);

            if ($dokumen->status !== Dokumen::STATUS_BERHASIL) {
                return redirect()->back()->with('error', 'Dokumen belum selesai direview');
            }

            return view('pages.cetak', compact('dokumen'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}