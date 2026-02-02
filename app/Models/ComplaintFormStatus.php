<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ComplaintFormStatus extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = ['complaints_id', 'official_use_supervisior', 'official_use_date','official_use_exhibits', 'official_use_feedback', 'official_use_signature', 'official_use_end_date', 'status', 'official_use_remark'];

    protected static $logName = 'complaints status';

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;


    protected $table = 'customer_complaints_status';
    protected $guarded = [''];

    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()
            ->useLogName('complaints status')
            ->logOnly(['complaints_id', 'official_use_supervisior', 'official_use_date','official_use_exhibits', 'official_use_feedback', 'official_use_signature', 'official_use_end_date', 'status', 'official_use_remark'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
