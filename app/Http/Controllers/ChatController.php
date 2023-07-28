<?php

namespace App\Http\Controllers;

use App\Exceptions\NotAuthorException;
use Illuminate\Http\Request;
use App\Http\Requests\ChatRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\Chat;
use App\Models\User;
use App\Models\Image;
use Illuminate\Support\Facades\Log;
use App\Exceptions\NotUserException;

class ChatController extends Controller
{
    public function addToChat (ChatRequest $request)
    {
        try {
            User::isAuthorized();
        }
        catch (NotUserException $exception)
        {
            Log::channel('daily')->info('Не авторизованный пользователь пытался написать сообщение в чат и получил ошибку : "' .$exception->getMessage().'"');
            return view('/error',['error' => $exception->getMessage()]);
        }
        $id['id'] = $request->route('id');
        $text = $request['text'];
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
            Log::channel('daily')->info('Не связанный с заявкой пользователь получил ошибку : "' .$exception->getMessage().'"');
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
        try{
            User::isAuthorOrManager($message->author_id);
        }
        catch (NotUserException $exception)
        {
            $userId = Auth::id();
            Log::channel('daily')->info('Не связанный с заявкой пользователь ' .$userId .' получил ошибку : "' .$exception->getMessage().'"');
            return view('/error',['error' => $exception->getMessage()]);
        }
        return view('/viewMessage',['message' => $message,'chats' => $chats,'images' => $images,'managerName' => $managerName,'userName' => $userName]);
    }
    public function showUpdateChatPage (Request $request)
    {
        try {
            User::isAuthorized();
        }
        catch (NotUserException $exception)
        {
            $userId = Auth::id();
            Log::channel('daily')->info('Не связанный с заявкой пользователь ' . $userId.' пытался отредактировать сообщение в чате и получил ошибку : "' .$exception->getMessage().'"');
            return view('/error',['error' => $exception->getMessage()]);
        }
        $chatMessage = Chat::find($request->route('id'));
        return view('updateChat',['chatMessage' => $chatMessage]);
    }
    public function updateChatMessage (ChatRequest $request)
    {
        $chat = Chat::updateText($request->route('id'),$request['text']);
        return redirect()->intended('/message/'.$chat->message_id);
    }
    public function deleteChatMessage (Request $request)
    {
        try {
            $chat_id = $request->route('id');
            $chat = Chat::find($chat_id);
            User::isAuthorOrManager($chat->author_id);
            Chat::where('id', $chat_id)->delete();
        }
        catch (NotAuthorException $exception)
        {
            $userId = Auth::id();
            Log::channel('daily')->info('Не связанный с заявкой пользователь ' . $userId.' пытался удалить сообщение в чате и получил ошибку : "' .$exception->getMessage().'"');
            return view('/error',['error' => $exception->getMessage()]);
        }
        return redirect()->intended('/message/'.$chat->message_id);
    }
}
