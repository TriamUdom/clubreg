<?php namespace App\Admin;

use DB;
use Config;
use Session;

class Admin{

  /**
   * Authenticate the admin
   *
   * @param mixed $username
   * @param mixed $password
   * @return bool
   */
  public function authenticateAdmin($username, $password){
    if($this->adminExist($username)){
      $data = DB::table('admin')->where('username',$username)->first();
      if(sha1($data->salt . $password) == $data->password){
        // Auth Successful
        // Laravel's Session Magic. Do Not Touch.
        Session::put('admin_logged_in', '1');
        Session::put('fullname', $username);


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
   * Check if admin exist
   *
   * @param mixed $username
   * @return bool
   */
  public function adminExist($username){
    if(DB::table('admin')->where('username', $username)->exists()){
      return true;
    }else{
      return false;
    }
  }

  /**
   * Check if admin already logged in
   *
   * @return bool
   */
  public static function adminLoggedIn(){
    if(Session::get('admin_logged_in') == 1){
      return true;
    }else{
      return false;
    }
  }

  public function doDBMigrate(){
    DB::table('teacher_year')->where('year', Config::get('applicationConfig.operation_year'))->delete();
    $group = DB::table('subject_group')->get();
    for($i=0;$i<count($group);$i++){
      for($j=1;$j<=$group[$i]->teacher_available;$j++){
        DB::table('teacher_year')->insert(array(
          'subject_code' => $group[$i]->subject_code,
          'number' => $j,
          'year' => Config::get('applicationConfig.operation_year')
        ));
      }
    }
    return true;
  }
}
