@extends('layout.main')

@section('content')
<legend>รายชื่อชมรมที่{{ $mode }}</legend>
<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>#</th>
      <th>รหัสชมรม</th>
      <th>ชมรม</th>
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
