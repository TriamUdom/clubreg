<?php namespace App\Http\Controllers;

use DB;

/*
|-----------------------------------|
|                                   |
|-----------------------------------|
*/
class DebugOperation extends Controller{

  public function moveData(){
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
  }
}
