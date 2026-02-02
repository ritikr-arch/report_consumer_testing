<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\UserDetails;

use Spatie\Permission\Traits\HasRoles;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, LogsActivity;


    protected static $logAttributes = ['title', 'name', 'email', 'image', 'status', 'password', 'user_devices'];

    protected static $logName = 'users';

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'name',
        'email',
        'image',
        'status',
        'password',
        'user_devices'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function devices(){
        return $this->hasMany(UserDevice::class, );
    }

    // public function userdetails(){
    //     return $this->hasOne(UserDetails::class,'user_id', 'id');
    // }
    
    public function userdetails(){
        return $this->belongsTo(UserDetails::class,'user_id', 'id' );
    }

    public function investigationOfficer()
    {
        return $this->belongsTo(Survey::class,'investigation_officer', 'id' );
    }

    public function chiefofficer()
    {
        return $this->belongsTo(Survey::class,'chief_investigation_officer', 'id' );
    }

    public function getActivitylogOptions(): LogOptions{
        return LogOptions::defaults()
            ->useLogName('users')
            ->logOnly(['title', 'name', 'email', 'image', 'status', 'password', 'user_devices'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getDescriptionForEvent(string $eventName): string{
        return "User is {$eventName}";
    }



}
