<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


error_reporting(0); // Turn off all PHP error reporting on production servers (0), or enable (1)

//apc_clear_cache(); apcu_clear_cache(); opcache_reset();  // DEBUGGING ONLY

$app_version = '4.00.0 BETA 4';  // 2019/DECEMBER/15TH


require_once("app-lib/php/loader.php");


date_default_timezone_set('UTC'); // Set time as UTC for logs etc ($local_time_offset in config.php can adjust UI / UX timestamps as needed)
ini_set('auto_detect_line_endings',TRUE); // Mac compatibility with CSV spreadsheet importing



hardy_session_clearing(); // Try to avoid edge-case bug where sessions didn't delete last runtime
session_start(); // New session start


// Start measuring script runtime (AFTER loading functions with app-lib/php/loader.php, AND starting a new session)
script_runtime('start');

$_SESSION['proxy_checkup'] = array();



// Check for runtime mode
if ( !$runtime_mode )  {
echo 'No runtime mode set, exiting.';
exit;
}



// Register the base directory
$base_dir = preg_replace("/\/app-lib(.*)/i", "", dirname(__FILE__) );



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
define('CURL_VERSION_ID', str_replace(".", "", $curl_setup["version"]) );
}



// HTTP SERVER user and system user detection variables, for cache compatibility auto-configuration
$http_runtime_user = ( $runtime_mode == 'ui' ? posix_getpwuid(posix_geteuid())['name'] : trim( file_get_contents('cache/vars/http_runtime_user.dat') ) );

$http_users = array(
						'www-data',
						'apache',
						'apache2',
						'httpd',
						'httpd2'
							);



// TLD-only for each API service that requires multiple calls (for each market)
// Used to throttle these market calls a bit, so we don't get blacklisted
$limited_apis = array(
						'bitforex.com',
						'bitstamp.net',
						'btcmarkets.net',
						'coinbase.com',
						'cryptofresh.com',
						'gemini.com',
						'okcoin.com'
							);
							


// We can create cache directories (if needed), with $http_runtime_user determined (for cache compatibility on certain PHP setups)

// Check for cache sub-directory creation, create if needed...if it fails, alert end-user
if ( dir_structure($base_dir . '/cache/alerts/') != TRUE
|| dir_structure($base_dir . '/cache/apis/') != TRUE
|| dir_structure($base_dir . '/cache/events/') != TRUE
|| dir_structure($base_dir . '/cache/logs/') != TRUE
|| dir_structure($base_dir . '/cache/charts/') != TRUE
|| dir_structure($base_dir . '/cache/vars/') != TRUE
|| dir_structure($base_dir . '/cache/queue/messages/') != TRUE ) {
echo "Cannot create cache sub-directories. Please make sure the folder '/cache/' has FULL read / write permissions (chmod 777 on unix / linux systems), so the cache sub-directories can be created automatically.";
exit;
}



// Recreate .htaccess to restrict web snooping of cache contents, if the cache directory was deleted / recreated
if ( !file_exists($base_dir . '/cache/.htaccess') ) {
store_file_contents($base_dir . '/cache/.htaccess', 'deny from all'); 
}



// Only need below logic during UI runtime
if ( $runtime_mode == 'ui' ) {
	

	// Have UI / HTTP runtime mode cache the runtime_user data every 24 hours, since CLI runtime cannot determine the UI / HTTP runtime_user 
	if ( update_cache_file('cache/vars/http_runtime_user.dat', (60 * 24) ) == true ) {
	store_file_contents($base_dir . '/cache/vars/http_runtime_user.dat', $http_runtime_user);
	}

	// Have UI runtime mode cache the app URL data every 24 hours, since CLI runtime cannot determine the app URL (for sending backup link emails during backups, etc)
	if ( update_cache_file('cache/vars/app_url.dat', (60 * 24) ) == true ) {
	$base_url = base_url();
	store_file_contents($base_dir . '/cache/vars/app_url.dat', $base_url);
	}
	

	if ( $_COOKIE['theme_selected'] != NULL ) {
	$theme_selected = $_COOKIE['theme_selected'];
	}
	elseif ( $_POST['theme_selected'] != NULL ) {
	$theme_selected = $_POST['theme_selected'];
	}
	else {
	$theme_selected = 'light';
	}
	// Sanitizing $theme_selected is very important, as we are calling external files with the value
	if ( $theme_selected != 'light' && $theme_selected != 'dark' ) {
	app_logging('security_error', 'Injected theme path value attack', 'Requested theme value: "' . $theme_selected . '";');
	error_logs();
	exit;
	}


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


$alert_percent = explode("|", ( $_POST['use_alert_percent'] != '' ? $_POST['use_alert_percent'] : $_COOKIE['alert_percent'] ) );


$show_charts = explode(',', rtrim( ( $_POST['show_charts'] != '' ? $_POST['show_charts'] : $_COOKIE['show_charts'] ) , ',') );

	
}



// Base URL, that even works during CLI runtime (horray)
$base_url = ( $base_url != '' ? $base_url : trim( file_get_contents('cache/vars/app_url.dat') ) );



?>