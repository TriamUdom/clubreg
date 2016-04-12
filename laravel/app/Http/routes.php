<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

if(isset($_SERVER['HTTP_USER_AGENT']) && (preg_match('~MSIE|Internet Explorer~i', $_SERVER['HTTP_USER_AGENT']) || (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false))){
  // Sorry, IE is not allowed here...
  Route::any('{path?}', function(){return view("unsupported_browser");});
}else{

  //Always open route
  Route::get('/','UIController@index');
  Route::get('/error/notloggedin',function(){
    return view('notloggedin');
  });
  Route::get('/contact',function(){
    return view('contact');
  });

  if(Config::get('applicationConfig.mode') != 'close' && Config::get('applicationConfig.mode') != 'technical_difficulties'){
    Route::get('/login','UIController@login');
    Route::post('/login.do','OperationController@login');
    Route::get('/logout','OperationController@logout');
    Route::get('/confirmed','OperationController@confirmed');

    switch(Config::get('applicationConfig.mode')){
      case 'confirmation':
        Route::get('/confirm','ConfirmationController@showConfirmationPage');
        Route::post('/confirm.do','ConfirmationController@confirm');
      break;
      case 'audition':
        Route::get('/audition','AuditionController@showAuditionPage');
        Route::post('/audition.do','AuditionController@addUserToQueue');
        Route::post('/audition.delete','AuditionController@removeUserFromQueue');
      break;
      case 'war':

      break;
      default:
        Route::any('{path?}',function(){return view("config_error");});
      break;
    }
  }

  if(Config::get('applicationConfig.mode') != 'technical_difficulties'){
    Route::get('/president','PresidentController@showPresidentPage');
    Route::get('/president/login','PresidentController@showLoginPage');
    Route::post('/president/login.do','PresidentController@presidentLogin');
    Route::get('/president/confirmed','PresidentController@showConfirmedPage');
    Route::get('/president/audition','PresidentController@showAuditionPage');
    Route::post('/president/audition.do','PresidentController@auditionAction');
    Route::post('/president/audition.cancel','PresidentController@auditionCancel');
  }

  if(Config::get('applicationConfig.administration')){
    Route::get('/debug/movedata','DebugOperation@moveData');
    Route::get('/admin','AdminController@showAdminPage');
    Route::get('/admin/login','AdminController@showLoginPage');
    Route::post('/admin/login.do','AdminController@adminLogin');
    Route::get('/admin/dbmigrate','AdminController@dbMigrate');
  }
}
