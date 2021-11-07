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
use App\Mail\InsertMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MessageInsertion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected  $message;
    protected  $manager;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Message $message,User $manager)
    {
        $this->message = $message;
        $this->manager = $manager;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->manager->email)->send(new InsertMessage($this->message,Auth::user()));
    }
}
