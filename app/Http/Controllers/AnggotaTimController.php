<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AnggotaTim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AnggotaTimController extends Controller
{
    public function index()
    {
        try {
            $anggotaTim = AnggotaTim::where('id_tim', Auth::id())->get();

            return view('pages.anggota-tim', compact('anggotaTim'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'nama' => 'required|string|max:255',
            ], [
                'nama.required' => 'Nama anggota tim wajib diisi',
                'nama.max' => 'Nama anggota tim maksimal 255 karakter',
            ]);
            $validated['id_tim'] = Auth::id();

            AnggotaTim::create($validated);

            DB::commit();

            return redirect()->back()->with('success', 'Anggota tim berhasil ditambahkan');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $anggotaTim = AnggotaTim::where('id_tim', Auth::id())
                ->where('id', $id)
                ->firstOrFail();

            $validated = $request->validate([
                'nama' => 'required|string|max:255',
            ], [
                'nama.required' => 'Nama anggota tim wajib diisi',
                'nama.max' => 'Nama anggota tim maksimal 255 karakter',
            ]);

            $anggotaTim->update($validated);

            DB::commit();

            return redirect()->back()->with('success', 'Anggota tim berhasil diperbarui');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Data anggota tim tidak ditemukan');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $anggotaTim = AnggotaTim::where('id_tim', Auth::id())
                ->where('id', $id)
                ->firstOrFail();

            $anggotaTim->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Anggota tim berhasil dihapus');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Data anggota tim tidak ditemukan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}