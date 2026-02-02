<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class Feedback extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = ['name', 'email', 'comment', 'rating', 'is_read'];

    protected static $logName = 'feedback';

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;


    protected $table = 'feedbacks';
    protected $fillable = ['id','name', 'email', 'comment', 'rating', 'created_at', 'updated_at'];

    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()
            ->useLogName('feedback')
            ->logOnly(['name', 'email', 'comment', 'rating', 'is_read'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

}
