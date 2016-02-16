<?php namespace App\Http\Controllers;

use DB;
use Input;
use Config;
use Session;
use Redirect;
use President;
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

  private $president;

  public function __construct(){
    $this->president = new President;
  }

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
    if(President::presidentLoggedIn()){
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

    if($this->president->authenticatePresident($username, $password)){
      return Redirect::to('/president');
    }else{
      return Redirect::back()->with('error','ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง');
    }
  }

  /**
   * Show member of the club who confirm their will to still be in the club
   *
   * @return view with data upon president logged in
   * @return Redirection upon president not logged in
   */
  public function showConfirmedPage(){
    if(President::presidentLoggedIn()){
      $data = DB::table('user')
      ->where('confirmation_status', 1)
      ->where('current_club', Session::get('club_code'))
      ->orderBy('room', 'asc')
      ->orderBy('number', 'asc')
      ->get();
      return view('admin.presidentConfirmed')->with('data',$data);
    }else{
      return Redirect::to('/president/login');
    }
  }

  public function showAuditionPage(){
    if(President::presidentLoggedIn()){
      $data = $this->president->getAuditionData();
      return view('admin.presidentAudition')->with('data',$data);
    }else{
      return Redirect::to('/president/login');
    }
  }

  public function auditionAction(){
    if(President::presidentLoggedIn()){
      $this->president->auditionAction();
    }else{
      return Redirect::to('/president/login');
    }
  }
}
