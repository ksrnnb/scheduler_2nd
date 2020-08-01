<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Schedule;
use App\User;
use App\Candidate;
use App\Availability;

use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{

    public function index() {
        return view('index');
    }

    public function create() {
        //Need to validate and registrate data
        $scheduleId = Str::uuid();
        return redirect('/add?id=' . $scheduleId);
    }

    public function add(Request $request) {
        
        $queryId = $request->query('id');

        $schedule = Schedule::where('scheduleUuid', $queryId)->first();

        if(isset($schedule)) {

            $users = User::where('scheduleId', $schedule->scheduleId)->orderBy('userId', 'asc')->get();
            
            $candidates = Candidate::where('scheduleId', $schedule->scheduleId)->orderBy('CandidateDate', 'asc')->get();

            $availabilities = Availability::where('scheduleId', $schedule->scheduleId)->get();

            $candidatesArray = [];
            $availabilitiesArray = [];
            $countAvailabilities = [];

            foreach($candidates as $candidate) {
                $candidatesArray[$candidate->candidateId] = $candidate->candidateDate;
            }

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

            $params = array(
                'scheduleId' => $schedule->scheduleId,
                'scheduleName' => $schedule->scheduleName,
                'users' => $users,
                'uuid' => $queryId,
                'candidates' => $candidatesArray,
                'availabilities' => $availabilitiesArray,
                'countAvailabilities' => $countAvailabilities,
            );

            return view('add', ['id' => $queryId, 'params' => $params]);
        } else {
            return redirect('/');
        }
    }

    public function update(Request $request) {

        $form = $request->all();
        unset($form['_token']);

        /*
            $form 
            'scheduleId' => integer,
            'userName' => string,
            'candidate_*' => 0 or 1 or 2,
            'id' => uuid
        */

        $validateRule = [
            'scheduleId' => 'required|integer|min:1',
            'userName' => 'required|string',
            'id' => 'required|uuid',
        ];

        $candidatesArray = [];
        foreach($form as $key => $value) {
            if (preg_match('/candidate_[1-9][0-9]*/', $key)) {
                
                $candidateId = (int)explode('_', $key)[1];
                $candidatesArray[$candidateId] = $value;

                // adding validation rule
                $validateRule['candidate_' . $candidateId] = 'integer|between:0,2';
            }
        }

        // Validation
        // return redirectにしてるからエラーあっても現状ではリダイレクトするだけ。
        $request->validate($validateRule);

        $scheduleId = $form["scheduleId"];

        if (!isset($form["userId"])) {
            $user = [
                "userId" => NULL,
                "userName" => $form["userName"],
                "scheduleId" => $scheduleId,
            ];
        } else {
            $user = [
                "userId" => $form["userId"],
                "userName" => $form["userName"],
                "scheduleId" => $scheduleId,
            ];
        }

        function registerUser($user) {
            if ($user["userId"]) {
                // ここ修正必要。。。
                $userInstance = User::find($user["userId"])->save($user);
                return $userInstance;
            } else {
                unset($user["userId"]);
                $schedule = Schedule::find($user["scheduleId"]);
                $userInstance = $schedule->users()->create($user);

                return $userInstance;
            }
        }

        function registerAvaialbility($user, $candidatesArray) {
            $test = [];
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

        $userInstance = registerUser($user);

        registerAvaialbility($userInstance, $candidatesArray);

        // return view('')
        return redirect('/add?id=' . $form["id"]);

    
    }

}
