@if(Session::get('success') != null)
<div class="alert alert-success" style="background: #3ada4f;">
  {{ Session::get('success') }}
</div>
@endif
