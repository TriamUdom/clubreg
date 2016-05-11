@extends('layout.main')

@section('content')
@include('layout.component.errorwbg')
@include('layout.component.successwbg')
<legend>รายชื่อนักเรียนที่ผ่าน{{ Session::get('fullname') }}</legend>
<table class="table table-striped table-hover ">
  <thead>
    <tr>
      <th>#</th>
      <th>คำนำ</th>
      <th>ชื่อ</th>
      <th>นามสกุล</th>
      <th>ชั้น</th>
      <th>ห้อง</th>
      <th>เลขที่</th>
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
        <td>{{ $pass[$i]->class }}</td>
        <td>{{ $pass[$i]->room }}</td>
        <td>{{ $pass[$i]->number }}</td>
        <td>
          <form class="form-horizontal" method="POST" action="/president/fm3305/student.do">
            <fieldset>
              <input type="hidden" name="national_id" value="{{ $pass[$i]->national_id }}">
              <input type="hidden" name="semester" value="{{ $semester }}">
              <button type="submit" class="btn btn-danger btn-block">ให้ไม่ผ่าน</button>
              {{ csrf_field() }}
            </fieldset>
          </form>
        </td>
      </tr>
    @endfor
  </tbody>
</table>
<legend>รายชื่อนักเรียนที่ไม่ผ่าน{{ Session::get('fullname') }}</legend>
<table class="table table-striped table-hover ">
  <thead>
    <tr>
      <th>#</th>
      <th>คำนำ</th>
      <th>ชื่อ</th>
      <th>นามสกุล</th>
      <th>ชั้น</th>
      <th>ห้อง</th>
      <th>เลขที่</th>
      <th>คำสั่ง</th>
    </tr>
  </thead>
  <tbody>
    @for ($i = 0; $i < count($notPass); $i++)
      <tr>
        <td>{{ $i+1 }}</td>
        <td>{{ $notPass[$i]->title }}</td>
        <td>{{ $notPass[$i]->fname }}</td>
        <td>{{ $notPass[$i]->lname }}</td>
        <td>{{ $notPass[$i]->class }}</td>
        <td>{{ $notPass[$i]->room }}</td>
        <td>{{ $notPass[$i]->number }}</td>
        <td>
          <form class="form-horizontal" method="POST" action="/president/fm3305/student.delete">
            <fieldset>
              <input type="hidden" name="national_id" value="{{ $notPass[$i]->national_id }}">
              <input type="hidden" name="semester" value="{{ $semester }}">
              <button type="submit" class="btn btn-warning btn-block">ให้ผ่าน</button>
              {{ csrf_field() }}
            </fieldset>
          </form>
        </td>
      </tr>
    @endfor
  </tbody>
</table>
@if(count($notPass) > 0)
<form class="form-horizontal" method="POST" action="/president/fm3305.do">
  <fieldset>
   <legend>Download FM33-05 สำหรับ{{ Session::get('fullname') }}</legend>
   @include('layout.component.errorwbg')
   {{ csrf_field() }}
   <div class="form-group">
     <div class="col-lg-12">
       <input type="hidden" name="semester" value="{{ $semester }}">
       <button type="submit" class="btn btn-success btn-block">Download</button>
     </div>
   </div>
  </fieldset>
</form>
@endif
@stop
