<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class EnquiryCategory extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = ['name', 'status'];

    protected static $logName = 'enquiry category';

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;


    protected $table = 'enquiry_categories';
    protected $fillable = ['name', 'status'];

    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()
            ->useLogName('enquiry category')
            ->logOnly(['name', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

}
