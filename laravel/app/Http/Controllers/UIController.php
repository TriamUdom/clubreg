<?php namespace App\Http\Controllers;

use DB;

class UIController extends Controller{
  public function index(){
    return view('index');
  }

  public function login(){
    if(OperationController::userLoggedIn()){
      return Redirect::to('/');
    }else{
      return view('login');
    }
  }
}
