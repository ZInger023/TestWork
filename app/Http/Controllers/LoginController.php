<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login (Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = true;
        if (Auth::attempt($credentials,$remember)) {
            $request->session()->regenerate();

            return redirect('dashboard');
        }
        else {
            return redirect('/login');
        }
    }
}
