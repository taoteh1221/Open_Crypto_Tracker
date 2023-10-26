<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// PRIMARY INIT 
//////////////////////////////////////////////////////////////////

     
// Adjust CSS for LINUX PHPDESKTOP or ALL OTHER browsers
if ( $ct['app_container'] == 'phpdesktop' ) {
$ct['dev']['small_font_size_css_selector'] .= $ct['dev']['small_font_size_css_selector_adjusted'];
$ct['dev']['tiny_font_size_css_selector'] .= $ct['dev']['tiny_font_size_css_selector_adjusted'];
}
else {
$ct['dev']['font_size_css_selector'] .= $ct['dev']['small_font_size_css_selector_adjusted'];
}
     

// Get any detected php.ini (for informative error messages)
$php_ini_path = php_ini_loaded_file();


// PHPbrowserBox BUILDS *FROM* from a SEPERATE ini file *TO* the
// one that PHP uses (https://github.com/dhtml/phpbrowserbox/wiki/Tweaks)
// (so we want people to edit that ini file instead, which updates PHP's used ini file)
if ( $ct['app_container'] == 'phpbrowserbox' ) {
$php_ini_path = preg_replace("/php\.ini/", "php-tpl.ini", $php_ini_path);
}


// ESSENTIAL REQUIRED LIB FILES...

// Config file check (MUST RUN *BEFORE* LOADING CONFIG.PHP [TO CHECK FOR PARSE / FATAL ERRORS])
require_once('app-lib/php/inline/debugging/config-check.php');

// Load the hard-coded (default) config BEFORE #ANYTHING ELSE#
require_once("config.php");

// Basic system checks (MUST RUN *BEFORE* LOADING CLASSES [TO CHECK FOR REQUIRED PHP EXTENSIONS])
require_once('app-lib/php/inline/system/system-checks.php');

// Load app classes AFTER SYSTEM CHECKS (before loading cached conf, after config.php)
require_once('app-lib/php/classes/extended-classes-loader.php');
require_once('app-lib/php/classes/core-classes-loader.php');

// Vars, #MUST# BE SET IMMEADIATELY AFTER loading core classes
require_once('app-lib/php/inline/vars/empty-vars.php');
require_once('app-lib/php/inline/vars/static-vars.php');

// System config VERY EARLY (after loading vars)
require_once('app-lib/php/inline/config/system-config.php');


// ESSENTIAL VARS / ARRAYS / INITS SET #BEFORE# config-init.php...

// Set $ct['app_id'] as a global (MUST BE SET AFTER system-config.php)
// (a 10 character install ID hash, created from the base URL or base dir [if cron])
// AFTER THIS IS SET, WE CAN USE EITHER $ct['app_id'] OR $ct['gen']->id() RELIABLY / EFFICIENTLY ANYWHERE
// $ct['gen']->id() can then be used in functions WITHOUT NEEDING ANY $ct['app_id'] GLOBAL DECLARED.
$ct['app_id'] = $ct['gen']->id();

// Sessions config (MUST RUN AFTER setting $ct['app_id'])
require_once('app-lib/php/inline/init/session-init.php');


// Nonce (CSRF attack protection) for user GET links (downloads etc) / admin login session logic WHEN NOT RUNNING AS CRON
if ( $ct['runtime_mode'] != 'cron' && !isset( $_SESSION['nonce'] ) ) {
$_SESSION['nonce'] = $ct['gen']->rand_hash(32); // 32 byte
}
	
	
// Flag this as a fast runtime if it is, to skip certain logic later in the runtime
// (among other things, skips setting $ct['system_info'] / some secured cache vars, and skips doing system resource usage alerts)
if ( $is_csv_export || $is_charts || $is_logs || $ct['runtime_mode'] == 'captcha' || $ct['runtime_mode'] == 'qr_code' ) {
$is_fast_runtime = true;
}


// Current runtime user (to determine how we want to set directory / file permissions)
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
// WE HAVE FALLBACKS IF THIS IS NULL IN $ct['cache']->save_file() WHEN WE STORE CACHE FILES, SO A BRAND NEW INTALL RUN FIRST VIA CRON IS #OK#
$http_runtime_user = ( $ct['runtime_mode'] != 'cron' ? $current_runtime_user : trim( file_get_contents('cache/vars/http_runtime_user.dat') ) );

					
// HTTP SERVER setup detection variables (for cache compatibility auto-configuration)
// MUST BE SET BEFORE CACHE STRUCTURE CREATION, TO RUN IN COMPATIBILITY MODE FOR THIS PARTICULAR SERVER'S SETUP
$possible_http_users = array(
    						'www-data',
    						'apache',
    						'apache2',
    						'httpd',
    						'httpd2',
							);


$fetched_feeds = 'fetched_feeds_' . $ct['runtime_mode']; // Unique feed fetch telemetry SESSION KEY (so related runtime BROWSER SESSION logic never accidentally clashes)


// Create cache directories AS EARLY AS POSSIBLE
// (#MUST# RUN AFTER $current_runtime_user / $http_runtime_user / $possible_http_users are set,
// and BEFORE setting /cache/vars/app_version.dat)
// Uses HARD-CODED $ct['dev']['chmod_cache_dir'] dev config at top of init.php
require_once('app-lib/php/inline/other/cache-directories.php');


// If upgrade check enabled / cached var set, set the runtime var for any configured alerts
if ( file_exists('cache/vars/upgrade_check_latest_version.dat') ) {
$upgrade_check_latest_version = trim( file_get_contents('cache/vars/upgrade_check_latest_version.dat') );
}


// If CACHED app version set, set the runtime var for any configured alerts
if ( file_exists('cache/vars/app_version.dat') ) {
$cached_app_version = trim( file_get_contents('cache/vars/app_version.dat') );
}
// Otherwise save app version to flat file (for auto-install/upgrade scripts to easily determine the currently-installed version)
else {
sleep(1); // In case it's a fresh install, and cache directory structure was just created
$cached_app_version = $ct['app_version'];
$ct['cache']->save_file($ct['base_dir'] . '/cache/vars/app_version.dat', $cached_app_version);
}


// Early security logic
// #MUST# run BEFORE any heavy init logic (for good security), #AFTER# directory creation (for error logging), and AFTER system checks
require_once('app-lib/php/inline/security/early-security-logic.php');

// Load any 3RD PARTY classes WITHOUT CONFIGS (MUST run after system-config / early-security-logic)
require_once('app-lib/php/classes/3rd-party-classes-loader.php');

// Get / check system info for debugging / stats (MUST run AFTER cache-directories [for error logging], AND AFTER core-classes-loader.php)
require_once('app-lib/php/inline/system/system-info.php');


//////////////////////////////////////////////////////////////////
// END PRIMARY INIT 
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

 
 ?>