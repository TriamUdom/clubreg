@extends('layout.main')

@section('content')
<form class="form-horizontal" method="POST" action="/admin/login.do">
  <fieldset>
   <legend>เข้าสู่ระบบสำหรับเจ้าหน้าที่</legend>
   @if(Session::get('error') != null)
    {{ Session::get('error') }}
   @endif
   <div class="form-group">
     <label for="username" class="col-lg-2 control-label">ชื่อผู้ใช้</label>
     <div class="col-lg-10">
       <input type="text" class="form-control" id="username" name="username">
     </div>
   </div>

   <div class="form-group">
     <label for="password" class="col-lg-2 control-label">รหัสผ่าน</label>
     <div class="col-lg-10">
       <input type="password" class="form-control" id="password" name="password">
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
