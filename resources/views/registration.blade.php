@extends('layouts.schedule')

@section('body')
<p>registration page</p>
<p>{{$scheduleId}}</p>
<form action="registration" method="post">
  {{ csrf_field() }}
  <!-- <div id="root"></div>
  <script src="{{asset('js/app.js')}}"></script> -->
</form>
@endsection