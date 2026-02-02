<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ComplaintVerifyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct($mail)
    {
        $this->name = $mail['name'];
        $this->path = $mail['path'];
    }

    public function build()
    {
        return $this->subject('Complete your profile | Consumer Affairs')->view('emails/verifyemail', ['name' => $this->name, 'path' => $this->path]);
    }
}
