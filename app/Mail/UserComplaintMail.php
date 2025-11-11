<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserComplaintMail extends Mailable
{
    use Queueable, SerializesModels;

    public $fullname;
    public $facultyName;
    public $bpiNumber;
    public $complaintTypeTitle;
    
    /**
     * Create a new message instance.
     */
    public function __construct($fullname, $facultyName, $bpiNumber, $complaintTypeTitle)
    {
        $this->fullname = $fullname;
        $this->facultyName = $facultyName;
        $this->bpiNumber = $bpiNumber;
        $this->complaintTypeTitle = $complaintTypeTitle;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New User Complaint')
                    ->view('mails.user_complaint') 
                    ->with([
                        'fullname' => $this->fullname,
                        'facultyName' => $this->facultyName,
                        'bpiNumber' => $this->bpiNumber,
                        'complaintTypeTitle' => $this->complaintTypeTitle,
                    ]);
    }
}
