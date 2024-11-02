<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginView()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {
            // Log request login
            Log::info('Percobaan login', [
                'email' => $request->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                // Log login berhasil 
                Log::info('Login berhasil', [
                    'user_id' => auth()->id(),
                    'email' => auth()->user()->email,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);

                return redirect()->intended('/dashboard')->with('alert', [
                    'type' => 'success',
                    'message' => 'Login berhasil!'
                ]);
            }

            // Log login gagal - kredensial salah
            Log::warning('Login gagal - kredensial salah', [
                'email' => $request->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return back()->with('alert', [
                'type' => 'error',
                'message' => 'Email atau password salah!'
            ]);

        } catch (\Exception $e) {

            Log::error('Error saat login', [
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'email' => $request->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return back()->with('alert', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan saat login. Silakan coba lagi.'
            ]);
        }
    }

    public function registerView()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'nik' => 'required|string|unique:users',
                'nidn_nuptk' => 'nullable|string|unique:users',
                'tempat_lahir' => 'required|string',
                'tanggal_lahir' => 'required|date',
                'jabatan_akademik' => 'required|in:Asisten Ahli,Lektor,Lektor Kepala,Guru Besar,Belum Memiliki Jabatan Akademik',
                'bidang_keahlian' => 'required|string',
                'program_studi' => 'required|string',
                'alamat_domisili' => 'required|string',
                'no_hp' => 'required|string',
            ], [
                'required' => 'Field :attribute harus diisi.',
                'email' => 'Format email tidak valid.',
                'unique' => ':attribute sudah terdaftar.',
                'min' => ':attribute minimal :min karakter.',
                'confirmed' => 'Konfirmasi password tidak cocok.',
                'in' => 'Pilihan :attribute tidak valid.'
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'nik' => $request->nik,
                'nidn_nuptk' => $request->nidn_nuptk,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jabatan_akademik' => $request->jabatan_akademik,
                'bidang_keahlian' => $request->bidang_keahlian,
                'program_studi' => $request->program_studi,
                'alamat_domisili' => $request->alamat_domisili,
                'no_hp' => $request->no_hp,
            ]);

            Auth::login($user);

            return redirect('/dashboard')->with('alert', [
                'type' => 'success',
                'message' => 'Registrasi berhasil! Selamat datang di dashboard.'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->with('alert', [
                'type' => 'error',
                'message' => $e->validator->errors()->first()
            ])->withInput();
        } catch (\Exception $e) {
            return back()->with('alert', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.'
            ])->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('alert', [
            'type' => 'success',
            'message' => 'Berhasil logout!'
        ]);
    }
}