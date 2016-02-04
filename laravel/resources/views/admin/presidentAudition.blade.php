@extends('layout.main')

@section('content')
<table class="table table-striped table-hover ">
  <thead>
    <tr>
      <th>#</th>
      <th>คำนำ</th>
      <th>ชื่อ</th>
      <th>นามสกุล</th>
      <th>ห้อง</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    @for ($i = 0; $i < count($data); $i++)
      <form class="form-horizontal" method="POST" action="/president/audition.do">
        <fieldset>
          <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $data[$i]->title }}</td>
            <td>{{ $data[$i]->fname }}</td>
            <td>{{ $data[$i]->lname }}</td>
            <td>{{ $data[$i]->room }}</td>
            <td><a href=""><button type="submit" class="btn btn-danger btn-block">ปฏิเสธ</button></a></td>
            <td><a href=""><button type="submit" class="btn btn-primary btn-block">ยืนยัน</button></a></td>
          </tr>

          {{ csrf_field() }}
        </fieldset>
      </form>
    @endfor
  </tbody>
</table>
@stop
