<?php namespace App\Http\Controllers;

use DB;
use Input;
use Session;
use Redirect;
use Audition;

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
    if(OperationController::userLoggedIn()){
      $data = $this->audition->getAuditionClub();
      return view('audition')->with('data',$data);
    }else{
      return Redirect::to('/login');
    }
  }

  public function addUserToQueue(){
    $club_code = Input::get('club_code');
    $this->audition->addUserToQueue($club_code);
    return Redirect::to('/audition');
  }
}
