@extends('layout.main')

@section('content')
@include('layout.component.errorwbg')
@include('layout.component.successwbg')
<legend>{{ Session::get('fullname') }} รายชื่อนักเรียนที่สมัครออดิชัน</legend>
<table class="table table-striped table-hover ">
  <thead>
    <tr>
      <th>#</th>
      <th>คำนำ</th>
      <th>ชื่อ</th>
      <th>นามสกุล</th>
      <th>ห้อง</th>
      <th>คำสั่ง</th>
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
        <td>
          <form class="form-horizontal" method="POST" action="/president/audition.do">
            <fieldset>
              <input type="hidden" name="national_id" value="{{ $data[$i]->national_id }}">
              <input type="hidden" name="action" value="dismiss">
              <button type="submit" class="btn btn-danger btn-block">ปฏิเสธ</button>
              {{ csrf_field() }}
            </fieldset>
          </form>
        </td>
        <td>
          <form class="form-horizontal" method="POST" action="/president/audition.do">
            <fieldset>
              <input type="hidden" name="national_id" value="{{ $data[$i]->national_id }}">
              <input type="hidden" name="action" value="confirm">
              <button type="submit" class="btn btn-success btn-block">ยืนยัน</button>
              {{ csrf_field() }}
            </fieldset>
          </form>
        </td>
      </tr>
    @endfor
  </tbody>
</table>
<legend>{{ Session::get('fullname') }} รายชื่อนักเรียนที่ผ่านออดิชัน</legend>
<table class="table table-striped table-hover ">
  <thead>
    <tr>
      <th>#</th>
      <th>คำนำ</th>
      <th>ชื่อ</th>
      <th>นามสกุล</th>
      <th>ห้อง</th>
      <th>คำสั่ง</th>
    </tr>
  </thead>
  <tbody>
    @for ($i = 0; $i < count($data2); $i++)
      <tr>
        <td>{{ $i+1 }}</td>
        <td>{{ $data2[$i]->title }}</td>
        <td>{{ $data2[$i]->fname }}</td>
        <td>{{ $data2[$i]->lname }}</td>
        <td>{{ $data2[$i]->room }}</td>
        <td>
          <form class="form-horizontal" method="POST" action="/president/audition.cancel">
            <fieldset>
              <input type="hidden" name="national_id" value="{{ $data2[$i]->national_id }}">
              <input type="hidden" name="action" value="cancel">
              <button type="submit" class="btn btn-danger btn-block">ยกเลิก</button>
              {{ csrf_field() }}
            </fieldset>
          </form>
        </td>
      </tr>
    @endfor
  </tbody>
</table>
@stop
