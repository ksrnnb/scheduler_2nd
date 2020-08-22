<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;

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

    public static function registerUser($user) {
        if ($user["userId"]) {

            $userInstance = User::find($user["userId"]);
            $updateColumn = ["userName" => $user["userName"]];
            $userInstance->fill($updateColumn)->save();

            return $userInstance;
        } else {
            unset($user["userId"]);
            $schedule = Schedule::find($user["scheduleId"]);
            $userInstance = $schedule->users()->create($user);

            return $userInstance;
        }
    }

    public static function registerAvaialbility($user, $candidatesArray) {

        foreach($candidatesArray as $id => $availability) {
            $array = [
                "scheduleId" => $user["scheduleId"],
                "userId" => $user["userId"],
                "candidateId" => $id,
                "availability" => $availability,
            ];

            Availability::create($array);

        }

    }

    public static function deleteUser($form) {
        
        $id = $form["userId"];
        
        if (isset($id)) {
            
            $user = User::find($id);

            // find error : return null
            if(isset($user)) {
                $user->delete();
                $user->availabilities()->delete();
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    public static function getParamsForAddPage($schedule) {

        $users = User::where('scheduleId', $schedule->scheduleId)->orderBy('userId', 'asc')->get();
            
        $candidates = Candidate::where('scheduleId', $schedule->scheduleId)->orderBy('candidateId', 'asc')->get();

        $availabilities = Availability::where('scheduleId', $schedule->scheduleId)->get();

        $candidatesArray = [];
        $availabilitiesArray = [];
        $usersAvailabilities = [];

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
                $userId = $availability->userId;
                $ava = $availability->availability;

                $availabilitiesArray[$availability->candidateId][$userId] = $ava;
                
                if (isset($usersAvailabilities[$userId])) {
                    $usersAvailabilities[$userId] .= $ava . '_';
                } else {
                    $usersAvailabilities[$userId] = $ava . '_';
                }
            }

            foreach($usersAvailabilities as $key => $ava) {
                $usersAvailabilities[$key] = substr($ava, 0, -1);
            }
                
        //  When schedule has not been made...
        } else {
            foreach($candidates as $candidate) {
                $id = $candidate->candidateId;
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
            'usersAvailabilities' => $usersAvailabilities,
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
