@extends('layout')

@section('title', $room->name.' | WatchParty Guest')

@section('main')
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand"><strong>WatchParty</strong> Guest</a>
  <div class="navbar-collapse">
    <ul class="navbar-nav mr-auto">
    </ul>
    <a class="btn btn-outline-danger" href="/" role="button">Logout</a>
  </div>
</nav>

<!-- Page Header -->
<div class="container-fluid">
  <div class="row justify-content-between align-items-center">
    <div class="col-6">
      <h1>{{$room->name}}</h1>
    </div>
    <div class="col-6 text-right">
      <h2>
        <small class="text-muted">Room Code: </small>
        {{$room->room_code}}
      </h2>
    </div>
  </div>
</div>

<!-- Alert -->
@if(session('status'))
<div class="alert alert-success">
  {{session('status')}}
</div>
<script>submitVideo();</script>
@endif

<!-- Search Form -->
<div class="d-flex justify-content-center">
  <form class="form-inline" action="/guest" method="get">
    @csrf
    <input type="text" class="form-control mb-2 mr-sm-2" name="search_query" placeholder="Search YouTube" />
    <input type="hidden" name="room_code" value="{{$room->room_code}}" />
    <button type="submit" class="btn btn-primary mb-2">Submit</button>
  </form>
</div>

<!-- Search Results -->
@isset($search_results)
<table class="table">
  <tbody>
    @foreach($search_results as $result)
    <tr>
      <td><iframe width="{{$result['snippet']['thumbnails']['medium']['width']}}" height="{{$result['snippet']['thumbnails']['medium']['height']}}" src="https://www.youtube.com/embed/{{$result['id']['videoId']}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></td>
      <td><strong>{{$result['snippet']['title']}}</strong><br />by {{$result['snippet']['channelTitle']}}</td>
      <td class="text-center align-middle">
        <form id="videoForm" action="/guest" method="post" onsubmit="myFunction('{{$room->room_code}}', '{{$room->id}}', '{{$room->youtube_id}}', '{{$result['snippet']['title']}}', '{{$result['snippet']['channelTitle']}}', '{{$result['id']['videoId']}}', '{{$result['snippet']['thumbnails']['medium']['width']}}', '{{$result['snippet']['thumbnails']['medium']['height']}}')">
          @csrf
          <input type="hidden" name="room_code" value="{{$room->room_code}}" />
          <input type="hidden" name="search_query" value="{{$search_query}}" />
          <input type="hidden" name="youtube_id" value="{{$result['id']['videoId']}}" />
          <input type="hidden" id="title" name="title" value="{{$result['snippet']['title']}}" />
          <input type="hidden" name="channel_name" value="{{$result['snippet']['channelTitle']}}" />
          <input type="hidden" name="width" value="{{$result['snippet']['thumbnails']['medium']['width']}}" />
          <input type="hidden" name="height" value="{{$result['snippet']['thumbnails']['medium']['height']}}" />
          <button type="submit" class="btn btn-success"><strong>+</strong></button>
        </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
@endisset

@empty($search_results)
<p style="text-align: center">
  You can add videos to <em>{{$room->name}}</em> here.<br />
  You can search YouTube by keyword or you can paste the entire YouTube video URL into the search bar to add a specific video.
</p>
@endempty

<script>
let connection = new WebSocket('ws://localhost:8080', 'guest');

connection.onopen = () => {
  console.log('Connected from the frontend.');
};

connection.onerror = () => {
  console.log('failed to connect from the frontend');
};

function myFunction(room_code, room_id, playlist_id, title, channel_name, youtube_id, width, height) {
  console.log("The form was submitted.");
  console.log(title);
  console.log(channel_name);
  console.log(youtube_id);
  console.log(width);
  console.log(height);
  console.log("end of variables.");

  let message = {
    'room_code': room_code,
    'room_id': room_id,
    'playlist_id': playlist_id,
    'title': title,
    'channel_name': channel_name,
    'youtube_id': youtube_id,
    'width': width,
    'height': height
  };
  connection.send(JSON.stringify(message));
}
</script>
@endsection
