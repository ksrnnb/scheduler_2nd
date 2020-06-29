@extends('layouts.schedule')

@section('body')
<form action="/" method="post">
  @csrf
  <div id="index"></div>
  <script src="{{asset('js/app.js')}}"></script>
</form>
@endsection