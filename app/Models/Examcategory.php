<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examcategory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'examcategories'; // টেবিলের সঠিক নাম ডিফাইন করা হলো

    protected $guarded = [];

    public function exams(){
        return $this->hasMany('App\Models\Exam');
    }
}
