<?php namespace App\President;

use DB;
use Crypt;
use Input;
use Config;
use Session;
use Redirect;

class President{

  /**
   * Authenticate the president
   *
   * @param mixed $username
   * @param mixed $password
   * @return bool
   */
  public function authenticatePresident($username, $password){
    if($this->presidentExist($username)){
      $data = DB::table('president')->where('username',$username)->first();
      if(sha1($data->salt . $password) == $data->password){
        // Auth Successful
        // Laravel's Session Magic. Do Not Touch.
        //Session::put('username', $user->national_id);
        Session::put('president_logged_in', '1');
        $club = DB::table('club')->where('club_code', $data->club_code)->pluck('club_name');
        Session::put('fullname', $club);
        Session::put('club_code',$data->club_code);

        if(Config::get('applicationConfig.environment') != 'testing'){
          // Log the request
          $ip_address = $_SERVER['REMOTE_ADDR'];
          $useragent = $_SERVER['HTTP_USER_AGENT'];
          $this->logAuthenticationAttempt($username, $ip_address, "1", $useragent); // Success
        }

        return true;
      }else{
        if(Config::get('applicationConfig.environment') != 'testing'){
          // Log the request
          $ip_address = $_SERVER['REMOTE_ADDR'];
          $useragent = $_SERVER['HTTP_USER_AGENT'];
          $this->logAuthenticationAttempt($username, $ip_address, "0", $useragent); // Login Failed
        }
        return false;
      }
    }else{
      if(Config::get('applicationConfig.environment') != 'testing'){
        // Log the request
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        $this->logAuthenticationAttempt($username, $ip_address, "0", $useragent); // Login Failed
      }
      return false;
    }
  }

  /**
   * Log user's login attempt
   *
   * @param mixed $nationalid
   * @param mixed $ip_address
   * @param bool $success whether or not the attempt success
   * @param string $useragent useragent
   * @return bool
   */
  private function logAuthenticationAttempt($username, $ip_address, $success, $useragent){
      $id = DB::table('login_log')->insertGetId(array(
          'unix_timestamp' => time(),
          'username' => $username,
          'type' => 'president',
          'ip_address' => $ip_address,
          'success' => $success,
          'useragent' => $useragent
      ));

      if ($id != 0) {
          return true;
      } else {
          return false;
      }

  }

  /**
   * Check if president exist
   *
   * @param mixed $username
   * @return bool
   */
  public function presidentExist($username){
    if(DB::table('president')->where('username', $username)->exists()){
      return true;
    }else{
      return false;
    }
  }

  /**
   * Check if president already logged in
   *
   * @return bool
   */
  public static function presidentLoggedIn(){
    if(Session::get('president_logged_in') == 1){
      return true;
    }else{
      return false;
    }
  }

  /**
   * Get data of pending auditioner
   *
   * @return object data
   */
  public function getAuditionData(){
    $data = DB::table('audition')
      ->join('user','audition.national_id','=','user.national_id')
      ->where('audition.club_code',Session::get('club_code'))
      ->where('status',0)
      ->select('audition.club_code','user.title','user.fname','user.lname','user.room','user.national_id')
      ->orderBy('room','asc')
      ->get();
    for($i=0;$i<count($data);$i++){
      $data[$i]->national_id = Crypt::encrypt($data[$i]->national_id);
    }

    return $data;
  }

  /**
   * Get data of passed auditioner
   *
   * @return object data
   */
  public function getAuditionPassed(){
    $data = DB::table('audition')
      ->join('user','audition.national_id','=','user.national_id')
      ->where('audition.club_code',Session::get('club_code'))
      ->where('status',1)
      ->select('audition.club_code','user.title','user.fname','user.lname','user.room','user.national_id')
      ->orderBy('room','asc')
      ->get();
    for($i=0;$i<count($data);$i++){
      $data[$i]->national_id = Crypt::encrypt($data[$i]->national_id);
    }

    return $data;
  }

  /**
   * Do audition action
   *
   * @return string on success
   * @return false on failure
   */
  public function auditionAction(){
    $national_id_encrypted  = Input::get('national_id');
    $action                 = Input::get('action');

    $national_id = Crypt::decrypt($national_id_encrypted);

    if(DB::table('audition')->where('national_id', $national_id)->where('status', 1)->exists()){
      return Redirect::to('/president/audition')->with('error','ผู้ใช้นี้มีชมรมแล้ว');
    }else{
      switch($action){
        case 'confirm':
          DB::beginTransaction();
            try{
              //Get user into our club
              DB::table('audition')
                ->where('national_id', $national_id)
                ->where('club_code', Session::get('club_code'))
                ->where('year', Config::get('applicationConfig.operation_year'))
                ->update(array('status' => 1));

              //Prevent user from attend to other club
              DB::table('audition')
                ->where('national_id', $national_id)
                ->whereNotIn('club_code', array(Session::get('club_code')))
                ->where('year', Config::get('applicationConfig.operation_year'))
                ->where('status', 0)
                ->update(array('status' => -2));
            }catch(Exception $e){
              DB::rollBack();
            }
          DB::commit();
          return 'confirm';
        break;
        case 'dismiss':
          DB::table('audition')
            ->where('national_id', $national_id)
            ->where('club_code', Session::get('club_code'))
            ->where('year', Config::get('applicationConfig.operation_year'))
            ->update(array('status' => -1));
          return 'dismiss';
        break;
        default:
          return false;
        break;
      }
    }
  }

  /**
   * Cancel audition
   *
   * @return string on success
   * @return false on failure
   */
  public function auditionCancel(){
    $national_id_encrypted  = Input::get('national_id');
    $action                 = Input::get('action');

    $national_id = Crypt::decrypt($national_id_encrypted);

    switch($action){
      case 'cancel':
        DB::beginTransaction();
          try{
            //Get user out of our club
            DB::table('audition')
              ->where('national_id', $national_id)
              ->where('club_code', Session::get('club_code'))
              ->where('year', Config::get('applicationConfig.operation_year'))
              ->update(array('status' => 0));

            //Allow user to attend to other club
            DB::table('audition')
              ->where('national_id', $national_id)
              ->whereNotIn('club_code', array(Session::get('club_code')))
              ->where('year', Config::get('applicationConfig.operation_year'))
              ->where('status', -2)
              ->update(array('status' => 0));
          }catch(Exception $e){
            DB::rollBack();
          }
        DB::commit();
        return 'cancel';
      break;
      default:
        return false;
      break;
    }

  }
}
