@extends('layout.main')

@section('content')
@include('layout.component.errorwbg')
@include('layout.component.successwbg')
<legend>รายชื่อนักเรียนที่ผ่าน{{ Session::get('fullname') }}</legend
<form class="form-horizontal" action="" method="POST">
    <select class="form-control" name="term" id="termSelect">
        <!-- TODO: Generate Term & TermID Here -->
    </select>
</form>
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
              <input type="hidden" name="action" value="dismiss">
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
              <input type="hidden" name="action" value="cancel">
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
   <div class="form-group">
     <label class="col-lg-2 control-label">ชื่อประธานที่ปรึกษาชมรม</label>
     <div class="col-lg-2">
       <select class="form-control" id="adviserTitle" name="adviserTitle">
         <option>นาย</option>
         <option>นางสาว</option>
         <option>นาง</option>
       </select>
     </div>
     <div class="col-lg-4">
       <input type="text" class="form-control" id="adviserFirstName" name="adviserFirstName" placeholder="ชื่อ">
     </div>
     <div class="col-lg-4">
       <input type="text" class="form-control" id="adviserLastName" name="adviserLastName" placeholder="นามสกุล">
     </div>
   </div>
   {{ csrf_field() }}
   <br />

   <div class="form-group">
     <label class="col-lg-2 control-label">ภาคเรียนที่</label>
     <div class="col-lg-10">
       <select class="form-control" id="semester" name="semester">
         <option>1</option>
         <option>2</option>
       </select>
     </div>
   </div>

   <div class="form-group">
     <div class="col-lg-10 col-lg-offset-2">
       <button type="submit" class="btn btn-success btn-block">Download</button>
     </div>
   </div>
  </fieldset>
</form>
@endif
@stop
