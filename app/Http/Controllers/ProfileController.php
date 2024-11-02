<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $jabatanOptions = User::getJabatanAkademikOptions();
        return view('pages.profile', compact('user', 'jabatanOptions'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'tempat_lahir' => ['required', 'string', 'max:255'],
            'tanggal_lahir' => ['required', 'date'],
            'nik' => ['required', 'string', 'size:16', Rule::unique('users')->ignore($user->id)],
            'nidn_nuptk' => ['required', 'string', Rule::unique('users')->ignore($user->id)],
            'jabatan_akademik' => ['required', Rule::in(User::getJabatanAkademikOptions())],
            'bidang_keahlian' => ['required', 'string', 'max:255'],
            'program_studi' => ['required', 'string', 'max:255'],
            'alamat_domisili' => ['required', 'string'],
            'no_hp' => ['required', 'string', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => ['required', 'min:8', 'confirmed'],
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        try {
            $user->update($validated);
            return back()->with('success', 'Profile berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui profile');
        }
    }
}