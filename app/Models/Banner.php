<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class Banner extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = ['title', 'type', 'slug', 'image', 'video', 'status'];

    protected static $logName = 'banners';

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;


    protected $table = 'banners';
    protected $fillable = ['title', 'type', 'slug', 'image', 'video', 'status'];

    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()
            ->useLogName('banners')
            ->logOnly(['title', 'type', 'slug', 'image', 'video', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

}
