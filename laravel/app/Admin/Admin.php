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
          'type' => 'admin',
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

  /**
   * Create database for each year opeartion
   */
  public function doDBMigrate(){
    DB::table('teacher_year')
      ->where('year', Config::get('applicationConfig.operation_year'))
      ->delete();

    //Add dynamic teacher
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

    //Add static teacher
    $static = DB::table('club')->where('active', 1)->get();
    for($q=0;$q<count($static);$q++){
      for($k=1;$k<=$static[$q]->fix_teacher;$k++){
        DB::table('teacher_year')->insert(array(
          'subject_code' => $static[$q]->subject_code,
          'number' => "-".$k,
          'year' => Config::get('applicationConfig.operation_year'),
          'club_code' => $static[$q]->club_code
        ));
      }
    }
    return true;
  }

  /**
   * Copy the club_code from confirmation table to user_year table
   *
   * @return true
   */
  public function moveConfirmationData(){
    $data = DB::table('confirmation')
              ->where('year', Config::get('applicationConfig.operation_year'))
              ->get();

    for($i=0; $i < count($data); $i++){
      DB::table('user_year')
        ->where('national_id', $data[$i]->national_id)
        ->where('year', Config::get('applicationConfig.operation_year'))
        ->update(array(
          'club_code' => $data[$i]->club_code
        ));
    }

    return true;
  }
}
