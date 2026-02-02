<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ThankYouMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mail)
    {
        $this->name = $mail['name'];
        $this->complaint_id = $mail['complaint_id'];
    }

    public function build()
    {
        return $this->subject('Complaint Submission Confirmation')->view('emails/ComplaintThank', ['name' => $this->name, 'complaint_id' => $this->complaint_id]);
    }
}
