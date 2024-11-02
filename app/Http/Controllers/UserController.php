<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'admin')->get();
        $jabatanOptions = User::getJabatanAkademikOptions();
        return view('pages.user', compact('users', 'jabatanOptions'));
    }

    public function store(Request $request)
    {
        try {
            Log::info('Memulai penambahan reviewer baru', [
                'request_data' => $request->except('password')
            ]);

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', Password::defaults()],
                'tempat_lahir' => ['required', 'string', 'max:255'],
                'tanggal_lahir' => ['required', 'date'],
                'nik' => ['required', 'string', 'size:16', 'unique:users'],
                'nidn_nuptk' => ['required', 'string', 'unique:users'],
                'jabatan_akademik' => ['required', Rule::in(User::getJabatanAkademikOptions())],
                'bidang_keahlian' => ['required', 'string', 'max:255'],
                'program_studi' => ['required', 'string', 'max:255'],
                'alamat_domisili' => ['required', 'string'],
                'no_hp' => ['required', 'string', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
            ]);

            Log::info('Validasi data reviewer berhasil', [
                'validated_data' => array_diff_key($validated, ['password' => ''])
            ]);

            $validated['password'] = Hash::make($validated['password']);
            $validated['role'] = 'reviewer';

            $reviewer = User::create($validated);

            Log::info('Reviewer berhasil ditambahkan', [
                'reviewer_id' => $reviewer->id,
                'reviewer_email' => $reviewer->email
            ]);

            return redirect()
                ->route('user.index')
                ->with('success', 'Reviewer berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('Error saat menambahkan reviewer', [
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'request_data' => $request->except('password')
            ]);

            return redirect()
                ->route('user.index')
                ->with('error', 'Gagal menambahkan reviewer: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);

            if ($user->role === 'admin') {
                return redirect()
                    ->route('user.index')
                    ->with('error', 'Tidak dapat menghapus akun admin');
            }

            // Check if reviewer has ongoing reviews
            if ($user->role === 'reviewer' && $user->dokumenDireview()->where('status', 'submitted')->exists()) {
                return redirect()
                    ->route('user.index')
                    ->with('error', 'Tidak dapat menghapus reviewer yang sedang memiliki tugas review');
            }

            $user->delete();

            return redirect()
                ->route('user.index')
                ->with('success', ($user->role === 'reviewer' ? 'Reviewer' : 'User') . ' berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error saat menghapus user', [
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'user_id' => $id
            ]);

            return redirect()
                ->route('user.index')
                ->with('error', 'Gagal menghapus user');
        }
    }
}