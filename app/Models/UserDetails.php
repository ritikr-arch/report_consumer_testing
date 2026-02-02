<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    use HasFactory;
    protected $table = 'user_details';
    protected $fillable = ['user_id', 'contact_number','address', 'date_of_birth','gender','status'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
