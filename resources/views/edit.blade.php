@extends('layouts.schedule')

@section('body')
<p>edit page</p>

<div>
  <form action="/edit?id={{$params['uuid']}}" method="POST">
    @csrf
    <p>Schedule Name</p>
    <input name="scheduleName" value="{{$params['scheduleName']}}">
    <input type="submit" value="Update">
  </form>
  <button><a href="/delete?id={{$params['uuid']}}">Delete</a></button>
</div>


@endsection