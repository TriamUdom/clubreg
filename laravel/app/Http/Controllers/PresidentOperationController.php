<?php namespace App\Http\Controllers;

use DB;
use Input;
use Config;
use Session;
use Redirect;
use Validator;

class PresidentOperationController extends Controller{
  public function showLoginPage(){
    return view('admin.presidentLogin');
  }

  public function presidentLogin(){
    Session::flush();
    Session::regenerate();

    $username = Input::get('username');
    $password = Input::get('password');

    $validator = Validator::make(array(
      'sid' => $sid,
      'nid' => $nid
    ),array(
      'sid' => 'required|numeric',
      'nid' => 'required|numeric'
    ));

    if($validator->fails()){
      return Redirect::back()->with('error','รูปแบบข้อมูลไม่ถูกต้องหรือมีข้อมูลเป็นค่าว่าง');
    }

  }

  public static function presidentLoggedIn(){

  }
}
