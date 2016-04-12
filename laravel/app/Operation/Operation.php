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


        // Log the request
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $this->logAuthenticationAttempt($nid, $ip_address, "1"); // Success

        return true;
      }else{
        // Log the request
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $this->logAuthenticationAttempt($nid, $ip_address, "0"); // Login Failed

        return false;
      }
    }else{
      // Log the request
      $ip_address = $_SERVER['REMOTE_ADDR'];
      $this->logAuthenticationAttempt($nid, $ip_address, "0"); // Login Failed

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
   * @return bool
   */
  private function logAuthenticationAttempt($nationalid, $ip_address, $success){

      $timestamp = time();

      $id = DB::table('login_log')->insertGetId(array(
          'unix_timestamp' => $timestamp,
          'user_nationalid' => $nationalid,
          'ip_address' => $ip_address,
          'success' => $success
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
   * @return bool if $config = bool
   */
  public function haveClub($config = true){
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
}
