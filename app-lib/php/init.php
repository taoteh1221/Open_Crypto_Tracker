<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


/////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////// A P P   V E R S I O N /////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////


// Application version
$app_version = '5.13.0';  // 2022/FEBUARY/9TH

// Application edition
$app_edition = 'server';  // 'server' OR 'desktop' edition (LOWERCASE)


/////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////// S Y S T E M   I N I T   S E T T I N G S ///////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////


// Set time as UTC for logs etc ('loc_time_offset' in Admin Config GENERAL section can adjust UI / UX timestamps as needed)
date_default_timezone_set('UTC'); 


// If debugging is enabled, turn on all PHP error reporting (BEFORE ANYTHING ELSE RUNS)
if ( $ct_conf['dev']['debug'] != 'off' ) {
error_reporting(-1); 
}
else {
error_reporting($ct_conf['init']['error_reporting']); 
}


// Set a max execution time (if the system lets us), TO AVOID RUNAWAY PROCESSES FREEZING THE SERVER
if ( $ct_conf['dev']['debug'] != 'off' ) {
$max_exec_time = 600; // 10 minutes in debug mode
}
elseif ( $runtime_mode == 'ui' ) {
$max_exec_time = $ct_conf['dev']['ui_max_exec_time'];
}
elseif ( $runtime_mode == 'ajax' ) {
$max_exec_time = $ct_conf['dev']['ajax_max_exec_time'];
}
elseif ( $runtime_mode == 'cron' ) {
$max_exec_time = $ct_conf['dev']['cron_max_exec_time'];
}
elseif ( $runtime_mode == 'int_api' ) {
$max_exec_time = $ct_conf['dev']['int_api_max_exec_time'];
}
elseif ( $runtime_mode == 'webhook' ) {
$max_exec_time = $ct_conf['dev']['webhook_max_exec_time'];
}


// If the script timeout var wasn't set properly / is not a whole number 3600 or less
if ( !ctype_digit($max_exec_time) || $max_exec_time > 3600 ) {
$max_exec_time = 120; // 120 seconds default
}


// Maximum time script can run (may OR may not be overridden by operating system values, BUT we want this if the system allows it)
ini_set('max_exec_time', $max_exec_time);


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


// Session start
session_start(); // New session start

///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////// APP   I N I T   S E T T I N G S /////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////


// Load app classes
require_once('app-lib/php/core-classes-loader.php');


// Initial BLANK arrays

$sel_opt = array();

$runtime_data = array();

$runtime_data['performance_stats'] = array();

$system_warnings = array();

$system_warnings_cron_interval = array();

$log_array = array();

$rand_color_ranged =  array();

$processed_msgs = array();

$api_connections = array();

$api_runtime_cache = array();

$limited_api_calls = array();

$coingecko_api = array();

$coinmarketcap_api = array();

$asset_stats_array = array();

$asset_tracking =  array();

$btc_worth_array = array();

$btc_pairing_markets = array();

$btc_pairing_markets_excluded = array();

$price_alert_fixed_reset_array = array();

$proxy_checkup = array();

$proxies_checked = array();

$plug_conf =  array();

$plug_class = array();

$activated_plugins =  array();

// Set as global, to update in / out of functions as needed
$upgraded_ct_conf = array();


// Initial BLANK strings

$cmc_notes = null;

$conf_upgraded = null;

$td_color_zebra = null;

$mcap_data_force_usd = null;

$defipulse_api_limit = null;
        
$kraken_pairs = null;
        
$upbit_pairs = null;
        
$generic_pairs = null;
        
$generic_assets = null;


//////////////////////////////////////////////////////////////
// Set global runtime app arrays / vars...
//////////////////////////////////////////////////////////////


// Register the base directory of this app (MUST BE SET BEFORE !ANY! init logic calls)
$file_loc = str_replace('\\', '/', dirname(__FILE__) ); // Windows compatibility (convert backslashes)
$base_dir = preg_replace("/\/app-lib(.*)/i", "", $file_loc );


//!!!!!!!!!! IMPORTANT, ALWAYS LEAVE THIS HERE !!!!!!!!!!!!!!!
// FOR #UI LOGIN / LOGOUT SECURITY#, WE NEED THIS SET #VERY EARLY# IN INIT TOO,
// EVEN THOUGH WE RUN LOGIC AGAIN FURTHER DOWN IN INIT TO SET THIS UNDER
// ALL CONDITIONS (EVEN CRON RUNTIMES), AND REFRESH VAR CACHE FOR CRON LOGIC
if ( $runtime_mode != 'cron' ) {
$base_url = $ct_gen->base_url();
}


// Set $ct_app_id as a global (MUST BE SET AFTER $base_url / $base_dir)
// (a 10 character install ID hash, created from the base URL or base dir [if cron])
// AFTER THIS IS SET, WE CAN USE EITHER $ct_app_id OR $ct_gen->id() RELIABLY / EFFICIENTLY ANYWHERE
// $ct_gen->id() can then be used in functions WITHOUT NEEDING ANY $ct_app_id GLOBAL DECLARED.
$ct_app_id = $ct_gen->id();


// Give our session a unique name 
// MUST BE SET AFTER $ct_app_id / first $ct_gen->id() call
session_name( $ct_gen->id() );


// Session array
if ( !isset( $_SESSION ) ) {
$_SESSION = array();
}


// Nonce (CSRF attack protection) for user GET links (downloads etc) / admin login session logic WHEN NOT RUNNING AS CRON
if ( $runtime_mode != 'cron' && !isset( $_SESSION['nonce'] ) ) {
$_SESSION['nonce'] = $ct_gen->rand_hash(32); // 32 byte
}


// Nonce for unique runtime logic
$runtime_nonce = $ct_gen->rand_hash(16); // 16 byte


// If user is logging out (run immediately after setting session vars, for quick runtime)
if ( $_GET['logout'] == 1 && $ct_gen->admin_hashed_nonce('logout') != false && $_GET['admin_hashed_nonce'] == $ct_gen->admin_hashed_nonce('logout') ) {
	
// Try to avoid edge-case bug where sessions don't delete, using our hardened function logic
$ct_gen->hardy_sess_clear(); 

// Delete admin login cookie
unset($_COOKIE['admin_auth_' . $ct_gen->id()]); 

header("Location: index.php");
exit;

}


// CSRF attack protection for downloads EXCEPT backup downloads (which require the nonce 
// in the filename [which we do already], since backup links are created during cron runtimes)
if ( $runtime_mode == 'download' && !isset($_GET['backup']) && $_GET['token'] != $ct_gen->nonce_digest('download') ) {
$ct_gen->log('security_error', 'aborted, security token mis-match/stale from ' . $_SERVER['REMOTE_ADDR'] . ', for request: ' . $_SERVER['REQUEST_URI']);
$ct_cache->error_log();
echo "Aborted, security token mis-match/stale.";
exit;
}


// Current runtime user
if ( function_exists('posix_getpwuid') && function_exists('posix_geteuid') ) {
$current_runtime_user = posix_getpwuid(posix_geteuid())['name'];
}
else {
$current_runtime_user = get_current_user();
}


// Get WEBSERVER runtime user (from cache if currently running from CLI)
// MUST BE SET BEFORE CACHE STRUCTURE CREATION, TO RUN IN COMPATIBILITY MODE (IF NEEDED) FOR THIS PARTICULAR SERVER'S SETUP
// WE HAVE FALLBACKS IF THIS IS NULL IN $ct_cache->save_file() WHEN WE STORE CACHE FILES, SO A BRAND NEW INTALL RUN FIRST VIA CRON IS #OK#
$http_runtime_user = ( $runtime_mode != 'cron' ? $current_runtime_user : trim( file_get_contents('cache/vars/http_runtime_user.dat') ) );


$interface_login_array = explode("||", $ct_conf['gen']['interface_login']);

// htaccess login...SET BEFORE system checks
$htaccess_username = $interface_login_array[0];
$htaccess_password = $interface_login_array[1];


// A bit of DOS attack mitigation for bogus / bot login attempts
// Speed up runtime SIGNIFICANTLY by checking EARLY for a bad / non-existent captcha code, and rendering the related form again...
// A BIT STATEMENT-INTENSIVE ON PURPOSE, AS IT KEEPS RUNTIME SPEED MUCH HIGHER
if ( $_POST['admin_submit_register'] || $_POST['admin_submit_login'] || $_POST['admin_submit_reset'] ) {


	if ( trim($_POST['captcha_code']) == '' || trim($_POST['captcha_code']) != '' && strtolower( trim($_POST['captcha_code']) ) != strtolower($_SESSION['captcha_code']) ) {
	
	
		if ( $_POST['admin_submit_register'] ) {
		$sel_opt['theme_selected'] = ( $_COOKIE['theme_selected'] ? $_COOKIE['theme_selected'] : $ct_conf['gen']['default_theme'] );
		require("templates/interface/desktop/php/admin/admin-login/register.php");
		exit;
		}
		elseif ( $_POST['admin_submit_login'] ) {
		$sel_opt['theme_selected'] = ( $_COOKIE['theme_selected'] ? $_COOKIE['theme_selected'] : $ct_conf['gen']['default_theme'] );
		require("templates/interface/desktop/php/admin/admin-login/login.php");
		exit;
		}
		elseif ( $_POST['admin_submit_reset'] ) {
		$sel_opt['theme_selected'] = ( $_COOKIE['theme_selected'] ? $_COOKIE['theme_selected'] : $ct_conf['gen']['default_theme'] );
		require("templates/interface/desktop/php/admin/admin-login/reset.php");
		exit;
		}
	
	
	}
	

}


// Coinmarketcap supported currencies array
require_once('app-lib/php/other/coinmarketcap-currencies.php');
					
// HTTP SERVER setup detection variables (for cache compatibility auto-configuration)
// MUST BE SET BEFORE CACHE STRUCTURE CREATION, TO RUN IN COMPATIBILITY MODE FOR THIS PARTICULAR SERVER'S SETUP
$possible_http_users = array(
						'www-data',
						'apache',
						'apache2',
						'httpd',
						'httpd2',
							);

$fetched_feeds = 'fetched_feeds_' . $runtime_mode; // Unique feed fetch telemetry SESSION KEY (so related runtime BROWSER SESSION logic never accidentally clashes)

$remote_ip = ( isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'localhost' );


// Create cache directories (if needed), with $http_runtime_user determined further above 
// (for cache compatibility on certain PHP setups)
require_once('app-lib/php/other/directory-creation/cache-directories.php');


// Get / check system info for debugging / stats (MUST run AFTER directory structure creation check, AND BEFORE system checks)
require_once('app-lib/php/other/system-info.php');


// If upgrade check enabled / cached var set, set the runtime var for any configured alerts
$upgrade_check_latest_version = trim( file_get_contents('cache/vars/upgrade_check_latest_version.dat') );

// If we are queued to run a UI alert that an upgrade is available
$ui_upgrade_alert = json_decode( trim( file_get_contents($base_dir . '/cache/events/ui_upgrade_alert.dat') ) , TRUE);


// Raspberry Pi device? (run after system info var)
if ( preg_match("/raspberry/i", $system_info['model']) ) {
$is_raspi = 1;
}

// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
if ( trim($ct_conf['comms']['telegram_your_username']) != '' && trim($ct_conf['comms']['telegram_bot_name']) != '' && trim($ct_conf['comms']['telegram_bot_username']) != '' && $ct_conf['comms']['telegram_bot_token'] != '' ) {
$telegram_activated = 1;
}

// User agent (MUST BE SET EARLY [BUT AFTER SYSTEM INFO VAR], FOR ANY API CALLS WHERE USER AGENT IS REQUIRED BY THE API SERVER)
if ( trim($ct_conf['dev']['override_user_agent']) != '' ) {
$user_agent = $ct_conf['dev']['override_user_agent'];  // Custom user agent
}
elseif ( is_array($ct_conf['proxy']['proxy_list']) && sizeof($ct_conf['proxy']['proxy_list']) > 0 ) {
$user_agent = 'Curl/' .$curl_setup["version"]. ' ('.PHP_OS.'; compatible;)';  // If proxies in use, preserve some privacy
}
else {
$user_agent = 'Curl/' .$curl_setup["version"]. ' ('.PHP_OS.'; ' . $_SERVER['SERVER_SOFTWARE'] . '; PHP/' .phpversion(). '; Open_Crypto_Tracker/' . $app_version . '; +https://github.com/taoteh1221/Open_Crypto_Tracker)';
}



// INCREASE CERTAIN RUNTIME SPEEDS / REDUCE LOADING EXCESS LOGIC (minimal inits included in libraries if needed)

// If we are just running a captcha image, ONLY run captcha library for runtime speed (exit after)
if ( $runtime_mode == 'captcha' ) {
require_once('app-lib/php/other/security/captcha-lib.php');
exit;
}
// If we are just running chart retrieval, ONLY run charts library for runtime speed (exit after)
elseif ( $is_charts ) {
require_once('app-lib/php/other/ajax/charts.php');
exit;
}
// If we are just running log retrieval, ONLY run logs library for runtime speed (exit after)
elseif ( $is_logs ) {
require_once('app-lib/php/other/ajax/logs.php');
exit;
}
// If we are just running CSV exporting, ONLY run csv export libraries for runtime speed / avoiding excess logic (exit after)
elseif ( $is_csv_export ) {

	// Example template download (SAFE FROM CSRF ATTACKS, since it's just example data)
	if ( $_GET['example_template'] == 1 ) {
	require_once('app-lib/php/other/downloads/example-csv.php');
	}
	// Portfolio export download (CSRF security / logging is in export-csv.php)
	elseif ( is_array($ct_conf['assets']) ) {
	require_once('app-lib/php/other/downloads/export-csv.php');
	}

exit;
}
// If we are just running notes download, avoiding excess logic (exit after)
// (CSRF security / logging is in download-notes.php)
elseif ( $is_notes ) {
require_once('app-lib/php/other/downloads/export-notes.php');
}



//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////


// Directory security check (MUST run AFTER directory structure creation check, AND BEFORE system checks)
require_once('app-lib/php/other/security/directory-security.php');


// UI-CACHED VARS THAT !MUST! BE AVAILABLE BEFORE SYSTEM CHECKS, #BUT# MUST RUN AFTER DIRECTORY CREATION
// RUN DURING 'ui' ONLY
if ( $runtime_mode == 'ui' ) {
	
	// Have UI / HTTP runtime mode RE-CACHE the runtime_user data every 24 hours, since CLI runtime cannot determine the UI / HTTP runtime_user 
	if ( $ct_cache->update_cache('cache/vars/http_runtime_user.dat', (60 * 24) ) == true ) {
	$ct_cache->save_file('cache/vars/http_runtime_user.dat', $http_runtime_user); // ALREADY SET FURTHER UP IN INIT.PHP
	}


	// Have UI runtime mode RE-CACHE the app URL data every 24 hours, since CLI runtime cannot determine the app URL (for sending backup link emails during backups, etc)
	if ( $ct_cache->update_cache('cache/vars/base_url.dat', (60 * 24) ) == true ) {
	$base_url = $ct_gen->base_url();
	$ct_cache->save_file('cache/vars/base_url.dat', $base_url);
	}
	else {
	$base_url = trim( file_get_contents('cache/vars/base_url.dat') );
	}

}
else {
$base_url = trim( file_get_contents('cache/vars/base_url.dat') );
}


if ( isset($base_url) ) {
$parse_temp = parse_url($base_url);
$app_host = $parse_temp['host'];
}


// Basic system checks (before allowing app to run ANY FURTHER, MUST RUN AFTER directory creation check / http server user vars / user agent var)
require_once('app-lib/php/other/debugging/system-checks.php');

// Plugins config (MUST RUN AFTER system checks and BEFORE secure cache files)
require_once('app-lib/php/other/plugins-config.php');

// SET original ct_conf array AFTER plugins config, BEFORE secure cache files, and BEFORE dynamic app config management
$default_ct_conf = $ct_conf; 

// SECURED cache files management (MUST RUN AFTER system checks and AFTER plugins config)
require_once('app-lib/php/other/security/secure-cache-files.php');

// Dynamic app config management (MUST RUN AFTER secure cache files FOR CACHED / config.php ct_conf comparison)
require_once('app-lib/php/other/app-config-management.php');

// Load any activated 3RD PARTY classes (MUST RUN AS EARLY AS POSSIBLE #AFTER SECURE CACHE FILES / APP CONFIG MANAGEMENT#)
require_once('app-lib/php/3rd-party-classes-loader.php');

// Chart sub-directory creation (if needed...MUST RUN AFTER app config management)
require_once('app-lib/php/other/directory-creation/chart-directories.php');

// Password protection management (MUST RUN AFTER system checks / secure cache files / app config management)
require_once('app-lib/php/other/security/password-protection.php');

// Primary Bitcoin markets (MUST RUN AFTER app config management)
require_once('app-lib/php/other/primary-bitcoin-markets.php');

// Misc dynamic interface vars (MUST RUN AFTER app config management)
require_once('app-lib/php/other/sub-init/interface-sub-init.php');

// Misc cron logic (MUST RUN AFTER app config management)
require_once('app-lib/php/other/sub-init/cron-sub-init.php');

// App configuration checks (MUST RUN AFTER app config management / primary bitcoin markets / sub inits)
require_once('app-lib/php/other/debugging/config-checks.php');

// Scheduled maintenance  (MUST RUN AFTER EVERYTHING IN INIT.PHP)
require_once('app-lib/php/other/scheduled-maintenance.php');



// Unit tests to run in debug mode (MUST RUN AT THE VERY END OF INIT.PHP)
if ( $ct_conf['dev']['debug'] != 'off' ) {
require_once('app-lib/php/other/debugging/tests.php');
require_once('app-lib/php/other/debugging/exchange-and-pairing-info.php');
}



?>
