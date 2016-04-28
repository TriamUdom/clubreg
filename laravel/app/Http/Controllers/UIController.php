<?php namespace App\Http\Controllers;

use DB;
use Session;
use Redirect;
use Operation;

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
}
