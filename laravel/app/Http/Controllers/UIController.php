<?php namespace App\Http\Controllers;

use DB;

class UIController extends Controller{
  public function index(){
    return view('index');
  }

  public function login(){
    return view('login');
  }
}
