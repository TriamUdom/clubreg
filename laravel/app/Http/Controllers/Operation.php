<?php namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class Operation extends Controller{
  public function login(){
    $sid = Input::get('sid');
    $nid = Input::get('nid');

    $validator = Validator::make(array(
      'sid' => $sid,
      'nid' => $nid
    ),array(
      'sid' => 'required|numeric',
      'nid' => 'required|numeric'
    ));

    if($validator->fails()){
      return Redirect::to('error/invalidlogin');
    }

    if ($this->authenticateUser($sid, $nid)) {
        return Redirect::to('account');
    } else {
        return Redirect::to('error/invalidlogin');
    }
  }

  private function authenticateUser($sid, $nid){
    if($this->userExist($nid)){
      $user = DB::table('user')->where('national_id',$nid)->first();
      if($user->student_id == $sid){
        // Auth Successful
        // Laravel's Session Magic. Do Not Touch.
        Session::put('logged_in', '1');
        Session::put('nationalid', $user->national_id);
        Session::put('fullname', $user->title . $user->fname . " " . $user->lname);

        // Log the request
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $this->logAuthenticationAttempt($nationalid, $ip_address, "1"); // Success

        return true;
      }else{
        // Log the request
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $this->logAuthenticationAttempt($nationalid, $ip_address, "0"); // Login Failed

        return false;
      }
    }else{
      // Log the request
      $ip_address = $_SERVER['REMOTE_ADDR'];
      $this->logAuthenticationAttempt($nationalid, $ip_address, "0"); // Login Failed

      return false;
    }
  }

  private function userExist($nationalid){
    if(DB::table('user')->where('nationalid', $nationalid)->exists()){
      return true;
    }else{
      return false;
    }
  }

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

  public static function userLoggedIn(){
    $login_session = Session::get('logged_in');
    if ($login_session == "1") {
        return true;
    } else {
        return false;
    }
  }
}
