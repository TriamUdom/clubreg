@extends('layout.main')

@section('content')
<div class="row">
  <div class="col-sm-4">
    <img src="/img/error/c4.png" class="pull-right" alt="Avatar" width="200px">
  </div>
  <div class="col-sm-7">
    <br /><br /><br />
    <h5>ดูเหมือนจะมีบางสิ่งผิดปกติ</h5>
    <h4><b>ข้อมูลไม่ถูกรูปแบบ</b></h4>
    <h6>HTTP Error 400 : Bad Request</h6>
    <br />
    <a href="/" class="btn btn-warning" target="_self">&nbsp;&nbsp;&nbsp;&nbsp;กลับไปหน้าหลัก&nbsp;&nbsp;&nbsp;&nbsp;</a>
  </div>
</div>

<br />

@stop
