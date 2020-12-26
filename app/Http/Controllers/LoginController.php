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
        $credentials = $request->validateWithBag("login.failed",['username'=>'required|min:3','password'=>'required|min:8']);
// dd($credentials);
        // $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->route('home')->with('toast', "login.success");
        } else {
            return redirect()->route('auth')->with('toast', "login.failed");
        }
    }

    public function logout(Request $request)
    {
        //dd(Auth::user());
        if (empty(Auth::user()))
            return redirect()->route('auth')->with('toast', "login.expire");
        else {
            Auth::logout();
            return redirect()->route('auth')->with('toast', "logout");
        }
    }
}
