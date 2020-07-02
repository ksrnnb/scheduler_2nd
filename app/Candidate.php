<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    //
    public $timestamps = false;
    protected $primaryKey = 'candidateId';

    public function schedule() {
        return $this->belongsTo('App\Schedule', 'scheduleId', 'scheduleId');
    }

    public function availabilities() {
        return $this->hasMany('App\Availability', 'candidateId', 'candidateId');
    }

    // public function users() {
    //     return $this->belongsToMany('App\User', 'availabilities', 'candidateId', 'userId');
    // }
}
