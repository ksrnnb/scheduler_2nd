<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;

// use App\Schedule;
// use App\Candidate;
// use App\Availability;

class User extends Authenticatable
{
    protected $guarded = ['userId'];
    
    protected $primaryKey = 'userId';
    public $timestamps = false;


    public function schedule() {
        return $this->belongsTo('App\Schedule', 'scheduleId', 'scheduleId');
    }

    public function availabilities() {
        return $this->hasMany('App\Availability', 'userId', 'userId');
    }

    public static function getParamsForAddPage($schedule) {

        $users = User::where('scheduleId', $schedule->scheduleId)->orderBy('userId', 'asc')->get();
            
        $candidates = Candidate::where('scheduleId', $schedule->scheduleId)->orderBy('candidateId', 'asc')->get();

        $availabilities = Availability::where('scheduleId', $schedule->scheduleId)->get();

        $candidatesArray = [];
        $availabilitiesArray = [];
        $countAvailabilities = [];

        foreach($candidates as $candidate) {
            $candidatesArray[$candidate->candidateId] = $candidate->candidateDate;
        }

        /*
            $candidatesArray
            array(n) sorted by candidate date
                array (candidateId) => (candidateDate)
                integer 1 => string 'mm/dd (day)',
                integer 2 => string 'mm/dd (day)',
        */

        // When schedule has been made...
        if (! $availabilities->isEmpty()) {
            foreach($availabilities as $availability) {
                $availabilitiesArray[$availability->candidateId][$availability->userId] = $availability->availability;
            }

            foreach($candidates as $candidate) {
                $temp = [0, 0, 0];
                foreach($availabilitiesArray[$candidate->candidateId] as $availability) {
                    $temp[$availability] += 1;
                }
                $countAvailabilities = array_merge($countAvailabilities, [
                    'candidate' . $candidate->candidateId => $temp,
                ]);
                
            }
        //  When schedule has not been made...
        } else {
            foreach($candidates as $candidate) {
                $id = $candidate->candidateId;
                $countAvailabilities = array_merge($countAvailabilities, [
                    'candidate' . $candidate->candidateId => [0, 0, 0]
                ]);
                $availabilitiesArray[$id] = [];
            }
        }

        /*
            $availabilitiesArray
            array(n)(m) 
                (candidateId) => array( (userId) => (availability) )
                integer 1 => array( integer 1 => string '0', integer 2 => ... )
                integer 2 => ...
        */

        $params = array(
            'scheduleId' => $schedule->scheduleId,
            'scheduleName' => $schedule->scheduleName,
            'users' => $users,
            'uuid' => $schedule->scheduleUuid,
            'candidates' => $candidatesArray,
            'availabilities' => $availabilitiesArray,
            'countAvailabilities' => $countAvailabilities,
        );

        return $params;
    }

    // use Notifiable;

    // /**
    //  * The attributes that are mass assignable.
    //  *
    //  * @var array
    //  */
    // protected $fillable = [
    //     'name', 'email', 'password',
    // ];

    // /**
    //  * The attributes that should be hidden for arrays.
    //  *
    //  * @var array
    //  */
    // protected $hidden = [
    //     'password', 'remember_token',
    // ];

    // /**
    //  * The attributes that should be cast to native types.
    //  *
    //  * @var array
    //  */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];
}
