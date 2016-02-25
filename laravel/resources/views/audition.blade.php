@extends('layout.main')

@section('content')

@if(isset($club_name))
<legend>ชมรมที่นักเรียนผ่านการคัดเลือก</legend>
<?php //@TODO : Align these to the center ?>
<h1>{{ $club_name }}</h1>
@else
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
              <td><a href=""><button type="submit" class="btn btn-danger btn-block">ยกเลิก</button></a></td>
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
      @for ($i = 0; $i < count($data['available']); $i++)
        <form class="form-horizontal" method="POST" action="/audition.do">
          <fieldset>
            <tr>
              <td>{{ $i+1 }}</td>
              <td>{{ $data['available'][$i]->club_name }}</td>
              <td><a href=""><button type="submit" class="btn btn-primary btn-block">เลือก</button></a></td>
            </tr>
            <input type="hidden" name="club_code" value="{{ $data['available'][$i]->club_code }}">
            {{ csrf_field() }}
          </fieldset>
        </form>
      @endfor
    </tbody>
  </table>
@endif
@stop
