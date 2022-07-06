<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;
use App\Exceptions\NotUserException;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function showDashboard ()
    {
        try {
            User::isAuthorized();
        }
        catch (NotUserException $exception)
        {
            return view('/error',['error' => $exception->getMessage()]);
        }
        return view('dashboard');
    }
}
