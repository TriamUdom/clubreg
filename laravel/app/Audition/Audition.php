<?php namespace App\Audition;

use DB;
use Config;
use Session;
use Operation;

class Audition{

  /**
   * Get all club that have audition which the user haven't selected
   *
   * @return array club that have audition which the user haven't selected
   */
  public function getAuditionClub(){
    $selected = DB::table('audition')
                  ->where('national_id', Session::get('national_id'))
                  ->where('year', Config::get('applicationConfig.operation_year'))
                  ->whereIn('status', [-1, 0, 1])
                  ->get();

    for($i=0;$i<count($selected);$i++){
      $selected_code[] = $selected[$i]->club_code;
    }
    if(isset($selected_code)){
      $data = DB::table('club')
                ->where('audition',1)
                ->where('active',1)
                ->whereNotIn('club_code', $selected_code)
                ->get();
    }else{
      $data = DB::table('club')
                ->where('audition',1)
                ->where('active',1)
                ->get();
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
    $Rawclub_code = DB::table('audition')
                      ->where('national_id', Session::get('national_id'))
                      ->where('year', Config::get('applicationConfig.operation_year'))
                      ->where('status', '!=', 1)
                      ->get();
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

  public function getAuditionPassed(){
    $Rawclub_code = DB::table('audition')
                      ->where('national_id', Session::get('national_id'))
                      ->where('year', Config::get('applicationConfig.operation_year'))
                      ->where('status', 1)
                      ->get();
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
   * @return true upon success
   * @return string error message upon failure
   */
  public function addUserToQueue($club_code){
    if(Operation::isClubActive($club_code)){
      if(Operation::isClubAudition($club_code)){
        if(DB::table('audition')->where('club_code', $club_code)->where('national_id', Session::get('national_id'))->where('year', Config::get('applicationConfig.operation_year'))->count() == 0){
          DB::table('audition')->insert(array(
            'national_id' => Session::get('national_id'),
            'club_code' => $club_code,
            'status' => 0,
            'timestamp' => time(),
            'year' => Config::get('applicationConfig.operation_year')
          ));

          return true;
        }else{
          return 'นักเรียนได้ลงทะเบียนชมรมนี้แล้ว ไม่สามารถลงทะเบียนซ้ำได้';
        }
      }else{
        return 'ชมรมนี้เปิดรับนักเรียนสำหรับการสมัครแบบธรรมดาเท่านั้น';
      }
    }else{
      return 'ชมรมที่เลือกไม่ได้เปิดรับสมัครนักเรียนในปีการศึกษานี้';
    }
  }

  /**
   * Remove user from audition waiting queue
   *
   * @return true upon success
   * @return string error message upon failure
   */
  public function removeUserFromQueue($club_code){
    if(Operation::isClubActive($club_code)){
      if(Operation::isClubAudition($club_code)){
        DB::table('audition')
          ->where('club_code', $club_code)
          ->where('national_id', Session::get('national_id'))
          ->where('year', Config::get('applicationConfig.operation_year'))
          ->delete();
        return true;
      }else{
        return 'ชมรมนี้เปิดรับนักเรียนสำหรับการสมัครแบบธรรมดาเท่านั้น';
      }
    }else{
      return 'ชมรมที่เลือกไม่ได้เปิดรับสมัครนักเรียนในปีการศึกษานี้';
    }
  }

  /**
   * Check if user have pending audition
   *
   * @param string $club_code specify club_code for searching
   * @return bool
   */
  public static function havePendingAudition($club_code = null){
    if(is_null($club_code)){
      $data = DB::table('audition')
                ->where('national_id', Session::get('national_id'))
                ->where('year', Config::get('applicationConfig.operation_year'))
                ->first();
      if(is_null($data)){
        return false;
      }else{
        return true;
      }
    }else{
      $data = DB::table('audition')
                ->where('national_id', Session::get('national_id'))
                ->where('club_code', $club_code)
                ->where('year', Config::get('applicationConfig.operation_year'))
                ->first();
      if(is_null($data)){
        return false;
      }else{
        return true;
      }
    }
  }

  /**
   * Confirm the club that user pass
   *
   * @param string $club_code
   * @return true upon success
   * @return string error message upon failure
   */
  public function confirmAudition($club_code){
    if(Operation::isClubActive($club_code)){
      if(Operation::isClubAudition($club_code)){
        DB::table('audition')
          ->where('national_id', Session::get('national_id'))
          ->where('club_code', $club_code)
          ->where('year', Config::get('applicationConfig.operation_year'))
          ->update(array(
            'status' => 2
          ));

        return true;
      }else{
        return 'ชมรมนี้เปิดรับนักเรียนสำหรับการสมัครแบบธรรมดาเท่านั้น';
      }
    }else{
      return 'ชมรมที่เลือกไม่ได้เปิดรับสมัครนักเรียนในปีการศึกษานี้';
    }
  }
}
