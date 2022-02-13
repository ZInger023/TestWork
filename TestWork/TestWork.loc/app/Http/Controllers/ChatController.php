<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Message;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;

class ChatController extends Controller
{
    public function addToChat (Request $request)
    {
        try {
            User::isAuthorized();
        }
        catch (NotUserException $exception)
        {
            return ($exception->getMessage());
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
            return ($exception->getMessage());
        }
        $id = $request['id'];
        $chats = Chat::getChat($id);
        $messages = Message::getMessages($id);
        return view('/viewMessage',['messages' => $messages,'chats' => $chats]);
    }
}
