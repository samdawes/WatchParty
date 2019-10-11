<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Room;
use App\Submission;
use Auth;
use Google_Client;
use Google_Service_YouTube;
use Google_Service_YouTube_PlaylistItem;
use Google_Service_YouTube_PlaylistItemSnippet;
use Google_Service_YouTube_ResourceId;

class RoomController extends Controller
{
  public function index($id = null)
  {
    //Get current selected room
    $room = Room::where('id', $id)->first();

    return view('room_host', [
      'room' => $room
    ]);
  }

  public function reviewSubmissions($id = null)
  {
    //Get current selected room
    $room = Room::where('id', $id)->first();

    //Get current submissions
    $submissions = Submission::where('room_id', $room->id)->get();

    return view('room_host_reviewSubmissions', [
      'room' => $room,
      'submissions' => $submissions
    ]);
  }

  public function settings($id = null)
  {
    //Get current selected room
    $room = Room::where('id', $id)->first();

    return view('room_host_settings', [
      'room' => $room
    ]);
  }

  public function processSubmission($id = null, Request $request)
  {
    $action = $request->action;
    if ($action == "add") {
      //Set-up new Google Client
      $client = new Google_Client();

      // Get access token from User DB
      $accessToken = Auth::user()->getToken();
      $client->setAccessToken($accessToken);

      // Define service object for making API requests.
      $service = new Google_Service_YouTube($client);

      // Define the $playlistItem object, which will be uploaded as the request body.
      $playlistItem = new Google_Service_YouTube_PlaylistItem();

      // Add 'snippet' object to the $playlistItem object.
      $playlistItemSnippet = new Google_Service_YouTube_PlaylistItemSnippet();
      $playlistItemSnippet->setPlaylistId($request->playlist_id);
      $resourceId = new Google_Service_YouTube_ResourceId();
      $resourceId->setKind('youtube#video');
      $resourceId->setVideoId($request->video_id);
      $playlistItemSnippet->setResourceId($resourceId);
      $playlistItem->setSnippet($playlistItemSnippet);

      $response = $service->playlistItems->insert('snippet', $playlistItem);

      //Delete submission from DB
      Submission::where([
        ['room_id', $request->room_id],
        ['youtube_id', $request->video_id]
      ])->delete();

      return redirect()
      ->route('host.reviewSubmissions', ['id' => $id])
      ->with('status', 'Successfully added submission: '.$request->submission_title.'.');
    }

    if ($action == "delete") {
      Submission::where([
        ['room_id', $request->room_id],
        ['youtube_id', $request->video_id]
      ])->delete();

      return redirect()
      ->route('host.reviewSubmissions', ['id' => $id])
      ->with('status', 'Successfully deleted submission: '.$request->submission_title.'.');
    }
  }
}
