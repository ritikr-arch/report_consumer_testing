<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ComplaintStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $complaint_id;
    public $status;
    public $result;
    public $remark;
    public $supervisior;
    public $date;

    public function __construct($name, $complaint_id, $status, $result, $remark, $supervisior, $date)
    {
        $this->name = $name;
        $this->complaint_id = $complaint_id;
        $this->status = $status;
        $this->result = $result;
        $this->remark = $remark;
        $this->supervisior = $supervisior;
        $this->date = $date;
    }

    public function build()
    {
        return $this->subject('Complaint Status Updated - Consumer Affairs')
                    ->view('emails.complaint-status-updated');
    }
}
