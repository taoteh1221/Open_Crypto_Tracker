<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// Based off: https://www.phpflow.com/php/php-web-application-authentication-using-google-oauth-2-0/


//Google API PHP Library includes
require_once($base_dir . '/app-lib/php/other/third-party/google-api-php-client-minimal/vendor/autoload.php');
 
 
// Set config params to acces Google API
 $application_name = $app_config['google_home_application_name'];
 $client_id = $app_config['google_home_client_id'];
 $client_secret = $app_config['google_home_client_secret'];
 $redirect_uri = $base_url . 'oauth.php';
 
 
//Create and Request to access Google API 
// We are using push notifications for the price alerts 
// (after firewalling the raspi with it's own subnetwork / WAP device, 
// and creating an authenticated google API action with our dynamic ip's URI)
// https://developers.google.com/assistant/engagement/notifications
// https://www.reddit.com/r/GoogleAssistantDev/comments/eptoha/need_google_home_api_authentication_locally_for/
$client = new Google_Client();
$client->setApplicationName($application_name);
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);
 
$objRes = new Google_Service_Oauth2($client);
 
 
//Add access token to php session after successfully authenticate
if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}
 
 
//set token
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
  $client->setAccessToken($_SESSION['access_token']);
}
 
 
//store with user data
if ($client->getAccessToken()) {
  $userData = $objRes->userinfo->get();
  if(!empty($userData)) {
	//insert data into database
  }
  $_SESSION['access_token'] = $client->getAccessToken();
} else {
  $googleAuthUrl  =  $client->createAuthUrl();
}




 
?>