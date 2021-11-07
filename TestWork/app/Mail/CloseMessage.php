<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;
use App\Models\User;

class CloseMessage extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $message;
    public $messageName;
    public $messageId;

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
        $this->messageName = $this->message->name;
        $this->messageId = $this->message->id;
        return $this->view('closeMessage');
    }
}
