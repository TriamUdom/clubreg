<?php namespace App\Http\Controllers;

use DB;
use Input;
use Config;
use Session;
use Redirect;
use Operation;
use Confirmation;

/*
|--------------------------------------------------------------------------
| Confirmation Controller
|--------------------------------------------------------------------------
|
| This controller handle both UI and backend for confirmation mode
|
|
*/

class ConfirmationController extends Controller{

  /**
   * Confirmation class instance
   *
   * @var \App\Confirmation
   */
  private $confirmation;

  /**
   * Construct new confirmation instance
   *
   * @return void
   */
  public function __construct(){
    $this->confirmation = new Confirmation();
  }

  /**
   * Show confirmation page
   *
   * @return view
   */
  public function showConfirmationPage(){
    if(Operation::userLoggedIn()){
      $club_code = DB::table('user_year')->where('year', Config::get('applicationConfig.operation_year')-1)->pluck('club_code');
      if(Operation::isClubActive($club_code)){
        $data = $this->confirmation->getCurrentClub();
        return view('confirmation')
          ->with('data',array(
            'confirmation_status' => $data['confirmation_status'],
            'current_club' => $data['current_club'],
            'club_code' => $data['club_code']
          ));
      }else{
        return view('confirmation')->with('not_open', 'ชมรมนี้ไม่ได้เปิดรับสมัครนักเรียนในปีการศึกษา'.Config::get('applicationConfig.mode'));
      }
    }else{
      return Redirect::to('/login');
    }
  }

  /**
   * Confirm handler
   *
   * @return Redirection
   */
  public function confirm(){
    if(Operation::userLoggedIn()){
      if(!Operation::haveClub()){
        $current_status = Input::get('current_status');
        $club_code   = Input::get('club_code');
        $confirm = $this->confirmation->confirm($current_status, $club_code);
        if($confirm === true){
          return Redirect::to('/confirmation');
        }else{
          return Redirect::to('/confirmation')->with('error', $confirm);
        }
      }else{
        return Redirect::to('/confirmation')->with('error', 'Error code : 0x000002');
      }
    }else{
      return Redirect::to('/login');
    }
  }

  /**
   * Delete handler
   *
   * @return Redirection
   */
  public function delete(){
    if(Operation::userLoggedIn()){
      if(Operation::haveClub()){
        $current_status = Input::get('current_status');
        $club_code   = Input::get('club_code');
        $delete = $this->confirmation->delete($current_status, $club_code);
        if($delete === true){
          return Redirect::to('/confirmation');
        }else{
          return Redirect::to('/confirmation')->with('error', $delete);
        }
      }else{
        return Redirect::to('/confirmation')->with('error', 'Error code : 0x000001');
      }
    }else{
      return Redirect::to('/login');
    }
  }
}
