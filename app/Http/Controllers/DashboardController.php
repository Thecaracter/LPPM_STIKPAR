<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dokumen;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [];
        $user = auth()->user();

        // Data untuk semua role
        $data['totalDokumen'] = Dokumen::count();

        if ($user->role === 'admin') {
            $data['userTerdaftar'] = User::where('role', 'user')->count();
            $data['totalReviewer'] = User::where('role', 'reviewer')->count();
            $data['dokumenSubmitted'] = Dokumen::where('status', 'submitted')->count();
            $data['dokumenSelesai'] = Dokumen::whereIn('status', ['ditolak', 'berhasil'])->count();
        } elseif ($user->role === 'reviewer') {
            $data['dokumenDireview'] = Dokumen::where('reviewer_id', $user->id)->count();
            $data['dokumenPending'] = Dokumen::where('reviewer_id', $user->id)
                ->where('status', 'submitted')
                ->count();
            $data['dokumenSelesai'] = Dokumen::where('reviewer_id', $user->id)
                ->whereIn('status', ['ditolak', 'berhasil'])
                ->count();
            $data['dokumenRevisi'] = Dokumen::where('reviewer_id', $user->id)
                ->where('status', 'revisi')
                ->count();
        } else { // role user
            $data['dokumenDiajukan'] = Dokumen::where('user_id', $user->id)->count();
            $data['dokumenDitolak'] = Dokumen::where('user_id', $user->id)
                ->where('status', 'ditolak')
                ->count();
            $data['dokumenDiterima'] = Dokumen::where('user_id', $user->id)
                ->where('status', 'berhasil')
                ->count();
            $data['dokumenRevisi'] = Dokumen::where('user_id', $user->id)
                ->where('status', 'revisi')
                ->count();
        }

        return view('pages.dashboard', compact('data', 'user'));
    }
}