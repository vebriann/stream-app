<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('admin.auth');
    }

    public function authenticate(Request $requests)
    {
        $requests->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $requests->only('email', 'password');
        $credentials['role'] = 'admin';

        // dd($credentials);

        if (Auth::attempt($credentials)) {
            $requests->session()->regenerate();
            return redirect()->route('admin.movie');
        }

        return back()->withErrors([
            'error' => 'Maaf Data Yang Ada Masukan Gagal'
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
