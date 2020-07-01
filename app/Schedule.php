<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $primaryKey = 'scheduleId';
    public $timestamps = false;
    //
    public function users() {
        return $this->hasMany('App\User', 'scheduleId', 'scheduleId');
    }

    public function candidates() {
        return $this->hasMany('App\Candidate', 'scheduleId', 'scheduleId');
    }
}
