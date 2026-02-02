<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyMarket extends Model
{
    use HasFactory;

    protected $table = 'survey_markets';

    protected $fillable = ['survey_id', 'market_id', 'status'];

    public function surveyMarketss(){
        return $this->belongsTo(Market::class, 'market_id', 'id');
    }
}
