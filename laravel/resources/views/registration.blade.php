@extends('layout.main')

@section('content')

@include('layout.component.errorwbg')

<legend>ชมรมที่เปิดรับ</legend>
<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>#</th>
      <th>ชมรม</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    @for ($i = 0; $i < count($data['available']); $i++)
      <form class="form-horizontal" onsubmit="return confirm('เมื่อยืนยันการลงทะเบียนชมรม{{ $data['pass'][$i]['club_name'] }}แล้ว \n นักเรียนจะไม่สามารถเปลี่ยนแปลงชมรมได้อีก');" method="POST" action="/registration.do">
        <fieldset>
          <tr>
            <td>{{ $i+1 }}</td>
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
