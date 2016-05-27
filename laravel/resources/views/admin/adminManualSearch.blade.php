@extends('layout.main')

@section('content')
<form class="form-horizontal" method="GET" action="/admin/manualadd">
  <fieldset>
    <legend>ค้นหา</legend>
    @include('layout.component.successwbg')
    @include('layout.component.errorList')
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
    <div class="form-group">
      <div class="col-lg-10 col-lg-offset-2">
        <button type="submit" class="btn btn-success btn-block">Submit</button>
      </div>
    </div>
  </fieldset>
</form>
@stop
