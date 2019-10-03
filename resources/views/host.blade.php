@extends('layout')

@section('title', 'Host')

@section('main')
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand"><strong>WatchParty</strong> Host</a>
  <div class="navbar-collapse">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link">Your Rooms<span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/host/create">Create New Room</a>
      </li>
    </ul>
    <a class="btn btn-outline-danger" href="/logout" role="button">Logout</a>
  </div>
</nav>

<!-- Page Header -->
<h1 class="display-4">Your Rooms</h1>

<!-- No Rooms Message -->
@if(count($rooms) == 0 || empty($rooms))
<p>You don't have any rooms.<br /><a href="/host/create">Click here</a> to create a new room.</p>
@endif

<!-- Rooms Table -->
@if(isset($rooms) && count($rooms) > 0)
<table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">Room Name</th>
      <th scope="col">Room Code</th>
    </tr>
  </thead>
  <tbody>
    @foreach($rooms as $room)
    <tr>
      <th scope="row"><a href="/host/room/{{$room->id}}">{{$room->name}}</a></th>
      <td>{{$room->room_code}}</tr>
    </tr>
    @endforeach
  </tbody>
</table>
@endif

@endsection
