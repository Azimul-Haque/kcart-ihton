<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_id',
        'question',
        'option1',
        'option2',
        'option3',
        'option4',
        'answer',
    ];
    
    public function topic(){
        return $this->belongsTo('App\Models\Topic');
    }

    public function questionexplanation(){
        return $this->hasOne('App\Models\Questionexplanation');
    }

    public function questionimage(){
        return $this->hasOne('App\Models\Questionimage');
    }

    public function examquestion(){
        return $this->hasOne('App\Models\Examquestion');
    }

    public function reportedquestions(){
        return $this->hasMany('App\Models\Reportedquestion');
    }

    protected static function boot()
    {
        parent::boot();

        // 1. Clear the cache when a new Question is created
        static::created(function () {
            Cache::forget('total_questions_count');
        });

        // 2. Clear the cache when a Question is deleted
        static::deleted(function () {
            Cache::forget('total_questions_count');
        });
        
        // You may also want to do this on 'updated' if you track soft-deletes
    }
}
