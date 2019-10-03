@extends('layout')

@section('title', 'Join a WatchParty')

@section('main')
<div class="container" style="width: 300px">
  <div class="row align-items-center">
    <div class="col" align="center">
      <h1 class="display-4">Join</h1>
      <form action="/guest" method="get">
        @csrf
        <div class="form-group">
          <label for="room_code">4-Digit Room Code</label>
          <input type="text" name="room_code" class="form-control" placeholder="0000" style="text-align:center; text-transform: uppercase;"/>
          <small class="text-danger">{{$errors->first('room_code')}}</small>
        </div>
        <button type="submit" class="btn btn-primary">Join</button>
      </form>
      <hr class="my-4">
      <a class="btn btn-secondary" href="/" role="button">Back</a>
    </div>
  </div>
</div>
@endsection
