<?php namespace App\Http\Controllers;

use DB;
use Redirect;
use Operation;
use Registration;

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
        $available = $this->registration->getRegistrationClub();
        return view('registration')->with('data', array(
          'available' => $available
        ));
      }
    }else{
      Redirect::to('/login');
    }
  }

  public function addUserToList(){
    if(Operation::userLoggedIn()){
      if(!Operation::haveClub(true)){

      }else{
        abort(403);
      }
    }else{
      return Redirect::to('/login');
    }
  }

  public function removeUserFromList(){
    if(Operation::userLoggedIn()){
      if(!Operation::haveClub(true)){

      }else{
        abort(403);
      }
    }else{
      return Redirect::to('/login');
    }
  }
}
