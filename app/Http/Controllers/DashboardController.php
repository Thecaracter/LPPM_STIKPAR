<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userTerdaftar = User::where('role', 'user')->count();
        return view('pages.dashboard', compact('userTerdaftar'));
    }


}
