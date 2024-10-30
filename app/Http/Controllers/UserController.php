<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Enum;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'admin')->get();
        return view('pages.user', compact('users'));
    }
    public function destroy(string $id)
    {
        try {
            $user = User::find($id);
            $user->delete();
            return redirect()->route('user.index')->with('success', 'User berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('user.index')->with('error', 'User gagal dihapus');
        }
    }
}
