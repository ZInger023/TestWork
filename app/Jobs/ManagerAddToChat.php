<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;
use App\Models\User;
use App\Mail\ManagerSendToChat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ManagerAddToChat implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected  $message;
    protected  $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Message $message,User $user)
    {
        $this->message = $message;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->user->email)->send(new ManagerSendToChat($this->message,Auth::user()));
    }
}
