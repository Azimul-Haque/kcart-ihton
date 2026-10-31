<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileMovement extends Model
{
    use HasFactory;

    protected $guarded = [];

    // ১. ফাইল মডেলের সাথে রিলেশনশিপ (এটি মিসিং ছিল)
    public function file()
    {
        return $this->belongsTo(File::class, 'file_id');
    }

    // ২. প্রেরক ইউজারের সাথে রিলেশনশিপ
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    // ৩. প্রাপক ইউজারের সাথে রিলেশনশিপ
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}
