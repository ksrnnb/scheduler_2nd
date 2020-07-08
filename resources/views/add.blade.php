@extends('layouts.schedule')

@section('body')
<p>add page</p>

<div>
  <p>{{$params['scheduleName']}}</p>
</div>
<div>
  <p>URL</p>
  <p>{{$params['url']}}</p>
</div>

<div style="margin-top:30pt">
  <table class="table">
    <th>Date</th><th>○</th><th>△</th><th>×</th>
    @foreach($params['users'] as $user)
      <th>{{$user->userName}}</th>
    @endforeach
    @foreach($params['candidates'] as $candidateId => $candidate)
      <tr>
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
  <form action="add" method="post">
    @csrf
    <p>Input availabilities</p>
    <p>User name</p>
    <input type="text" name="userName">
    <p>Candidates</p>
    @foreach($params['candidates'] as $candidateId => $candidate)
      <input type="hidden" class="candidates"name="{{$candidateId}}">
    @endforeach
    <div id="add"></div>  

    <input type="submit" value="Add user">
  </form>
</div>

<script src="{{asset('js/app.js')}}"></script>

@endsection