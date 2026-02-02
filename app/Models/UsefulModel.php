<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class UsefulModel extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = ['type', 'title', 'content', 'created_at', 'updated_at'];

    protected static $logName = 'useful_links';

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;

    
    protected $table = 'useful_links';
    protected $fillable = ['id', 'type', 'title', 'content', 'created_at', 'updated_at'];

    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()
            ->useLogName('useful_links')
            ->logOnly(['type', 'title', 'content', 'created_at', 'updated_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    
}
