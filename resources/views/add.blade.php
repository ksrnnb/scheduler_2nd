@extends('layouts.schedule')

@section('body')
<p>add page</p>

<form action="add" method="post">
  @csrf
  <div>
    <p>{{$params['scheduleName']}}</p>
  </div>
  <div>
    <input type="text" id="url" value="{{$params['url']}}" readonly>
  </div>

  <div style="margin-top:30pt">
    <table>
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


  <div id="add"></div>
  <script src="{{asset('js/app.js')}}"></script>
</form>
@endsection