<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Broachers extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = ['type', 'title', 'image', 'document', 'content','status'];

    protected static $logName = 'broachers';

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;


    protected $table = 'broachers_presentations';
    protected $fillable = ['type', 'title', 'image', 'document', 'content','status'];


    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()
            ->useLogName('broachers')
            ->logOnly(['type', 'title', 'image', 'document', 'content','status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

}
