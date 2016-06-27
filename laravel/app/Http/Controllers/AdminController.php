<?php namespace App\Http\Controllers;

use DB;
use Crypt;
use Input;
use Admin;
use Config;
use Session;
use Redirect;
use Validator;

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

  public function showManualSearch(){
      return view('admin.adminManualSearch');
  }

  public function showManualAdd(){
      $input = array();
      $input['nid'] = 0;
      $input['sid'] = 0;
      if(Input::all()){
          $input = Input::all();
      }
      $club_code = DB::table('user_year')
                      ->join('user', 'user_year.national_id', '=', 'user.national_id')
                      ->join('club', 'user_year.club_code', '=', 'club.club_code')
                      ->where('user_year.year', 2559)
                      ->where(
                      function($query) use ($input){
                          $query->where('user.national_id', $input['nid'])
                                ->orWhere('user.student_id', $input['sid']);
                      })
                      ->pluck('club.club_code');
      if($club_code){
          $data = DB::table('user_year')
                    ->join('user', 'user_year.national_id', '=', 'user.national_id')
                    ->join('club', 'user_year.club_code', '=', 'club.club_code')
                    ->where('user_year.year', 2559)
                    ->where(
                    function($query) use ($input){
                        $query->where('user.national_id', $input['nid'])
                              ->orWhere('user.student_id', $input['sid']);
                    })
                    ->first();
      }else{
          $data = DB::table('user_year')
                    ->join('user', 'user_year.national_id', '=', 'user.national_id')
                    ->where('user_year.year', 2559)
                    ->where(
                    function($query) use ($input){
                        $query->where('user.national_id', $input['nid'])
                              ->orWhere('user.student_id', $input['sid']);
                    })
                    ->first();
      }
      $clubs = DB::table('club')
                 ->where('active', 1)
                 ->orderBy('club_code', 'asc')
                 ->get();
      if(isset($data->national_id)){
          $manual = DB::table('manualadd')
                      ->where('national_id', $data->national_id)
                      ->where('year', Config::get('applicationConfig.operation_year'))
                      ->first();
          return view('admin.adminManualAdd')
                    ->with('data', $data)
                    ->with('encrypted', array(
                            'national_id' => Crypt::encrypt($data->national_id)
                        ))
                    ->with('manual', $manual)
                    ->with('clubs', $clubs);
      }else{
          return view('admin.adminManualAdd')
                    ->with('data', $data)
                    ->with('clubs', $clubs);
      }
  }

  public function manualAdd(){
      $input = Input::all();
      $current_club = DB::table('user_year')
                          ->where('national_id', Crypt::decrypt($input['nid']))
                          ->where('year', Config::get('applicationConfig.operation_year'))
                          ->pluck('club_code');
      if($input['wanted_club'] == $current_club){
          return Redirect::to('/admin/manualadd?sid=&nid='.Crypt::decrypt($input['nid']))
                          ->with('error', 'ไม่มีการเปลี่ยนแปลงชมรม ไม่สามารถบันทึกข้อมูลได้');
      }
      DB::table('manualadd')
          ->where('national_id', Crypt::decrypt($input['nid']))
          ->where('year', Config::get('applicationConfig.operation_year'))
          ->delete();
      DB::table('manualadd')
          ->insert(array(
              'national_id' => Crypt::decrypt($input['nid']),
              'current_club' => $current_club,
              'wanted_club' => $input['wanted_club'],
              'description' => $input['description'],
              'timestamp' => time(),
              'year' => Config::get('applicationConfig.operation_year'),
              'done' => 0
          ));
      return Redirect::to('/admin/manualadd?sid=&nid='.Crypt::decrypt($input['nid']))
                     ->with('success','บันทึกข้อมูลเรียบร้อยแล้ว');
  }

  public function manualReg(){
      $datas = DB::table('manualadd')->where('year', 2559)->where('done', 0)->get();
      $audition_club = DB::table('club')->where('audition', 1)->lists('club_code');
      $messages = array();
      $required_audition = array();
      foreach($datas as $data){
          $user_year = DB::table('user_year')
                         ->where('national_id', $data->national_id)
                         ->where('year', Config::get('applicationConfig.operation_year'))
                         ->first();
          if($data->wanted_club == $user_year->club_code){
              $messages[] = "<h4 color=\"green\">No action need to be done for $data->national_id<h4>";
          }else{
              if(in_array($data->wanted_club, $audition_club)){
                  $club = DB::table('audition')
                            ->where('national_id', $data->national_id)
                            ->where('year', Config::get('applicationConfig.operation_year'))
                            ->where('club_code', $data->wanted_club)
                            ->first();
                  if(!empty($club) && $club->status == 1){
                      if(!empty($user_year->club_code)){
                          $this->userYearDataReset($data->national_id);
                      }
                      DB::beginTransaction();
                          try{
                              DB::table('audition')
                                ->where('national_id', $data->national_id)
                                ->where('year', Config::get('applicationConfig.operation_year'))
                                ->where('club_code', $data->wanted_club)
                                ->update(array(
                                    'status' => 2
                                ));

                              DB::table('user_year')
                                ->where('national_id', $data->national_id)
                                ->where('year', Config::get('applicationConfig.operation_year'))
                                ->update(array(
                                    'club_code' => $data->wanted_club
                                ));

                              DB::table('manualadd')
                                ->where('national_id', $data->national_id)
                                ->where('year', Config::get('applicationConfig.operation_year'))
                                ->update(array(
                                    'done' => 1
                                ));
                          }catch(Exception $e){
                              DB::rollback();
                              echo($e->getMessage());
                          }
                      DB::commit();
                      $messages[] = "<h3 color=\"green\">Register $data->national_id by audition finished</h3>";
                  }else{
                      $messages[] = "<h3 color=\"red\">Cannot proceed for national_id : $data->national_id</h3><br><h4>Club required audition</h4>";
                      $required_audition[] = $data->national_id;
                  }
              }else{
                  if(!empty($user_year->club_code)){
                      $this->userYearDataReset($data->national_id);
                  }
                  DB::beginTransaction();
                      try{
                          DB::table('registration')
                            ->insert(array(
                                'national_id' => $data->national_id,
                                'club_code' => $data->wanted_club,
                                'timestamp' => time(),
                                'year' => Config::get('applicationConfig.operation_year')
                            ));

                          DB::table('user_year')
                            ->where('national_id', $data->national_id)
                            ->where('year', Config::get('applicationConfig.operation_year'))
                            ->update(array(
                                'club_code' => $data->wanted_club
                            ));

                          DB::table('manualadd')
                            ->where('national_id', $data->national_id)
                            ->where('year', Config::get('applicationConfig.operation_year'))
                            ->update(array(
                                'done' => 1
                            ));
                      }catch(Exception $e){
                          DB::rollback();
                          echo($e->getMessage());
                      }
                  DB::commit();
                  $messages[] = "<h3 color=\"green\">Register $data->national_id by registration finished</h3>";
              }
          }
      }
      return view('admin.adminManualRegistration')->with('messages', $messages)->with('required_audition', $required_audition);
  }

  private function userYearDataReset($national_id){
      $audition_club = DB::table('club')->where('audition', 1)->lists('club_code');
      $current_club = DB::table('user_year')
                        ->where('national_id', $national_id)
                        ->where('year', Config::get('applicationConfig.operation_year'))
                        ->pluck('club_code');

      DB::beginTransaction();
          try{
              DB::table('user_year')
                ->where('national_id', $national_id)
                ->where('year', Config::get('applicationConfig.operation_year'))
                ->update(array(
                    'club_code' => ''
                ));

              if(in_array($current_club, $audition_club)){
                  DB::table('audition')
                    ->where('national_id', $national_id)
                    ->where('year', Config::get('applicationConfig.operation_year'))
                    ->where('status', 2)
                    ->update(array(
                        'status' => 1
                    ));
              }else{
                  DB::table('registration')
                    ->where('national_id', $national_id)
                    ->where('year', Config::get('applicationConfig.operation_year'))
                    ->delete();
              }
          }catch(Exception $e){
              DB::rollback();
              echo($e->getMessage());
          }
      DB::commit();
      return true;
  }

  public function fixExcelEncodingError(){
      $datas = DB::table('manualadd')->where('wanted_club', 'LIKE', '?%')->get();
      foreach($datas as $data){
          $new = 'ก'.substr($data->wanted_club, 1);
          DB::table('manualadd')->where('national_id', $data->national_id)->update(array(
              'wanted_club' => $new
          ));
      }
  }
}
