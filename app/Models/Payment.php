<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public function user() {
      return $this->belongsTo('App\Models\User');
    }

    public function package() {
      return $this->belongsTo('App\Models\Package');
    }
}
