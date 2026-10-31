<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';
    protected $guarded = [];

    // PSR-12 স্ট্যান্ডার্ড অনুযায়ী camelCase ব্যবহার
    public function courseexams()
    {
        return $this->hasMany(Courseexam::class, 'course_id');
    }

    public function meritlists()
    {
        return $this->hasMany(Meritlist::class, 'course_id');
    }
}