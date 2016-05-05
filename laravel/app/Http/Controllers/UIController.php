<?php namespace App\Http\Controllers;

use DB;
use Session;
use Audition;
use Redirect;
use Operation;
use Registration;

/*
|--------------------------------------------------------------------------
| UI Controller
|--------------------------------------------------------------------------
|
| This controller handle general UI rendering for every mode
|
|
*/

class UIController extends Controller{

  private $audition;
  private $registration;

  /**
   * Show index page
   *
   * @return view
   */
  public function index(){
    if(Session::get('president_logged_in') == 1){
      return Redirect::to('/president');
    }else{
      return view('index');
    }
  }

  /**
   * Show login page
   *
   * @return Redirection if already login
   * @return view login if not yet login
   */
  public function login(){
    if(Operation::userLoggedIn()){
      return Redirect::to('/');
    }else{
      return view('login');
    }
  }

  public function showVOAudition(){
    if(!isset($this->audition)){
      $this->audition = new Audition;
    }
    $data = $this->audition->getAuditionClub(true);
    return view('viewOnly')->with('data', $data)->with('mode', 'มีการคัดเลือก (ออดิชัน)');
  }

  public function showVORegistration(){
    if(!isset($this->registration)){
      $this->registration = new Registration;
    }
    $data = $this->registration->getRegistrationClub(true);
    return view('viewOnly')->with('data', $data)->with('mode', 'ไม่มีการคัดเลือก (ออดิชัน)');
  }
}
