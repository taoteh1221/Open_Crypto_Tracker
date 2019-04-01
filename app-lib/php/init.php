<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */

//apc_clear_cache(); apcu_clear_cache(); opcache_reset();  // DEBUGGING ONLY

$app_version = '2.4.3';  // 2019/MARCH/31ST
 
date_default_timezone_set('UTC');

session_start();

$_SESSION['proxy_checkup'] = array();


// Check for runtime mode
if ( !$runtime_mode )  {
echo 'No runtime mode set, exiting.';
exit;
}


// Register the base directory
$base_dir = preg_replace("/\/app-lib(.*)/i", "", dirname(__FILE__) );

// Register the PHP apps directory
$php_app_dir = dirname(__FILE__);



// Check for cache sub-directory creation, create if needed...if it fails, alert end-user
if ( dir_structure($base_dir . '/cache/alerts/') != TRUE
|| dir_structure($base_dir . '/cache/apis/') != TRUE
|| dir_structure($base_dir . '/cache/events/') != TRUE
|| dir_structure($base_dir . '/cache/logs/') != TRUE ) {
echo "Cannot create cache sub-directories. Please either manually create the sub-directories 'alerts', 'apis', 'events', and 'logs' with read / write permissions inside the folder 'cache', OR make sure the folder 'cache' itself has read / write permissions (and these sub-directories should be created automatically).";
exit;
}


// Make sure we have a PHP version id
if (!defined('PHP_VERSION_ID')) {
    $version = explode('.', PHP_VERSION);

    define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
}


// Check for curl
if ( !function_exists('curl_version') ) {
echo "Curl for PHP (version ID ".PHP_VERSION_ID.") is not installed yet. Curl is required to run this application.";
exit;
}
else {
$curl_setup = curl_version();
}


// Only need below logic during UI runtime
if ( $runtime_mode == 'ui' ) {

$sort_settings = ( $_COOKIE['sort_by'] ? $_COOKIE['sort_by'] : $_POST['sort_by'] );
$sort_settings = explode("|",$sort_settings);

$sorted_by_col = $sort_settings[0];
$sorted_by_asc_desc = $sort_settings[1];

	if ( !$sorted_by_col ) {
	$sorted_by_col = 0;
	}
	if ( !$sorted_by_asc_desc ) {
	$sorted_by_asc_desc = 0;
	}

$alert_percent = explode("|", $_COOKIE['alert_percent']);

require_once( $php_app_dir . "/other/cookies.php");

}


?>