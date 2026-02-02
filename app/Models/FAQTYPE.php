<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class FAQTYPE extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = ['type', 'status'];

    protected static $logName = 'faq type';

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;
    
    protected $table = 'faq_types';
    protected $fillable = ['id','type', 'status', 'created_at', 'updated_at'];

    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()
            ->useLogName('faq type')
            ->logOnly(['type', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }


}
