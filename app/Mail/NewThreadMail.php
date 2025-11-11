<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewThreadMail extends Mailable
{
    use Queueable, SerializesModels;

    public $thread;

    /**
     * Create a new message instance.
     */
    public function __construct($thread)
    {
        $this->thread = $thread;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->view('mails.new_thread')
                    ->subject('Notifikasi Thread Baru')
                    ->with([
                        'thread' => $this->thread,
                    ]);
    }
}
