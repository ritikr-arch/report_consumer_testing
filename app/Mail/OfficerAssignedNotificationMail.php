<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OfficerAssignedNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $complaint_id;
    public $officer_name;

    public function __construct($complaint_id, $officer_name = null)
    {
        $this->complaint_id = $complaint_id;
        $this->officer_name = $officer_name;
    }

    public function build()
    {
        return $this->subject('New Complaint Assigned to You')
                    ->view('emails.officerassigned');
    }
}
