<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Exceptions\TimeLimitException;
use App\Jobs\SetMessageClosed;
use App\Jobs\MessageInsertion;
use App\Exceptions\NotAuthorException;

class Message extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'text',
        'author_id',
        'manager_id',

    ];


    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'open',
    ];

    public static function insertMessage (array $fields)
    {
        $lastMessages = Message::where('author_id', Auth::user()->id)->where('created_at', '>', Carbon::now()->subDay())->first();
        if (empty($lastMessages)) {
           $message =  Message::create([
                'name' => $fields['name'],
                'text' => $fields['text'],
                'author_id' => Auth::user()->id,
                'manager_id' => NULL,
            ]);
            $managers = User::all()->where('role','manager');
            foreach ($managers as $manager)
            {
                MessageInsertion::dispatch($message,$manager);
           }
        }
        if (!empty($lastMessages))
        {
            throw new TimeLimitException ('Вы можете отправить заявку лишь 1 раз за день.');
        }
        return $message->id;
    }

    public static function  showAllMessages()
    {
        $messages = Message::all()->where('author_id', Auth::id());
        return $messages;
    }

    public static function  closeMessage(int $id) {
        $message = Message::find($id);
        if ($message->manager_id !== null) {
            SetMessageClosed::dispatch($message);
        }
        if(Auth::user()->id !== $message->author_id) {
            throw new NotAuthorException ('Вы не являетесь автором этой заявки!');
        }
            DB::table('messages')
                ->where('id', $id)
                ->update(['status' => 'closed']);

    }
    public static function setMessageViewed(int $id)
    {
        DB::table('messages')
            ->where('id',$id)
            ->update(['status' => 'viewed','manager_id' => Auth::user()->id]);
        return redirect()->intended('dashboard');
    }
    public static function getMessages (int $id)
    {
        $messages = Message::find($id);
        return $messages;
    }
    public static function updateInfo (array $fields)
    {
        Message::where('id',$fields['id'])->update([
            'name' => $fields['name'],
            'text' => $fields['text'],
        ]);
    }
}
