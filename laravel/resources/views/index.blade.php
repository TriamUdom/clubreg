@extends('layout.main')

@section('content')
<legend>การเลือกชมรม ประจำปีการศึกษา {{ Config::get('applicationConfig.operation_year') }}</legend>
@if(Config::get('applicationConfig.mode') == 'confirmation')
  @include('layout.indexComponent.confirmation')
@elseif(Config::get('applicationConfig.mode') == 'audition')
  @include('layout.indexComponent.audition')
@elseif(Config::get('applicationConfig.mode') == 'war')
  @include('layout.indexComponent.registration')
@elseif(Config::get('applicationConfig.mode') == 'close')
  @include('layout.indexComponent.close')
@endif
<br><br>
@if(Session::get('logged_in') == 1)
  @if(Config::get('applicationConfig.mode') == 'confirmation')
  <a href="/confirmation">
    <button class="btn btn-success btn-block">ดำเนินการต่อ</button>
  </a>
  @elseif(Config::get('applicationConfig.mode') == 'audition')
  <a href="/audition">
    <button class="btn btn-success btn-block">ดำเนินการต่อ</button>
  </a>
  @elseif(Config::get('applicationConfig.mode') == 'war')
  <a href="/registration">
    <button class="btn btn-success btn-block">ดำเนินการต่อ</button>
  </a>
  @elseif(Config::get('applicationConfig.mode') == 'close')

  @endif
@else
  @if(Config::get('applicationConfig.mode') != 'close' && Config::get('applicationConfig.mode') != 'technical_difficulties')
    <a href="/login">
      <button class="btn btn-success btn-block">ดำเนินการต่อ</button>
    </a>
  @endif
@endif
@stop
