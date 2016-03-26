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

  /**
   * President class instance
   *
   * @var \App\President
   */
  private $president;

  /**
   * Construct new president class
   *
   * @return void
   */
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
      $data = DB::table('club')->where('club_code', Session::get('club_code'))->first();
      return view('admin.president')->with('data', $data);
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

  /**
   * Show audition selection page
   *
   * @return view if loggedin
   * @return Redirection if not loggedin
   */
  public function showAuditionPage(){
    if(President::presidentLoggedIn()){
      $data = $this->president->getAuditionData();
      $data2 = $this->president->getAuditionPassed();
      return view('admin.presidentAudition')->with('data', $data)->with('data2', $data2);
    }else{
      return Redirect::to('/president/login');
    }
  }

  /**
   * Call 
   *
   *
   */
  public function auditionAction(){
    if(President::presidentLoggedIn()){
      $result = $this->president->auditionAction();
      switch($result){
        case 'confirm':
          return Redirect::to('/president/audition')->with('success','ยืนยันการสมัครแล้ว');
        break;
        case 'dismiss':
          return Redirect::to('/president/audition')->with('success','ปฏิเสธการสมัครแล้ว');
        break;
        default:
          return Redirect::to('/president/audition')->with('error','เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง');
        break;
      }
    }else{
      return Redirect::to('/president/login');
    }
  }

  public function auditionCancel(){
    if(President::presidentLoggedIn()){
      $result = $this->president->auditionCancel();
      switch($result){
        case 'cancel':
          return Redirect::to('/president/audition')->with('success','ยกเลิกการสมัครแล้ว');
        break;
        default:
          return Redirect::to('/president/audition')->with('error','เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง');
        break;
      }
    }else{
      return Redirect::to('/president/login');
    }
  }
}
