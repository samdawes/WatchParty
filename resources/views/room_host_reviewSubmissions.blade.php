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
        <div class="row">
          <div class="col">
            <form action="/host/room/{{$room->id}}/reviewSubmissions" method="post">
              @csrf
              <input type="hidden" name="action" value="add" />
              <input type="hidden" name="submission_id" value="{{$submission->id}}" />
              <input type="hidden" name="submission_title" value="{{$submission->title}}" />
              <input type="hidden" name="playlist_id" value="{{$room->youtube_id}}" />
              <input type="hidden" name="video_id" value="{{$submission->youtube_id}}" />
              <button type="submit" class="btn btn-success">Add</button>
            </form>
          </div>
          <div class="col">
            <form action="/host/room/{{$room->id}}/reviewSubmissions" method="post">
              @csrf
              <input type="hidden" name="action" value="delete" />
              <input type="hidden" name="submission_id" value="{{$submission->id}}" />
              <input type="hidden" name="submission_title" value="{{$submission->title}}" />
              <button type="submit" class="btn btn-danger">Delete</button>
            </form>
          </div>
        </div>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
@endsection
