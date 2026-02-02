<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyProduct extends Model
{
    use HasFactory;

    protected $table = 'survey_products';

    protected $fillable = ['survey_id', 'category_id', 'product_id', 'status'];
    
}
