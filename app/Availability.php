<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    //
    public function user() {
        return $this->belongsTo('user');
    }

    public function candidate() {
        return $this->belongsTo('candidate');
    }
}
