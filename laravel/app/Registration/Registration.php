<?php namespace App\Registration;

use DB;
use Session;

class Registration{

  /**
   * Get all club that don't have audition which the user haven't selected
   *
   * @return array club that don't have audition which the user haven't selected
   */
  public function getRegistrationClub(){
    $selected = DB::table('registration')->where('national_id', Session::get('national_id'))->get();
    for($i=0;$i<count($selected);$i++){
      $selected_code[] = $selected[$i]->club_code;
    }
    if(isset($selected_code)){
      $data = DB::table('club')->where('audition',0)->where('active',1)->whereNotIn('club_code', $selected_code)->get();
    }else{
      $data = DB::table('club')->where('audition',0)->where('active',1)->get();
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
    $Rawclub_code = DB::table('registration')->where('national_id', Session::get('national_id'))->get();
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

}
