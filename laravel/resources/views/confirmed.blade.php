@extends('layout.main')

@section('content')

@include('layout.component.errorwbg')

<legend>ชมรมที่นักเรียนลงทะเบียน</legend>
<h6>ปีการศึกษา 2559 : {{ $club }}</h6><br>
@stop
