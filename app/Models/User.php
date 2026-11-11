<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'mobile',
        'role',
        'designation',
        'email',
        'password',
        'onesignal_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function messages(){
        return $this->hasMany('App\Models\Message');
    }

    public function filesOnDesk()
    {
        return $this->hasMany(File::class, 'current_desk_id');
    }

    public function filesCreated()
    {
        return $this->hasMany(File::class, 'created_by');
    }

    public function filesForwarded()
    {
        return $this->hasMany(FileMovement::class, 'from_user_id');
    }

    public function filesReceived()
    {
        return $this->hasMany(FileMovement::class, 'to_user_id');
    }

    public function hearingsConducted()
    {
        return $this->hasMany(Hearing::class, 'conducted_by');
    }
}
