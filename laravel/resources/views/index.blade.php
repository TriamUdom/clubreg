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
<b>* หมายเหตุ วันและเวลาอาจมีการเปลี่ยนแปลงได้ตามความเหมาะสม</b><br>
<b>** นักเรียนต้องตรวจสอบชื่อ นามสกุล และชมรมที่ตนเองอยู่ในระบบ ว่าถูกต้องหรือไม่ หากไม่ถูกต้อง ให้มาพบอาจารย์ดวงพร ใจเพิ่ม ที่ตึก 50 ปี ฝั่งโดมทอง ภายในวันที่ 8 กุมภาพันธ์ 2559 ก่อนเวลา 16.00 น.</b>
@elseif(Config::get('applicationConfig.mode') == 'audition')
Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
@elseif(Config::get('applicationConfig.mode') == 'war')

@elseif(Config::get('applicationConfig.mode') == 'close')

@endif
<br><br>
@if(Session::get('logged_in') == 1)
  @if(Config::get('applicationConfig.mode') == 'confirmation')
  <a href="/confirm">
    <button class="btn btn-success btn-block">ดำเนินการต่อ</button>
  </a>
  @elseif(Config::get('applicationConfig.mode') == 'audition')
  <a href="/audition">
    <button class="btn btn-success btn-block">ดำเนินการต่อ</button>
  </a>
  @elseif(Config::get('applicationConfig.mode') == 'war')

  @elseif(Config::get('applicationConfig.mode') == 'close')

  @endif
@else
  @if(Config::get('applicationConfig.mode') != 'close' && Config::get('applicationConfig.mode') != 'technical_difficulties')
    <a href="/login">
      <button class="btn btn-success btn-block">ดำเนินการต่อ</button>
    </a>
  @endif
@endif
@stop
