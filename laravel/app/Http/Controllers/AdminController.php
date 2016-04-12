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

  public function showAdminPage(){
    if(Admin::adminLoggedIn()){
      return view('admin.admin');
    }else{
      return Redirect::to('/admin/login');
    }
  }

  public function showLoginPage(){
    return view('admin.adminLogin');
  }

  public function adminLogin(){
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

    if($this->admin->authenticateAdmin($username, $password)){
      return Redirect::to('/admin');
    }else{
      return Redirect::back()->with('error','ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง');
    }
  }

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

  public function doDBMigrate(){
    if(Admin::adminLoggedIn()){
      $this->admin->doDBMigrate();
    }else{
      return Redirect::to('/admin/login');
    }
  }
}
