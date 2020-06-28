@extends('layouts.schedule')

@section('body')
<form action="create" method="post">
  {{ csrf_field() }}
  <div id="root"></div>
  <script src="{{asset('js/app.js')}}"></script>
</form>
@endsection