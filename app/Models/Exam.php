<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    public function examcategory(){
        return $this->belongsTo('App\Models\Examcategory');
    }

    public function examquestions(){
        return $this->hasMany('App\Models\Examquestion');
    }

    public function course(){
        return $this->belongsTo('App\Models\Course', 'course_id');
    }

    public function courseexams(){
        return $this->hasMany('App\Models\Courseexam');
    }

    public function meritlists(){
        return $this->hasMany('App\Models\Meritlist');
    }
}
