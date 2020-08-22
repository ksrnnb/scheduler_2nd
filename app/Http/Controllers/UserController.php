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

        // validate
        if (! Str::isUuid($queryId)) {
            return redirect('error');
        }

        $schedule = Schedule::where('scheduleUuid', $queryId)->first();

        if(isset($schedule)) {

            $params = User::getParamsForAddPage($schedule);

            return view('add', ['params' => $params]);

        } else {
            return redirect('error');
        }
    }


    public function create(Request $request) {

        // $request->input()とおなじ
        $form = $request->all();
        unset($form['_token']);

        /*
            $form 
            'scheduleId' => integer,
            'userName' => string,
            'candidate_*' => 0 or 1 or 2,
            'id' => uuid
        */

        if ($request->input('delete')) {

            $isDeleted = User::deleteUser($form);

            if (! $isDeleted) {
                return redirect('/error');
            }

            
        } elseif ($request->input('add')) {

            // $validateRule = [
            //     'scheduleId' => 'required|integer|min:1',
            //     'userName' => 'required|string',
            //     'id' => 'required|uuid',
            // ];

            if(! $this->isValidatedAdd($form)) {
                return redirect('error');
            }
    
            $candidatesArray = [];
            foreach($form as $key => $value) {
                if (preg_match('/candidate_[1-9][0-9]*/', $key)) {
                    
                    $candidateId = (int)explode('_', $key)[1];
                    $candidatesArray[$candidateId] = $value;

                }
            }
    
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
    
            $userInstance = User::registerUser($user);
    
            User::registerAvaialbility($userInstance, $candidatesArray);
        } else {
            return redirect('error');
        }


        return redirect('/add?id=' . $form["id"]);

    
    }

    /*
     *  $form 
     *      'scheduleId' => integer,
     *      'userName' => string,
     *      'candidate_*' => 0 or 1 or 2,
     *      'id' => uuid
     *  
     *  @return boolean
     *  
     */

    public function isValidatedAdd($form) {
        if (! ctype_digit(strval($form['scheduleId']))) {
            return false;
        } elseif (empty($form['userName'])) {
            return false;
        } elseif (! Str::isUuid($form['id'])) {
            return false;
        } else {
            foreach($form as $key => $value) {
                if (preg_match('/candidate_[0-9]+/', $key)) {
                    if (! ($value == 0 || $value == 1 || $value == 2)) {
                        echo 4;
                        return false;
                    }
                }
            }

            return true;
        }
    }
}
