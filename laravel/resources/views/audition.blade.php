@extends('layout.main')

@section('content')

@include('layout.component.errorwbg')

@if(!empty($data['pass']))
<legend>ชมรมที่นักเรียนผ่านการคัดเลือก</legend>
<table class="table table-striped table-hover ">
  <thead>
    <tr>
      <th>#</th>
      <th>ชมรม</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    @for ($i = 0; $i < count($data['pass']); $i++)
      <form class="form-horizontal" method="POST" action="/audition/confirm.do">
        <fieldset>
          <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $data['pass'][$i]['club_name'] }}</td>
            <td><a href=""><button type="submit" class="btn btn-success btn-block" name="{{ $data['pass'][$i]['club_code'] }}">ยืนยัน</button></a></td>
          </tr>
          <input type="hidden" name="club_code" value="{{ $data['pass'][$i]['club_code'] }}">
          {{ csrf_field() }}
        </fieldset>
      </form>
    @endfor
  </tbody>
</table>
@endif

@if(!empty($data['selected']))
<legend>ชมรมที่เลือก</legend>
<table class="table table-striped table-hover ">
  <thead>
    <tr>
      <th>#</th>
      <th>ชมรม</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    @for ($i = 0; $i < count($data['selected']); $i++)
      <form class="form-horizontal" method="POST" action="/audition.delete">
        <fieldset>
          <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $data['selected'][$i]['club_name'] }}</td>
            <td><a href=""><button type="submit" class="btn btn-danger btn-block" name="{{ $data['selected'][$i]['club_code'] }}">ยกเลิก</button></a></td>
          </tr>
          <input type="hidden" name="club_code" value="{{ $data['selected'][$i]['club_code'] }}">
          {{ csrf_field() }}
        </fieldset>
      </form>
    @endfor
  </tbody>
</table>
@endif

<legend>ชมรมที่เปิดรับ</legend>
<table class="table table-striped table-hover ">
  <thead>
    <tr>
      <th>#</th>
      <th>ชมรม</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    @for ($j = 0; $j < count($data['available']); $j++)
      <form class="form-horizontal" method="POST" action="/audition.do">
        <fieldset>
          <tr>
            <td>{{ $j+1 }}</td>
            <td>{{ $data['available'][$j]->club_name }}</td>
            <td><button type="submit" class="btn btn-primary btn-block" name="{{ $data['available'][$j]->club_code }}">เลือก</button></td>
          </tr>
          <input type="hidden" name="club_code" value="{{ $data['available'][$j]->club_code }}">
          {{ csrf_field() }}
        </fieldset>
      </form>
    @endfor
  </tbody>
</table>
@stop
