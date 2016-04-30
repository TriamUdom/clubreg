<?php namespace App\Registration;

use DB;
use Config;
use Session;
use Operation;
use App\Exceptions\DataException;

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

  /**
   * Add user to list
   *
   * @param string $club_code
   * @return true upon success
   * @return error message upon failure
   */
  public function addUserToList($club_code){
    if(Operation::isClubActive($club_code)){
      if(!Operation::isClubAudition($club_code)){
        DB::beginTransaction();
        $totalInClub = 0;
        $totalInClub += DB::table('confirmation')
                          ->where('club_code', $club_code)
                          ->where('year', Config::get('applicationConfig.operation_year'))
                          ->count();
        $totalInClub += DB::table('registration')
                          ->where('club_code', $club_code)
                          ->where('year', Config::get('applicationConfig.operation_year'))
                          ->count();
        $teacherUsage = DB::table('teacher_year')
                          ->where('club_code', $club_code)
                          ->where('year', Config::get('applicationConfig.operation_year'))
                          ->count();
        if($totalInClub < (($teacherUsage*1)+0)){
          //Still room for more student
          if(DB::table('audition')->where('club_code', $club_code)->where('national_id', Session::get('national_id'))->where('year', Config::get('applicationConfig.operation_year'))->count() == 0){
            try{
              DB::table('registration')->insert(array(
                'national_id' => Session::get('national_id'),
                'club_code'   => $club_code,
                'timestamp'   => time(),
                'year'        => Config::get('applicationConfig.operation_year')
              ));

              if(!empty(DB::table('user_year')->where('national_id', Session::get('national_id'))->where('year', Config::get('applicationConfig.operation_year'))->pluck('club_code'))){
                throw new DataException("club_code is not empty cannot proceed");
              }else{
                DB::table('user_year')
                  ->where('national_id', Session::get('national_id'))
                  ->where('year', Config::get('applicationConfig.operation_year'))
                  ->update(array(
                    'club_code' => $club_code
                  ));
              }
            }catch(DataException $e){
              DB::rollBack();
              abort(500);
            }catch(Exception $e){
              DB::rollBack();
              abort(500);
            }
            DB::commit();
            DB::commit();
            return true;
          }else{
            return 'นักเรียนมีประวัติการลงทะเบียนชมรมนี้แล้ว';
          }
        }else{
          //All teacher had been used up
          //Let's see if we can get some more
          if($this->assignTeacherToClub($club_code, true)){
            DB::rollBack();
            if($this->assignTeacherToClub($club_code)){
              $this->addUserToList($club_code);
            }else{
              return 'ชมรมนี้มีนักเรียนเต็มแล้ว';
            }
          }else{
            return 'ชมรมนี้มีนักเรียนเต็มแล้ว';
          }
        }
      }else{
        return 'ชมรมนี้เปิดรับนักเรียนสำหรับการสมัครแบบคัดเลือกเท่านั้น';
      }
    }else{
      return 'ชมรมที่เลือกไม่ได้เปิดรับสมัครนักเรียนในปีการศึกษานี้';
    }
  }

  /**
   * Assign teacher to a specify club (if available)
   *
   * @param string $club_code
   * @return bool
   */
  private function assignTeacherToClub($club_code, $fake = false){
    $subject_code = DB::table('club')
                      ->where('club_code', $club_code)
                      ->pluck('subject_code');
    $min_number   = DB::table('teacher_year')
                      ->where('club_code', null)
                      ->where('subject_code', $subject_code)
                      ->where('number', '>=', 1)
                      ->where('year', Config::get('applicationConfig.operation_year'))
                      ->min('number');
    if(is_null($min_number)){
      //All teacher had been assigned
      return false;
    }else{
      if(!$fake){
        //There's still some teacher(s) available
        DB::table('teacher_year')
          ->where('number', $min_number)
          ->where('subject_code', $subject_code)
          ->where('year', Config::get('applicationConfig.operation_year'))
          ->update(array(
            'club_code' => $club_code
          ));
      }

      return true;
    }
  }
}
