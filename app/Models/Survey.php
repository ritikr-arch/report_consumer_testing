<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class Survey extends Model
{

    use HasFactory, LogsActivity;

    protected static $logAttributes = ['survey_id', 'name', 'zone_id', 'type_id', 'investigation_officer', 'chief_investigation_officer', 'start_date', 'end_date', 'is_complete', 'status', 'is_approve'];

    protected static $logName = 'survey';

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;



    protected $table = 'surveys';

    protected $fillable = ['survey_id', 'name', 'zone_id', 'type_id', 'investigation_officer', 'chief_investigation_officer', 'start_date', 'end_date', 'is_complete', 'status', 'is_approve'];


    public function zone(){

        return $this->hasOne(Zone::class, 'id', 'zone_id',);

    }



    public function markets(){

        return $this->hasMany(SurveyMarket::class, 'survey_id', 'id');

    }



    public function categories(){

        return $this->hasMany(SurveyCategory::class, 'survey_id', 'id');

    }



    public function products(){

        return $this->hasMany(SurveyProduct::class, 'survey_id', 'id');

    }



    public function surveyors(){

        return $this->hasMany(SurveySurveyor::class, 'survey_id', 'id');

    }

    public function submittedSurveys()
    {
        return $this->hasMany(SubmittedSurvey::class, 'survey_id', 'id');
    }

    public function type()
    {
        return $this->hasMany(Type::class, 'id', 'type_id');
    }

    public function surveyType(){
        return $this->hasOne(Type::class, 'id', 'type_id');
    }

    public function investigationOfficer()
    {
        return $this->hasMany(User::class, 'id', 'investigation_officer');
    }

    public function chiefofficer()
    {
        return $this->hasMany(User::class, 'id', 'chief_investigation_officer');
    }

    public function surveyLog(){
        return $this->hasMany(PublishLog::class, 'survey_id', 'id');
    }

    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()
            ->useLogName('survey')
            ->logOnly(['survey_id', 'name', 'zone_id', 'type_id', 'investigation_officer', 'chief_investigation_officer', 'start_date', 'end_date', 'is_complete', 'status', 'is_approve'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }



}

