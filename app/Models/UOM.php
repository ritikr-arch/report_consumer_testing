<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class UOM extends Model{

	use HasFactory, LogsActivity;
	protected $table = 'uom';
	protected $fillable = ['name', 'status'];

	protected static $logAttributes = ['name', 'status'];

	protected static $logName = 'uom';

	protected static $logOnlyDirty = true;

	protected static $submitEmptyLogs = false;

	public function getActivitylogOptions(): LogOptions{
	    return LogOptions::defaults()
	        ->useLogName('uom')
	        ->logOnly(['name', 'status'])
	        ->logOnlyDirty()
	        ->dontSubmitEmptyLogs();
	}


}