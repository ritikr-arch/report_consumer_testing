<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyCategory extends Model
{
    use HasFactory;

    protected $table = 'survey_categories';

    protected $fillable = ['survey_id', 'category_id', 'status'];

    public function surveyCategoriesss(){
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    
}
