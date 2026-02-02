<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{

    use HasFactory;

    protected $table = 'types';

    protected $fillable = ['name', 'status'];

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id', 'id');
    }
}


