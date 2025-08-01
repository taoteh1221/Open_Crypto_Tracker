<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


//////////////////////////////////////////////////////////////////
// EARLY CONFIG INIT 
////////////////////////////////////////////////////////////////// 


// Email FROM check
if ( isset($ct['conf']['comms']['from_email']) && $ct['gen']->valid_email($ct['conf']['comms']['from_email']) == 'valid' ) {
$valid_from_email = true;
}
////
// Email services ready to rock?
if (
$ct['smtp_server_ok'] // true, IF not using / server verified online
&& $valid_from_email
&& isset($ct['conf']['comms']['to_email'])
&& $ct['gen']->valid_email($ct['conf']['comms']['to_email']) == 'valid'
) {
$ct['email_activated'] = true;
}


// Notifyme ready to rock?
if ( isset($ct['conf']['ext_apis']['notifyme_access_code']) && trim($ct['conf']['ext_apis']['notifyme_access_code']) != '' ) {
$ct['notifyme_activated'] = true;
}


// Texting (SMS) ready to rock?
// (if MORE THAN ONE is activated, keep ALL disabled to avoid a texting firestorm)
if ( isset($ct['conf']['ext_apis']['textbelt_api_key']) && trim($ct['conf']['ext_apis']['textbelt_api_key']) != '' ) {
$ct['activated_sms_services'][] = 'textbelt';
}


if (
isset($ct['conf']['ext_apis']['twilio_number']) && trim($ct['conf']['ext_apis']['twilio_number']) != ''
&& isset($ct['conf']['ext_apis']['twilio_sid']) && trim($ct['conf']['ext_apis']['twilio_sid']) != ''
&& isset($ct['conf']['ext_apis']['twilio_token']) && trim($ct['conf']['ext_apis']['twilio_token']) != ''
) {
$ct['activated_sms_services'][] = 'twilio';
}


// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
if (
isset($ct['conf']['ext_apis']['textlocal_sender'])
&& trim($ct['conf']['ext_apis']['textlocal_sender']) != ''
&& isset($ct['conf']['ext_apis']['textlocal_api_key'])
&& $ct['conf']['ext_apis']['textlocal_api_key'] != ''
) {
$ct['activated_sms_services'][] = 'textlocal';
}


$text_email_gateway_check = explode("||", trim($ct['conf']['comms']['to_mobile_text']) );


if (
isset($text_email_gateway_check[0])
&& isset($text_email_gateway_check[1])
&& trim($text_email_gateway_check[0]) != ''
&& trim($text_email_gateway_check[1]) != ''
&& trim($text_email_gateway_check[1]) != 'skip_network_name'
&& $ct['gen']->valid_email( $ct['gen']->text_email($ct['conf']['comms']['to_mobile_text']) ) == 'valid'
) {
$ct['activated_sms_services'][] = 'email_gateway';
}


if ( sizeof($ct['activated_sms_services']) == 1 ) {
$ct['sms_service'] = $ct['activated_sms_services'][0];
}
elseif ( sizeof($ct['activated_sms_services']) > 1 ) {
$ct['gen']->log( 'conf_error', 'only one SMS service is allowed, please deactivate ALL BUT ONE of the following: ' . implode(", ", $ct['activated_sms_services']) );
}


// Backup archive password protection / encryption
if ( $ct['conf']['sec']['backup_archive_password'] != '' ) {
$backup_archive_password = $ct['conf']['sec']['backup_archive_password'];
}
else {
$backup_archive_password = false;
}

// htaccess login...SET BEFORE ui-preflight-security-checks.php
$interface_login_array = explode("||", $ct['conf']['sec']['interface_login']);
$htaccess_username = $interface_login_array[0];
$htaccess_password = $interface_login_array[1];


// User agent (MUST BE SET VERY EARLY [AFTER primary-init / CONFIG-AUTO-ADJUST], 
// FOR ANY CURL-BASED API CALLS WHERE USER AGENT IS REQUIRED BY THE API SERVER)


if ( trim($ct['conf']['power']['override_curl_user_agent']) != '' ) {
$ct['curl_user_agent'] = $ct['conf']['power']['override_curl_user_agent'];  // Custom user agent
}
elseif ( $ct['activate_proxies'] == 'on' && is_array($ct['conf']['proxy']['proxy_list']) && sizeof($ct['conf']['proxy']['proxy_list']) > 0 ) {
$ct['curl_user_agent'] = 'Curl/' .$curl_setup["version"]. ' ('.PHP_OS.'; compatible;)';  // If proxies in use, preserve some privacy
}
else {
$ct['curl_user_agent'] = $ct['strict_curl_user_agent']; // SET IN primary-init.php (NEEDED MUCH EARLIER THAN HERE [FOR ADMIN INPUT VALIDATION])
}


// Configged google font
if ( isset($ct['conf']['gen']['google_font']) && trim($ct['conf']['gen']['google_font']) != '' ) {
          
$google_font_name = trim($ct['conf']['gen']['google_font']);
     
$font_name_url_formatting = $google_font_name;
$font_name_url_formatting = preg_replace("/  /", " ", $font_name_url_formatting);
$font_name_url_formatting = preg_replace("/  /", " ", $font_name_url_formatting);
$font_name_url_formatting = preg_replace("/ /", "+", $font_name_url_formatting);

}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!
 
 ?>