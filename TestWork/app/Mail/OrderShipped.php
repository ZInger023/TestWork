<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderShipped extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $message;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user,Message $message)
    {
        $this->message = $message;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
            $from = $this->user->role;
        return $this->from($from)->view('emailMessage');
    }
}
