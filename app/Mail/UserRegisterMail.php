<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRegisterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $fullname;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($fullname)
    {
        $this->fullname = $fullname;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.user_register')
                    ->subject('Notifikasi Pendaftaran Pengguna Baru')
                    ->with(['fullname' => $this->fullname]);
    }
}
