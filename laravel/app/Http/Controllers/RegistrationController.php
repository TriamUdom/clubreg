<?php namespace App\Http\Controllers;

use DB;
use Redirect;
use Operation;

/*
|--------------------------------------------------------------------------
| Registration Controller
|--------------------------------------------------------------------------
|
| This controller handle the rendering of registration page
| and data flow to Registration model
|
*/

class RegistrationController extends Controller{

  /**
   * Registration class instance
   *
   * @var \App\Registration
   */
  private $registration;

  /**
   * Construct new audition instance
   *
   * @return void
   */
  public function __construct(){
    $this->registration = new Registration();
  }

  public function showRegistrationPage(){
    if(Operation::userLoggedIn()){
      if(Operation::haveClub(true)){
        //Already confirm club
        return Redirect::to('/confirmed');
      }else{
        return view('registration');
      }
    }else{
      Redirect::to('/login');
    }
  }

}
