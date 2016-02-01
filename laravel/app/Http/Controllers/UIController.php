<?php namespace App\Http\Controllers;

use DB;
use Redirect;

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
    return view('index');
  }

  /**
   * Show login page
   *
   * @return Redirection if already login
   * @return view login if not yet login
   */
  public function login(){
    if(OperationController::userLoggedIn()){
      return Redirect::to('/');
    }else{
      return view('login');
    }
  }
}
