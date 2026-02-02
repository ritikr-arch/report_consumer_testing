<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ConsumerCorner extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = ['title', 'link', 'content', 'status'];

    protected static $logName = 'consumer corner';

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;


    protected $table = 'consumer_corner';
    protected $fillable = ['id','title', 'link', 'content', 'status'];

    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()
            ->useLogName('consumer corner')
            ->logOnly(['title', 'link', 'content', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

}
