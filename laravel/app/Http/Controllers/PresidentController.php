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

  /**
   * Show president login page
   *
   * @return view
   */
  public function showLoginPage(){
    return view('admin.presidentLogin');
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

    if($this->authenticatePresident()){

    }
  }

  private function authenticatePresident($username, $password){
    if($this->presidentExist()){

    }
  }

  public function presidentExist(){

  }

  /**
   * Check if president already logged in
   */
  public static function presidentLoggedIn(){

  }
}
