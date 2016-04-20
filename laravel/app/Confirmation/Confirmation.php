<?php namespace App\Confirmation;

use DB;
use Config;
use Session;
use Redirect;
use Operation;

class Confirmation{

  // @TODO : Rewrite this class according to the table

  /**
   * Get user's current club
   *
   * @return array data
   */
  public function getCurrentClub(){
    $club_code = DB::table('user_year')
                   ->where('national_id', Session::get('national_id'))
                   ->where('year', Config::get('applicationConfig.operation_year')-1)
                   ->pluck('club_code');
    if(!is_null(DB::table('confirmation')->where('national_id', Session::get('national_id'))->where('club_code', $club_code)->first())){
      $confirmation_status = 1;
    }else{
      $confirmation_status = 0;
    }
    $current_club = DB::table('club')->where('club_code', $club_code)->pluck('club_name');
    return array(
      'confirmation_status' => $confirmation_status,
      'current_club' => $current_club,
      'club_code' => $club_code
    );
  }

  /**
   * Confirm current club
   *
   * @return string Redirection action
   */
  public function doConfirm($current_status){
    if($current_status == 1){
      try{
        if(DB::table('user')->where('national_id',Session::get('national_id'))->exists()){
          DB::table('user')->where('national_id',Session::get('national_id'))->update(array(
            'confirmation_status' => 0
          ));
          return 'back';
        }else{
          throw new UserDataException('No national_id');
        }
      }catch(\Exception $e){
        return 'notloggedin';
      }
    }else{
      try{
        if(DB::table('user')->where('national_id',Session::get('national_id'))->exists()){
          DB::table('user')->where('national_id',Session::get('national_id'))->update(array(
            'confirmation_status' => 1
          ));
          return 'back';
        }else{
          throw new UserDataException('No national_id');
        }
      }catch(\Exception $e){
        return 'notloggedin';
      }
    }
  }
}
