<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questionexplanation extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'explanation',
    ];

    public function qustion(){
        return $this->belongsTo('App\Models\Question');
    }
}
