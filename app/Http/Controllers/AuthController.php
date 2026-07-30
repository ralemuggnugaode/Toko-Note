<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signIn(){
        return view('pages.auth.signIn', [
            'title' => 'Login'
        ]);
    }

    public function signInProcc(Request $request){
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            switch($user->identification_number){
                case '719':
                    return redirect()->intended(route('page.stok-barang-719.index'));
                case '729':
                    return redirect()->intended(route('page.catatan-masuk-729.index'));
                case '742':
                    return redirect()->intended(route('page.catatan-keluar-742.index'));
            }
        }
        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->withInput();
    }

    public function signOutProcc(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('sign.inView');
    }
}
