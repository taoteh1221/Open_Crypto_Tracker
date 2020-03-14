<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


/////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////// S Y S T E M   I N I T   S E T T I N G S ///////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////


// If debugging is enabled, turn on all PHP error reporting (BEFORE ANYTHING ELSE RUNS)
if ( $app_config['debug_mode'] != 'off' ) {
error_reporting(1); 
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


// Mac compatibility with CSV spreadsheet importing / exporting
if (  preg_match("/darwin/i", php_uname()) || preg_match("/webkit/i", $_SERVER['HTTP_USER_AGENT']) ) {
ini_set('auto_detect_line_endings', true); 
}


// Set time as UTC for logs etc ($app_config['local_time_offset'] in config.php can adjust UI / UX timestamps as needed)
date_default_timezone_set('UTC'); 


///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////// APP   I N I T   S E T T I N G S /////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////


// Application version
$app_version = '4.08.10';  // 2020/MARCH/14TH


// Load app functions
require_once('app-lib/php/functions-loader.php');


// Session start
hardy_session_clearing(); // Try to avoid edge-case bug where sessions didn't delete last runtime
session_start(); // New session start


//////////////////////////////////////////////////////////////
// Set global runtime app arrays / vars...
//////////////////////////////////////////////////////////////


// Initial arrays
$logs_array = array();

$proxy_checkup = array();

$proxies_checked = array();

$btc_worth_array = array();

$coin_stats_array = array();

$api_runtime_cache = array();

$limited_api_calls = array();

$processed_messages = array();

$btc_pairing_markets = array();

$btc_pairing_markets_blacklist = array();

// Coinmarketcap supported currencies array
require_once('app-lib/php/other/coinmarketcap-currencies.php');

// SET original app_config array BEFORE dynamic app config management
$original_app_config = $app_config; 

$interface_login_array = explode("||", $app_config['interface_login']);
					
// HTTP SERVER setup detection variables (for cache compatibility auto-configuration)
// MUST BE SET BEFORE CACHE STRUCTURE CREATION, TO RUN IN COMPATIBILITY MODE FOR THIS PARTICULAR SERVER'S SETUP
$possible_http_users = array(
						'www-data',
						'apache',
						'apache2',
						'httpd',
						'httpd2',
							);


// Initial vars
$cmc_notes = null;

$td_color_zebra = null;

$cap_data_force_usd = null;

$selected_btc_primary_exchange = null;

$selected_btc_primary_currency_pairing = null;

// htaccess login...SET BEFORE system checks
$htaccess_username = $interface_login_array[0];
$htaccess_password = $interface_login_array[1];

// Register the base directory of this app (MUST BE SET BEFORE !ANY! Init logic)
$base_dir = preg_replace("/\/app-lib(.*)/i", "", dirname(__FILE__) );

// Get system info for debugging / stats
$system_info = system_info(); // MUST RUN AFTER SETTING $base_dir

// Current runtime user
$current_runtime_user = posix_getpwuid(posix_geteuid())['name'];

// Get WEBSERVER runtime user (from cache if currently running from CLI)
// MUST BE SET BEFORE CACHE STRUCTURE CREATION, TO RUN IN COMPATIBILITY MODE (IF NEEDED) FOR THIS PARTICULAR SERVER'S SETUP
$http_runtime_user = ( $runtime_mode == 'ui' ? posix_getpwuid(posix_geteuid())['name'] : trim( file_get_contents('cache/vars/http_runtime_user.dat') ) );

// If upgrade check enabled / cached var set, set the runtime var for any configured alerts
$upgrade_check_latest_version = trim( file_get_contents('cache/vars/upgrade_check_latest_version.dat') );

// Raspberry Pi device? (run after system info var)
if ( preg_match("/raspberry/i", $system_info['model']) ) {
$is_raspi = 1;
}

// User agent (MUST BE SET EARLY [BUT AFTER SYSTEM INFO VAR], FOR ANY API CALLS WHERE USER AGENT IS REQUIRED BY THE API SERVER)
if ( trim($app_config['override_default_user_agent']) != '' ) {
$user_agent = $app_config['override_default_user_agent'];  // Custom user agent
}
elseif ( sizeof($app_config['proxy_list']) > 0 ) {
$user_agent = 'Curl/' .$curl_setup["version"]. ' ('.PHP_OS.'; compatible;)';  // If proxies in use, preserve some privacy
}
else {
$user_agent = 'Curl/' .$curl_setup["version"]. ' ('.PHP_OS.'; ' . $_SERVER['SERVER_SOFTWARE'] . '; PHP/' .phpversion(). '; DFD_Cryptocoin_Values/' . $app_version . '; +https://github.com/taoteh1221/DFD_Cryptocoin_Values)';
}

//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////



// Create cache directories (if needed), with $http_runtime_user determined further above 
// (for cache compatibility on certain PHP setups)

// Check for cache directory path creation, create if needed...if it fails, flag a force exit and alert end-user
if ( dir_structure('cache/alerts/') != true
|| dir_structure('cache/charts/spot_price_24hr_volume/archival/') != true
|| dir_structure('cache/charts/spot_price_24hr_volume/lite/') != true
|| dir_structure('cache/charts/system/archival/') != true
|| dir_structure('cache/charts/system/lite/') != true
|| dir_structure('cache/events/') != true
|| dir_structure('cache/logs/debugging/api/') != true
|| dir_structure('cache/logs/errors/api/') != true
|| dir_structure('cache/secured/apis/') != true
|| dir_structure('cache/secured/backups/') != true
|| dir_structure('cache/secured/messages/') != true
|| dir_structure('cache/vars/') != true
|| dir_structure('cron-plugins/') != true ) {
$system_error = 'Cannot create cache or cron-plugin sub-directories. Please make sure the primary sub-directories "/cache/" and "/cron-plugins/" are created, and have FULL read / write permissions (chmod 777 on unix / linux systems), so the required files and secondary sub-directories can be created automatically. <br /><br />';
app_logging('system_error', $system_error);
echo $system_error;
$force_exit = 1;
}



// UI-CACHED VARS THAT !MUST! BE AVAILABLE BEFORE SYSTEM CHECKS, BUT MUST RUN AFTER DIRECTORY CREATION
if ( $runtime_mode == 'ui' ) {
	
	// Have UI / HTTP runtime mode cache the runtime_user data every 24 hours, since CLI runtime cannot determine the UI / HTTP runtime_user 
	if ( update_cache_file('cache/vars/http_runtime_user.dat', (60 * 24) ) == true ) {
	store_file_contents('cache/vars/http_runtime_user.dat', $http_runtime_user); // ALREADY SET FURTHER UP IN INIT.PHP
	}


	// Have UI runtime mode cache the app URL data every 24 hours, since CLI runtime cannot determine the app URL (for sending backup link emails during backups, etc)
	if ( update_cache_file('cache/vars/base_url.dat', (60 * 24) ) == true ) {
	$base_url = base_url();
	store_file_contents('cache/vars/base_url.dat', $base_url);
	}

}
else {
$base_url = trim( file_get_contents('cache/vars/base_url.dat') );
}



// Directory security check (MUST run AFTER directory structure creation check, AND BEFORE system checks)
require_once('app-lib/php/other/security/directory.php');

// Basic system checks (before allowing app to run ANY FURTHER, MUST RUN AFTER directory creation check / http server user vars / user agent var)
require_once('app-lib/php/other/debugging/system-checks.php');

// SECURED cache files management (MUST RUN AFTER system checks)
require_once('app-lib/php/other/security/secure-cache-files.php');

// Dynamic app config management (MUST RUN AFTER secure cache files)
require_once('app-lib/php/other/app-config-management.php');

// Primary Bitcoin markets (MUST RUN AFTER app config management)
require_once('app-lib/php/other/primary-bitcoin-markets.php');

// Misc dynamic interface vars (MUST RUN AFTER app config management)
require_once('app-lib/php/other/interface-init.php');

// App configuration checks (MUST RUN AFTER app config management / primary bitcoin markets / interface init)
require_once('app-lib/php/other/debugging/config-checks.php');

// Password protection management (MUST RUN AFTER system checks / secure cache files / app config management)
require_once('app-lib/php/other/security/password-protection.php');

// Chart sub-directory creation (if needed...MUST RUN AFTER app config management)
require_once('app-lib/php/other/chart-directories.php');

// Load any activated classes (MUST RUN AFTER app config management)
require_once('app-lib/php/classes-loader.php');

// Scheduled maintenance  (MUST RUN AFTER EVERYTHING IN INIT.PHP)
require_once('app-lib/php/other/scheduled-maintenance.php');

// Chart update frequency (SET AFTER SCHEDULED MAINTENANCE)
$charts_update_freq = ( $charts_update_freq != '' ? $charts_update_freq : trim( file_get_contents('cache/vars/chart_interval.dat') ) );


// Unit tests to run in debug mode (MUST RUN AFTER EVERYTHING IN INIT.PHP)
if ( $app_config['debug_mode'] != 'off' ) {
require_once('app-lib/php/other/debugging/tests.php');
require_once('app-lib/php/other/debugging/exchange-and-pairing-info.php');
}



?>