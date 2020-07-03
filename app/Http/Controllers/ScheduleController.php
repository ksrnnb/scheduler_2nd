<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Schedule;
use App\Candidate;

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
            
            $candidatesObjects = Candidate::where('scheduleId', $schedule->scheduleId)->get();

            $candidatesArray = [];

            foreach($candidatesObjects as $candidatesObject) {
                array_push($candidatesArray, $candidatesObject);
            }

            $params = array(
                'scheduleId' => $queryId,
                'scheduleName' => $schedule->scheduleName,
                'url' => url()->full(),
                'candidates' => $candidatesArray,
            );

            return view('add', ['id' => $queryId, 'params' => $params]);
        } else {
            return redirect('/');
        }
    }
}
