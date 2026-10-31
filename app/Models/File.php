<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class File extends Model
{
    use HasFactory;
    protected $guarded = [];

    // UUID Auto Generate
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    // Status Constants
    const STATUS_INITIATED = 'রুজু হয়েছে';
    const STATUS_UNDER_INVESTIGATION = 'তদন্তাধীন';
    const STATUS_FOR_HEARING = 'শুনানির জন্য';
    const STATUS_FOR_ORDER = 'আদেশের জন্য';
    const STATUS_FINAL_HEARING = 'চূড়ান্ত শুনানি';
    const STATUS_RESOLVED = 'নিষ্পত্তি';

    // Relationships
    public function currentDesk() {
        return $this->belongsTo(User::class, 'current_desk_id');
    }

    public function movements()
    {
        return $this->hasMany(FileMovement::class, 'file_id')->orderBy('created_at', 'desc');
    }

    public function hearings() {
        return $this->hasMany(Hearing::class)->orderBy('hearing_date', 'desc');
    }
}
