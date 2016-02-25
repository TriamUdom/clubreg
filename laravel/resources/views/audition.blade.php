@extends('layout.main')

@section('content')
<table class="table table-striped table-hover ">
  <thead>
    <tr>
      <th>#</th>
      <th>ชมรม</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    @for ($i = 0; $i < count($data); $i++)
      <form class="form-horizontal" method="POST" action="/president/audition.do">
        <fieldset>
          <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $data[$i]->club_name }}</td>
            <td><a href=""><button type="submit" class="btn btn-primary btn-block">เลือก</button></a></td>
          </tr>
          <input type="hidden" name="club_code" value="{{ $data['club_code'] }}">
          {{ csrf_field() }}
        </fieldset>
      </form>
    @endfor
  </tbody>
</table>
@stop
