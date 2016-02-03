<?php namespace App\Http\Controllers;

use DB;
use Input;
use Config;
use Session;
use Redirect;
use Validator;

/*
|--------------------------------------------------------------------------
| President Controller
|--------------------------------------------------------------------------
|
| This controller handle both UI and backend for president operation
|
|
*/

class PresidentController extends Controller{

  public $club_code = null;

  /**
   * Show president login page
   *
   * @return view
   */
  public function showLoginPage(){
    return view('admin.presidentLogin');
  }

  /**
   * Show president main page
   *
   * @return view upon logged in
   * @return Redirection upon not logged in
   */
  public function showPresidentPage(){
    if(self::presidentLoggedIn()){
      return view('admin.president');
    }else{
      return Redirect::to('/president/login');
    }
  }

  /**
   * President login handler
   *
   * @return Redirection
   */
  public function presidentLogin(){
    Session::flush();
    Session::regenerate();

    $username = Input::get('username');
    $password = Input::get('password');

    $validator = Validator::make(array(
      'username' => $username,
      'password' => $password
    ),array(
      'username' => 'required',
      'password' => 'required'
    ));

    if($validator->fails()){
      return Redirect::back()->with('error','รูปแบบข้อมูลไม่ถูกต้องหรือมีข้อมูลเป็นค่าว่าง');
    }

    if($this->authenticatePresident($username, $password)){
      return Redirect::to('/president');
    }else{
      return Redirect::back()->with('error','ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง');
    }
  }

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
        Session::put('president_logged_in', '1');
        //Session::put('username', $user->national_id);
        $club = DB::table('club')->where('club_code', $data->club_code)->pluck('club_name');
        Session::put('fullname', $club);
        $this->club_code = $data->club_code;

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

  public function showConfirmedPage(){
    $data = DB::table('user')->where('confirmation_status',1)->get();
    return view('admin.presidentConfirmed',$data);
  }
}
