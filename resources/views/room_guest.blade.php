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
        <form action="/guest" method="post">
          @csrf
          <input type="hidden" name="room_code" value="{{$room->room_code}}" />
          <input type="hidden" name="search_query" value="{{$search_query}}" />
          <input type="hidden" name="youtube_id" value="{{$result['id']['videoId']}}" />
          <input type="hidden" name="title" value="{{$result['snippet']['title']}}" />
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
@endsection
