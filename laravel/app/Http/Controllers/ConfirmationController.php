<?php namespace App\Http\Controllers;

use DB;
use Input;
use Session;
use Redirect;
use Operation;
use Confirmation;

/*
|--------------------------------------------------------------------------
| Confirmation Controller
|--------------------------------------------------------------------------
|
| This controller handle both UI and backend for confirmation mode
|
|
*/

class ConfirmationController extends Controller{

  private $confirmation;

  public function __construct(){
    $this->confirmation = new Confirmation();
  }

  /**
   * Show confirmation page
   *
   * @return view on success
   */
  public function showConfirmationPage(){
    if(Operation::userLoggedIn()){
      $data = $this->confirmation->getCurrentClub();
      return view('confirm')
        ->with('data',array(
          'confirmation_status' => $data['confirmation_status'],
          'current_club' => $data['current_club']
        ));
    }else{
      return Redirect::to('/login');
    }
  }

  /**
   * Confirm handler
   *
   * @return Redirection
   */
  public function confirm(){
    $current_status = Input::get('current_status');

    $this->confirmation->doConfirm($current_status);
    /*
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
        return Redirect::to('/login');
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
        return Redirect::to('/login');
      }
    }*/
  }
}
