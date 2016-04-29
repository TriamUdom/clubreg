@extends('layout.main')

@section('content')
<a class="btn btn-primary btn-lg btn-block" href="/president/confirmed" target="_self">รายชื่อนักเรียนที่ยืนยันชมรมเดิม</a>
@if($audition)
  <a class="btn btn-primary btn-lg btn-block" href="/president/audition" target="_self">รายชื่อนักเรียนที่สมัครออดิชัน</a>
@else
  <a class="btn btn-primary btn-lg btn-block" href="/president/registration" target="_self">รายชื่อนักเรียนที่สมัครชมรม</a>
@endif

<a class="btn btn-primary btn-lg btn-block" href="/president/all" target="_self">รายชื่อนักเรียนทั้งหมด</a>
<a class="btn btn-primary btn-lg btn-block" href="/president/setup" target="_self">ตั้งค่าชื่อประธานชมรมและครูที่ปรึกษา</a>

@if($canEdit)
  <a class="btn btn-primary btn-lg btn-block" href="/president/fm3301" target="_self">Download FM33-01</a>
  <a class="btn btn-primary btn-lg btn-block" href="/president/fm3304" target="_self">Download FM33-04</a>
  <a class="btn btn-primary btn-lg btn-block" href="/president/rollcall" target="_self">ระบบเช็คชื่อ</a>
  <a class="btn btn-primary btn-lg btn-block" href="/president/fm3305" target="_self">แจ้งรายชื่อนักเรียนที่ไม่ผ่านชมรม และ Download FM33-05</a>
@else
  <a class="btn btn-primary btn-lg btn-block" href="/president/fm3301" target="_self" disabled>Download FM33-01</a>
  <a class="btn btn-primary btn-lg btn-block" href="/president/fm3304" target="_self" disabled>Download FM33-04</a>
  <a class="btn btn-primary btn-lg btn-block" href="/president/rollcall" target="_self" disabled>ระบบเช็คชื่อ</a>
  <a class="btn btn-primary btn-lg btn-block" href="/president/fm3305" target="_self" disabled>แจ้งรายชื่อนักเรียนที่ไม่ผ่านชมรม และ Download FM33-05</a>
@endif
@stop
