@extends('layout.main')

@section('content')
<form class="form-horizontal" method="POST" action="/confirm/go">
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
   {{ csrf_field() }}
   <br />
   <br />

   <div class="form-group">
     <div class="col-lg-12">
       <button type="submit" class="btn btn-success btn-block">ยืนยัน</button>
     </div>
   </div>
  </fieldset>
</form>
@stop
