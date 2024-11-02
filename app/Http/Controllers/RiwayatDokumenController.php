<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use Illuminate\Http\Request;

class RiwayatDokumenController extends Controller
{
    public function index()
    {
        try {
            $query = Dokumen::with(['user', 'jenisDokumen', 'reviewer'])
                ->whereIn('status', ['ditolak', 'berhasil'])
                ->latest('tanggal_review');

            // Jika bukan admin, filter berdasarkan role
            if (!auth()->user()->isAdmin()) {
                if (auth()->user()->isReviewer()) {
                    // Reviewer hanya melihat yang dia review
                    $query->where('reviewer_id', auth()->id());
                } else {
                    // User biasa hanya melihat dokumennya sendiri
                    $query->where('user_id', auth()->id());
                }
            }

            $dokumen = $query->paginate(10);

            return view('pages.riwayat', compact('dokumen'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data: ' . $e->getMessage());
        }
    }
}