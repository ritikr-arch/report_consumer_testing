<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Zone extends Model
{

    use HasFactory, LogsActivity;

    protected static $logAttributes = ['name', 'status'];

    protected static $logName = 'zone';

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;



    protected $table = 'zones';

    protected $fillable = ['name', 'status'];

    public function markets(){
        return $this->hasMany(Market::class, 'zone_id', 'id');
    }


    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()
            ->useLogName('zone')
            ->logOnly(['name', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function zonesLog(){
        // return $this->morphedByMany()
    }


}

