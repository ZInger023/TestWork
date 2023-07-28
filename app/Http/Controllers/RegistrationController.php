<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\RegistrationRequest;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    public function registration(RegistrationRequest $request)
    {
        User::create([
            'name' => $request['nickname'],
            'email' => $request['email'],
            'password' => Hash::make($request['password'])
        ]);
        $remember = true;
        Registration::SendMessage($request['email']);

        if (Auth::attempt(['email' => $request->email, 'password' =>$request->password], $remember)) {
            $request->session()->regenerate();
            return redirect('dashboard');
        }
    }
}
