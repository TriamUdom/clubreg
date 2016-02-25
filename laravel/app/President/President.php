<?php namespace App\President;

use DB;
use Crypt;
use Input;
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
      if($data->password == $password){
        // Auth Successful
        // Laravel's Session Magic. Do Not Touch.
        //Session::put('username', $user->national_id);
        Session::put('president_logged_in', '1');
        $club = DB::table('club')->where('club_code', $data->club_code)->pluck('club_name');
        Session::put('fullname', $club);
        Session::put('club_code',$data->club_code);


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

  public function auditionAction(){
    $national_id_encrypted  = Input::get('national_id');
    $action                 = Input::get('action');

    try{
      $national_id = Crypt::decrypt($national_id_encrypted);
    }catch(DecryptException $e){
      die('DecryptException');
    }

    if(DB::table('audition')->where('national_id', $national_id)->where('status', 1)->exists()){
      return Redirect::to('/president/audition')->with('error','ผู้ใช้นี้มีชมรมแล้ว');
    }else{
      switch($action){
        case 'confirm':
          //Get user into our club
          DB::table('audition')
            ->where('national_id', $national_id)
            ->where('club_code', Session::get('club_code'))
            ->update(array('status' => 1));

          //Prevent user from attend to other club
          DB::table('audition')
            ->where('national_id', $national_id)
            ->whereNotIn('club_code', array(Session::get('club_code')))
            ->where('status', 0)
            ->update(array('status' => -2));
          return 'confirm';
        break;
        case 'dismiss':
          DB::table('audition')
            ->where('national_id', $national_id)
            ->where('club_code', Session::get('club_code'))
            ->update(array('status' => -1));
          return 'dismiss';
        break;
        default:
          return false;
        break;
      }
    }
  }

  public function auditionCancel(){
    $national_id_encrypted  = Input::get('national_id');
    $action                 = Input::get('action');

    try{
      $national_id = Crypt::decrypt($national_id_encrypted);
    }catch(DecryptException $e){
      die('DecryptException');
    }

    switch($action){
      case 'cancel':
        //Get user out of our club
        DB::table('audition')
          ->where('national_id', $national_id)
          ->where('club_code', Session::get('club_code'))
          ->update(array('status' => 0));

        //Prevent user from attend to other club
        DB::table('audition')
          ->where('national_id', $national_id)
          ->whereNotIn('club_code', array(Session::get('club_code')))
          ->where('status', -2)
          ->update(array('status' => 0));
        return 'cancel';
      break;
      default:
        return false;
      break;
    }

  }
}
