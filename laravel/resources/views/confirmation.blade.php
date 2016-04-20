@extends('layout.main')

@section('content')

@include('layout.component.errorwbg')

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
@if($data['confirmation_status'] == 1)
<form class="form-horizontal" method="POST" action="/confirmation.delete">
  <fieldset>
    <input type="hidden" value="{{ $data['club_code'] }}" name="club_code">
    <input type="hidden" value="{{ $data['confirmation_status'] }}" name="current_status">
    <button type="submit" class="btn btn-danger btn-block">ยกเลิกการลงทะเบียน</button>
    {{ csrf_field() }}
  </fieldset>
</form>
@else
<form class="form-horizontal" method="POST" action="/confirmation.do">
  <fieldset>
    <input type="hidden" value="{{ $data['club_code'] }}" name="club_code">
    <input type="hidden" value="{{ $data['confirmation_status'] }}" name="current_status">
    <button type="submit" class="btn btn-success btn-block">ยืนยันการลงทะเบียน</button>
    {{ csrf_field() }}
  </fieldset>
</form>
@endif
@stop
