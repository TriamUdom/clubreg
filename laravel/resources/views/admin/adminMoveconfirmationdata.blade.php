@extends('layout.main')

@section('content')
@include('layout.component.errorwbg')
@include('layout.component.successwbg')
<legend>Move Confirmation Data</legend>
<form class="form-horizontal" method="POST" action="/admin/moveconfirmationdata.do">
  <fieldset>
    {{ csrf_field() }}
    <button type="submit" class="btn btn-success btn-block">Move</button>
  </fieldset>
</form>
@stop
