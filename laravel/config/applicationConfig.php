<?php

return [

  /*
  release: release notation & software state
  values: alpha, beta, release
  */
  'release' => 'release',

  /*
  mode: application operating mode
  values: confirmation, audition, war, close

  confirmation => confirm that user will be in the same club as before
  audition => audition new member
  war => get in to reg war
  close => close
  technical_difficulties => just in case, almost every route will be close and we'll throw error
    // I'm not really know exactly how this will work
    // But I'm hoping that we'll never have to use it
  */
  'mode' => call_user_func(function(){
        $auto = false;
        if($auto){
            //automatic mode selection
            if(in_array(time(), range(1463317200, 1463504399))){
                return 'audition';
            }elseif(in_array(time(), range(1463504400, 1463633999))){
                return 'close';
            }elseif(in_array(time(), range(1463634000, 1463763599))){
                return 'war';
            }else{
                return 'close';
            }
        }else{
            //manual mode selection
            return 'close';
        }
  }),

  /*
  year: application operation year
  value: study year in buddhist era
  */
  'operation_year' => '2559',

  /*
  administration: enable admin operation
  value: true, false
  */
  'administration' => false,

  /*
  environment: determain what mode the application is on
  value: local (default), testing
  */
  'environment' => env('APP_ENV'),

  /*
  pepper: additional string to further secure the hashed password
  */
  'pepper' => env('APP_PEPPER'),

  /*
  TUSD Server Address
  */
  'tusd_address' => '',

  /*
  TUSD API key and secret
  */
  'tusd_api_key' => '',
  'tusd_api_secret' => '',

];
