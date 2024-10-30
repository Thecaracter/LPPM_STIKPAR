<?php

namespace App\Http\Controllers;

use App\Models\JenisDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JenisDokumenController extends Controller
{
    public function index()
    {
        try {
            $jenisDokumen = JenisDokumen::all();
            return view('pages.jenis-dokumen', compact('jenisDokumen'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengambil data jenis dokumen: ' . $e->getMessage());
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama' => 'required|string|max:255|unique:jenis_dokumen,nama'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            JenisDokumen::create([
                'nama' => $request->nama
            ]);

            return redirect()->route('jenis-dokumen.index')
                ->with('success', 'Jenis dokumen berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan jenis dokumen: ' . $e->getMessage())
                ->withInput();
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $jenisDokumen = JenisDokumen::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nama' => 'required|string|max:255|unique:jenis_dokumen,nama,' . $id
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $jenisDokumen->update([
                'nama' => $request->nama
            ]);

            return redirect()->route('jenis-dokumen.index')
                ->with('success', 'Jenis dokumen berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui jenis dokumen: ' . $e->getMessage())
                ->withInput();
        }
    }
    public function destroy($id)
    {
        try {
            $jenisDokumen = JenisDokumen::findOrFail($id);
            $jenisDokumen->delete();

            return redirect()->route('jenis-dokumen.index')
                ->with('success', 'Jenis dokumen berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus jenis dokumen: ' . $e->getMessage());
        }
    }
}
