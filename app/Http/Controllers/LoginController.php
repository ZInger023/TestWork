<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login (LoginRequest $request)
    {
        $remember = true;
        if (Auth::attempt(['email' => $request->email, 'password' =>$request->password], $remember)) {
            $request->session()->regenerate();
            return redirect('dashboard');
        }
        else {
            $error = 'Неправильный логин и/или пароль';
            return view('/loginPage',['error' => $error]);
        }
    }
}
