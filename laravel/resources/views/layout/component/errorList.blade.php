@if(Session::get('errorList') != null)
  {{ Session::put('errorList', array_unique(Session::get('errorList'))) }}
  @for($i=0;$i<count(Session::get('errorList'));$i++)
    {{ Session::get('errorList')[$i] }} <br>
  @endfor
@endif
