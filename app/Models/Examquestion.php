<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Examquestion extends Model
{

    protected $fillable = [
        'exam_id',
        'question_id',
    ];

    public function exam(){
        return $this->belongsTo('App\Models\Exam');
    }

    public function question(){
        return $this->belongsTo('App\Models\Question');
    }
}
