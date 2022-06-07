<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Message;
use App\Models\Chat;
use App\Models\User;
use App\Models\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use App\Exceptions\NotUserException;

class ChatController extends Controller
{
    public function addToChat (Request $request)
    {
        try {
            User::isAuthorized();
        }
        catch (NotUserException $exception)
        {
            return view('/error',['error' => $exception->getMessage()]);
        }
        $text =$request->validate([
            'text' => 'required',
        ]);
        $id['id'] = $request->route('id');
        Chat::addToChat($id,$text);
        return redirect()->route('message', ['id' => $id['id']]);
    }

    public function showChat (Request $request)
    {
        try {
            User::isAuthorized();
        }
        catch (NotUserException $exception)
        {
            return view('/error',['error' => $exception->getMessage()]);
        }
        $id = $request['id'];
        $chats = Chat::getChat($id);
        $message = Message::getMessages($id);
        $images = Image::getImages($id);
        $userName = User::getUserName($message->author_id);
        $managerName = NULL;
        if(!empty($message->manager_id)) {
            $managerName = User::getUserName($message->manager_id);
        }
        return view('/viewMessage',['message' => $message,'chats' => $chats,'images' => $images,'managerName' => $managerName,'userName' => $userName]);
    }
}
