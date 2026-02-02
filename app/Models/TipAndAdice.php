<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class TipAndAdice extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = ['title', 'image', 'content', 'status'];

    protected static $logName = 'tips_advice';

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;


    protected $table = 'tips_and_advices';
    protected $fillable = ['title', 'image', 'content', 'status'];

    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()
            ->useLogName('tips_advice')
            ->logOnly(['title', 'image', 'content', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

}
