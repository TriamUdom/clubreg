<?php namespace App\President;

use DB;

class President{

  private $loginData = null;

  /**
   * Authenticate the president
   *
   * @param mixed $username
   * @param mixed $password
   * @return bool
   */
  private function authenticatePresident($username, $password){
    if($this->presidentExist($username)){
      $data = DB::table('president')->where('username',$username)->first();
      if($data->password == $password){
        // Auth Successful
        // Laravel's Session Magic. Do Not Touch.
        //Session::put('president_logged_in', '1');
        //Session::put('username', $user->national_id);
        $club = DB::table('club')->where('club_code', $data->club_code)->pluck('club_name');
        Session::put('fullname', $club);
        Session::put('club_code',$data->club_code);

        $this->loginData = array(
          'club_code' => $data->club_code
        );

        // Log the request
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $this->logAuthenticationAttempt($username, $ip_address, "1"); // Success

        return true;
      }else{
        // Log the request
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $this->logAuthenticationAttempt($username, $ip_address, "0"); // Login Failed

        return false;
      }
    }else{
      // Log the request
      $ip_address = $_SERVER['REMOTE_ADDR'];
      $this->logAuthenticationAttempt($username, $ip_address, "0"); // Login Failed

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
  private function logAuthenticationAttempt($username, $ip_address, $success){

      $timestamp = time();

      $id = DB::table('login_log')->insertGetId(array(
          'unix_timestamp' => $timestamp,
          'user_nationalid' => $username,
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
}
