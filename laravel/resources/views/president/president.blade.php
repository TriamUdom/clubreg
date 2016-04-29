@extends('layout.main')

@section('content')
<a class="btn btn-primary btn-lg btn-block" href="/president/confirmed" target="_self">1. นักเรียนที่ยืนยันชมรมเดิม</a>
@if($data->audition == 1)
<a class="btn btn-primary btn-lg btn-block" href="/president/audition" target="_self">2. นักเรียนที่สมัครออดิชัน</a>
@else
<a class="btn btn-primary btn-lg btn-block" href="/president/registration" target="_self">2. นักเรียนที่สมัครชมรม</a>
@endif
<a class="btn btn-primary btn-lg btn-block" href="/president/all" target="_self">3. นักเรียนทั้งหมด</a>
<a class="btn btn-primary btn-lg btn-block" href="/president/fm3301" target="_self">4. Download FM33-01</a>
<a class="btn btn-primary btn-lg btn-block" href="/president/fm3304" target="_self">5. Download FM33-04</a>
@stop
