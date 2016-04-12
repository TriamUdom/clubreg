@extends('layout.main')

@section('content')
<legend>Database Migration</legend>
<h5>ปีในฐานข้อมูล : </h5>
<h5>ปีในไฟล์ตั้งค่า : {{ Config::get('applicationConfig.operation_year') }}</h5>
@stop
