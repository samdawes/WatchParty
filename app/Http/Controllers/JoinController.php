<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Room;
use App\Submission;
use Google_Client;
use Google_Service_YouTube;

class JoinController extends Controller
{
  function getSearchResponse($search_query)
  {
    $client = new Google_Client();
    $client->setApplicationName('WatchParty');
    $client->setDeveloperKey(env('GOOGLE_API_KEY'));

    $service = new Google_Service_YouTube($client);

    $queryParams = [
      'q' => $search_query,
      'safeSearch' => 'none',
      'type' => 'video'
    ];

    return $service->search->listSearch('snippet', $queryParams);
  }

  public function index(Request $request)
  {
    //Logic to handle first-time room login
    $input = $request->all();
    $validation = Validator::make($input, [
      'room_code' => 'required|size:4|exists:rooms,room_code'
    ]);

    if ($validation->fails()) {
      return redirect('/join')
        ->withInput()
        ->withErrors($validation);
    }

    $room = Room::where('room_code', $request->room_code)->first();

    if (isset($request->search_query)) {
      $response = self::getSearchResponse($request->search_query);

      return view('room_guest', [
        'room' => $room,
        'search_query' => $request->search_query,
        'search_results' => $response['items']
      ]);
    }

    return view('room_guest', [
      'room' => $room
    ]);
  }

  public function submitVideo(Request $request)
  {
    //Get current room
    $room = Room::where('room_code', $request->room_code)->first();

    //Save submission to DB
    $submission = new Submission;
    $submission->youtube_id = $request->youtube_id;
    $submission->channel_name = $request->channel_name;
    $submission->title = $request->title;
    $submission->width = $request->width;
    $submission->height = $request->height;
    $submission->room_id = $room->id;
    $submission->save();

    //Get search results for query
    $response = self::getSearchResponse($request->search_query);

    //Set alert
    $alert = "Video was successfully submitted.";

    return redirect()
    ->action('JoinController@index', [
      'room_code' => $request->room_code,
      'search_query' => $request->search_query
    ])->with('status', 'Successfully submitted video');
  }
}
