<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmountUpdateLog extends Model
{
    use HasFactory;

    protected $table = 'amount_update_logs';

    protected $fillable = ['submitted_survey_id', 'survey_id', 'old_amount', 'new_amount', 'old_amount_1', 'new_amount_1', 'updated_by', 'updated_at'];


    public function updatedBy(){
        return $this->hasOne(User::class, 'id', 'updated_by');
    }

    public function submittedSurvey(){
        return $this->hasOne(SubmittedSurvey::class, 'id', 'submitted_survey_id');
    }


}
