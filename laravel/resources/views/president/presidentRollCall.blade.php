@extends('layout.main')

@section('content')
<legend>ระบบเช็คชื่อ{{ Session::get('fullname') }}</legend>
<div class="row">
  <div class="table-responsive">
    <table class="table table-striped table-hover ">
      <thead>
        <tr>
          <th>#</th>
          <th>คำนำ</th>
          <th class="col-md-5">ชื่อ</th>
          <th class="col-md-5">นามสกุล</th>
          <th>ชั้น</th>
          <th>ห้อง</th>
          <th>เลขที่</th>
          @for($k=1;$k<=20;$k++)
            <th>ครั้งที่ {{ $k }}</th>
          @endfor
        </tr>
      </thead>
      <tbody>
        @for ($i = 0; $i < count($data); $i++)
          <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $data[$i]->title }}</td>
            <td>{{ $data[$i]->fname }}</td>
            <td>{{ $data[$i]->lname }}</td>
            <td>{{ $data[$i]->class }}</td>
            <td>{{ $data[$i]->room }}</td>
            <td>{{ $data[$i]->number }}</td>
          </tr>
        @endfor
      </tbody>
    </table>
  </div>
</div>
@stop
