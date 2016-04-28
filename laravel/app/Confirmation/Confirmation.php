<?php namespace App\Confirmation;

use DB;
use Config;
use Session;
use Redirect;
use Operation;

class Confirmation{

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

  public function confirm($current_status, $club_code){
    if($current_status == 0){
      if(Operation::isClubActive($club_code)){
        $club_code_in_db = DB::table('user_year')
                             ->where('national_id', Session::get('national_id'))
                             ->where('year', Config::get('applicationConfig.operation_year')-1)
                             ->pluck('club_code');
        if($club_code == $club_code_in_db){
          DB::table('confirmation')
            ->insert(array(
              'national_id' => Session::get('national_id'),
              'club_code' => $club_code,
              'timestamp' => time(),
              'year' => Config::get('applicationConfig.operation_year')
            ));

          return true;
        }else{
          abort(400);
        }
      }else{
        return 'ชมรมที่เลือกไม่ได้เปิดรับสมัครนักเรียนในปีการศึกษานี้';
      }
    }else{
      abort(400);
    }
  }

  public function delete($current_status, $club_code){
    if($current_status == 1){
      $club_code_in_db = DB::table('user_year')
                           ->where('national_id', Session::get('national_id'))
                           ->where('year', Config::get('applicationConfig.operation_year')-1)
                           ->pluck('club_code');
      if($club_code == $club_code_in_db){
        DB::table('confirmation')
          ->where('national_id', Session::get('national_id'))
          ->where('club_code', $club_code)
          ->where('year', Config::get('applicationConfig.operation_year'))
          ->delete();

        return true;
      }else{
        abort(400);
      }
    }else{
      abort(400);
    }
  }
}
