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
          {{-- 
          @foreach($params['countAvailabilities'] as $countAvailability)
            <td>{{$countAvailability}}</td>
          @endforeach
          --}}
          <td></td>
          <td></td>
          <td></td>

          @foreach($params['availabilities'][$candidateId] as $availability)
            <td>{{$availability}}</td>
          @endforeach
        </tr>
      @endforeach
    </table>
  </div>

  {{var_dump($params['availabilities'])}}



  <div id="add"></div>
  <script src="{{asset('js/app.js')}}"></script>
</form>
@endsection