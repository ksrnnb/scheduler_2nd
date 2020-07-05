<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Schedule;
use App\User;
use App\Candidate;
use App\Availability;

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
            $countAvailabilities = [0, 0, 0];

            foreach($candidates as $candidate) {
                $candidatesArray[$candidate->candidateId] = $candidate->candidateDate;
            }

            foreach($availabilities as $availability) {
                $availabilitiesArray[$availability->candidateId][$availability->userId] = $availability->availability;
            }

            foreach($candidates as $candidate) {
                foreach($availabilitiesArray[$candidate->candidateId] as $availability) {
                    $countAvailabilities[$availability] += 1;
                }
            }

            

            $params = array(
                'scheduleName' => $schedule->scheduleName,
                'users' => $users,
                'url' => url()->full(),
                'candidates' => $candidatesArray,
                'availabilities' => $availabilitiesArray,
                'countAvailabilities' => $countAvailabilities,
            );

            return view('add', ['id' => $queryId, 'params' => $params]);
        } else {
            return redirect('/');
        }
    }
}
