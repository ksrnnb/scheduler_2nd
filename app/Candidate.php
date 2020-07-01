<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    //
    public function schedule() {
        return $this->belongsTo('App\Schedule', 'scheduleId');
    }

    public function availabilities() {
        return $this->hasMany('App\Availability');
    }
}
