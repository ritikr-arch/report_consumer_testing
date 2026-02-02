<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class FAQ extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = ['slug', 'type_id', 'title', 'description', 'status'];

    protected static $logName = 'faqs';

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;


    protected $table = 'faqs';
    protected $fillable = ['id','slug', 'type_id', 'title', 'description', 'status'];

    public function types(){
        return $this->hasOne(FAQTYPE::class, 'id', 'type_id');
    }

    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()
            ->useLogName('faqs')
            ->logOnly(['slug', 'type_id', 'title', 'description', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }



}
