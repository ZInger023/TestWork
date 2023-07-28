<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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

    public static function NotPngOrJpg ()
    {
            throw new NotPngOrJpgException('Ошибка!Вы загружаете не png и не jpg файл.');
    }

    public static function insertImage (string $path,int $id)
    {
            Image::create([
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
