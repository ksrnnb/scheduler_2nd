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

        $uuid = Str::uuid();

        $schedule = [
            'scheduleName' => $form['scheduleName'],
            'scheduleUuid' => $uuid,
        ];

        $scheduleInstance = Schedule::create($schedule);

        // need to use "" for \n
        // フォーム送信された改行は\r\nらしい。→trimで除く必要あり。
        $c = explode("\n", $form["candidates"]);
        $c = array_map('trim', $c);

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
            return view('error');
        }
    }

    public function update(Request $request) {
        $queryId = $request->query('id');

        $schedule = Schedule::where('scheduleUuid', $queryId)->first();
        $scheduleName = $request->all()['scheduleName'];

        $schedule->fill(['scheduleName' => $scheduleName])->save();

        $params = User::getParamsForAddPage($schedule, $queryId);
        
        $params = array_merge(['message' => 'Schedule name has changed.'], $params);

        return view('add', ['params' => $params]);

    }
    
    public function delete(Request $request) {
        $queryId = $request->query('id');

        $schedule = Schedule::where('scheduleUuid', $queryId)->first();

        if(isset($schedule)) {

            $params = [
                'scheduleName' => $schedule->scheduleName,
                'uuid' => $queryId,
            ];

            return view('delete', ['params' => $params]);

        } else {
            return view('error');
        }
    }
}
