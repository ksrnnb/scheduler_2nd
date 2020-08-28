@extends('layouts.schedule')

@section('body')

@if(isset($params['message']))
<p>{{$params['message']}}</p>
@endif

<div>
  <p>Schedule Name</p>
  <p class="h2 mb-5"><a href="/edit?id={{$params['uuid']}}">{{$params['scheduleName']}}</a></p>
</div>
<div id="schedule-url" data-url="{{request()->fullUrl()}}">

</div>

<div>
  <form action="/add?id={{$params['uuid']}}" method="post">
    @csrf
    <input type="hidden" name="scheduleId" value="{{$params['scheduleId']}}">
    
    @foreach($params['candidates'] as $candidateId => $candidate)
      <input type="hidden" data-date="{{$candidate}}" class="candidates" name="{{'candidate_' . $candidateId}}">
    @endforeach

    <!-- React -->
    <div id="add"></div>  

  </form>

</div>

<script>
  window.users = @json($params['users']);
  window.candidates = @json($params['candidates']);
  window.availabilities = @json($params['availabilities']);
  window.usersAvailabilities = @json($params['usersAvailabilities']);
</script>

<script src="{{asset('js/app.js')}}"></script>

@endsection