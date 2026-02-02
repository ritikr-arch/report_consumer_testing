<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PublicHealthAct extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = ['id','title', 'image', 'content', 'status'];

    protected static $logName = 'public_health_acts';

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;


    protected $table = 'public_health_acts';
    protected $fillable = ['id','title', 'image', 'content', 'status'];

    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()
            ->useLogName('public_health_acts')
            ->logOnly(['id','title', 'image', 'content', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }


}
