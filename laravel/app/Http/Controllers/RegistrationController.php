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
      if(DB::table('confirmation')->where('national_id', Session::get('national_id'))->first()){
        //Already confirm club
        return Redirect::to('/confirmed');
      }else{
        //Not yet have club
        $club_code = $this->audition->haveClub();
        if($club_code){
          //Some club had accepted the user
          $club_name = DB::table('club')->where('club_code', $club_code)->pluck('club_name');
          return view('audition')->with('club_name', $club_name);
        }else{
          //No club have accepted the user
          $available = $this->audition->getAuditionClub();
          $selected = $this->audition->getSelected();
          return view('audition')->with('data',
            array(
              'available' => $available,
              'selected' => $selected
            )
          );
        }
      }
    }else{
      Redirect::to('/login');
    }
  }

}
