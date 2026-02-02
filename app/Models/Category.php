<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Category extends Model
{

    use HasFactory,LogsActivity;


    protected static $logAttributes = ['name', 'image', 'status', 'type_id'];

    protected static $logName = 'stores';

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;


    protected $table = 'categories';
    protected $fillable = ['name', 'image', 'status', 'type_id'];

    public function commodities(){
        return $this->hasMany(Commodity::class, 'category_id', 'id');
    }

    public function submittedSurveys(){
        return $this->hasMany(SubmittedSurvey::class);
    }

    public function market()
    {
        return $this->belongsTo(Market::class);
    }
    public function type(){
        return $this->hasOne(Type::class, 'id', 'type_id');
    }

    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()
            ->useLogName('categories')
            ->logOnly(['name', 'image', 'status', 'type_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }



}

