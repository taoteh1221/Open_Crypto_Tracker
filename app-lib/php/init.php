<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


$app_version = '4.07.3';  // 2020/JANUARY/26TH


// Make sure we have a PHP version id
if (!defined('PHP_VERSION_ID')) {
$version = explode('.', PHP_VERSION);
define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
}


///////////////////// I N I T /////////////////////////////////////////////////


// Load app functions
require_once('app-lib/php/loader.php');


// Basic system checks (before allowing app to run)
require_once('app-lib/php/other/system-checks.php');


// If debugging is enabled, turn on all PHP error reporting
if ( $app_config['debug_mode'] != 'off' ) {
error_reporting(1); 
}


// App defaults

// Set time as UTC for logs etc ($app_config['local_time_offset'] in config.php can adjust UI / UX timestamps as needed)
date_default_timezone_set('UTC'); 

// Mac compatibility with CSV spreadsheet importing
if (  preg_match("/darwin/i", php_uname())  ) {
ini_set('auto_detect_line_endings', true); 
}

// Session start
hardy_session_clearing(); // Try to avoid edge-case bug where sessions didn't delete last runtime
session_start(); // New session start

// Register the base directory of this app
$base_dir = preg_replace("/\/app-lib(.*)/i", "", dirname(__FILE__) );



//////////////////////////////////////////////////////////////////
// Set global runtime app vars now...
//////////////////////////////////////////////////////////////////


// Arrays
$logs_array = array();

$proxy_checkup = array();

$proxies_checked = array();

$btc_worth_array = array();

$coin_stats_array = array();

$api_runtime_cache = array();

$limited_api_calls = array();

$processed_messages = array();

$btc_pairing_markets = array();

// Vars
$cmc_notes = null;

$td_color_zebra = null;

$cap_data_force_usd = null;

$selected_btc_primary_exchange = null;

$selected_btc_primary_currency_pairing = null;

// SET BEFORE dynamic app config management
$original_app_config = $app_config; 

// Get system info for debugging / stats
$system_info = system_info(); // MUST RUN AFTER SETTING $base_dir

// Raspberry Pi device?
if ( preg_match("/raspberry/i", $system_info['model']) ) {
$is_raspi = 1;
}


// Get WEBSERVER runtime user (from cache if currently running from CLI)
// MUST BE SET BEFORE CACHE STRUCTURE CREATION, TO RUN IN COMPATIBILITY MODE (IF NEEDED) FOR THIS PARTICULAR SERVER'S SETUP
$http_runtime_user = ( $runtime_mode == 'ui' ? posix_getpwuid(posix_geteuid())['name'] : trim( file_get_contents('cache/vars/http_runtime_user.dat') ) );
$http_runtime_user = trim($http_runtime_user);
					
// HTTP SERVER setup detection variables (for cache compatibility auto-configuration)
// MUST BE SET BEFORE CACHE STRUCTURE CREATION, TO RUN IN COMPATIBILITY MODE FOR THIS PARTICULAR SERVER'S SETUP
$possible_http_users = array(
						'www-data',
						'apache',
						'apache2',
						'httpd',
						'httpd2',
							);


// Create cache directories (if needed), with $http_runtime_user determined further above 
// (for cache compatibility on certain PHP setups)

// Check for cache directory path creation, create if needed...if it fails, exit and alert end-user
if ( dir_structure('cache/alerts/') != TRUE
|| dir_structure('cache/apis/') != TRUE
|| dir_structure('cache/charts/spot_price_24hr_volume/archival/') != TRUE
|| dir_structure('cache/charts/spot_price_24hr_volume/lite/') != TRUE
|| dir_structure('cache/charts/system/archival/') != TRUE
|| dir_structure('cache/charts/system/lite/') != TRUE
|| dir_structure('cache/events/') != TRUE
|| dir_structure('cache/logs/debugging/api/') != TRUE
|| dir_structure('cache/logs/errors/api/') != TRUE
|| dir_structure('cache/secured/backups/') != TRUE
|| dir_structure('cache/secured/messages/') != TRUE
|| dir_structure('cache/vars/') != TRUE ) {
echo "Cannot create cache sub-directories. Please make sure the folder '/cache/' has FULL read / write permissions (chmod 777 on unix / linux systems), so the cache sub-directories can be created automatically.";
exit;
}


// Security (MUST run AFTER directory structure creation check)
require_once('app-lib/php/other/security/directory.php');


// SECURED cache files management (MUST RUN AFTER directory structure creation check)
require_once('app-lib/php/other/security/secure-cache-files.php');


// Dynamic app config management (MUST RUN AFTER secure cache files)
require_once('app-lib/php/other/app-config-management.php');


// Chart sub-directory creation (if needed...MUST RUN AFTER dynamic app config management)
require_once('app-lib/php/other/chart-directories.php');


// Password protection management 
// (MUST RUN AFTER directory structure creation check / secure cache files / dynamic app config management)
require_once('app-lib/php/other/security/password-protection.php');


// Interface vars
require_once('app-lib/php/other/interface-init.php');


// Scheduled maintenance 
require_once('app-lib/php/other/scheduled-maintenance.php');


// Primary Bitcoin markets
require_once('app-lib/php/other/primary-bitcoin-markets.php');


// Coinmarketcap supported currencies
require_once('app-lib/php/other/coinmarketcap-currencies.php');


// App configuration checks, !AFTER! loading primary init logic
require_once('app-lib/php/other/config-checks.php');



// Chart update frequency (SET AFTER SCHEDULED MAINTENANCE)
$charts_update_freq = ( $charts_update_freq != '' ? $charts_update_freq : trim( file_get_contents('cache/vars/chart_interval.dat') ) );



// SMTP email setup
// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
if ( $app_config['smtp_login'] != '' && $app_config['smtp_server'] != '' ) {

require_once('app-lib/php/classes/smtp-mailer/SMTPMailer.php');

// Passing smtp server login vars to config file structure used by the 3rd party SMTP class, to maintain ease with any future upgrade compatibility
// Must be loaded as a global var before class instance is created
$smtp_vars = smtp_vars();
global $smtp_vars; // Needed for class compatibility (along with second instance in the class config_smtp.php file)

// Initiation of the 3rd party SMTP class
$smtp = new SMTPMailer();

}



// User agent
if ( sizeof($app_config['proxy_list']) > 0 ) {
$user_agent = 'Mozilla/5.0 (compatible; API_Endpoint_Parser;) Gecko Firefox';  // If proxies in use, preserve some privacy
}
else {
$user_agent = 'Mozilla/5.0 ('.( isset($system_info['operating_system']) ? $system_info['operating_system'] : 'compatible' ).'; ' . $_SERVER['SERVER_SOFTWARE'] . '; PHP/' .phpversion(). '; Curl/' .$curl_setup["version"]. '; DFD_Cryptocoin_Values/' . $app_version . '; API_Endpoint_Parser; +https://github.com/taoteh1221/DFD_Cryptocoin_Values) Gecko Firefox';
}



// Unit tests to run in debug mode, !AFTER! loading ALL init logic
if ( $app_config['debug_mode'] != 'off' ) {
require_once('app-lib/php/other/debugging/tests.php');
require_once('app-lib/php/other/debugging/exchange-and-pairing-info.php');
}


?>