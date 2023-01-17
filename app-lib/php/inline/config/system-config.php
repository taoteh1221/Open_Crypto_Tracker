<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
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


// Remote IP
$remote_ip = ( isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'localhost' );

// Register the base directory of this app (MUST BE SET BEFORE !ANY! init logic calls)
$base_dir = str_replace('\\', '/', dirname(__FILE__) ); // Windows compatibility (convert backslashes)
// WITH dirname(__FILE__), WE ONLY NEED TO COVER THE app-lib/php PATH (AS WE ALWAYS GET THE PATH TO THIS CURRENT FILE)
$base_dir = preg_replace("/\/app-lib\/php(.*)/i", "", $base_dir);
////
//!!!!!!!!!! IMPORTANT, ALWAYS LEAVE THIS HERE !!!!!!!!!!!!!!!
if ( file_exists('cache/vars/base_url.dat') ) {
$base_url = trim( file_get_contents('cache/vars/base_url.dat') );
}
elseif ( $runtime_mode == 'ui' ) {
// Skip security check with base_url(false) flag, until later in runtime when the full app config is processed
// (WE CAN'T CHECK FOR HEADER HOSTNAME SPOOFING ATTACKS UNTIL AFTER config-auto-adjust.php [in ui-preflight-security-checks.php])
// (ONLY DURING 'ui' RUNTIMES, TO ASSURE IT'S NEVER FROM A REWRITE [PRETTY LINK] URL LIKE /api OR /hook)
$base_url = $ct_gen->base_url(false); 
}


// Our FINAL $base_url logic has run, so set app host var
if ( isset($base_url) && trim($base_url) != '' ) {
    
$parse_temp = parse_url($base_url);

    if ( isset($parse_temp['port']) ) {
    $app_port = ':' . $parse_temp['port'];
    }

$app_host = $parse_temp['host'];
$app_host_address = $parse_temp['scheme'] . "://" . $app_host . $app_port;
$app_path = $parse_temp['path'];

}


//////////////////////////////////////////////////////////////////
// END SYSTEM CONFIG
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>