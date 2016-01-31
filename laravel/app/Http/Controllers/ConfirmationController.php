<?php namespace App\Http\Controllers;

use DB;
use Input;
use Session;
use Redirect;

class ConfirmationController extends Controller{

  /**
   * Show confirmation page
   *
   * @return view on success
   */
  public function showConfirmationPage(){
    if(OperationController::userLoggedIn()){
      $data = DB::table('user')->where('national_id',Session::get('national_id'))->first();
      $current_club = DB::table('club')->where('club_code',$data->current_club)->pluck('club_name');
      return view('confirm')
        ->with('data',array(
          'fullname' => Session::get('fullname'),
          'confirmation_status' => $data->confirmation_status,
          'current_club' => $current_club
        ));
    }else{
      return Redirect::to('error/notloggedin');
    }
  }

  /**
   * Confirm handler
   *
   * @return Redirection
   */
  public function confirm(){
    $current_status = Input::get('current_status');

    if($current_status == 1){
      try{
        if(DB::table('user')->where('national_id',Session::get('national_id'))->exists()){
          DB::table('user')->where('national_id',Session::get('national_id'))->update(array(
            'confirmation_status' => 0
          ));
          return Redirect::back();
        }else{
          throw new Exception('No national_id');
        }
      }catch(\Exception $e){
        return Redirect::to('error/notloggedin');
      }
    }else{
      try{
        if(DB::table('user')->where('national_id',Session::get('national_id'))->exists()){
          DB::table('user')->where('national_id',Session::get('national_id'))->update(array(
            'confirmation_status' => 1
          ));
          return Redirect::back();
        }else{
          throw new Exception('No national_id');
        }
      }catch(\Exception $e){
        return Redirect::to('error/notloggedin');
      }
    }
  }
}
