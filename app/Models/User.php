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
        'role',
        'mobile',
        'email',
        'is_approved',
        'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function payments(){
        return $this->hasMany('App\Models\Payment');
    }

    public function messages(){
        return $this->hasMany('App\Models\Message');
    }

    public function meritlists(){
        return $this->hasMany('App\Models\Meritlist');
    }

    public function reportedquestions(){
        return $this->hasMany('App\Models\Reportedquestion');
    }

    public function blogs(){
        return $this->hasMany('App\Models\Blog');
    }

    public function payoutRequests()
    {
        return $this->hasMany(PayoutRequest::class);
    }

    // protected static function boot()
    // {
    //     parent::boot();

    //     // ইউজার তৈরি হওয়ার ঠিক আগে এই ফাংশনটি কল হবে
    //     static::creating(function ($user) {
    //         // যদি আগে থেকে কোনো কোড না থাকে, তবেই নতুন কোড তৈরি করবে
    //         if (empty($user->referral_code)) {
    //             $user->referral_code = self::generateUniqueCode();
    //         }
    //     });
    // }

    // /**
    //  * একটি ইউনিক ৬ অক্ষরের মিক্সড কোড জেনারেট করার ফাংশন
    //  */
    // public static function generateUniqueCode()
    // {
    //     do {
    //         // ৬ অক্ষরের রেন্ডম স্ট্রিং (যেমন: X72B9P)
    //         $code = strtoupper(Str::random(6));
    //     } while (self::where('referral_code', $code)->exists()); // ডাটাবেসে চেক করবে ডুপ্লিকেট আছে কি না

    //     return $code;
    // }
}
