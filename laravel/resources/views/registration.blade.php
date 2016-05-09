@extends('layout.main')

@section('content')

@include('layout.component.errorwbg')

<legend>ชมรมที่เปิดรับ</legend>
<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th class="col-sm-1">#</th>
      <th class="col-sm-2">รหัสชมรม</th>
      <th class="col-sm-6">ชมรม</th>
      <th class="col-sm-3"></th>
    </tr>
  </thead>
  <tbody>
    @for ($i = 0; $i < count($data['available']); $i++)
      <form class="form-horizontal" onsubmit="confirmSubmission({{ $data['available'][$i]->club_name }})" method="POST" action="/registration.do">
        <fieldset>
          <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $data['available'][$i]->club_code }}</td>
            <td>{{ $data['available'][$i]->club_name }}</td>
            <td><a href=""><button type="submit" class="btn btn-primary btn-block" name="{{ $data['available'][$i]->club_code }}">เลือก</button></a></td>
          </tr>
          <input type="hidden" name="club_code" value="{{ $data['available'][$i]->club_code }}">
          {{ csrf_field() }}
        </fieldset>
      </form>
    @endfor
  </tbody>
</table>
@stop

@section('body')
<script src="/assets/js/bootbox.min.js"></script>
<script type="text/javascript">
  function confirmSubmission(clubName){
    console.log('TEST');
    return 0;
    bootbox.confirm("เมื่อลงทะเบียน"+clubName+"แล้ว \n นักเรียนจะไม่สามารถเปลี่ยนแปลงชมรมได้อีก",function(result){
    })
  }
</script>
@stop
