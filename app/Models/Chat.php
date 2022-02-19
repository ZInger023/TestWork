<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Jobs\ManagerAddToChat;
use App\Jobs\UserAddToChat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Chat extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'message_id',
        'text',
        'author_id' ,

    ];


    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];
    public static function getChat (int $id)
    {
        $messages = Message::all()->where('id', $id);
        $chats = Chat::all()->where('message_id', $id);
        return $chats;
    }

    public static function addToChat (array $id,array $text)
    {

        Chat::create([
            'message_id' => $id['id'],
            'text' => $text['text'],
            'author_id' => Auth::user()->id,

        ]);
        if(Auth::user()->role == 'manager')
        {
            DB::table('messages')
                ->where('id',$id['id'])
                ->update(['status' => 'answered']);
            $message = Message::find($id['id']);
            if($message->manager_id !== 'NULL') {
                $user = User::find($message->author_id);
                ManagerAddToChat::dispatch($message,$user);
            }
        }
        if(Auth::user()->role == 'user')
        {
            $message = Message::find($id['id']);
            $manager = User::find($message->manager_id);
            UserAddToChat::dispatch($message,$manager);;
        }

        return;
    }

}
