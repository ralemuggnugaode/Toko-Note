<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
    return view('pages.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $request->session()->regenerate();
            
            $nim = Auth::user()->username;
            if ($nim == '719') {
                return redirect()->route('stok-barang-719.index');
            } elseif ($nim == '729') {
                return redirect()->route('catatan-masuk.index');
            } elseif ($nim == '742') {
                return redirect()->route('catatan-keluar-742.index');
            } else {
                return redirect('/');
            }
        }

        return back()->withErrors([
            'username' => 'NIM atau password salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
