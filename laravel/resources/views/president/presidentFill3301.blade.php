@extends('layout.main')

@section('content')
<form class="form-horizontal" method="POST" action="/president/fm3301.do">
  <fieldset>
   <legend>Download FM33-01 สำหรับ{{ Session::get('fullname') }}</legend>
   @include('layout.component.errorwbg')
   {{ csrf_field() }}
   <div class="form-group">
     <div class="col-lg-12">
       <button type="submit" class="btn btn-success btn-block">Download</button>
     </div>
   </div>
  </fieldset>
</form>

@stop
