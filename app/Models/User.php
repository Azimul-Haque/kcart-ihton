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

    /* ---------------------------------------------------------
     * Eloquent Relationships (সিস্টেমের কোর রিলেশনশিপ)
     * --------------------------------------------------------- */

    /**
     * ইউজারের নিজের ডেস্কে বর্তমানে থাকা সমস্ত ফাইল 
     * (ফ্লাটার অ্যাপে "My Desk" বা "আমার ফাইলসমূহ" স্ক্রিনে এটি কল হবে)
     */
    public function filesOnDesk()
    {
        return $this->hasMany(File::class, 'current_desk_id');
    }

    /**
     * ইউজার যেসব ফাইল নিজে সিস্টেমে এন্ট্রি/তৈরি করেছেন
     */
    public function filesCreated()
    {
        return $this->hasMany(File::class, 'created_by');
    }

    /**
     * ইউজার যেসব ফাইল অন্য ডেস্কে পাঠিয়েছেন (Transfer History)
     */
    public function filesForwarded()
    {
        return $this->hasMany(FileMovement::class, 'from_user_id');
    }

    /**
     * ইউজার যেসব ফাইল অন্য ডেস্ক থেকে রিসিভ করেছেন (Receive History)
     */
    public function filesReceived()
    {
        return $this->hasMany(FileMovement::class, 'to_user_id');
    }

    /**
     * ইউজার (যেমন: এসি ল্যান্ড) যেসব ফাইলের শুনানি নিয়েছেন
     */
    public function hearingsConducted()
    {
        return $this->hasMany(Hearing::class, 'conducted_by');
    }
}
