@extends('layout.main')

@section('content')
<legend>ชมรมที่เปิดรับ</legend>
<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>#</th>
      <th>ชมรม</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    @for ($i = 0; $i < count($data['available']); $i++)
      <form class="form-horizontal" method="POST" action="/registration.do">
        <fieldset>
          <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $data['available'][$i]->club_name }}</td>
            <td><a href=""><button type="submit" class="btn btn-primary btn-block">เลือก</button></a></td>
          </tr>
          <input type="hidden" name="club_code" value="{{ $data['available'][$i]->club_code }}">
          {{ csrf_field() }}
        </fieldset>
      </form>
    @endfor
  </tbody>
</table>
@stop
