@extends('layout.main')

@section('content')
@if(Session::get('error') != null)
<div class="alert alert-danger" style="background: #FF0000;">
  {{ Session::get('error') }}
</div>
@endif
@if(Session::get('success') != null)
<div class="alert alert-success" style="background: #3ada4f;">
  {{ Session::get('success') }}
</div>
@endif
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
    @for ($i = 0; $i < count($data); $i++)
      <tr>
        <td>{{ $i+1 }}</td>
        <td>{{ $data[$i]->title }}</td>
        <td>{{ $data[$i]->fname }}</td>
        <td>{{ $data[$i]->lname }}</td>
        <td>{{ $data[$i]->room }}</td>
        <td>
          <form class="form-horizontal" method="POST" action="/president/audition.cancel">
            <fieldset>
              <input type="hidden" name="national_id" value="{{ $data[$i]->national_id }}">
              <input type="hidden" name="action" value="dismiss">
              <button type="submit" class="btn btn-danger btn-block">ปฏิเสธ</button>
              {{ csrf_field() }}
            </fieldset>
          </form>
        </td>
      </tr>
    @endfor
  </tbody>
</table>
@stop
