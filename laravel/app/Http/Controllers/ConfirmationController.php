<?php namespace App\Http\Controllers;

use DB;
use Input;
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
      if(!Operation::haveClub()){
        $data = $this->confirmation->getCurrentClub();
        return view('confirmation')
          ->with('data',array(
            'confirmation_status' => $data['confirmation_status'],
            'current_club' => $data['current_club']
          ));
      }else{
        return Redirect::to('/confirmed')->with('error', 'นักเรียนเลือกชมรมแล้ว ไม่สามารถเพิ่มข้อมูลได้');
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

        $case = $this->confirmation->doConfirm($current_status);
        switch($case){
          case 'back':
            return Redirect::back();
          break;
          case 'notloggedin':
            return Redirect::to('/login');
          break;
        }
      }
    }else{
      return Redirect::to('/login');
    }
  }
}
