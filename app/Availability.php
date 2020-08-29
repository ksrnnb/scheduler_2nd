<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\Pivot;

class Availability extends Model
// class Availability extends Pivot
{
    //
    // protected $fillable = ['userId'];
    protected $guarded = [];
    protected $primaryKey = ['userId', 'candidateId'];
    public $incrementing = false;

    public $timestamps = false;
    // protected $tables = 'availabilities';
    
    public function users() {
        return $this->belongsToMany('App\Availability', 'App\User', 'userId', 'userId');
        // return $this->belongsToMany('user', 'userId', 'userId');
    }

    public function candidates() {
        return $this->belongsToMany('App\Availability', 'App\Candidate', 'candidateId', 'candidateId');
    }
}
