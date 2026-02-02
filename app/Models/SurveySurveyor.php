<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class SurveySurveyor extends Model

{

    use HasFactory;
    protected $table = 'survey_surveyors';
    protected $fillable = ['survey_id', 'surveyor_id', 'status'];

    public function surveySurveyorsss()
    {
        return $this->belongsTo(User::class, 'surveyor_id', 'id');
    }
}

