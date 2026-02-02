<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ComplaintMail extends Mailable
{
    use Queueable, SerializesModels;

    public $complains;
    public function __construct($complains)
    {
        $this->complains = $complains;
    }

    public function build()
    {
        return $this->subject('Complaint Feedback')
            ->view('emails.complaint')
            ->with(['user'=>$this->complains]);
    }
   
}