<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Exceptions\TimeLimitException;
use App\Jobs\SetMessageClosed;
use App\Jobs\MessageInsertion;
use App\Exceptions\NotAuthorException;
use App\Exceptions\NotPngOrJpgException;

class Image extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'path',
        'message_id',
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    public static function validateType (string $type)
    {
        if(($type=='image/jpg')||($type=='image/png')) {
            return;
        }
        else {
            throw new NotPngOrJpgException('Ошибка!Вы загружаете не png и не jpg файл.');
        }
    }

    public static function insertImage (string $path,int $id)
    {
            $image =  Image::create([
                'path' => $path,
                'message_id' => $id,
            ]);
    }
    public static function getImages (int $id)
    {
        $images = Image::all()->where('message_id', $id);
        return $images;
    }
}
