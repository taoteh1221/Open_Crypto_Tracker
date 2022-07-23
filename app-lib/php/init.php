<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


/////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////// A P P   V E R S I O N  /  E D I T I O N  /  P L A T F O R M  //////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////


// Application version
$app_version = '5.15.4';  // 2022/JULY/22ND


// Detect if we are running the desktop or server edition
// (MUST BE SET #AFTER# APP VERSION NUMBER, AND #BEFORE# EVERYTHING ELSE!)
if ( file_exists('../libcef.so') ) {
$app_edition = 'desktop';  // 'desktop' (LOWERCASE)
$app_platform = 'linux';
}
else if ( file_exists('../libcef.dll') ) {
$app_edition = 'desktop';  // 'desktop' (LOWERCASE)
$app_platform = 'windows';
}
else {
$app_edition = 'server';  // 'server' (LOWERCASE)
$app_platform = 'web';
}


/////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////// C O N F I G   I N I T   S E T T I N G S ///////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////


// SINCE WE RUN THE CONFIG FROM A CACHED JSON FILE, THIS MUST RUN BEFORE #ANY# INIT LOGIC


// Register the base directory of this app (MUST BE SET BEFORE !ANY! init logic calls)
$file_loc = str_replace('\\', '/', dirname(__FILE__) ); // Windows compatibility (convert backslashes)
$base_dir = preg_replace("/\/app-lib(.*)/i", "", $file_loc );


// Load app classes VERY EARLY (before loading cached conf)
require_once('app-lib/php/core-classes-loader.php');


$log_array = array();

$plug_conf =  array();

$plug_class = array();

$activated_plugins =  array();

$upgraded_ct_conf = array();

$refresh_cached_ct_conf = 0;

$check_default_ct_conf = trim( file_get_contents('cache/vars/default_ct_conf_md5.dat') );


// Current runtime user
if ( function_exists('posix_getpwuid') && function_exists('posix_geteuid') ) {
$current_runtime_user = posix_getpwuid(posix_geteuid())['name'];
}
elseif ( function_exists('get_current_user') ) {
$current_runtime_user = get_current_user();
}
else {
$current_runtime_user = null;
}


// Get WEBSERVER runtime user (from cache if currently running from CLI)
// MUST BE SET BEFORE CACHE STRUCTURE CREATION, TO RUN IN COMPATIBILITY MODE (IF NEEDED) FOR THIS PARTICULAR SERVER'S SETUP
// WE HAVE FALLBACKS IF THIS IS NULL IN $ct_cache->save_file() WHEN WE STORE CACHE FILES, SO A BRAND NEW INTALL RUN FIRST VIA CRON IS #OK#
$http_runtime_user = ( $runtime_mode != 'cron' ? $current_runtime_user : trim( file_get_contents('cache/vars/http_runtime_user.dat') ) );

					
// HTTP SERVER setup detection variables (for cache compatibility auto-configuration)
// MUST BE SET BEFORE CACHE STRUCTURE CREATION, TO RUN IN COMPATIBILITY MODE FOR THIS PARTICULAR SERVER'S SETUP
$possible_http_users = array(
    						'www-data',
    						'apache',
    						'apache2',
    						'httpd',
    						'httpd2',
							);


// Create cache directories AS EARLY AS POSSIBLE (if needed)
// REQUIRES $http_runtime_user determined further above (for cache compatibility on certain PHP setups)
// Uses HARD-CODED $ct_conf['dev']['chmod_cache_dir'], BUT IF THE DIRECTORIES DON'T EXIST YET, A CACHED CONFIG PROBABLY DOESN'T EITHER
// (#MUST# RUN BEFORE load_cached_config(), OR IT THROWS A FATAL ERROR ON WIN11 / PHP 8.X)
require_once('app-lib/php/other/directory-creation/cache-directories.php');


// Plugins config
// (MUST RUN #BEFORE# load_cached_config(), #UNTIL WE SWITCH ON USING THE CACHED USER EDITED CONFIG#,
// THE WE MUST RUN IT #AFTER# INSTEAD)
// RE-ENABLE $refresh_cached_ct_conf IN THIS FILE, #WHEN WE SWITCH ON USING THE CACHED USER EDITED CONFIG#
require_once('app-lib/php/other/plugins-config.php');


// SET default ct_conf array BEFORE load_cached_config(), and BEFORE dynamic app config management
// (ALSO MUST BE #AFTER# PLUGINS CONFIG)
// #MUST# BE COMPLETELY REMOVED FROM ALL LOGIC, #WHEN WE SWITCH ON USING THE CACHED USER EDITED CONFIG#
$default_ct_conf = $ct_conf; 


// Load cached config (user-edited via admin interface), unless it's corrupt json 
// (if corrupt, it will reset from hard-coded default config in config.php)
// SEE upgrade_cache_ct_conf() AND subarray_ct_conf_upgrade(), #WHEN WE SWITCH ON USING THE CACHED USER EDITED CONFIG# 
$ct_gen->load_cached_config();


/////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////// S Y S T E M   I N I T   S E T T I N G S ///////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////


// Set time as UTC for logs etc ('loc_time_offset' in Admin Config GENERAL section can adjust UI / UX timestamps as needed)
date_default_timezone_set('UTC'); 

$remote_ip = ( isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'localhost' );


// If debugging is enabled, turn on all PHP error reporting (BEFORE ANYTHING ELSE RUNS)
if ( $ct_conf['dev']['debug'] != 'off' ) {
error_reporting(-1); 
}
else {
error_reporting($ct_conf['dev']['error_reporting']); 
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


///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////// APP   I N I T   S E T T I N G S /////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////


// Set / populate primary app vars / arrays FIRST
require_once('app-lib/php/other/primary-vars.php');

// Protection from different types of attacks, #MUST# run BEFORE any heavy init logic, AFTER setting vars
require_once('app-lib/php/other/security/attack-protection.php');

// Fast runtimes, MUST run AFTER attack protection, BUT EARLY AS POSSIBLE
require_once('app-lib/php/other/fast-runtimes.php');

// Directory security check (MUST run AFTER directory structure creation check, AND BEFORE system checks)
require_once('app-lib/php/other/security/directory-security.php');

// Get / check system info for debugging / stats (MUST run AFTER directory structure creation check, AND BEFORE system checks)
require_once('app-lib/php/other/system-info.php');

// Basic system checks (before allowing app to run ANY FURTHER, MUST RUN AFTER directory creation check / http server user vars / user agent var)
require_once('app-lib/php/other/debugging/system-checks.php');

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
require_once('app-lib/php/other/debugging/exchange-and-pair-info.php');
}


// DON'T CREATE ANY WHITESPACE AFTER CLOSING PHP TAG, A WE ARE STILL IN INIT! (NO HEADER ESTABLISHED YET)

?>