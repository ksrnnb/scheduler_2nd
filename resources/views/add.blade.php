@extends('layouts.schedule')

@section('body')
<p>add page</p>

<form action="add" method="post">
  @csrf
  <input type="hidden" id="scheduleId" value="{{$params['scheduleId']}}">
  <input type="hidden" id="scheduleName" value="{{$params['scheduleName']}}">
  <input type="hidden" id="url" value="{{$params['url']}}">
  <input type="hidden" id="candidates" value="{{$params['candidates']}}">

  <div id="add"></div>
  <script src="{{asset('js/app.js')}}"></script>
</form>
@endsection