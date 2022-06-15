<?php

namespace app\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\NotManagerException;
use App\Exceptions\NotUserException;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $attributes = [
        'role' => 'user'
    ];
    public function messages()
    {
        return $this->hasMany(Message::class,'author_id');
    }
    public static function isManager()
    {
        if (empty(Auth::user())) {
            throw new NotManagerException ('Для этого нужно быть менеджером.');
        }
        if (Auth::user()->role !== 'manager') {
            throw new NotManagerException ('Для этого нужно быть менеджером.');
        }
    }
    public static function isUser()
    {
        if (empty(Auth::user())) {
            throw new NotUserException ('Для этого нужно быть авторизованным пользователем.');
        }
        if (Auth::user()->role !== 'user') {
            throw new NotUserException ('Для этого нужно быть авторизованным пользователем.');
        }
    }
    public static function isAuthorized()
    {
        if (empty(Auth::user())) {
            throw new NotUserException ('Для этого нужно быть авторизованным пользователем.');
        }
        if ((Auth::user()->role !== 'user')&&(Auth::user()->role !== 'manager')) {
            throw new NotUserException ('Для этого нужно быть авторизованным пользователем.');
        }
    }
    public static function isAuthorOrManager(int $author_id)
    {
        if ( ((Auth::user()->role == 'user')&&(Auth::user()->id !== $author_id))) {
            throw new NotUserException ('Вы не можете просмотреть эту заявку.');
        }
    }

    public static function getUserName (int $id)
    {
        $user = User::find($id);
        return $user->name;
    }
}
