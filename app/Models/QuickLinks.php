<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class QuickLinks extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = ['title', 'image', 'content', 'slug', 'button', 'document', 'status'];

    protected static $logName = 'quick_links';

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;


    protected $table = 'quick_links';
    protected $fillable = ['title', 'image', 'content', 'slug', 'button', 'document', 'status'];


    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()
            ->useLogName('quick_links')
            ->logOnly(['title', 'image', 'content', 'slug', 'button', 'document', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

}
