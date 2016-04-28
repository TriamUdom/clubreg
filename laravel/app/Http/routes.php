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
    Route::get('/confirmed','OperationController@confirmedClub');

    switch(Config::get('applicationConfig.mode')){
      case 'confirmation':
        Route::get('/confirmation','ConfirmationController@showConfirmationPage');
        Route::post('/confirmation.do','ConfirmationController@confirm');
        Route::post('/confirmation.delete','ConfirmationController@delete');
      break;
      case 'audition':
        Route::get('/audition','AuditionController@showAuditionPage');
        Route::post('/audition.do','AuditionController@addUserToQueue');
        Route::post('/audition.delete','AuditionController@removeUserFromQueue');
        Route::post('/audition/confirm.do','AuditionController@confirmUser');
      break;
      case 'war':
        Route::get('/registration','RegistrationController@showRegistrationPage');
        Route::post('/registration.do','RegistrationController@addUserToList');
        Route::post('/registration.delete','RegistrationController@removeUserFromList');
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
    Route::get('/president/registration', 'PresidentController@showRegisteredPage');
    Route::get('/president/all', 'PresidentController@showAllPage');
    Route::get('/president/fm3301', 'PresidentController@fillFM3301');
    Route::post('/president/fm3301.do', 'PresidentController@showFM3301');
  }

  if(Config::get('applicationConfig.administration')){
    Route::get('/debug/movedata','DebugOperation@moveData');
    Route::get('/debug/moveuserdata','DebugOperation@moveuserdata');
    Route::get('/admin','AdminController@showAdminPage');
    Route::get('/admin/login','AdminController@showLoginPage');
    Route::post('/admin/login.do','AdminController@adminLogin');
    Route::get('/admin/dbmigrate','AdminController@dbMigrate');
    Route::post('/admin/dbmigrate.do','AdminController@doDBMigrate');
    Route::get('/admin/moveconfirmationdata','AdminController@showMoveconfirmationdata');
    Route::post('/admin/moveconfirmationdata.do','AdminController@moveConfirmationData');
    Route::get('/admin/checklist','AdminController@showCheckListPage');
    Route::get('/admin/checklist/beforeconfirmation','AdminController@showBeforeConfirmationPage');
    Route::get('/admin/checklist/afterconfirmation','AdminController@showAfterConfirmationPage');
    Route::get('/admin/checklist/beforeaudition','AdminController@showBeforeAuditionPage');
    Route::get('/admin/checklist/afteraudition','AdminController@showAfterAuditionPage');
    Route::get('/admin/checklist/beforeregistration','AdminController@showBefoReregistrationPage');
    Route::get('/admin/checklist/afterregistration','AdminController@showAfterRegistrationPage');
  }
}
