@extends('layout.main')

@section('content')
<a class="btn btn-primary btn-lg btn-block" href="/admin/checklist" target="_self">Check List</a>
<a class="btn btn-primary btn-lg btn-block" href="/admin/dbmigrate" target="_self">Database migration</a>
<a class="btn btn-primary btn-lg btn-block" href="/admin/moveconfirmationdata" target="_self">Move Confirmation Data</a>
<a class="btn btn-primary btn-lg btn-block" href="/admin/excmanualregistration" target="_self">Manual registration execution</a>
@stop
