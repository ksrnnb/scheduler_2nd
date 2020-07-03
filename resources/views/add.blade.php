@extends('layouts.schedule')

@section('body')
<p>add page</p>

<form action="add" method="post">
  @csrf
  <input type="hidden" id="scheduleId" value="{{$params['scheduleId']}}">
  <input type="hidden" id="scheduleName" value="{{$params['scheduleName']}}">
  <input type="hidden" id="url" value="{{$params['url']}}">
  @foreach($params['candidates'] as $candidate)
  <input type="hidden" class="candidates" value="{{$candidate->candidateDate}}">
  @endforeach


  <div id="add"></div>
  <script src="{{asset('js/app.js')}}"></script>
</form>
@endsection