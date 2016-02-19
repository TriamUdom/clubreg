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

  private $audition;

  public function __construct(){
    $this->audition = new Audition();
  }

  public function showAuditionPage(){
    if(Operation::userLoggedIn()){
      $available = $this->audition->getAuditionClub();
      $selected = $this->audition->getSelected();
    return view('audition')->with('data',array('available' => $available, 'selected' => $selected));
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
