<?php namespace App\Http\Controllers;

use DB;
use Admin;
use Redirect;

/*
|-----------------------------------|
|                                   |
|-----------------------------------|
*/
class DebugOperation extends Controller{

  public function moveData(){
    if(Admin::adminLoggedIn()){
      $raw = DB::table('user')->where('confirmation_status', 1)->get();
      for($i=0;$i<count($raw);$i++){
        $userSet[] = array(
          'national_id' => $raw[$i]->national_id,
          'club_code' => $raw[$i]->current_club
        );
      }

      DB::table('user')->where('confirmation_status', 1)->update(['confirmation_status' => 2]);

      for($j=0;$j<count($userSet);$j++){
        DB::table('confirmation')->where('national_id', $userSet[$j]['national_id'])->insert([
          'national_id' => $userSet[$j]['national_id'],
          'club_code' => $userSet[$j]['club_code']
        ]);
      }
    }else{
      abort(401);
    }
  }

  public function moveuserdata(){
    if(Admin::adminLoggedIn()){
      $raw = DB::table('user')->get();
      for($i=0;$i<count($raw);$i++){
        $userSet[] = array(
          'national_id' => $raw[$i]->national_id,
          'room' => $raw[$i]->room,
          'number' => $raw[$i]->number,
          'year' => 2558,
          'club_code' => $raw[$i]->current_club
        );
      }

      for($j=0;$j<count($userSet);$j++){
        DB::table('user_year')->where('national_id', $userSet[$j]['national_id'])->insert([
          'national_id' => $userSet[$j]['national_id'],
          'room' => $userSet[$j]['room'],
          'number' => $userSet[$j]['number'],
          'year' => $userSet[$j]['year'],
          'club_code' => $userSet[$j]['club_code']
        ]);
      }
    }else{
      abort(401);
    }
  }
}
