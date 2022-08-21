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


// Cookie defaults (only used if cookies are set)
$url_parts = pathinfo($_SERVER['REQUEST_URI']);

if ( substr($url_parts['dirname'], -1) != '/' ) {
$rel_http_path = $url_parts['dirname'] . '/';
}
else {
$rel_http_path = $url_parts['dirname'];
}

if ( PHP_VERSION_ID >= 70300 ) {
	
	session_set_cookie_params([
    'path' => $rel_http_path,
    'secure' => true,
    'samesite' => 'Strict'
	]);

}
else {
	
	session_set_cookie_params([
    'path' => $rel_http_path . ';SameSite=Strict',
    'secure' => true,
    'samesite' => 'Strict'
	]);

}


//////////////////////////////////////////////////////////////////
// END SYSTEM CONFIG
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>