@extends('layout')

@section('title', 'Create New Room')

@section('main')
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand"><strong>WatchParty</strong> Host</a>
  <div class="navbar-collapse">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="/host">Your Rooms</a>
      </li>
      <li class="nav-item active">
        <a class="nav-link">Create New Room<span class="sr-only">(current)</span></a>
      </li>
    </ul>
    <a class="btn btn-outline-danger" href="/logout" role="button">Logout</a>
  </div>
</nav>

<h1 class="display-4">Create a New Room</h1>
<form action="/host/room" method="post">
  @csrf
  <div class="form-group">
    <label for="name">Room Name</label>
    <input type="text" class="form-control" id="name" name="name" placeholder="Name">
    <small class="text-danger">{{$errors->first('name')}}</small>
  </div>
  <button type="submit" class="btn btn-primary">Create</button>
</form>
@endsection
