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

    public function create(Request $request) {
        //Need to validate and registrate data
        $form = $request->all();
        unset($form['_token']);

        $scheduleName = $form['scheduleName'];
        $uuid = Str::uuid();

        // need to validate
        $schedule = [
            'scheduleName' => $scheduleName,
            'scheduleUuid' => $uuid,
        ];

        $scheduleInstance = Schedule::create($schedule);

        // need to use "" for \n
        // フォーム送信された改行は\r\nらしい。→trimで除く必要あり。
        $c = explode("\n", $form["candidates"]);
        $c = array_map('trim', $c);

        // validation
        if (! $this->isValidatedSchedule($scheduleName, $c) ) {
            return redirect('error');
        }

        $candidates = [];

        foreach($c as $candidateDate) {
            $candidates[] = ['candidateDate' => $candidateDate];
        }

        /*
        $candidates: array (size=2)
                    [
                        ['candidateDate' => '8/18(Tue)'],
                        ['candidateDate' => '8/25(Tue)'],
                    ]
        */

        $scheduleInstance->candidates()->createMany($candidates);

        return redirect('/add?id=' . $uuid);
    }
    

    public function edit(Request $request) {
        $queryId = $request->query('id');

        $schedule = Schedule::where('scheduleUuid', $queryId)->first();

        if(isset($schedule)) {

            $params = [
                'scheduleName' => $schedule->scheduleName,
                'uuid' => $queryId,
            ];
            return view('edit', ['params' => $params]);

        } else {
            return redirect('error');
        }
    }

    public function update(Request $request) {
        $queryId = $request->query('id');
        $scheduleName = $request->all()['scheduleName'];

        // validation
        if (! $this->isValidatedUpdate($queryId, $scheduleName)) {
            return redirect('error');
        }

        $schedule = Schedule::where('scheduleUuid', $queryId)->first();

        if ($request->input('update')) {
            
            $schedule->fill(['scheduleName' => $scheduleName])->save();
    
            return redirect()->action('UserController@add', ['id' => $queryId]);
        
        } elseif ($request->input('delete')) {

            if(isset($schedule)) {

                Schedule::deleteSchedule($schedule);

                return view('delete');


            } else {
                return redirect('error');
            }

        } else {
            return redirect('error');
        }


    }

    public function isValidatedSchedule($scheduleName, $candidates) {
        foreach($candidates as $candidate) {

            if(! preg_match('/[0-9]+\/[0-9]+ \(.+\)/', $candidate)) {
                return false;
            }
        }
       
        if (empty($scheduleName)) {
            return false;
        } else  {
            return true;
        }
    }

    public function isValidatedUpdate($queryId, $scheduleName) {

        if (! Str::isUuid($queryId)) {
            return false;
        } elseif (empty($scheduleName)) {
            return false;
        } else {
            return true;
        }
    }

    public function error(Request $request) {
        return view('error');
    }

}
