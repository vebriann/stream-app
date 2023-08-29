<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginMemberController extends Controller
{
    public function index()
    {
        return view('member.auth');
    }

    public function auth(Request $requests)
    {
        $requests->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $requests->only('email', 'password');
        $credentials['role'] = 'member';

        // dd($credentials);

        if (Auth::attempt($credentials)) {
            $requests->session()->regenerate();
            return redirect()->route('member.dashboard');
        }

        return back()->withErrors([
            'credentials' => 'Maaf gagal login, coba untuk memasukan email dan password dengan benar!'
        ])->withInput();
    }

    public function logout(Request $requests)
    {

    }
}
