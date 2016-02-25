@extends('layout.main')

@section('content')

<legend>{{ Session::get('fullname') }} รายชื่อนักเรียนที่ยืนยันการลงทะเบียนชมรมเดิม</legend>

<table class="table table-striped table-hover ">
  <thead>
    <tr>
      <th>#</th>
      <th>คำนำ</th>
      <th>ชื่อ</th>
      <th>นามสกุล</th>
      <th>ห้อง</th>
    </tr>
  </thead>
  <tbody>
    @for ($i = 0; $i < count($data); $i++)
      <tr>
        <td>{{ $i+1 }}</td>
        <td>{{ $data[$i]->title }}</td>
        <td>{{ $data[$i]->fname }}</td>
        <td>{{ $data[$i]->lname }}</td>
        <td>{{ $data[$i]->room }}</td>
      </tr>
    @endfor
  </tbody>
</table>

@stop
