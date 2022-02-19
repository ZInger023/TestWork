<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    public function registration(Request $request)
    {
        $request->validate([
            'nickname' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        User::create([
            'name' => $request['nickname'],
            'email' => $request['email'],
            'password' => Hash::make($request['password'])
        ]);
        $remember = true;
        if (Auth::attempt($credentials,$remember)) {
            $request->session()->regenerate();

            return redirect('dashboard');
        }
    }
}