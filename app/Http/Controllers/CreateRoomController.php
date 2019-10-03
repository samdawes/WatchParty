<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Room;
use Auth;
use GuzzleHttp\Client;
use Google_Client;
use Google_Service_YouTube;
use Google_Service_YouTube_Playlist;
use Google_Service_YouTube_PlaylistSnippet;
use Google_Service_YouTube_PlaylistStatus;

class CreateRoomController extends Controller
{
  function generateRandomString($length = 4)
  {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

  public function createRoom(Request $request)
  {
    $input = $request->all();
    $validation = Validator::make($input, [
      'name' => 'required|max:100'
    ]);

    if ($validation->fails()) {
      return redirect('/create')
        ->withInput()
        ->withErrors($validation);
    } else {
      $client = new Google_Client();

      // Get access token from User DB
      $accessToken = Auth::user()->getToken();
      $client->setAccessToken($accessToken);

      // Define service object for making API requests.
      $service = new Google_Service_YouTube($client);

      // Define the $playlist object, which will be uploaded as the request body.
      $playlist = new Google_Service_YouTube_Playlist();

      // Add 'snippet' object to the $playlist object.
      $playlistSnippet = new Google_Service_YouTube_PlaylistSnippet();
      $playlistSnippet->setTitle($request->name);
      $playlist->setSnippet($playlistSnippet);

      // Add 'status' object to the $playlist object.
      $playlistStatus = new Google_Service_YouTube_PlaylistStatus();
      $playlistStatus->setPrivacyStatus('public');
      $playlist->setStatus($playlistStatus);

      //Send request
      $response = $service->playlists->insert('snippet,status', $playlist);

      //Get youtube playlist ID from response body
      $youtube_id = $response['id'];

      //Save Room model to DB
      $room = new Room;
      $room->name = $request->name;
      $room->user_id = Auth::id();
      $room->room_code = self::generateRandomString();
      $room->youtube_id = $youtube_id;
      $room->save();

      return redirect('/host');
    }
  }
}
