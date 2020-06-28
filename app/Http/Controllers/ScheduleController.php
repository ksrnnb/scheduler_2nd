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
        return view('registration', ['scheduleId' => Str::uuid()]);
    }
}
