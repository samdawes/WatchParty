@extends('layout')

@section('title', 'Review Submissions for '.$room->name.' | WatchParty Host')

@section('main')
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand"><strong>WatchParty</strong> Host</a>
  <div class="navbar-collapse">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="/host">Your Rooms</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/host/create">Create New Room</a>
      </li>
    </ul>
    <a class="btn btn-outline-danger" href="/logout" role="button">Logout</a>
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
@endif

<!-- Room Navbar -->
<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link" href="/host/room/{{$room->id}}">Playlist</a>
  </li>
  <li class="nav-item">
    <a class="nav-link active">Review Submissions</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="/host/room/{{$room->id}}/settings">Settings</a>
  </li>
</ul>

<!-- Submissions Table -->
<table class="table">
  <tbody>
    @foreach($submissions as $submission)
    <tr>
      <td><iframe width="{{$submission->width}}" height="{{$submission->height}}" src="https://www.youtube.com/embed/{{$submission->youtube_id}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></td>
      <td><strong>{{$submission->title}}</strong><br />by {{$submission->channel_name}}</td>
      <td class="text-center align-middle">
        <form action="/host/room/{{$room->id}}/reviewSubmissions" method="post">
          @csrf
          <input type="hidden" name="action" value="add" />
          <input type="hidden" name="room_id" value="{{$room->id}}" />
          <input type="hidden" name="submission_title" value="{{$submission->title}}" />
          <input type="hidden" name="playlist_id" value="{{$room->youtube_id}}" />
          <input type="hidden" name="video_id" value="{{$submission->youtube_id}}" />
          <button type="submit" class="btn btn-success">Add</button>
        </form>
        <br />
        <form action="/host/room/{{$room->id}}/reviewSubmissions" method="post">
          @csrf
          <input type="hidden" name="action" value="delete" />
          <input type="hidden" name="room_id" value="{{$room->id}}" />
          <input type="hidden" name="submission_title" value="{{$submission->title}}" />
          <input type="hidden" name="video_id" value="{{$submission->youtube_id}}" />
          <button type="submit" class="btn btn-danger">Delete</button>
        </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

<script>
let connection = new WebSocket('ws://localhost:8080', '{{$room->room_code}}');

connection.onopen = () => {
  console.log('connected from the frontend');
};

connection.onerror = () => {
  console.log('failed to connect from the frontend');
};

connection.onmessage = (event) => {
  console.log('Received data', event.data);

  //Convert data from string to JS object
  var message = JSON.parse(event.data);
  var room_code = message.room_code;
  var room_id = message.room_id;
  var title = message.title;
  var channel_name = message.channel_name;
  var youtube_id = message.youtube_id;
  var playlist_id = message.playlist_id;
  var width = message.width;
  var height = message.height;

  //Add row to the table
  var tr = document.createElement('tr');

  var td_1 = document.createElement('td');
  var iframe = document.createElement('IFRAME');
  iframe.width = width;
  iframe.height = height;
  iframe.src = 'https://www.youtube.com/embed/' + youtube_id;
  iframe.style.border = 0;
  td_1.appendChild(iframe);
  tr.appendChild(td_1);

  var td_2 = document.createElement('td');
  td_2.innerHTML = '<strong>'+title+'</strong><br />by '+channel_name;
  tr.appendChild(td_2);

  var td_3 = document.createElement('td');
  td_3.className = 'text-center align-middle';

  var form_1 = document.createElement('FORM');
  form_1.action = '/host/room/'+ room_id +'/reviewSubmissions';
  form_1.method = 'post';
  form_1.innerHTML = '@csrf<input type=\"hidden\" name=\"action\" value=\"add\" /><input type=\"hidden\" name=\"room_id\" value=\"'+ room_id +'\" /><input type=\"hidden\" name=\"submission_title\" value=\"'+ title +'\" /><input type=\"hidden\" name=\"playlist_id\" value=\"'+ playlist_id +'\" /><input type=\"hidden\" name=\"video_id\" value=\"'+ youtube_id +'\" /><button type=\"submit\" class=\"btn btn-success\">Add</button>';
  td_3.appendChild(form_1);

  var br = document.createElement('BR');
  td_3.appendChild(br);

  var form_2 = document.createElement('FORM');
  form_2.action = '/host/room/'+ room_id +'/reviewSubmissions';
  form_2.method = 'post';
  form_2.innerHTML = '@csrf<input type=\"hidden\" name=\"action\" value=\"delete\" /><input type=\"hidden\" name=\"room_id\" value=\"'+ room_id +'\" /><input type=\"hidden\" name=\"submission_title\" value=\"'+ title +'\" /><input type=\"hidden\" name=\"video_id\" value=\"'+ youtube_id +'\" /><button type=\"submit\" class=\"btn btn-danger\">Delete</button>';
  td_3.appendChild(form_2);
  tr.appendChild(td_3);

  document.querySelector('tbody').appendChild(tr);
};
</script>
@endsection
