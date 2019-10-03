<?php

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

//Homepage
Route::get('/', function () {
  return view('home');
});

Route::post('/guest', 'JoinController@submitVideo');
Route::get('/guest', 'JoinController@index');

Route::get('/join', function () {
  return view('join');
});

//Login + Logout
Route::get('login/google', 'LoginController@redirectToProvider');
Route::get('login/google/callback', 'LoginController@handleProviderCallback');
Route::get('/logout', 'LoginController@logout');

Route::middleware(['authenticated'])->group(function() {
  Route::get('/host', 'HostController@index');

  Route::get('/host/create', function() {
    return view('createRoom');
  });

  Route::get('/host/room/{id}', 'RoomController@index');

  Route::post('/host/room/{id}/reviewSubmissions', 'RoomController@processSubmission');
  Route::get('/host/room/{id}/reviewSubmissions', 'RoomController@reviewSubmissions')->name('host.reviewSubmissions');

  Route::get('/host/room/{id}/settings', 'RoomController@settings');
  Route::post('/host/room', 'CreateRoomController@createRoom');
});
