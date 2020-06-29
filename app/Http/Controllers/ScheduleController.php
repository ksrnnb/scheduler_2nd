<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        
        $scheduleId = $request->query('id');

        // Need to validate id is uuid or not.
        $params = array(
            'scheduleId' => $scheduleId,
            'scheduleName' => 'Schedule NAME',
            'url' => url()->full(),
            'candidates' => '2020-07-01',
        );

        if (isset($scheduleId)) {
            return view('add', ['id' => $scheduleId, 'params' => $params]);
        } else {
            return redirect('/');
        }
    }
}
