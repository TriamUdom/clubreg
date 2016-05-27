@extends('layout.main')

@section('head')
<style>
p{
    font-size: 20px;
    margin-top: 0px;
    margin-bottom: 0px;
}
</style>
@stop

@section('content')
    @include('layout.component.successwbg')
    @include('layout.component.errorList')
    @include('layout.component.errorwbg')

    @if(empty($data))
        <div class="row">
            <div class="col-md-12" style="display:flex;justify-content:center;align-items:center;">
                <h4>ไม่พบข้อมูล</h4>
            </div>
            <div class="col-md-12" style="display:flex;justify-content:center;align-items:center;">
                <a href="/admin/manual" class="btn btn-warning" target="_self">&nbsp;&nbsp;&nbsp;&nbsp;กลับ&nbsp;&nbsp;&nbsp;&nbsp;</a>
            </div>
        </div>

    @else
        <div class="row">
            <div class="col-md-5">
                <p><b>ชื่อ-นามสกุล : </b>{{ $data->title }} {{ $data->fname }} {{ $data->lname }}</p>
            </div>
            <div class="col-md-2">
                <p><b>ชั้น : </b>ม.{{ $data->class }}</p>
            </div>
            <div class="col-md-2">
                <p><b>ห้อง : </b>{{ $data->room }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <p><b>เลขประจำตัวนักเรียน : </b>{{ $data->student_id }}</p>
            </div>
            <div class="col-md-6">
                <p><b>เลขประจำตัวประชาชน : </b>{{ $data->national_id }}</p>
            </div>
        </div>
        @if(isset($data->club_name))
            <div class="row">
                <div class="col-md-12">
                    <p><b>ชมรมปัจจุบัน : </b>{{ $data->club_name }}</p>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-md-12">
                    <p><b>ชมรมปัจจุบัน : </b>ไม่มี</p>
                </div>
            </div>
        @endif
        <form class="form-horizontal" method="POST" action="/admin/manualadd">
          <fieldset>
            <div class="form-group">
              <label for="wanted_club" class="col-lg-2 control-label">ชมรมที่ต้องการเข้า</label>
              <div class="col-lg-10">
                <select class="form-control" id="wanted_club" name="wanted_club">
                    @if(empty($manual->wanted_club))
                        @if(empty($data->club_code))
                            @foreach($clubs as $club)
                                <option value="{{ $club->club_code }}">{{ $club->club_name }}</option>
                            @endforeach
                        @else
                            @foreach($clubs as $club)
                                @if($club->club_code == $data->club_code)
                                    <option selected value="{{ $club->club_code }}">{{ $club->club_name }}</option>
                                @else
                                    <option value="{{ $club->club_code }}">{{ $club->club_name }}</option>
                                @endif
                            @endforeach
                        @endif
                    @else
                        @foreach($clubs as $club)
                            @if($club->club_code == $manual->wanted_club)
                                <option selected value="{{ $club->club_code }}">{{ $club->club_name }}</option>
                            @else
                                <option value="{{ $club->club_code }}">{{ $club->club_name }}</option>
                            @endif
                        @endforeach
                    @endif
                </select>
              </div>
            </div>
            <div class="form-group">
                <label for="description" class="col-lg-2 control-label">คำอธิบายเพิ่มเติม (ถ้ามี)</label>
                <div class="col-lg-10">
                    @if(empty($manual->description))
                        <textarea class="form-control" rows="5" id="description" name="description"></textarea>
                    @else
                        <textarea class="form-control" rows="5" id="description" name="description">{{ $manual->description }}</textarea>
                    @endif
                </div>
            </div>
            <input type="hidden" name="nid" value="{{ $encrypted['national_id'] }}">
            {{ csrf_field() }}
            <div class="form-group">
              <div class="col-lg-12">
                <button type="submit" class="btn btn-success btn-block">Submit</button>
              </div>
            </div>
          </fieldset>
        </form>
    @endif

@stop
