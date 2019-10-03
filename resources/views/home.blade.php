@extends('layout')

@section('title', 'WatchParty')

@section('main')
<div class="jumbotron">
  <div class="container">
    <h1 class="display-4 text-center">WatchParty</h1>
    <p class="lead text-center">Create a shared YouTube playlist with your friends!</p>
    <hr class="my-4">
    <div class="container">
      <div class="row">
        <div class="col" align="center">
          <a class="btn btn-primary btn-lg" href="/login/google" role="button" style="margin-bottom: 20px">Host</a>
          <p>Create your own WatchParty. You'll get a 4-digit code that your friends can use to add videos to your playlist.</p>
        </div>
        <div class="col" align="center">
          <a class="btn btn-primary btn-lg" href="/join" role="button" style="margin-bottom: 20px">Join</a>
          <p>Join an existing WatchParty. Ask the host for the 4-digit code to login.</p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
