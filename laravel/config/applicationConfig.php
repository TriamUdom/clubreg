<?php

return [

  /*
  release: release notation & software state
  values: alpha, beta, release
  */
  'release' => 'alpha',

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
  'mode' => 'audition',

  /*
  year: application operation year
  value: study year in buddhist era
  */
  'operation_year' => '2559',

  /*
  administration: enable admin operation
  value: true, false
  */
  'administration' => true,

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
