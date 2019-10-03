@extends('layout')

@section('title', $room->name.' | WatchParty Host')

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

<!-- Room Navbar -->
<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active">Playlist</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="/host/room/{{$room->id}}/reviewSubmissions">Review Submissions</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="/host/room/{{$room->id}}/settings">Settings</a>
  </li>
</ul>

<!-- Playlist View -->
<div class="row justify-content-center">
  <iframe width="560" height="315" src="https://www.youtube.com/embed/videoseries?list={{$room->youtube_id}}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
</div>
@endsection
