<?php namespace App\Http\Controllers;

use DB;
use Input;
use Config;
use Session;
use Redirect;
use Operation;
use Validator;

/*
|--------------------------------------------------------------------------
| Operation Controller
|--------------------------------------------------------------------------
|
| This controller handle general operation (e.g.login logout) for every mode
| This controller also handle page render that doesn't fall into Audition,
| Confirmation or Registration controller
|
*/

class OperationController extends Controller{

  /**
   * Operation class instance
   *
   * @var \App\Confirmation
   */
  private $operation;

  /**
   * Construct new operation instance
   *
   * @return void
   */
  public function __construct(){
    $this->operation = new Operation;
  }

  /**
   * Login handler
   *
   * @return Redirection
   */
  public function login(){
    Session::flush();
    Session::regenerate();

    $sid = Input::get('sid');
    $nid = Input::get('nid');
    $recaptcha = Input::get('g-recaptcha-response');

    $validator = Validator::make(array(
      'sid' => $sid,
      'nid' => $nid,
      'g-recaptcha-response' => $recaptcha
    ),array(
      'sid' => 'required|digits:5',
      'nid' => 'required|digits:13',
      'g-recaptcha-response' => 'required|recaptcha'
    ),array(
      'sid.required' => 'ต้องกรอกเลขประจำตัวนักเรียน',
      'sid.digits' => 'เลขประจำตัวนักเรียนต้องเป็นตัวเลข 5 หลัก',
      'nid.required' => 'ต้องกรอกเลขประชาชน',
      'nid.digits' => 'เลขประชาชนต้องเป็นตัวเลข 13 หลัก',
      'g-recaptcha-response.recaptcha' => 'ต้องทำเครื่องหมายถูกในช่อง "ฉันไม่ใช่โปรแกรมอัตโนมัติ"',
      'g-recaptcha-response.required' => 'ต้องทำเครื่องหมายถูกในช่อง "ฉันไม่ใช่โปรแกรมอัตโนมัติ"'
    ));

    if($validator->fails()){
      return Redirect::back()->with('errorList', $validator->errors()->all());
    }

    if ($this->operation->authenticateUser($sid, $nid)) {
      switch(Config::get('applicationConfig.mode')){
        case 'confirmation':
          return Redirect::to('/confirmation');
        break;
        case 'audition':
          return Redirect::to('/audition');
        break;
        case 'war':
          return Redirect::to('/registration');
        break;
        default:
          abort(500, "Invalid operation mode");
        break;
      }

        return Redirect::to('/');
    } else {
        return Redirect::back()->with('error','รหัสประจำตัวนักเรียนหรือรหัสประชาชนไม่ถูกต้อง');
    }
  }

  /**
   * Log the user out
   *
   * @return Redirection
   */
  public function logout(){
    $this->operation->logout();
    return Redirect::to('/');
  }

  /**
   * Render page for user that already have club by someway
   *
   * @return view
   */
  public function confirmedClub(){
    if(Operation::userLoggedIn()){
      if(Operation::haveClub(true)){
        $club = DB::table('club')->where('club_code', Operation::haveClub('club'))->pluck('club_name');
        return view('confirmed')->with('club', $club)->with('year', Config::get('applicationConfig.operation_year'));
      }else{
        return Redirect::to('/');
      }
    }else{
      return Redirect::to('/login');
    }
  }
}
