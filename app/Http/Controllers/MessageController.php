<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;
use App\Http\Requests\MessageRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\User;
use App\Models\Image;
use App\Exceptions\TimeLimitException;
use App\Exceptions\NotAuthorException;
use App\Exceptions\NotUserException;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public function insertToBd (MessageRequest $request)
    {
        $fields = array(
            "name" => $request['name'],
            "text" => $request['text'],
        );
        $numberOfPaths = 0;
        if (!empty($request->file('image'))) {
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
            $userId = Auth::id();
            Log::channel('daily')->info('Пользователь '. $userId. ' получил ошибку : "' .$exception->getMessage().'"');
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
            Log::channel('daily')->info('Не авторизованный пользователь получил ошибку : "' .$exception->getMessage().'"');
            return view('/error',['error' => $exception->getMessage()]);
        }
        $messages = Message::showAllMessages();
        return view('/allmessages',['messages' => $messages]);
    }

    public  function  closeMessage(Request $request) {
        try {
            User::isUser();
        }
        catch (NotUserException $exception)
        {
            Log::channel('daily')->info('Не авторизованный пользователь получил ошибку : "' .$exception->getMessage().'"');
            return view('/error',['error' => $exception->getMessage()]);
        }
        try {
            $message_id = $request->route('id');
            Message::closeMessage($message_id);
        }
        catch (NotAuthorException $exception)
        {
            $userId = Auth::id();
            Log::channel('daily')->info('Пользователь '. $userId. ' пытался закрыть заявку '.$message_id. ' и получил ошибку : "' .$exception->getMessage().'"');
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
            Log::channel('daily')->info('Не авторизованный пользователь пытался оставить заявку и получил ошибку : "' .$exception->getMessage().'"');
            return view('/error',['error' => $exception->getMessage()]);
        }
        return view('createMessage');
    }
    public  function  showUpdatePage(Request $request) {
        try {
            User::isUser();
        }
        catch (NotUserException $exception) {
            Log::channel('daily')->info('Не авторизованный пользователь пытался начать редактирование заявки и получил ошибку : "' .$exception->getMessage().'"');
            return view('/error',['error' => $exception->getMessage()]);
        }
        $message = Message::find ($request->route('id'));
        $images = Image::getImages($message->id);
        return view('updateMessage',['message' => $message,'images' => $images]);
    }
    public  function  updateMessage(MessageRequest $request) {
        $fields = array(
            "name" => $request['name'],
            "text" => $request['text'],
            "id" => $request['id'],

        );
        $numberOfPaths = 0;
        if (!empty($request->file('image'))) {
            foreach ($request->file('image') as $file) {
                $pathToImage[$numberOfPaths] = $file->store('images', 'public');
                $numberOfPaths++;
            };
        }
            Message::updateInfo($fields);
            for ($numberOfImage=0; $numberOfImage<$numberOfPaths; $numberOfImage++) {
                Image::insertImage($pathToImage[$numberOfImage], $request['id']);
            }
        return redirect()->intended('/message/'.$request['id']);
    }

    public  function  deleteMessage(Request $request) {
        try {
            $message_id = $request->route('id');
            $message = Message::find($message_id);
            User::isAuthorOrManager($message->author_id);
            Image::where('message_id', $message_id)->delete();
            Chat::where('message_id', $message_id)->delete();
            Message::where('id', $message_id)->delete();
        }
        catch (NotAuthorException $exception)
        {
            $userId = Auth::id();
            Log::channel('daily')->info('Пользователь '. $userId. ' пытался удалить заявку '.$message_id. ' и получил ошибку : "' .$exception->getMessage().'"');
            return view('/error',['error' => $exception->getMessage()]);
        }
        return redirect()->intended('/myMessages');
    }
    public  function  deleteImage(Request $request) {
        try {
            $image = Image::find($request->route('id'));
            $message = Message::find($image->message_id);
            User::isAuthorOrManager($message->author_id);
            Image::where('id',$image->id)->delete();
            $images = Image::where('message_id',$message->id)->get();
        }
        catch (NotAuthorException $exception)
        {
            $userId = Auth::id();
            Log::channel('daily')->info('Пользователь '. $userId. ' пытался удалить изображение '.$message_id. ' и получил ошибку : "' .$exception->getMessage().'"');
            return view('/error',['error' => $exception->getMessage()]);
        }
        return view('/updateMessage',['message' => $message,'images' => $images]);
    }
}
