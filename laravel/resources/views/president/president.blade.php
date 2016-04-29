@extends('layout.main')

@section('content')
<a class="btn btn-primary btn-lg btn-block" href="/president/confirmed" target="_self">1. นักเรียนที่ยืนยันชมรมเดิม</a>
@if($audition)
  <a class="btn btn-primary btn-lg btn-block" href="/president/audition" target="_self">2. นักเรียนที่สมัครออดิชัน</a>
@else
  <a class="btn btn-primary btn-lg btn-block" href="/president/registration" target="_self">2. นักเรียนที่สมัครชมรม</a>
@endif

<a class="btn btn-primary btn-lg btn-block" href="/president/all" target="_self">3. นักเรียนทั้งหมด</a>
<a class="btn btn-primary btn-lg btn-block" href="/president/setup" target="_self">4. ตั้งค่าชื่อประธานชมรมและครูที่ปรึกษา</a>

@if($canEdit)
  <a class="btn btn-primary btn-lg btn-block" href="/president/fm3301" target="_self">5. Download FM33-01</a>
  <a class="btn btn-primary btn-lg btn-block" href="/president/fm3304" target="_self">6. Download FM33-04</a>
  <a class="btn btn-primary btn-lg btn-block" href="/president/fm3305" target="_self">7. แจ้งรายชื่อนักเรียนที่ไม่ผ่านชมรม และ Download FM33-05</a>
@else
  <a class="btn btn-primary btn-lg btn-block" href="/president/fm3301" target="_self" disabled>5. Download FM33-01</a>
  <a class="btn btn-primary btn-lg btn-block" href="/president/fm3304" target="_self" disabled>6. Download FM33-04</a>
  <a class="btn btn-primary btn-lg btn-block" href="/president/fm3305" target="_self" disabled>7. แจ้งรายชื่อนักเรียนที่ไม่ผ่านชมรม และ Download FM33-05</a>
@endif
@stop
