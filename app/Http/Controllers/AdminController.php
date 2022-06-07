<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exceptions\NotManagerException;

class AdminController extends Controller
{
    public function showToAdmin(Request $request)
    {
        try {
            User::isManager();
        }
        catch (NotManagerException $exception)
        {
            return view('/error',['error' => $exception->getMessage()]);
        }
        $CurrentAndPreviousStatus = explode("|", $request['status']);
        switch ($CurrentAndPreviousStatus[0]) {
            case 'open':
                if((isset($CurrentAndPreviousStatus[1])) && ($CurrentAndPreviousStatus[0]==$CurrentAndPreviousStatus[1])) {
                $up = DB::table('messages')
                    ->where('status', 'closed')
                    ->get();
                    $CurrentAndPreviousStatus[0] = $selectString = 'Закрытые';
                }
                else {
        $up = DB::table('messages')
            ->where('status', 'open')
            ->get();
        $selectString = 'Открытые';
                }
                break;
            case 'viewed':
                if((isset($CurrentAndPreviousStatus[1])) && ($CurrentAndPreviousStatus[0]==$CurrentAndPreviousStatus[1])) {
                    $up = DB::table('messages')
                        ->where('status', 'open')
                        ->get();
                    $CurrentAndPreviousStatus[0] = $selectString = 'Непросмотренные';
                }
                else {
                    $up = DB::table('messages')
                        ->where('status', 'viewed')
                        ->get();
                    $selectString = 'Просмотренные';
                }
                break;
            case 'answered':
                if((isset($CurrentAndPreviousStatus[1])) && ($CurrentAndPreviousStatus[0]==$CurrentAndPreviousStatus[1])) {
                    $up = DB::table('messages')
                        ->where('status','!=' ,'answered')
                        ->get();
                    $CurrentAndPreviousStatus[0] = $selectString = 'Нет ответа';
                }
                else {
                    $up = DB::table('messages')
                        ->where('status', 'answered')
                        ->get();
                    $selectString = 'Есть ответ';
                }
                break;
            default:
            return redirect()->intended('dashboard');
        }
        return view('/showToManager',['ups' => $up,'selectString' => $selectString,'prevStatus' => $CurrentAndPreviousStatus[0]]);
    }
    public function showAll()
    {
        try {
            User::isManager();
        }
        catch (NotManagerException $exception)
        {
            return view('/error',['error' => $exception->getMessage()]);
        }
        $messages = Message::all();
        return view('/allmessages',['messages' => $messages]);
    }
    public function setViewed(Request $request)
    {
        try {
            User::isManager();
        }
        catch (NotManagerException $exception)
        {
            return view('/error',['error' => $exception->getMessage()]);
        }
        $id = $request->route('id');
        Message::setMessageViewed($id);
        //return redirect()->intended('dashboard');
        return redirect()->route('message', ['id' => $id]);
    }
}

