@extends('layouts.schedule')

@section('body')
<p>edit page</p>

<div>
  <form action="/edit?id={{$params['uuid']}}" method="POST">
    @csrf
    <p>Schedule Name</p>
    <input name="scheduleName" value="{{$params['scheduleName']}}">
    <input type="submit" class="btn btn-outline-primary" name="update" value="Update">
    <input type="submit" class="btn btn-outline-danger" name="delete" value="Delete">
  </form>
</div>


@endsection