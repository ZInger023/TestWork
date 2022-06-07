<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Jobs\RegJob;

class Registration extends Model
{
    public static function  SendMessage(string $email)
    {
        RegJob::dispatch($email);
        return;
    }
}
