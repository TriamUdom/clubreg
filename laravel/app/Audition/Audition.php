<?php namespace App\Audition;

use DB;
use Session;

class Audition{
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

  public function getSelected(){
    $Rawclub_code = DB::table('audition')->where('national_id', Session::get('national_id'))->get();
    if(!empty($Rawclub_code)){
      for($i=0;$i<count($Rawclub_code);$i++){
        $club_code = $Rawclub_code[$i]->club_code;
        $club_name = DB::table('club')->where('club_code', $club_code)->pluck('club_name');
        $data[] = array('club_name' => $club_name, 'club_code' => $club_code);
      }
      return $data;
    }
    else{
      return false;
    }
  }

  public function addUserToQueue($club_code){
    DB::table('audition')->insert(array(
      'national_id' => Session::get('national_id'),
      'club_code' => $club_code,
      'status' => 0,
      'timestamp' => time()
    ));

    return true;
  }

  public function removeUserFromQueue($club_code){
    DB::table('audition')->where('club_code', $club_code)->where('national_id', Session::get('national_id'))->delete();
    return true;
  }
}
