<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $guarded = ['scheduleId'];
    protected $primaryKey = 'scheduleId';
    public $timestamps = false;
    //
    public function users() {
        return $this->hasMany('App\User', 'scheduleId', 'scheduleId');
    }

    public function candidates() {
        return $this->hasMany('App\Candidate', 'scheduleId', 'scheduleId');
    }

    public static function deleteSchedule($schedule) {

        $users = $schedule->users();
        $candidates = $schedule->candidates();

        if (isset($users)) {
            $users->each(function($user) {
                $user->availabilities()->delete();
            });
            $users->delete();
        }
        
        $candidates->delete();

        $schedule->delete();
    }
}
