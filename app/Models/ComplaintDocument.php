<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintDocument extends Model
{
    use HasFactory;
    protected $table = 'complaint_document';
    protected $fillable = ['id', 'complaint_id', 'document', 'created_at', 'updated_at'];
}
