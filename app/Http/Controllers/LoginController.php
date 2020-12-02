<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function show()
    {
        return inertia("Login/Index");
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->route('home')->with('loginToast', ['loginSuccess' => true]);
        } else {
            return redirect()->route('auth')->with('loginToast', ['loginSuccess' => false, 'error' => 'credential failed!']);
        }
    }

    public function logout(Request $request)
    {
        //dd(Auth::user());
        if (empty(Auth::user()))
            return redirect()->route('auth')->with('authToast', ['sessionExpired' => true]);
        else {
            Auth::logout();
            return redirect()->route('auth')->with('authToast', ['logout' => true]);
        }
    }
}
