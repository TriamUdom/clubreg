<?php namespace App\Http\Controllers;

use DB;
use Input;
use Session;
use Redirect;

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
  public function showAuditionPage(){
    if(OperationController::userLoggedIn()){
      $data = DB::table('club')->where('audition',1)->where('active',1)->get();
      return view('audition')->with('data',$data);
    }else{
      return Redirect::to('/login');
    }
  }
}
