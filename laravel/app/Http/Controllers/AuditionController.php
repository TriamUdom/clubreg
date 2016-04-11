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

  /**
   * Show audition page
   *
   * @return view
   */
  public function showAuditionPage(){
    if(Operation::userLoggedIn()){
      if($confirmed_club = DB::table('confirmation')->where('national_id', Session::get('national_id'))->first()){
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
          return view('audition')->with('data',array('available' => $available, 'selected' => $selected));
        }
      }
    }else{
      return Redirect::to('/login');
    }
  }

  /**
   * Call audition model to add user to audition queue
   *
   * @return Redirection
   */
  public function addUserToQueue(){
    $club_code = Input::get('club_code');
    $this->audition->addUserToQueue($club_code);
    return Redirect::to('/audition');
  }

  /**
   * Call audition model to remove user from audition queue
   *
   * @return Redirection
   */
  public function removeUserFromQueue(){
    $club_code = Input::get('club_code');
    $this->audition->removeUserFromQueue($club_code);
    return Redirect::to('/audition');
  }
}
