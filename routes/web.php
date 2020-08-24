<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('index');
// });
Route::get('/', 'ScheduleController@index');
Route::post('/', 'ScheduleController@create');

Route::get('/add', 'UserController@add');
Route::post('/add', 'UserController@create');

Route::get('/edit', 'ScheduleController@edit');
Route::post('/edit', 'ScheduleController@update');

Route::get('/error', 'ScheduleController@error');

Route::fallback(function() {
  return redirect('error');
});


// Route::get('/test', function() {
//     return view('test');
// });
// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
