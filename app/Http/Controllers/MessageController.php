<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Message;
use App\Models\User;
use App\Models\Image;
use Illuminate\Support\Facades\DB;
use App\Jobs\SetMessageClosed;
use App\Exceptions\TimeLimitException;
use App\Exceptions\NotAuthorException;
use App\Exceptions\NotUserException;

class MessageController extends Controller
{
    public function insertToBd (Request $request)
    {
        $fields = $request->validate([
            'name' => 'required',
            'text' => 'required',
        ]);
        try {
             $messageId = Message::insertMessage($fields);
        }
        catch (TimeLimitException $exception)
        {
            return view('/error',['error' => $exception->getMessage()]);
    }
        if (!empty($request->file('image'))) {
            foreach ($request->file('image') as $file) {
                $pathToImage = $file->store('images', 'public');
                Image::insertImage($pathToImage, $messageId);
            };
        }
        return view('/messageCreatedSuccessfully');
    }

    public  function  showMessages()
    {
        try {
            User::isUser();
        }
        catch (NotUserException $exception)
        {
            return view('/error',['error' => $exception->getMessage()]);
        }
        $messages = Message::showAllMessages();
        return view('/allmessages',['messages' => $messages]);
    }

    public  function  deleteMessage(Request $request) {
        try {
            $message_id = $request->route('id');
            Message::closeMessage($message_id);
        }
        catch (NotAuthorException $exception)
        {
            return view('/error',['error' => $exception->getMessage()]);
        }
        return redirect()->intended('dashboard');
    }
    public function showCreateForm ()
    {
        try {
            User::isUser();
        }
        catch (NotUserException $exception)
        {
            return view('/error',['error' => $exception->getMessage()]);
        }
        return view('createMessage');
    }
}
