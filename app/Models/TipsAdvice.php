<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class TipsAdvice extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = ['title', 'link', 'content', 'status'];

    protected static $logName = 'tips and advice';

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;


    protected $table = 'tips_advice';
    protected $fillable = ['id','title', 'link', 'content', 'status'];

    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()
            ->useLogName('tips and advice')
            ->logOnly(['title', 'link', 'content', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }


}
