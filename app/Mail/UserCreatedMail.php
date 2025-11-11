<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $password;
    public $fullname;
    public $token;

    public function __construct($email, $password, $fullname, $token)
    {
        $this->email = $email;
        $this->password = $password;
        $this->fullname = $fullname;
        $this->token = $token;
    }

    public function build()
    {
        $url = url(route('password.reset', ['token' => $this->token, 'email' => $this->email], true));
        return $this->view('mails.user-created')
            ->subject('User Created Notification')
            ->with([
                'email' => $this->email,
                'password' => $this->password,
                'fullname' => $this->fullname,
                'url' => $url
            ]);
    }
}
