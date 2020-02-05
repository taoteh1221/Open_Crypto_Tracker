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
$app_version = '4.07.7';  // 2020/FEBRUARY/3RD


// Load app functions
require_once('app-lib/php/loader.php');


// Session start
hardy_session_clearing(); // Try to avoid edge-case bug where sessions didn't delete last runtime
session_start(); // New session start


// Set global runtime app vars...


// Initial null arrays
$logs_array = array();

$proxy_checkup = array();

$proxies_checked = array();

$btc_worth_array = array();

$coin_stats_array = array();

$api_runtime_cache = array();

$limited_api_calls = array();

$processed_messages = array();

$btc_pairing_markets = array();


// Initial null vars
$cmc_notes = null;

$td_color_zebra = null;

$cap_data_force_usd = null;

$selected_btc_primary_exchange = null;

$selected_btc_primary_currency_pairing = null;


// Register the base directory of this app (MUST BE SET BEFORE !ANY! Init logic)
$base_dir = preg_replace("/\/app-lib(.*)/i", "", dirname(__FILE__) );


// Base URL FOR UI, that even works during CLI runtime (horray)
// !MUST BE AVAILABLE FOR OTHER RUNTIMES! (CRON ETC), SO INCLUDE HERE
$base_url = trim( file_get_contents('cache/vars/app_url.dat') );


// If upgrade check enabled, set the var for any configured alerts
$upgrade_check_latest_version = trim( file_get_contents('cache/vars/upgrade_check_latest_version.dat') );


// htaccess login...SET BEFORE system checks
$htaccess_login_array = explode("||", $app_config['htaccess_login']);
$htaccess_username = $htaccess_login_array[0];
$htaccess_password = $htaccess_login_array[1];


// SET original app_config BEFORE dynamic app config management
$original_app_config = $app_config; 


// Get system info for debugging / stats
$system_info = system_info(); // MUST RUN AFTER SETTING $base_dir


// Raspberry Pi device?
if ( preg_match("/raspberry/i", $system_info['model']) ) {
$is_raspi = 1;
}


// Current runtime user
$current_runtime_user = posix_getpwuid(posix_geteuid())['name'];


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



// Basic system checks (before allowing app to run ANY FURTHER, MUST RUN AFTER http server user vars / user agent var)
require_once('app-lib/php/other/debugging/system-checks.php');

// SECURED cache files management (MUST RUN AFTER system checks)
require_once('app-lib/php/other/security/secure-cache-files.php');

// Dynamic app config management (MUST RUN AFTER secure cache files)
require_once('app-lib/php/other/app-config-management.php');

// Password protection management 
// (MUST RUN AFTER system checks / secure cache files / dynamic app config management)
require_once('app-lib/php/other/security/password-protection.php');

// Chart sub-directory creation (if needed...MUST RUN AFTER dynamic app config management)
require_once('app-lib/php/other/chart-directories.php');



// SMTP email setup (if needed...MUST RUN AFTER dynamic app config management)
// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
if ( $app_config['smtp_email_login'] != '' && $app_config['smtp_email_server'] != '' ) {

require_once('app-lib/php/classes/smtp-mailer/SMTPMailer.php');

// Passing smtp server login vars to config file structure used by the 3rd party SMTP class, to maintain ease with any future upgrade compatibility
// Must be loaded as a global var before class instance is created
$smtp_vars = smtp_vars();
global $smtp_vars; // Needed for class compatibility (along with second instance in the class config_smtp.php file)

// Initiation of the 3rd party SMTP class
$smtp = new SMTPMailer();

}



// Run AFTER above...


// Interface vars
require_once('app-lib/php/other/interface-init.php');

// Scheduled maintenance 
require_once('app-lib/php/other/scheduled-maintenance.php');

// Chart update frequency (SET AFTER SCHEDULED MAINTENANCE)
$charts_update_freq = ( $charts_update_freq != '' ? $charts_update_freq : trim( file_get_contents('cache/vars/chart_interval.dat') ) );

// Primary Bitcoin markets
require_once('app-lib/php/other/primary-bitcoin-markets.php');

// Coinmarketcap supported currencies
require_once('app-lib/php/other/coinmarketcap-currencies.php');

// App configuration checks (!RUN AFTER! loading ALL primary init.php logic)
require_once('app-lib/php/other/debugging/config-checks.php');



// Unit tests to run in debug mode (!RUN AFTER! loading ALL primary init.php logic)
if ( $app_config['debug_mode'] != 'off' ) {
require_once('app-lib/php/other/debugging/tests.php');
require_once('app-lib/php/other/debugging/exchange-and-pairing-info.php');
}



?>