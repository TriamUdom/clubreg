<?php namespace App\Http\Controllers;

use DB;
use Input;
use Session;
use Redirect;
use Audition;
use Operation;

/*
|--------------------------------------------------------------------------
| Audition Controller
|--------------------------------------------------------------------------
|
| This controller handle both UI and backend for confirmation mode
|
|
*/

class AuditionController extends Controller{

  /**
   * Audition class instance
   *
   * @var \App\Audition
   */
  private $audition;

  /**
   * Construct new audition instance
   *
   * @return void
   */
  public function __construct(){
    $this->audition = new Audition();
  }

  public function showAuditionPage(){
    if(Operation::userLoggedIn()){
      $club_code = $this->audition->haveClub();
      if($club_code){
        $club_name = DB::table('club')->where('club_code', $club_code)->pluck('club_name');
        return view('audition')->with('club_name', $club_name);
      }else{
        $available = $this->audition->getAuditionClub();
        $selected = $this->audition->getSelected();
        return view('audition')->with('data',array('available' => $available, 'selected' => $selected));
      }
    }else{
      return Redirect::to('/login');
    }
  }

  public function addUserToQueue(){
    $club_code = Input::get('club_code');
    $this->audition->addUserToQueue($club_code);
    return Redirect::to('/audition');
  }

  public function removeUserFromQueue(){
    $club_code = Input::get('club_code');
    $this->audition->removeUserFromQueue($club_code);
    return Redirect::to('/audition');
  }
}
