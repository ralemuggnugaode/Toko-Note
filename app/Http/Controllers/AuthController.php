<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
=======
use App\Models\User;
>>>>>>> 43773f0907c635af242290f113bc7a48de03194f
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
<<<<<<< HEAD
    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
=======
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
            return redirect()->route('page.home');
    }
    }

    public function signOutProcc(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('sign.inView');
>>>>>>> 43773f0907c635af242290f113bc7a48de03194f
    }
}
