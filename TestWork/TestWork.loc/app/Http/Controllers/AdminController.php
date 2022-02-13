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
            return ($exception->getMessage());
        }
        switch ($request['status']) {
            case 'open':
                $up = DB::table('messages')
                    ->where('status', 'open')
                    ->get();

                break;
            case 'closed':
                $up = DB::table('messages')
                    ->where('status', 'closed')
                    ->get();
                break;
            case 'viewed':
                $up = DB::table('messages')
                    ->where('status', 'viewed')
                    ->get();
                break;
            case 'unviewed':
                $up = DB::table('messages')
                    ->where('status', 'open')
                    ->get();
                break;
            case 'answered':
                $up = DB::table('messages')
                    ->where('status', 'answered')
                    ->get();
                break;
            case 'unanswered':
                $up = DB::table('messages')
                    ->where('status','!=' ,'answered')
                    ->get();
                break;
            default:
            return redirect()->intended('dashboard');
        }
        return view('/showToManager',['ups' => $up]);
    }
    public function showAll()
    {
        try {
            User::isManager();
        }
        catch (NotManagerException $exception)
        {
            return ($exception->getMessage());
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
            return ($exception->getMessage());
        }
        $id = $request->route('id');
        Message::setMessageViewed($id);
        return redirect()->intended('dashboard');
    }
    public function showManagerPage ()
    {
        try {
            User::isManager();
        }
        catch (NotManagerException $exception)
        {
            return ($exception->getMessage());
        }
        return view('managerPage');
    }
}

