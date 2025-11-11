<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    public $fullname;
    public $email;
    public $degree;
    public $facultyName;
    public $studyProgramName;

    /**
     * Create a new message instance.
     *
     */
    public function __construct($fullname, $email, $degree, $facultyName, $studyProgramName)
    {
        $this->fullname = $fullname;
        $this->email = $email;
        $this->degree = $degree;
        $this->facultyName = $facultyName;
        $this->studyProgramName = $studyProgramName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.user_registered')
                    ->subject('Notifikasi Pendaftaran Pengguna Baru')
                    ->with([
                        'fullname' => $this->fullname,
                        'email' => $this->email,
                        'degree' => $this->degree,
                        'facultyName' => $this->facultyName,
                        'studyProgramName' => $this->studyProgramName,
                    ]);
    }
}
