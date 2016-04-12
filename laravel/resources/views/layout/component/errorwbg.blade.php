@if(Session::get('error') != null)
<div class="alert alert-danger" style="background: #FF0000;">
  {{ Session::get('error') }}
</div>
@endif
