@extends('layout.main')

@section('content')
<legend>รายชื่อชมรมที่{{ $mode }}</legend>
<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th class="col-sm-1">#</th>
      <th class="col-sm-2">รหัสชมรม</th>
      <th class="col-sm-9">ชมรม</th>
    </tr>
  </thead>
  <tbody>
    @for ($i = 0; $i < count($data); $i++)
      <tr>
        <td>{{ $i+1 }}</td>
        <td>{{ $data[$i]->club_code }}</td>
        <td>{{ $data[$i]->club_name }}</td>
      </tr>
    @endfor
  </tbody>
</table>
@stop
