<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $awardee;
    public $note;

    /**
     * Create a new message instance.
     */
    public function __construct($awardee, string $note)
    {
        $this->awardee = $awardee;
        $this->note = $note;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->view('mails.user_rejected')
                    ->subject('Account Registration Rejected')
                    ->with([
                        // 'fullname' => $this->awardee->fullname,
                        // 'email' => $this->awardee->email,
                        // 'bpiNumber' => $this->awardee->bpiNumber,
                        // 'faculty' => $this->awardee->studyProgram->faculty->name,
                        // 'programStudy' => $this->awardee->studyProgram,
                        // 'degree' => $this->awardee->degree,
                        // 'year' => $awardee->year,
                        'awardee' => $this->awardee,
                        'note' => $this->note,
                    ]);
    }
}
