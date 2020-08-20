@extends('layouts.schedule')

@section('body')

@if(isset($params['message']))
<p>{{$params['message']}}</p>
@endif

<div>
  <p>Schedule Name</p>
  <p class="h2"><a href="/edit?id={{$params['uuid']}}">{{$params['scheduleName']}}</a></p>
</div>
<div>
  <p class="mt-4">Schedule URL</p>
  {{request()->fullUrl()}}
</div>

<div>
    <!-- json data to javascript -->
    <p id="users">{{json_encode($params['users'])}}</p>
    <p id="candidates">{{json_encode($params['candidates'])}}</p>
    <p class="mb-5" id="availabilities">{{json_encode($params['availabilities'])}}</p>
    <p class="mb-5" id="usersAvailabilities">{{json_encode($params['usersAvailabilities'])}}</p>

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
<script src="{{asset('js/user.js')}}"></script>

@endsection