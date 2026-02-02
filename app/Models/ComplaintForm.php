<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ComplaintForm extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = ['complaint_id', 'first_name', 'last_name', 'email', 'country_code', 'phone', 'address', 'gender', 'age_group', 'business_name', 'business_email', 'business_country_code', 'business_phone', 'business_address', 'service', 'serial', 'category', 'date_of_purchase', 'warranty', 'brand', 'hire_purchase_item', 'sign_contract', 'additional_statement', 'documents', 'signed', 'date', 'official_use_supervisior', 'investing_officer', 'official_use_date', 'official_use_exhibits', 'official_use_result', 'official_use_signature', 'official_use_end_date', 'remark', 'email_verified', 'status', 'is_completed'];

    protected static $logName = 'complaints';

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;


    protected $table = 'customer_complaints';
    protected $guarded = [''];

    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()
            ->useLogName('complaints')
            ->logOnly(['complaint_id', 'first_name', 'last_name', 'email', 'country_code', 'phone', 'address', 'gender', 'age_group', 'business_name', 'business_email', 'business_country_code', 'business_phone', 'business_address', 'service', 'serial', 'category', 'date_of_purchase', 'warranty', 'brand', 'hire_purchase_item', 'sign_contract', 'additional_statement', 'documents', 'signed', 'date', 'official_use_supervisior', 'investing_officer', 'official_use_date', 'official_use_exhibits', 'official_use_result', 'official_use_signature', 'official_use_end_date', 'remark', 'email_verified', 'status', 'is_completed'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected static function booted(): void {
        static::created(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user() ?? null)
                ->withProperties($model->only($model->getActivitylogOptions()->logAttributes ?? []))
                ->log('created');
        });
    }



}
