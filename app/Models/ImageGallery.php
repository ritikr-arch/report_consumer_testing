<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class ImageGallery extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = ['title', 'slug', 'image', 'status'];

    protected static $logName = 'image_gallery';

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;


    protected $table = 'image_galleries';
    protected $fillable = ['id','title', 'slug', 'image', 'status'];

    public function multiImages(){
        return $this->hasMany(GalleryMultiImage::class, 'image_gallery_id', 'id');
    }

    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()
            ->useLogName('image_gallery')
            ->logOnly(['title', 'slug', 'image', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

}
