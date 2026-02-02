<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class ConsumerEducationKids extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = ['title', 'image', 'content', 'slug'];

    protected static $logName = 'consumer_education_for_kids';

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;


    protected $table = 'consumer_education_for_kids';
    protected $fillable = ['title', 'image', 'content', 'status'];

    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()
            ->useLogName('consumer_education_for_kids')
            ->logOnly(['title', 'image', 'content', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }


}
