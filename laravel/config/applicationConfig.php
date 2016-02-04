<?php

return [

  /*
  release: release notation & software state
  values: alpha, beta, release
  */
  'release' => 'release',

  /*
  mode: application operating mode
  values: confirmation, audition, sorting1, sorting2, war, close

  confirmation => confirm that user will be in the same club as before
  audition => audition new member
  sorting1 => user select the sequence and we'll random it out
  sorting2 => phase 2 of sorting, this will give the user to decide whether or not
    they accept the club that we give them
  war => get in to reg war
  close => close
  technical_difficulties => just in case, almost every route will be close and we'll throw error
    // I'm not really know exactly how this will work
    // But I'm hoping that we'll never have to use it
  */
  'mode' => 'close',

  /*
  TUSD Server Address
  */
  'tusd_address' => '',

  /*
  TUSD API key and secret
  */
  'tusd_api_key' => '',
  'tusd_api_secret' => '',

  /*
  reCAPTCHA Verification URL : URL for the Google reCAPTCHA API service
  */
  'recaptcha_verification_url' => 'https://www.google.com/recaptcha/api/siteverify',

  /*
  reCAPTCHA Secret : Secret (API Key) for the reCAPTCHA service
  */
  'recaptcha_secret' => '6LdeihYTAAAAAHic28L24vHAgEaJM8EH_WwdifUd',


];
