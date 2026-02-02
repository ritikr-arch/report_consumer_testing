<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class Enquiry extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = ['id', 'name', 'email','country_code', 'phone', 'message', 'type', 'status', 'created_at', 'updated_at', 'is_read', 'category_id'];

    protected static $logName = 'enquiries';

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;


    protected $table = 'enquiries';
    protected $fillable = ['id', 'name', 'email','country_code', 'phone', 'message', 'type', 'status', 'created_at', 'updated_at', 'is_read', 'category_id'];

    public function enquiryCategory(){
        return $this->belongsTo(EnquiryCategory::class, 'category_id', 'id');
    }

    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()
            ->useLogName('enquiries')
            ->logOnly(['id', 'name', 'email','country_code', 'phone', 'message', 'type', 'status', 'created_at', 'updated_at', 'is_read', 'category_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }


}
