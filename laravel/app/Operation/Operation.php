<?php namespace App\Operation;

use DB;
use Config;
use Session;
use Redirect;

class Operation{

  /**
   * Authenticate user and do necessary operation associate with that
   *
   * @param int $sid
   * @param int $nid
   * @return bool
   */
  public function authenticateUser($sid, $nid){
    if($this->userExist($nid)){
      $user = DB::table('user')->where('national_id',$nid)->first();
      if($user->student_id == $sid){
        // Auth Successful
        // Laravel's Session Magic. Do Not Touch.
        Session::put('logged_in', '1');
        Session::put('national_id', $user->national_id);
        Session::put('fullname', $user->title . $user->fname . " " . $user->lname);


        if(Config::get('applicationConfig.environment') != 'testing'){
          // Log the request
          $ip_address = $_SERVER['REMOTE_ADDR'];
          $useragent = $_SERVER['HTTP_USER_AGENT'];
          $this->logAuthenticationAttempt($nid, $ip_address, "1", $useragent); // Success
        }

        return true;
      }else{
        if(Config::get('applicationConfig.environment') != 'testing'){
          // Log the request
          $ip_address = $_SERVER['REMOTE_ADDR'];
          $useragent = $_SERVER['HTTP_USER_AGENT'];
          $this->logAuthenticationAttempt($nid, $ip_address, "0", $useragent); // Login Failed
        }
        return false;
      }
    }else{
      if(Config::get('applicationConfig.environment') != 'testing'){
        // Log the request
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        $this->logAuthenticationAttempt($nid, $ip_address, "0", $useragent); // Login Failed
      }
      return false;
    }
  }

  /**
   * Check if user exist
   *
   * @param int $nationalid
   * @return bool
   */
  public function userExist($nationalid){
    if(DB::table('user')->where('national_id', $nationalid)->exists()){
      return true;
    }else{
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
          'type' => 'student',
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
   * Check if user already logged in
   *
   * @return bool
   */
  public static function userLoggedIn(){
    if(Session::get('logged_in') == 1){
      return true;
    }else{
      return false;
    }
  }

  /**
   * Log the user out
   *
   * @return Redirection
   */
  public function logout(){
    Session::flush();
    Session::regenerate();

    return true;
  }

  /**
   * Check if user have club
   *
   * @param string $config set desired return value (club, bool)
   * @return string club_code if $config = club
   * @return bool if $config = true
   */
  public static function haveClub($config = true){
    $confirmation = DB::table('confirmation')
                      ->where('national_id', Session::get('national_id'))
                      ->where('year', Config::get('applicationConfig.operation_year'))
                      ->first();
    $audition     = DB::table('audition')
                      ->where('national_id', Session::get('national_id'))
                      ->where('status', 1)
                      ->where('year', Config::get('applicationConfig.operation_year'))
                      ->first();
    $registration = DB::table('registration')
                      ->where('national_id', Session::get('national_id'))
                      ->where('year', Config::get('applicationConfig.operation_year'))
                      ->first();

    if($config === true){
      if(isset($confirmation) || isset($audition) || isset($registration)){
        return true;
      }else{
        return false;
      }
    }else if($config == 'club'){
      if(isset($confirmation)){
        return $confirmation->club_code;
      }else if(isset($audition)){
        return $audition->club_code;
      }else if(isset($registration)){
        return $registration->club_code;
      }else{
        return "No data";
      }
    }
  }

  public static function isClubActive($club_code){
    if(DB::table('club')->where('club_code', $club_code)->pluck('active') == 1){
      return true;
    }else{
      return false;
    }
  }

  public static function isClubAudition($club_code){
    if(DB::table('club')->where('club_code', $club_code)->pluck('audition') == 1){
      return true;
    }else{
      return false;
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
                ->first();
      if(is_null($data)){
        return false;
      }else{
        return true;
      }
    }
  }
}
