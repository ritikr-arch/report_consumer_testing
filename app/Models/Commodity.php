<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class Commodity extends Model
{

    use HasFactory, LogsActivity;


    protected static $logAttributes = ['name', 'category_id', 'brand_id', 'uom_id', 'unit_value', 'image', 'status'];

    protected static $logName = 'commodities';

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;


    protected $table = 'commodities';

    protected $fillable = ['name', 'category_id', 'brand_id', 'uom_id', 'unit_value', 'image', 'status'];

    public function category(){

        return $this->hasOne(Category::class, 'id', 'category_id');

    }

    public function brand(){
        return $this->hasOne(Brand::class, 'id', 'brand_id');
    }

    public function uom(){

        return $this->hasOne(UOM::class, 'id', 'uom_id');

    }

    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    // public function category()
    // {
    //     return $this->belongsTo(Category::class);
    // }

    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()
            ->useLogName('commodities')
            ->logOnly(['name', 'category_id', 'brand_id', 'uom_id', 'unit_value', 'image', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }



}

