<?php
/*
 * Copyright 2014-2021 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// Based off: https://www.phpflow.com/php/php-web-application-authentication-using-google-oauth-2-0/

if ( isset($google_0auth) ) {
 
 
    // Add access token to php session after successfully authenticated
    if ( isset($_GET['code']) ) {
        
    $google_client->authenticate($_GET['code']);
    
    $_SESSION['access_token'] = $google_client->getAccessToken();
    
    header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
    exit;
    
    }
     
     
    //set token
    if ( isset($_SESSION['access_token']) && $_SESSION['access_token'] ) {
    $google_client->setAccessToken($_SESSION['access_token']);
    }
     
     
    //store with user data
    if ( $google_client->getAccessToken() ) {
        
    $userData = $google_0auth->userinfo->get();
      
      if( !empty($userData) ) {
        //insert data into database
      }
      
    $_SESSION['access_token'] = $google_client->getAccessToken();
      
    }
    else {
    $googleAuthUrl  =  $google_client->createAuthUrl();
    }


}

?>