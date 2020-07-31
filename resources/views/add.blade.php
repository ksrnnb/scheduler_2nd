@extends('layouts.schedule')

@section('body')
<p>add page</p>

<div>
  <p>{{$params['scheduleName']}}</p>
</div>
<div>
  <p>URL</p>
  <p>{{request()->fullUrl()}}</p>
</div>

<div style="margin-top:30pt">
<!-- Need to adjust table width later -->
  <table class="table-bordered text-center" style="width: 80%;">

    <tr>
      <th>Date</th><th scope="col">○</th><th scope="col">△</th><th scope="col">×</th>
      @foreach($params['users'] as $user)
      <!-- Need to modify link -->
        <th scope="col"><a href="/editUser" method="GET">{{$user->userName}}</a></th>
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
    <p>Input availabilities</p>
    <p>User name</p>
    <input type="text" name="userName" required>
    <p>Candidates</p>
    @foreach($params['candidates'] as $candidateId => $candidate)
      <input type="hidden" data-date="{{$candidate}}" class="candidates" name="{{'candidate_' . $candidateId}}">
    @endforeach
    <div id="add"></div>  

    <input type="submit" value="Add user">
  </form>
</div>

<script src="{{asset('js/app.js')}}"></script>

@endsection