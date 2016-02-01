@extends('layout.main')

@section('content')
<legend>การเลือกชมรม ประจำปีการศึกษา 2559</legend>
@if(Config::get('applicationConfig.mode') == 'confirmation')
1. ให้นักเรียนชั้นมัธยมศึกษาปีที่ 5 และ 6 ประจำปีการศึกษา 2559 ปฏิบัติตามประกาศต่อไปนี้
<br>
<ul>
  <li>นักเรียนที่ต้องการเลือกชมรมเดิม ลงทะเบียนได้ตั้งแต่วันที่ 2 ถึงวันที่ 4 กุมภาพันธ์ 2559</li>
  <li>สำหรับนักเรียนที่ต้องการเปลี่ยนชมรมห้ามกดยืนยัน และให้รอประกาศจากงานกิจกรรมพัฒนาผู้เรียนช่วงเปิดภาคเรียนที่ 1 ปีการศึกษา 2559</li>
  <li><b>หลังจากวันที่ 4 กุมภาพันธ์ 2559 นักเรียนจะไม่สามารถเปลี่ยนแปลงการยืนยันเข้าชมรมเดิมได้</b></li>
</ul>
2. ให้นักเรียนที่ต้องการเข้าชมรมที่มีการคัดเลือก (ออดิชัน) ปฏิบัติตามประกาศต่อไปนี้
<br>
<ul>
  <li>ลงทะเบียนในระบบสมัครเข้าชมรมที่มีการคัดเลือก (ออดิชัน) ในวันที่ 16* ถึงวันที่ 17* พฤษภาคม 2559</li>
  <li>ทำการคัดเลือกตามขั้นตอนของแต่ละชมรมให้เรียบร้อยภายในวันที่ 18* ถึงวันที่ 19* พฤษภาคม 2559</li>
</ul>
3. ให้นักเรียนที่ต้องการเข้าชมรมที่ไม่มีการคัดเลือก (ออดิชัน) รอประกาศจากงานกิจกรรมพัฒนาผู้เรียนในช่วงเปิดภาคเรียนที่ 1 ปีการศึกษา 2559<br>
<b>* หมายเหตุ วันและเวลาอาจมีการเปลี่ยนแปลงได้ตามความเหมาะสม</b>
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
