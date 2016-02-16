<?php namespace App\Audition;

use DB;

class Audition{
  public function getAuditionClub(){
    $data = DB::table('club')->where('audition',1)->where('active',1)->get();
    return $data;
  }

  public function addUserToQueue($club_code){
    DB::table('audition')->insert(array(
      'national_id' => , Session::get('national_id'),
      'club_code' => $club_code,
      'status' => 0,
      'timestamp' => time()
    ));

    return true;
  }
}
