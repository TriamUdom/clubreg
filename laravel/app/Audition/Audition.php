<?php namespace App\Audition;

use DB;
use Config;
use Session;

class Audition{

  /**
   * Get all club that have audition which the user haven't selected
   *
   * @return array club that have audition which the user haven't selected
   */
  public function getAuditionClub(){
    $selected = DB::table('audition')->where('national_id', Session::get('national_id'))->where('status', 0)->get();
    for($i=0;$i<count($selected);$i++){
      $selected_code[] = $selected[$i]->club_code;
    }
    if(isset($selected_code)){
      $data = DB::table('club')->where('audition',1)->where('active',1)->whereNotIn('club_code', $selected_code)->get();
    }else{
      $data = DB::table('club')->where('audition',1)->where('active',1)->get();
    }
    return $data;
  }

  /**
   * Get user's selected club
   *
   * @return array data if user have selected club
   * @return false if user don't have selected club
   */
  public function getSelected(){
    $Rawclub_code = DB::table('audition')->where('national_id', Session::get('national_id'))->get();
    if(!empty($Rawclub_code)){
      for($i=0;$i<count($Rawclub_code);$i++){
        $club_code = $Rawclub_code[$i]->club_code;
        $club_name = DB::table('club')->where('club_code', $club_code)->pluck('club_name');
        $data[] = array('club_name' => $club_name, 'club_code' => $club_code);
      }
      return $data;
    }else{
      return false;
    }
  }

  /**
   * Add user to audition waiting queue
   *
   * @return true
   */
  public function addUserToQueue($club_code){
    DB::table('audition')->insert(array(
      'national_id' => Session::get('national_id'),
      'club_code' => $club_code,
      'status' => 0,
      'timestamp' => time(),
      'year' => Config::get('applicationConfig.operation_year')
    ));

    return true;
  }

  /**
   * Remove user from audition waiting queue
   *
   * @return true
   */
  public function removeUserFromQueue($club_code){
    DB::table('audition')->where('club_code', $club_code)->where('national_id', Session::get('national_id'))->delete();
    return true;
  }

  /**
   * Check if any club had accepted user
   *
   * @return club_code if any club had accepted user
   * @return false if no club had accepted user
   */
  public function haveClub(){
    $result = DB::table('audition')->where('national_id', Session::get('national_id'))->where('status', 1)->pluck('club_code');
    if(isset($result)){
      return $result;
    }else{
      return false;
    }
  }
}
