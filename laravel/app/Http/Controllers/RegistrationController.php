<?php namespace App\Http\Controllers;

use DB;
use Input;
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

  /**
   * Render registration page
   *
   * @return view
   */
  public function showRegistrationPage(){
    if(Operation::userLoggedIn()){
      if(Operation::haveClub(true)){
        //Already confirm club
        return Redirect::to('/confirmed');
      }else{
        $data = $this->registration->getRegistrationClub();
        if(isset($data['full'])){
          return view('registration')->with('data', array(
            'available' => $data['notFull'],
            'full' => $data['full'],
          ));
        }else{
          return view('registration')->with('data', array(
            'available' => $data['notFull']
          ));
        }
      }
    }else{
      Redirect::to('/login');
    }
  }

  /**
   * Call registration model to add user to list
   *
   * @return view
   */
  public function addUserToList(){
    if(Operation::userLoggedIn()){
      if(!Operation::haveClub(true)){
        $club_code = Input::get("club_code");
        $add = $this->registration->addUserToList($club_code);
        if($add === true){
          return Redirect::to('/confirmed');
        }else{
          return Redirect::to('/registration')->with('error', $add);
        }
      }else{
        return Redirect::to('/confirmed')->with('error', 'นักเรียนเลือกชมรมแล้ว ไม่สามารถเปลี่ยนแปลงได้');
      }
    }else{
      return Redirect::to('/login');
    }
  }

  /**
   * Call registration model to remove user from list
   *
   * @return view
   */
  public function removeUserFromList(){
    if(Operation::userLoggedIn()){
      if(Operation::haveClub(true)){
        $club_code = Input::get("club_code");
        $remove = $this->registration->removeUserFromList($club_code);
        if($remove === true){
          return Redirect::to('/registration');
        }else{
          return Redirect::to('/registration')->with('error', $remove);
        }
      }else{
        return Redirect::to('/confirmed')->with('error', 'นักเรียนเลือกชมรมแล้ว ไม่สามารถเปลี่ยนแปลงได้');
      }
    }else{
      return Redirect::to('/login');
    }
  }
}
