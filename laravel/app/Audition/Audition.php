<?php namespace App\Audition;

use DB;

class Audition{
  public function getAuditionClub(){
    $data = DB::table('club')->where('audition',1)->where('active',1)->get();
    return $data;
  }
}
