<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


/////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////// S Y S T E M   I N I T   S E T T I N G S ///////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////


// If debugging is enabled, turn on all PHP error reporting (BEFORE ANYTHING ELSE RUNS)
if ( $app_config['developer']['debug_mode'] != 'off' ) {
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


// Set time as UTC for logs etc ($app_config['general']['local_time_offset'] in config.php can adjust UI / UX timestamps as needed)
date_default_timezone_set('UTC'); 


///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////// APP   I N I T   S E T T I N G S /////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////


// Application version
$app_version = '4.12.0';  // 2020/MAY/20TH


// Load app functions
require_once('app-lib/php/functions-loader.php');


// Session start
session_start(); // New session start


// Register the base directory of this app (MUST BE SET BEFORE !ANY! Init logic)
$base_dir = preg_replace("/\/app-lib(.*)/i", "", dirname(__FILE__) );


//////////////////////////////////////////////////////////////
// Set global runtime app arrays / vars...
//////////////////////////////////////////////////////////////


// Session data


// Nonce for secured login session logic
if ( !isset($_SESSION['nonce']) ) {
$_SESSION['nonce'] = random_hash(32);
}


// If user is logging out (run immediately after setting session vars, for quick runtime)
if ( $_GET['logout'] == 1 && admin_hashed_nonce('logout') != false && $_GET['admin_hashed_nonce'] == admin_hashed_nonce('logout') ) {
hardy_session_clearing(); // Try to avoid edge-case bug where sessions don't delete, using our hardened function logic
header("Location: index.php");
exit;
}


// INCREASE CERTAIN RUNTIME SPEEDS
// If we are just running a captcha image, ONLY run captcha library for runtime speed (exit after)
if (  $runtime_mode == 'captcha' ) {
require_once('app-lib/php/other/security/captcha-lib.php');
exit;
}
// If we are just running log retrieval, ONLY run logs library for runtime speed (exit after)
elseif (  $runtime_mode == 'logs' ) {
require_once('app-lib/php/other/debugging/logs-lib.php');
exit;
}



// A bit of DOS attack mitigation for bogus / bot login attempts
// Speed up runtime SIGNIFICANTLY by checking EARLY for a bad / non-existent captcha code, and rendering the related form again...
// A BIT STATEMENT-INTENSIVE ON PURPOSE, AS IT KEEPS RUNTIME SPEED MUCH HIGHER
if ( $_POST['admin_submit_register'] || $_POST['admin_submit_login'] || $_POST['admin_submit_reset'] ) {


	if ( trim($_POST['captcha_code']) == '' || trim($_POST['captcha_code']) != '' && strtolower( trim($_POST['captcha_code']) ) != strtolower($_SESSION['captcha_code']) ) {
	
	
		if ( $_POST['admin_submit_register'] ) {
		$theme_selected = ( $_COOKIE['theme_selected'] ? $_COOKIE['theme_selected'] : $app_config['general']['default_theme'] );
		require("templates/interface/php/admin/admin-login/register.php");
		exit;
		}
		elseif ( $_POST['admin_submit_login'] ) {
		$theme_selected = ( $_COOKIE['theme_selected'] ? $_COOKIE['theme_selected'] : $app_config['general']['default_theme'] );
		require("templates/interface/php/admin/admin-login/login.php");
		exit;
		}
		elseif ( $_POST['admin_submit_reset'] ) {
		$theme_selected = ( $_COOKIE['theme_selected'] ? $_COOKIE['theme_selected'] : $app_config['general']['default_theme'] );
		require("templates/interface/php/admin/admin-login/reset.php");
		exit;
		}
	
	
	}
	

}


// Initial arrays
$logs_array = array();

$proxy_checkup = array();

$proxies_checked = array();

$btc_worth_array = array();

$coin_stats_array = array();

$coingecko_api = array();

$coinmarketcap_api = array();

$api_runtime_cache = array();

$limited_api_calls = array();

$processed_messages = array();

$btc_pairing_markets = array();

$price_alerts_fixed_reset_array = array();

$btc_pairing_markets_blacklist = array();

// Coinmarketcap supported currencies array
require_once('app-lib/php/other/coinmarketcap-currencies.php');

// SET original app_config array BEFORE dynamic app config management
$default_app_config = $app_config; 

// Set as global, to update in / out of functions as needed
$upgraded_app_config = array();

$interface_login_array = explode("||", $app_config['general']['interface_login']);
					
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
$fetched_reddit_feeds = 0;

$fetched_stackexchange_feeds = 0;

$fetched_medium_feeds = 0;

$fetched_bitcoincore_feeds = 0;

$fetched_ethereumorg_feeds = 0;

$fetched_kraken_feeds = 0;

$fetched_firesidefm_feeds = 0;

$fetched_libsyn_feeds = 0;

$cmc_notes = null;

$config_upgraded = null;

$td_color_zebra = null;

$cap_data_force_usd = null;

$selected_btc_primary_exchange = null;

$selected_btc_primary_currency_pairing = null;

// htaccess login...SET BEFORE system checks
$htaccess_username = $interface_login_array[0];
$htaccess_password = $interface_login_array[1];

$remote_ip = ( isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'localhost' );

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

// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
if ( trim($app_config['comms']['telegram_your_username']) != '' && trim($app_config['comms']['telegram_bot_name']) != '' && trim($app_config['comms']['telegram_bot_username']) != '' && $app_config['comms']['telegram_bot_token'] != '' ) {
$telegram_activated = 1;
}

// User agent (MUST BE SET EARLY [BUT AFTER SYSTEM INFO VAR], FOR ANY API CALLS WHERE USER AGENT IS REQUIRED BY THE API SERVER)
if ( trim($app_config['developer']['override_user_agent']) != '' ) {
$user_agent = $app_config['developer']['override_user_agent'];  // Custom user agent
}
elseif ( sizeof($app_config['proxy']['proxy_list']) > 0 ) {
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
|| dir_structure('cache/events/throttling/') != true
|| dir_structure('cache/internal-api/') != true
|| dir_structure('cache/logs/debugging/external_api/') != true
|| dir_structure('cache/logs/errors/external_api/') != true
|| dir_structure('cache/secured/activation/') != true
|| dir_structure('cache/secured/backups/') != true
|| dir_structure('cache/secured/external_api/') != true
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
	else {
	$base_url = trim( file_get_contents('cache/vars/base_url.dat') );
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

// Dynamic app config management (MUST RUN AFTER secure cache files FOR CACHED / config.php app_config comparison)
require_once('app-lib/php/other/app-config-management.php');

// Load any activated classes (MUST RUN AS EARLY AS POSSIBLE #AFTER SECURE CACHE FILES / APP CONFIG MANAGEMENT#)
require_once('app-lib/php/classes-loader.php');

// Primary Bitcoin markets (MUST RUN AFTER app config management)
require_once('app-lib/php/other/primary-bitcoin-markets.php');

// Misc dynamic interface vars (MUST RUN AFTER app config management)
require_once('app-lib/php/other/interface-init.php');

// Misc cron logic (MUST RUN AFTER app config management)
require_once('app-lib/php/other/cron-init.php');

// App configuration checks (MUST RUN AFTER app config management / primary bitcoin markets / interface init)
require_once('app-lib/php/other/debugging/config-checks.php');

// Password protection management (MUST RUN AFTER system checks / secure cache files / app config management)
require_once('app-lib/php/other/security/password-protection.php');

// Chart sub-directory creation (if needed...MUST RUN AFTER app config management)
require_once('app-lib/php/other/chart-directories.php');

// Scheduled maintenance  (MUST RUN AFTER EVERYTHING IN INIT.PHP)
require_once('app-lib/php/other/scheduled-maintenance.php');


// Unit tests to run in debug mode (MUST RUN AFTER EVERYTHING IN INIT.PHP)
if ( $app_config['developer']['debug_mode'] != 'off' ) {
require_once('app-lib/php/other/debugging/tests.php');
require_once('app-lib/php/other/debugging/exchange-and-pairing-info.php');
}



// DEBUGGING NEW LITE CHART LOGIC
//update_lite_chart('cache/charts/spot_price_24hr_volume/archival/BTC/btc_chart_usd.dat', 1); // 1 day lite chart


?>