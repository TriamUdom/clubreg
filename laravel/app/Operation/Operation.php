<?php namespace App\Operation;

use DB;
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
}
