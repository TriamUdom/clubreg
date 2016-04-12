@extends('layout.main')

@section('content')
@if(Session::get('error') != null)
<div class="alert alert-danger" style="background: #FF0000;">
  {{ Session::get('error') }}
</div>
@endif
@if(Session::get('success') != null)
<div class="alert alert-success" style="background: #3ada4f;">
  {{ Session::get('success') }}
</div>
@endif
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
