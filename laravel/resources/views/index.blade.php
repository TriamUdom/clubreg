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
<div style="margin-bottom: 10px;"></div>
@if(Session::get('logged_in') == 1)
  @include('layout.indexComponent.viewOnlyButton')
  <br>
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
  @include('layout.indexComponent.viewOnlyButton')
  <br>
  @if(Config::get('applicationConfig.mode') != 'close' && Config::get('applicationConfig.mode') != 'technical_difficulties')
    <div class="row">
      <div class="col-lg-12">
        <a href="/login">
          <button class="btn btn-success btn-block">ดำเนินการต่อ</button>
        </a>
      </div>
    </div>
  @endif
@endif
@stop
