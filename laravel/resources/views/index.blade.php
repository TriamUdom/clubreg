@extends('layout.main')

@section('content')
@if(Config::get('applicationConfig.mode') == 'confirmation')
<legend>การลงทะเบียนชมรมเดิม</legend>
นักเรียนที่ต้องการลงทะเบียนชมรมเดิม ลงทะเบียนได้ตั้งแต่วันที่ 2 ถึงวันที่ 4 กุมภาพันธ์ 2559<br>
สำหรับนักเรียนที่ต้องการเปลี่ยนชมรมห้ามยืนยัน และให้รอประกาศจากโรงเรียนเตรียมอุดมศึกษาช่วงเปิดภาคเรียนที่ 1 ปีการศึกษา 2559
@elseif(Config::get('applicationConfig.mode') == 'audition')

@elseif(Config::get('applicationConfig.mode') == 'sorting1')

@elseif(Config::get('applicationConfig.mode') == 'sorting2')

@elseif(Config::get('applicationConfig.mode') == 'war')

@elseif(Config::get('applicationConfig.mode') == 'close')

@endif
<br><br>
@if(Session::get('logged_in') == 1)
<a href="/confirm">
  <button class="btn btn-success btn-block">ดำเนินการต่อ</button>
</a>
@else
<a href="/login">
  <button class="btn btn-success btn-block">ดำเนินการต่อ</button>
</a>
@endif
@stop
