<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Setting extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = ['company_image', 'company_title', 'company_address', 'company_registration_no', 'email_address', 'email_support', 'password', 'host', 'port', 'user_name', 'phone', 'base_url', 'social_fb', 'social_instagram', 'social_twitter', 'linked_in', 'social_youtube', 'social_quora', 'favicon', 'admin_logo', 'date_format', 'price_collection', 'admin_email'];

    protected static $logName = 'settings';

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;


    protected $table = 'settings';
    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()
            ->useLogName('settings')
            ->logOnly(['company_image', 'company_title', 'company_address', 'company_registration_no', 'email_address', 'email_support', 'password', 'host', 'port', 'user_name', 'phone', 'base_url', 'social_fb', 'social_instagram', 'social_twitter', 'linked_in', 'social_youtube', 'social_quora', 'favicon', 'admin_logo', 'date_format', 'price_collection', 'admin_email'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }


}
