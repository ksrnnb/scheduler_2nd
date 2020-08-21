@extends('layouts.schedule')

@section('body')

@if(isset($params['message']))
<p>{{$params['message']}}</p>
@endif

<div>
  <p>Schedule Name</p>
  <p class="h2 mb-5"><a href="/edit?id={{$params['uuid']}}">{{$params['scheduleName']}}</a></p>
</div>
<div>
  <p>Schedule URL</p>
  <p class="mb-5">{{request()->fullUrl()}}</p>
</div>

<div>
    <!-- json data to javascript -->
    <p class="d-none" id="users">{{json_encode($params['users'])}}</p>
    <p class="d-none" id="candidates">{{json_encode($params['candidates'])}}</p>
    <p class="d-none" id="availabilities">{{json_encode($params['availabilities'])}}</p>
    <p class="d-none mb-5" id="usersAvailabilities">{{json_encode($params['usersAvailabilities'])}}</p>

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

<script src="{{asset('js/app.js')}}"></script>

@endsection