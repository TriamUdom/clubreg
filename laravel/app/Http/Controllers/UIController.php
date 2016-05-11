<?php namespace App\Http\Controllers;

use DB;
use Log;
use Crypt;
use Config;
use Session;
use Audition;
use Redirect;
use Operation;
use Registration;
use App\Exceptions\EasterEggException;

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

  public function tucchiring(){
      if(Config::get('applicationConfig.mode') == 'audition' || Config::get('applicationConfig.mode') == 'close'){
          $crypt = Crypt::encrypt(Session::get('national_id').time());
          $crypted = Crypt::encrypt($crypt.'-'.sha1($crypt));
          $plain = Session::get('national_id').'-'.time();
          try{
              throw new EasterEggException('New blood have been found');
          }catch(EasterEggException $e){
              Log::warning($e, array($plain, $crypted));
          }

          echo('Use this reference code for audition :<br>');
          echo($crypted);
          echo('<br><br><br>Keep it secret');
      }else{
          echo('come back next year');
      }
  }
}
