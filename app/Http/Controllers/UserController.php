<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Schedule;
use App\User;
use App\Candidate;
use App\Availability;

class UserController extends Controller
{
    
    public function add(Request $request) {
        
        $queryId = $request->query('id');

        $schedule = Schedule::where('scheduleUuid', $queryId)->first();

        if(isset($schedule)) {

            $params = User::getParamsForAddPage($schedule);

            return view('add', ['params' => $params]);

        } else {
            return redirect('/');
        }
    }

    public function create(Request $request) {

        function registerUser($user) {
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

        function registerAvaialbility($user, $candidatesArray) {

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

        if (!isset($form["userId"])) {
            $user = [
                "userId" => NULL,
                "userName" => $form["userName"],
                "scheduleId" => $form["scheduleId"],
            ];
        } else {
            $user = [
                "userId" => $form["userId"],
                "userName" => $form["userName"],
                "scheduleId" => $form["scheduleId"],
            ];
        }

        $userInstance = registerUser($user);

        registerAvaialbility($userInstance, $candidatesArray);

        // return view('')
        return redirect('/add?id=' . $form["id"]);

    
    }
}
