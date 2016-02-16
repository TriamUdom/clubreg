@extends('layout.main')

@section('content')
@if(Session::get('error') != null)
<div class="alert alert-danger" style="background: #FF0000;">
  {{ Session::get('error') }}
</div>
@endif
<table class="table table-striped table-hover ">
  <thead>
    <tr>
      <th>#</th>
      <th>คำนำ</th>
      <th>ชื่อ</th>
      <th>นามสกุล</th>
      <th>ห้อง</th>
      <th>คำสั่ง</th>
    </tr>
  </thead>
  <tbody>
    @for ($i = 0; $i < count($data); $i++)
      <form class="form-horizontal" method="POST" action="/president/audition.do">
        <fieldset>
          <input type="hidden" value="">
          <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $data[$i]->title }}</td>
            <td>{{ $data[$i]->fname }}</td>
            <td>{{ $data[$i]->lname }}</td>
            <td>{{ $data[$i]->room }}</td>
            <td>
              <input type="hidden" name="national_id" value="{{ $data[$i]->national_id }}">
              <input type="hidden" name="action" value="dismiss">
              <button type="submit" class="btn btn-danger btn-block">ปฏิเสธ</button>
            </td>
            <td>
              <input type="hidden" name="national_id" value="{{ $data[$i]->national_id }}">
              <input type="hidden" name="action" value="confirm">
              <button type="submit" class="btn btn-success btn-block">ยืนยัน</button>
            </td>
          </tr>

          {{ csrf_field() }}
        </fieldset>
      </form>
    @endfor
  </tbody>
</table>
@stop
