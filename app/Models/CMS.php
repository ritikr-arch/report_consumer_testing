<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class CMS extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = ['type', 'title', 'image', 'content', 'slug'];

    protected static $logName = 'cms';

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;


    protected $table = 'cms';
    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()
            ->useLogName('cms')
            ->logOnly(['type', 'title', 'image', 'content', 'slug'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }



}
