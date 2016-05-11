<?php namespace App\Http\Controllers;

use DB;
use Input;
use Admin;
use Config;
use Session;
use Validator;
use Redirect;

/*
|--------------------------------------------------------------------------
| Admin Controller
|--------------------------------------------------------------------------
|
| This controller handle admin UI
|
|
*/

class AdminController extends Controller{

  /**
   * Admin class instance
   *
   * @var \App\Admin
   */
  private $admin;

  /**
   * Construct new audition instance
   *
   * @return void
   */
  public function __construct(){
    $this->admin = new Admin();
  }

  /**
   * Render admin page
   *
   * @return view
   */
  public function showAdminPage(){
    if(Admin::adminLoggedIn()){
      return view('admin.admin');
    }else{
      return Redirect::to('/admin/login');
    }
  }

  /**
   * Render admin's login page
   *
   * @return view
   */
  public function showLoginPage(){
    return view('admin.adminLogin');
  }

  /**
   * Handle admin's login form POST
   *
   * @return Redirection
   */
  public function adminLogin(){
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

    if($this->admin->authenticateAdmin($username, $password)){
      return Redirect::to('/admin');
    }else{
      return Redirect::back()->with('error','ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง');
    }
  }

  /**
   * Render DB migration page
   *
   * @return view
   */
  public function dbMigrate(){
    if(Admin::adminLoggedIn()){
      return view('admin.dbmigrate')->with('data',
        array(
          'current_year' => DB::table('teacher_year')->max('year'),
          'operation_year' => Config::get('applicationConfig.operation_year')
        )
      );
    }else{
      return Redirect::to('/admin/login');
    }
  }

  /**
   * Handle migration form POST
   *
   * @return Redirection
   */
  public function doDBMigrate(){
    if(Admin::adminLoggedIn()){
      if($this->admin->doDBMigrate()){
        return Redirect::to('/admin/dbmigrate')->with('success', 'Migration success');
      }else{
        return Redirect::to('/admin/dbmigrate')->with('error', 'Migration fail');
      }
    }else{
      return Redirect::to('/admin/login');
    }
  }

  /**
   * Render moveConfirmationData view
   *
   * @return view
   */
  public function showMoveconfirmationdata(){
    if(Admin::adminLoggedIn()){
      return view('admin.adminMoveconfirmationdata');
    }else{
      return Redirect::to('/admin/login');
    }
  }

  /**
   * Handle moveConfirmationData form POST
   *
   * @return Redirection
   */
  public function moveConfirmationData(){
    if(Admin::adminLoggedIn()){
      if($this->admin->moveConfirmationData()){
        return Redirect::to('/admin/moveconfirmationdata')->with('success', 'Move success');
      }else{
        return Redirect::to('/admin/moveconfirmationdata')->with('error', 'Move fail');
      }
    }else{
      return Redirect::to('/admin/login');
    }
  }

  /**
   * Render checklist view
   *
   * @return view
   */
  public function showCheckListPage(){
    if(Admin::adminLoggedIn()){
      return view('admin.adminCheckList');
    }else{
      return Redirect::to('/admin/login');
    }
  }

  /**
   * Render checklist view
   *
   * @return view
   */
  public function showBeforeConfirmationPage(){
    if(Admin::adminLoggedIn()){
      return view('admin.adminBC');
    }else{
      return Redirect::to('/admin/login');
    }
  }

  /**
   * Render checklist view
   *
   * @return view
   */
  public function showAfterConfirmationPage(){
    if(Admin::adminLoggedIn()){
      return view('admin.adminAC');
    }else{
      return Redirect::to('/admin/login');
    }
  }

  /**
   * Render checklist view
   *
   * @return view
   */
  public function showBeforeAuditionPage(){
    if(Admin::adminLoggedIn()){
      return view('admin.adminBA');
    }else{
      return Redirect::to('/admin/login');
    }
  }

  /**
   * Render checklist view
   *
   * @return view
   */
  public function showAfterAuditionPage(){
    if(Admin::adminLoggedIn()){
      return view('admin.adminAA');
    }else{
      return Redirect::to('/admin/login');
    }
  }

  /**
   * Render checklist view
   *
   * @return view
   */
  public function showBefoReregistrationPage(){
    if(Admin::adminLoggedIn()){
      return view('admin.adminBR');
    }else{
      return Redirect::to('/admin/login');
    }
  }

  /**
   * Render checklist view
   *
   * @return view
   */
  public function showAfterRegistrationPage(){
    if(Admin::adminLoggedIn()){
      return view('admin.adminAR');
    }else{
      return Redirect::to('/admin/login');
    }
  }
}
