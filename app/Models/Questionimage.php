<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Questionimage extends Model
{
    public function qustion(){
        return $this->belongsTo('App\Models\Question');
    }
}
