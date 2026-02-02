<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ConsumerProtectionBill extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = ['title', 'image', 'content', 'slug'];

    protected static $logName = 'consumer_protection_bills';

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;


    protected $table = 'consumer_protection_bills';
    protected $fillable = ['title', 'image', 'content', 'status'];

    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()
            ->useLogName('consumer_protection_bills')
            ->logOnly(['title', 'image', 'content', 'slug'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }


}
