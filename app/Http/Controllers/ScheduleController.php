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
                // Log::debug($countAvailabilities);
                
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

        // $queryId = ...
        $form = $request->all();
        unset($form['_token']);

        // get candidates associative array
        // $candidateId => $symbol (integer)
        $candidatesArray = array_filter($form, function($value, $key) {
            return (is_int($key));
        }, ARRAY_FILTER_USE_BOTH);

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
                // たぶんこれでいける。。。？
                User::find($user["userId"])->save($user);
                return NULL;    //ちゃんと返す？
            } else {
                unset($user["userId"]);
                $schedule = Schedule::find($user["scheduleId"]);
                $userInstance = $schedule->users()->create($user);
                // return $userInstance["userId"];
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
                // return var_dump($array);
                // return var_dump($user);

                array_push($test, $array);

                // $user->availabilities()->create($array);
                // return var_dump($array);
                Availability::create($array);
                // $a_model = new Availability;
                // $a_model->create($a_array);
            }

            return var_dump($test);
        }

        // $userId = registerUser($user);
        $userInstance = registerUser($user);

        // if(is_null($user["userId"])) {
        //     $user["userId"] = $userId;
        // }

        // return '<p>' . var_dump($user) . '</p>';

        registerAvaialbility($userInstance, $candidatesArray);
        // registerAvaialbility($user, $candidatesArray);

        return '<p>' . var_dump($form) . '</p>';

        

    }

}
