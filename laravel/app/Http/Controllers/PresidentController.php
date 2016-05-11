<?php namespace App\Http\Controllers;

use DB;
use Input;
use Config;
use Session;
use Redirect;
use President;
use Operation;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

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
      $presidentName = $this->president->getPresidentName();
      $adviserName = $this->president->getAdviserName();
      if(isset($presidentName[0]) && isset($presidentName[1]) && isset($presidentName[2]) && isset($adviserName[0]) && isset($adviserName[1]) && isset($adviserName[2])){
        $canEdit = true;
      }else{
        $canEdit = false;
      }
      return view('president.president')
              ->with('audition', Operation::isClubAudition(Session::get('club_code')))
              ->with('canEdit', $canEdit);
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
    $recaptcha = Input::get('g-recaptcha-response');

    $validator = Validator::make(array(
      'username' => $username,
      'password' => $password,
      'g-recaptcha-response' => $recaptcha
    ),array(
      'username' => 'required',
      'password' => 'required',
      'g-recaptcha-response' => 'required|recaptcha'
    ),array(
      'username.required' => 'ต้องกรอกชื่อผู้ใช้',
      'password.required' => 'ต้องกรอกรหัสผ่าน',
      'g-recaptcha-response.recaptcha' => 'ต้องทำเครื่องหมายถูกในช่อง "ฉันไม่ใช่โปรแกรมอัตโนมัติ"',
      'g-recaptcha-response.required' => 'ต้องทำเครื่องหมายถูกในช่อง "ฉันไม่ใช่โปรแกรมอัตโนมัติ"'
    ));

    if($validator->fails()){
      return Redirect::back()->with('errorList', $validator->errors()->all());
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
                ->orderBy('user_year.class', 'asc')
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
      $confirmed = $this->president->getAuditionConfirmed();

      return view('president.presidentAudition')
        ->with('pending', $pending)
        ->with('pass', $pass)
        ->with('fail', $fail)
        ->with('confirmed', $confirmed);
    }else{
      return Redirect::to('/president/login');
    }
  }

  /**
   * Call president model to do audition action
   *
   * @return Redirection
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

  /**
   * Call president model to cancel the passed audition
   *
   * @return Redirection
   */
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

  /**
   * Render registered list page
   *
   * @return view
   */
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
                ->orderBy('user_year.class', 'asc')
                ->orderBy('user_year.room', 'asc')
                ->orderBy('user_year.number', 'asc')
                ->get();
      return view('president.presidentRegistered')->with('data', $data);
    }else{
      return Redirect::to('/president/login');
    }
  }

  /**
   * Render all member list page
   *
   * @return view
   */
  public function showAllPage(){
    if(President::presidentLoggedIn()){
      $data = $this->president->getAllStudentList();
      return view('president.presidentAll')->with('data', $data);
    }else{
      return Redirect::to('/president/login');
    }
  }

  /**
   * Render club setup page
   *
   * @return view
   */
  public function showSetUpPage(){
    if(President::presidentLoggedIn()){
      $presidentName = $this->president->getPresidentName();
      $adviserName = $this->president->getAdviserName();
      return view('president.presidentSetUp')
              ->with('presidentTitle', $presidentName[0])
              ->with('presidentFname', $presidentName[1])
              ->with('presidentLname', $presidentName[2])
              ->with('adviserTitle', $adviserName[0])
              ->with('adviserFname', $adviserName[1])
              ->with('adviserLname', $adviserName[2]);
    }else{
      return Redirect::to('/president/login');
    }
  }

  /**
   * Prepare and send data to nameSetUp method in model
   *
   * @return Redirection
   */
  public function doSetUp(){
    if(President::presidentLoggedIn()){
      $president_title = trim(Input::get('presidentTitle'));
      $president_fname = trim(Input::get('presidentFirstName'));
      $president_lname = trim(Input::get('presidentLastName'));
      $adviser_title = trim(Input::get('adviserTitle'));
      $adviser_fname = trim(Input::get('adviserFirstName'));
      $adviser_lname = trim(Input::get('adviserLastName'));

      $validator = Validator::make(array(
        'president_title' => $president_title,
        'president_fname' => $president_fname,
        'president_lname' => $president_lname,
        'adviser_title' => $adviser_title,
        'adviser_fname' => $adviser_fname,
        'adviser_lname' => $adviser_lname
      ),array(
        'president_title' => 'required',
        'president_fname' => 'required',
        'president_lname' => 'required',
        'adviser_title' => 'required',
        'adviser_fname' => 'required',
        'adviser_lname' => 'required'
      ));

      if($validator->fails()){
        return Redirect::to('/president/setup')->with('error','รูปแบบข้อมูลไม่ถูกต้องหรือมีข้อมูลเป็นค่าว่าง');
      }

      if($this->president->nameSetUp($president_title, $president_fname, $president_lname, $adviser_title, $adviser_fname, $adviser_lname)){
        return Redirect::to('/president/setup')->with('success', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
      }else{
        return Redirect::to('/president/setup')->with('error', 'มีข้อผิดพลาดเกิดขึ้น กรุณาลองใหม่ในภายหลัง');
      }
    }else{
      return Redirect::to('/president/login');
    }
  }

  /**
   * Render fillFM3301 page
   *
   * @return view
   */
  public function fillFM3301(){
    if(President::presidentLoggedIn()){
      return view('president.presidentFill3301');
    }else{
      return Redirect::to('/president/login');
    }
  }

  /**
   * Give order to create FM3301 and send it to user
   *
   * @return response
   */
  public function showFM3301(){
    if(President::presidentLoggedIn()){
      $path = $this->president->createFM3301();
      return response()->download($path)->deleteFileAfterSend(true);
    }else{
      return Redirect::to('/president/login');
    }
  }

  /**
   * Render fillFM3304 page
   *
   * @return view
   */
  public function fillFM3304(){
    if(President::presidentLoggedIn()){
      return view('president.presidentFill3304');
    }else{
      return Redirect::to('/president/login');
    }
  }

  /**
   * Give order to create FM3304 and send it to user
   *
   * @return response
   */
  public function showFM3304(){
    if(President::presidentLoggedIn()){
      $semester     = Input::get('semester');
      if($semester != 1 && $semester != 2){
        return Redirect::to('/president/fm3304')->with('error', 'ภาคเรียนต้องมีค่าเป็น 1 หรือ 2 เท่านั้น');
      }
      $path = $this->president->createFM3304($semester);
      return response()->download($path)->deleteFileAfterSend(true);
    }else{
      return Redirect::to('/president/login');
    }
  }

  /**
   * Render page to select year to fill FM3305
   *
   * @return view
   */
  public function selectYearToFillFM3305(){
    if(President::presidentLoggedIn()){
      return view('president.presidentSelectYearToFillFM3305');
    }else{
      return Redirect::to('/president/login');
    }
  }

  /**
   * Redirect user to appropriate url to continue the filling of FM3305
   *
   * @return Redirection
   */
  public function selectYear(){
    $year = Config::get('applicationConfig.operation_year');
    $semester = Input::get('semester');
    return Redirect::to('/president/fm3305/'.$year.'/'.$semester);
  }

  /**
   * Render fillFM3305 view
   *
   * @return view
   */
  public function fillFM3305(Request $request, $year, $semester){
    if(President::presidentLoggedIn()){
      if(($semester == 1 || $semester == 2) && $year == Config::get('applicationConfig.operation_year')){
        $pass = $this->president->getMemberPass(true, $semester);
        $notPass = $this->president->getMemberNotPass(true, $semester);
        return view('president.presidentFill3305')
          ->with('pass', $pass)
          ->with('notPass', $notPass)
          ->with('semester', $semester);
      }else{
        abort(400, "Invalid semester or year");
      }
    }else{
      return Redirect::to('/president/login');
    }
  }

  /**
   * Give order to addUserToNotPass in president model
   *
   * @return Redirection
   */
  public function addUserToNotPass(){
    if(President::presidentLoggedIn()){
      $national_id_encrypted = Input::get('national_id');
      $semester = Input::get('semester');
      $year = Config::get('applicationConfig.operation_year');
      $add = $this->president->addUserToNotPass($national_id_encrypted, $semester);
      if($add === true){
        return Redirect::to('/president/fm3305/'.$year.'/'.$semester)->with('success', 'ให้มผ.นักเรียนเรียบร้อย');
      }else{
        return Redirect::to('/president/fm3305/'.$year.'/'.$semester)->with('error', $add);
      }
    }else{
      return Redirect::to('/president/login');
    }
  }

  /**
   * Give order to removeUserFromNotPass in president model
   *
   * @return Redirection
   */
  public function removeUserFromNotPass(){
    if(President::presidentLoggedIn()){
      $national_id_encrypted = Input::get('national_id');
      $semester = Input::get('semester');
      $year = Config::get('applicationConfig.operation_year');
      $remove = $this->president->removeUserFromNotPass($national_id_encrypted, $semester);
      if($remove === true){
        return Redirect::to('/president/fm3305/'.$year.'/'.$semester)->with('success', 'ให้ผ.นักเรียนเรียบร้อย');
      }else{
        return Redirect::to('/president/fm3305/'.$year.'/'.$semester)->with('error', $add);
      }
    }else{
      return Redirect::to('/president/login');
    }
  }

  /**
   * Give order to create FM3305 and send it to user
   *
   * @return response
   */
  public function showFM3305(){
    if(President::presidentLoggedIn()){
      $semester = Input::get('semester');
      if($semester != 1 && $semester != 2){
        return Redirect::to('/president/fm3304')->with('error', 'ภาคเรียนต้องมีค่าเป็น 1 หรือ 2 เท่านั้น');
      }
      $path = $this->president->createFM3305($semester);
      return response()->download($path)->deleteFileAfterSend(true);
    }else{
      return Redirect::to('/president/login');
    }
  }

  /**
   * Render rollcall page
   *
   * @return view
   */
  public function showRowCallPage(){
    if(President::presidentLoggedIn()){
      $data = $this->president->getAllStudentList();
      return view('president.presidentRollCall')->with('data', $data);
    }else{
      return Redirect::to('/president/login');
    }
  }
}
