<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;
use App\Models\Image;
use App\Exceptions\NotUserException;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateAccountInfoRequest;
use Illuminate\Support\Facades\Log;

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
            Log::channel('daily')->info('Не авторизованный пользователь пытался попасть на главную страницу и получил ошибку : "' .$exception->getMessage().'"');
            return view('/error',['error' => $exception->getMessage()]);
        }
        return view('dashboard');
    }

    public function showUpdateProfile ()
    {
        try {
            User::isAuthorized();
        }
        catch (NotUserException $exception)
        {
            Log::channel('daily')->info('Не авторизованный пользователь пытался начать редактирование профиля и получил ошибку : "' .$exception->getMessage().'"');
            return view('/error',['error' => $exception->getMessage()]);
        }
        return view('updateAccountInfo');
    }

    public function changeAccountInfo (UpdateAccountInfoRequest $request)
    {
        User::updateInfo($request['name'],$request['email'],$request['password']);
        //return view('updateAccountInfo');
        return redirect()->route('myAccount');
    }
    public function deleteAccount ()
    {
        $messages = Message::all()->where('author_id', Auth::id());
        foreach ($messages as $message){
            Image::where('message_id',$message->id)->delete();
            Chat::where('message_id',$message->id)->delete();
            Message::where('id',$message->id)->delete();
        }
        User::where('id',Auth::user()->id)->delete();
        return view('welcome');
    }
}
