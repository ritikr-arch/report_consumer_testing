<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublishLog extends Model
{

    use HasFactory;
    protected $table = 'publish_logs';
    protected $fillable = ['survey_id', 'updated_by', 'status', 'type'];

    public function survey(){
        return $this->hasMany(Survey::class, 'id', 'survey_id');
    }

    public function user(){
        return $this->hasOne(User::class, 'id', 'updated_by');
    }

}

