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
            return redirect()->route('page.home');
    }
    }

    public function signOutProcc(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('sign.inView');
    }
}
