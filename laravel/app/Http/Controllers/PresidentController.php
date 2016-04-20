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
    return view('president.presidentLogin');
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
      return view('president.president')->with('data', $data);
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
      $data = DB::table('confirmation')
                ->join('user_year', function($join){
                  $join->on('confirmation.national_id', '=', 'user_year.national_id')
                       ->on('confirmation.year', '-1 =', 'user_year.year')
                       ->on('confirmation.club_code', '=', 'user_year.club_code');
                })
                ->join('user', 'confirmation.national_id', '=', 'user.national_id')
                ->where('user_year.year', Config::get('applicationConfig.operation_year')-1)
                ->where('user_year.club_code', Session::get('club_code'))
                ->orderBy('user_year.room', 'asc')
                ->orderBy('user_year.number', 'asc')
                ->get();

      return view('president.presidentConfirmed')->with('data',$data);
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
      $pending = $this->president->getAuditionData();
      $pass = $this->president->getAuditionPassed();
      $fail = $this->president->getAuditionFailed();

      return view('president.presidentAudition')
        ->with('pending', $pending)
        ->with('pass', $pass)
        ->with('fail', $fail);
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

  public function showRegisteredPage(){
    if(President::presidentLoggedIn()){
      $data = DB::table('registration')
                ->join('user_year', function($join){
                  $join->on('registration.national_id', '=', 'user_year.national_id')
                       ->on('registration.year', '=', 'user_year.year')
                       ->on('registration.club_code', '=', 'user_year.club_code');
                })
                ->join('user', 'registration.national_id', '=', 'user.national_id')
                ->where('user_year.year', Config::get('applicationConfig.operation_year'))
                ->where('user_year.club_code', Session::get('club_code'))
                ->orderBy('user_year.room', 'asc')
                ->orderBy('user_year.number', 'asc')
                ->get();
      return view('president.presidentRegistered')->with('data', $data);
    }else{
      return Redirect::to('/president/login');
    }
  }
}
