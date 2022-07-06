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
use App\Exceptions\NotPngOrJpgException;

class MessageController extends Controller
{
    public function insertToBd (Request $request)
    {
        $fields = $request->validate([
            'name' => 'required',
            'text' => 'required',
        ]);
        if (!empty($request->file('image'))) {
            for ($numberOfImage=0; $numberOfImage<count($request['image']); $numberOfImage++) {
                $validation = $request->validate([
                    'image.'.$numberOfImage => 'mimes:jpg,png'
                ]);
            }
            $numberOfPaths = 0;
            foreach ($request->file('image') as $file) {
                $pathToImage[$numberOfPaths] = $file->store('images', 'public');
                $numberOfPaths++;
            };
        }
        try {
            $messageId = Message::insertMessage($fields);
            for ($numberOfImage=0; $numberOfImage<$numberOfPaths; $numberOfImage++) {
                Image::insertImage($pathToImage[$numberOfImage], $messageId);
            }
        }
        catch (TimeLimitException $exception)
        {
            return view('/error',['error' => $exception->getMessage()]);
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
            User::isUser();
        }
        catch (NotUserException $exception)
        {
            return view('/error',['error' => $exception->getMessage()]);
        }
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
