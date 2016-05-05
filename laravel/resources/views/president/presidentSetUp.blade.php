@extends('layout.main')

@section('content')
<form class="form-horizontal" method="POST" action="/president/setup.do">
  <fieldset>
   <legend>ตั้งค่า{{ Session::get('fullname') }}</legend>
   @include('layout.component.errorwbg')
   @include('layout.component.successwbg')
   <div class="form-group">
     <label class="col-lg-2 control-label">ชื่อประธานชมรม</label>
     <div class="col-lg-2">
       <select class="form-control" id="presidentTitle" name="presidentTitle">
         @if($presidentTitle == 'นาย')
           <option selected>นาย</option>
         @else
           <option>นาย</option>
         @endif
         @if($presidentTitle == 'นางสาว')
           <option selected>นางสาว</option>
         @else
           <option>นางสาว</option>
         @endif
         @if($presidentTitle == 'ด.ช.')
           <option selected>ด.ช.</option>
         @else
           <option>ด.ช.</option>
         @endif
         @if($presidentTitle == 'ด.ญ.')
           <option selected>ด.ญ.</option>
         @else
           <option>ด.ญ.</option>
         @endif
       </select>
     </div>
     <div class="col-lg-4">
       <input type="text" class="form-control" id="presidentFirstName" name="presidentFirstName" placeholder="ชื่อ" value="{{ $presidentFname }}">
     </div>
     <div class="col-lg-4">
       <input type="text" class="form-control" id="presidentLastName" name="presidentLastName" placeholder="นามสกุล" value="{{ $presidentLname }}">
     </div>
   </div>

   <div class="form-group">
     <label class="col-lg-2 control-label">ชื่อประธานที่ปรึกษาชมรม</label>
     <div class="col-lg-2">
       <select class="form-control" id="adviserTitle" name="adviserTitle">
         @if($adviserTitle == 'นาย')
           <option selected>นาย</option>
         @else
           <option>นาย</option>
         @endif
         @if($adviserTitle == 'นางสาว')
           <option selected>นางสาว</option>
         @else
           <option>นางสาว</option>
         @endif
         @if($adviserTitle == 'นาง')
           <option selected>นาง</option>
         @else
           <option>นาง</option>
         @endif
       </select>
     </div>
     <div class="col-lg-4">
       <input type="text" class="form-control" id="adviserFirstName" name="adviserFirstName" placeholder="ชื่อ" value="{{ $adviserFname }}">
     </div>
     <div class="col-lg-4">
       <input type="text" class="form-control" id="adviserLastName" name="adviserLastName" placeholder="นามสกุล" value="{{ $adviserLname }}">
     </div>
   </div>
   {{ csrf_field() }}
   <br />

   <div class="form-group">
     <div class="col-lg-10 col-lg-offset-2">
       <button type="submit" class="btn btn-success btn-block">ตั้งค่า</button>
     </div>
   </div>
  </fieldset>
</form>

@stop
