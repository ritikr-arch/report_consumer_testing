<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class NewsAndUpdate extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = ['title', 'slug', 'iamge', 'description', 'status', 'type'];

    protected static $logName = 'news_and_updates';

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;


    protected $table = 'news_and_updates';

    protected $fillable = ['id', 'title', 'slug', 'iamge', 'description', 'status', 'type'];

    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()
            ->useLogName('news_and_updates')
            ->logOnly(['title', 'slug', 'iamge', 'description', 'status', 'type'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }


}
