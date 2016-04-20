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
    @for ($i = 0; $i < count($pending); $i++)
      <tr>
        <td>{{ $i+1 }}</td>
        <td>{{ $pending[$i]->title }}</td>
        <td>{{ $pending[$i]->fname }}</td>
        <td>{{ $pending[$i]->lname }}</td>
        <td>{{ $pending[$i]->room }}</td>
        <td>
          <form class="form-horizontal" method="POST" action="/president/audition.do">
            <fieldset>
              <input type="hidden" name="national_id" value="{{ $pending[$i]->national_id }}">
              <input type="hidden" name="action" value="dismiss">
              <button type="submit" class="btn btn-danger btn-block">ปฏิเสธ</button>
              {{ csrf_field() }}
            </fieldset>
          </form>
        </td>
        <td>
          <form class="form-horizontal" method="POST" action="/president/audition.do">
            <fieldset>
              <input type="hidden" name="national_id" value="{{ $pending[$i]->national_id }}">
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
    @for ($i = 0; $i < count($pass); $i++)
      <tr>
        <td>{{ $i+1 }}</td>
        <td>{{ $pass[$i]->title }}</td>
        <td>{{ $pass[$i]->fname }}</td>
        <td>{{ $pass[$i]->lname }}</td>
        <td>{{ $pass[$i]->room }}</td>
        <td>
          <form class="form-horizontal" method="POST" action="/president/audition.cancel">
            <fieldset>
              <input type="hidden" name="national_id" value="{{ $pass[$i]->national_id }}">
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
<legend>{{ Session::get('fullname') }} รายชื่อนักเรียนที่ไม่ผ่านออดิชัน</legend>
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
    @for ($i = 0; $i < count($fail); $i++)
      <tr>
        <td>{{ $i+1 }}</td>
        <td>{{ $fail[$i]->title }}</td>
        <td>{{ $fail[$i]->fname }}</td>
        <td>{{ $fail[$i]->lname }}</td>
        <td>{{ $fail[$i]->room }}</td>
        <td>
          <form class="form-horizontal" method="POST" action="/president/audition.cancel">
            <fieldset>
              <input type="hidden" name="national_id" value="{{ $fail[$i]->national_id }}">
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
