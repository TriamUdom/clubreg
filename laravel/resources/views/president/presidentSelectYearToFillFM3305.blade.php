@extends('layout.main')

@section('content')
<form class="form-horizontal" method="POST" action="/president/fm3305.selectyear">
  <fieldset>
   <legend>Download FM33-01 สำหรับ{{ Session::get('fullname') }}</legend>
   @include('layout.component.errorwbg')
   <div class="form-group">
     <label class="col-lg-2 control-label">ปีการศึกษา</label>
     <div class="col-lg-10">
       <span>{{ Config::get('applicationConfig.operation_year') }}</span>
     </div>
   </div>

   <div class="form-group">
     <label class="col-lg-2 control-label">ภาคเรียนที่</label>
     <div class="col-lg-10">
       <select class="form-control" id="semester" name="semester">
         <option>1</option>
         <option>2</option>
       </select>
     </div>
   </div>
   {{ csrf_field() }}
   <br />

   <div class="form-group">
     <div class="col-lg-10 col-lg-offset-2">
       <button type="submit" class="btn btn-success btn-block">ต่อไป</button>
     </div>
   </div>
  </fieldset>
</form>
@stop
