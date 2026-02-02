<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class GalleryMultiImage extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = ['image_gallery_id', 'name', 'status'];

    protected static $logName = 'multi_images';

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;


    protected $table = 'gallery_multi_images';
    protected $fillable = ['image_gallery_id', 'name', 'status'];

    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()
            ->useLogName('multi_images')
            ->logOnly(['image_gallery_id', 'name', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

}
