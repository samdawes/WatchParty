<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Room;
use Auth;

class HostController extends Controller
{
  public function index()
  {
    $rooms = Room::where('user_id', Auth::id())->get();

    return view('host', [
      'rooms' => $rooms
    ]);
  }
}
