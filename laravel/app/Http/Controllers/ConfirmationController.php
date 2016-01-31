<?php namespace App\Http\Controllers;

use DB;
use Session;
use Illuminate\Routing\Controller;

class ConfirmationController extends Controller{

  /**
   * Show confirmation page
   *
   * @return view on success
   */
  public function showConfirmationPage(){
    if(OperationController::userLoggedIn()){
      $data = DB::table('user')->where('national_id',Session::get('national_id'))->first();
      return view('confirm')
        ->with('data',array(
          'fullname' => Session::get('fullname'),
          'confirmation_status' => $data->confirmation_status,
          'current_club' => $data->current_club
        ));
    }else{
      Redirect::to('error/notloggedin');
    }
  }
}
