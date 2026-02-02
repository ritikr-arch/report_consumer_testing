<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnquiryThankYouMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $formType;

    // Receive user's name and form type (getintouch or feedback)
    public function __construct($name, $formType)
    {
        $this->name = $name;
        $this->formType = $formType;
    }

    public function build()
    {
       $subject = 'Thank you for your submission';

    if ($this->formType === 'getintouch') {
        $subject = 'Thank You for Getting in Touch!';
    } elseif ($this->formType === 'feedback') {
        $subject = 'Thanks for Your Feedback!';
    }

    return $this->subject($subject)
                ->view('emails.thankyou')
                ->with([
                    'name' => $this->name,
                    'formType' => $this->formType
                ]);
    }
}
