@extends('layout.main')

@section('content')
<form class="form-horizontal" method="POST" action="/login.do">
  <fieldset>
   <legend>เข้าสู่ระบบ</legend>
   @include('layout.component.errornobg')
   <div class="form-group">
     <label for="sid" class="col-lg-2 control-label">หมายเลขประจำตัวนักเรียน</label>
     <div class="col-lg-10">
       <input type="text" class="form-control" id="sid" name="sid">
       <span class="help-block">นักเรียนชั้นมัธยมศึกษาปีที่ 4 ที่ยังไม่มีเลขประจำตัวนักเรียนให้ใส่เป็น 00000</span>
     </div>
   </div>

   <div class="form-group">
     <label for="nid" class="col-lg-2 control-label">หมายเลขบัตรประจำตัวประชาชน</label>
     <div class="col-lg-10">
       <input type="text" class="form-control" id="nid" name="nid">
       <span class="help-block">ไม่ต้องใส่เครื่องหมายขีด (-) คั่น</span>
     </div>
   </div>
   {{ csrf_field() }}
   <br />

   <div class="form-group">
     <div class="col-lg-10 col-lg-offset-2">
       <button type="submit" class="btn btn-success btn-block">เข้าสู่ระบบ</button>
     </div>
   </div>
  </fieldset>
</form>
@stop
