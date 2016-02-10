<?php namespace App\Confirmation;

use DB;
use Redirect;

class Confirmation{
  public function getCurrentClub(){
    $data = DB::table('user')->where('national_id',Session::get('national_id'))->first();
    $current_club = DB::table('club')->where('club_code',$data->current_club)->pluck('club_name');
    return array('confirmation_status' => $data->confirmation_status, 'current_club' => $current_club);
  }

  public function doConfirm($current_status){
    if($current_status == 1){
      try{
        if(DB::table('user')->where('national_id',Session::get('national_id'))->exists()){
          DB::table('user')->where('national_id',Session::get('national_id'))->update(array(
            'confirmation_status' => 0
          ));
          return Redirect::back();
        }else{
          throw new UserDataException('No national_id');
        }
      }catch(\Exception $e){
        return Redirect::to('/login');
      }
    }else{
      try{
        if(DB::table('user')->where('national_id',Session::get('national_id'))->exists()){
          DB::table('user')->where('national_id',Session::get('national_id'))->update(array(
            'confirmation_status' => 1
          ));
          return Redirect::back();
        }else{
          throw new UserDataException('No national_id');
        }
      }catch(\Exception $e){
        return Redirect::to('/login');
      }
    }
  }
}
