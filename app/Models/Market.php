<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class Market extends Model
{


    use HasFactory, LogsActivity;

    protected static $logAttributes = ['zone_id', 'name', 'image', 'zone_name', 'zone_details', 'status'];

    protected static $logName = 'stores';

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;



    protected $table = 'markets';

    protected $fillable = ['zone_id', 'name', 'image', 'zone_name', 'zone_details', 'status'];

    public function submittedSurveys(){
        return $this->hasMany(SubmittedSurvey::class);
    }

    public function categories()
   {
       return $this->hasMany(Category::class);
   }

   public function commodities()
   {
       return $this->hasMany(Commodity::class);
   }

    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()
            ->useLogName('stores')
            ->logOnly(['zone_id', 'name', 'image', 'zone_name', 'zone_details', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }



}

