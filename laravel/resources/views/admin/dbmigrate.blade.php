@extends('layout.main')

@section('content')
@include('layout.component.errorwbg')
@include('layout.component.successwbg')
<legend>Database Migration</legend>
<h5>ปีในฐานข้อมูล : {{ $data['current_year'] }}</h5>
<h5>ปีในไฟล์ตั้งค่า : {{ $data['operation_year'] }}</h5>
<form class="form-horizontal" method="POST" action="/admin/dbmigrate.do">
  <fieldset>
    {{ csrf_field() }}
    <button type="submit" class="btn btn-success btn-block">Migrate</button>
  </fieldset>
</form>
@stop
