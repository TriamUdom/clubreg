@extends('layout.main')

@section('content')
<a class="btn btn-primary btn-lg btn-block" href="/president/confirmed" target="_self">1. นักเรียนที่ยืนยันชมรมเดิม</a>
<?php //@TODO add @if to check if this club have audition?>
<a class="btn btn-primary btn-lg btn-block" href="/president/audition" target="_self">2. นักเรียนที่สมัครออดิชัน</a>
@stop
