@extends('layout.main')

@section('content')
Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
<br><br>
@if(Session::get('logged_in') == 1)
<a href="/confirm">
  <button class="btn btn-success btn-block">ดำเนินการต่อ</button>
</a>
@else
<a href="/login">
  <button class="btn btn-success btn-block">ดำเนินการต่อ</button>
</a>
@endif
@stop
