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

<div style="margin-top:30pt">
<!-- Need to adjust table width later -->
  <table class="table-bordered text-center" style="width: 100%;">

    <tr>
      <th>Date</th><th scope="col">○</th><th scope="col">△</th><th scope="col">×</th>
      @foreach($params['users'] as $user)
      <!-- Need to modify link -->
        <th scope="col"><a class="users" data-id="{{$user['userId']}}" href="#input-title" method="GET">{{$user->userName}}</a></th>
      @endforeach
    </tr>
    
    @foreach($params['candidates'] as $candidateId => $candidate)
      <tr scope="row">
        <td>{{$candidate}}</td>
        @foreach($params['countAvailabilities']['candidate' . $candidateId] as $countAvailability)
          <td>{{$countAvailability}}</td>
        @endforeach

        @foreach($params['availabilities'][$candidateId] as $availability)
          @switch($availability)
            @case(0)
              <td>○</td>
              @break
            
            @case(1)
              <td>△</td>
              @break

            @case(2)
              <td>×</td>
              @break

          @endswitch
        @endforeach
        
      </tr>
    @endforeach
  </table>
</div>

<div>
  <form action="/add?id={{$params['uuid']}}" method="post">
    @csrf
    <input type="hidden" name="scheduleId" value="{{$params['scheduleId']}}">
    
    <p id="input-title">Input availabilities</p>
    <p>User name</p>
    <input type="hidden" id="user-id" name="userId" required>
    <input type="text" id="user-name" name="userName" required>
    <p>Candidates</p>
    @foreach($params['candidates'] as $candidateId => $candidate)
      <input type="hidden" data-date="{{$candidate}}" class="candidates" name="{{'candidate_' . $candidateId}}">
    @endforeach
    <div id="add"></div>  

    <input type="submit" id="submit-button" class="btn btn-outline-primary" name="add" value="Add user">
    <input type="submit" id="delete-button" class="btn btn-outline-danger display-none" name="delete" value="Delete user">
  </form>
  <button id="reset-button" class="btn btn-outline-success w-100 mb-5">Reset input information</button>

</div>

<script src="{{asset('js/app.js')}}"></script>
<script src="{{asset('js/user.js')}}"></script>

@endsection