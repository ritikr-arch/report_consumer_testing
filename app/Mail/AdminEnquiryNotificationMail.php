<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminEnquiryNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;  // usually the full request or just relevant fields
    }

    public function build()
    {
        return $this->subject('New Enquiry Submission Received')
                    ->view('emails.adminenquirynotification');  // create this view
    }
}
