@extends('layout.main')

@section('content')
<form class="form-horizontal" method="POST" action="/confirm.do">
  <fieldset>
   <legend>ยืนยันการลงทะเบียนชมรมเดิม</legend>
   <span>ชมรมปัจจุบัน : {{ $data['current_club'] }}</span><br>
   <span>สถานะ :
     @if($data['confirmation_status'] == 1)
      ยืนยันการลงทะเบียนแล้ว
     @else
      ยังไม่ได้ยืนยันการลงทะเบียน
     @endif
   </span>

   <br />
   <br />

   <input type="hidden" value="{{ $data['confirmation_status'] }}" name="current_status">

   <div class="form-group">
     <div class="col-lg-12">
       @if($data['confirmation_status'] == 1)
        <button type="submit" class="btn btn-danger btn-block">ยกเลิกการลงทะเบียน</button>
       @else
        <button type="submit" class="btn btn-success btn-block">ยืนยันการลงทะเบียน</button>
       @endif
     </div>
   </div>
   {{ csrf_field() }}
  </fieldset>
</form>
@stop
