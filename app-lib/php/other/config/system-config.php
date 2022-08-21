<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// SYSTEM CONFIG
//////////////////////////////////////////////////////////////////


// Set time as UTC for logs etc ('loc_time_offset' in Admin Config GENERAL section can adjust UI / UX timestamps as needed)
date_default_timezone_set('UTC'); 


// Mac compatibility with CSV spreadsheet importing / exporting
if (  preg_match("/darwin/i", php_uname()) || preg_match("/webkit/i", $_SERVER['HTTP_USER_AGENT']) ) {
ini_set('auto_detect_line_endings', true); 
}


// Make sure we have a PHP version id set EARLY
if (!defined('PHP_VERSION_ID')) {
$version = explode('.', PHP_VERSION);
define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
}


// Set curl version var EARLY (for user agent, etc)
if ( function_exists('curl_version') ) {
$curl_setup = curl_version();
define('CURL_VERSION_ID', str_replace(".", "", $curl_setup["version"]) );
}


// Apache modules that are activated (avoids calling this function more than once / further down in system checks)
if ( function_exists('apache_get_modules') ) {
$apache_modules = apache_get_modules(); 
}

// Register the base directory of this app (MUST BE SET BEFORE !ANY! init logic calls)
$file_loc = str_replace('\\', '/', dirname(__FILE__) ); // Windows compatibility (convert backslashes)
$base_dir = preg_replace("/\/app-lib(.*)/i", "", $file_loc);
////
//!!!!!!!!!! IMPORTANT, ALWAYS LEAVE THIS HERE !!!!!!!!!!!!!!!
// FOR #UI LOGIN / LOGOUT SECURITY#, WE NEED THIS SET #VERY EARLY# IN INIT FOR APP ID / ETC,
// EVEN THOUGH WE RUN LOGIC AGAIN FURTHER DOWN IN INIT TO SET THIS UNDER
// ALL CONDITIONS (EVEN CRON RUNTIMES), AND REFRESH VAR CACHE FOR CRON LOGIC
if ( $runtime_mode != 'cron' ) {
$base_url = $ct_gen->base_url();
}
else {
$base_url = trim( file_get_contents('cache/vars/base_url.dat') );
}


// Our FINAL $base_url logic has run, so set app host var
if ( isset($base_url) ) {
    
$parse_temp = parse_url($base_url);

    if ( isset($parse_temp['port']) ) {
    $app_port = ':' . $parse_temp['port'];
    }

$app_host = $parse_temp['host'];
$app_host_address = $parse_temp['scheme'] . "://" . $app_host . $app_port;
$app_path = $parse_temp['path'];

}


// PHP session cookie defaults

$php_sess_time = time() + 31536000;
$php_sess_secure = ( $app_edition == 'server' ? true : false );

if ( PHP_VERSION_ID >= 70300 ) {
	
	session_set_cookie_params([
                                'lifetime' => $php_sess_time,
                                'path' => $app_path,
                                'domain' => $app_host,
                                'secure' => $php_sess_secure,
                                'httponly' => false,
                                'samesite' => 'Strict',
                    	       ]);

}
else {
	
	session_set_cookie_params([
                                $php_sess_time,
                                $app_path . '; samesite=Strict',
                                $app_host,
                                $php_sess_secure, // secure
                                false, //httponly
                              ]);

}


//////////////////////////////////////////////////////////////////
// END SYSTEM CONFIG
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>