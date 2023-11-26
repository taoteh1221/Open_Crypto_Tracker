<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
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
require_once($ct['base_dir'] . '/app-lib/php/inline/debugging/config-check.php');

// Load the hard-coded (default) config BEFORE #ANYTHING ELSE#
require_once("config.php");

// Basic system checks (MUST RUN *BEFORE* LOADING CLASSES [TO CHECK FOR REQUIRED PHP EXTENSIONS])
require_once($ct['base_dir'] . '/app-lib/php/inline/system/system-checks.php');

// Load app classes AFTER SYSTEM CHECKS (before loading cached conf, after config.php)
require_once($ct['base_dir'] . '/app-lib/php/classes/extended-classes-loader.php');
require_once($ct['base_dir'] . '/app-lib/php/classes/core-classes-loader.php');

// Vars, #MUST# BE SET IMMEADIATELY AFTER loading core classes
require_once($ct['base_dir'] . '/app-lib/php/inline/vars/empty-vars.php');
require_once($ct['base_dir'] . '/app-lib/php/inline/vars/static-vars.php');

// System config VERY EARLY (after loading vars)
require_once($ct['base_dir'] . '/app-lib/php/inline/config/system-config.php');

// Setup cache directories AS EARLY AS POSSIBLE
require_once($ct['base_dir'] . '/app-lib/php/inline/other/cache-setup.php');


// ESSENTIAL VARS / ARRAYS / INITS SET #BEFORE# config-init.php...

// Set $ct['app_id'] as a global (MUST BE SET AFTER system-config.php)
// (a 10 character install ID hash, created from the base URL or base dir [if cron])
// AFTER THIS IS SET, WE CAN USE EITHER $ct['app_id'] OR $ct['gen']->id() RELIABLY / EFFICIENTLY ANYWHERE
// $ct['gen']->id() can then be used in functions WITHOUT NEEDING ANY $ct['app_id'] GLOBAL DECLARED.
$ct['app_id'] = $ct['gen']->id();

// Sessions config (MUST RUN AFTER setting $ct['app_id'])
require_once($ct['base_dir'] . '/app-lib/php/inline/init/session-init.php');


// Nonce (CSRF attack protection) for user GET links (downloads etc) / admin login session logic WHEN NOT RUNNING AS CRON
if ( $ct['runtime_mode'] != 'cron' && !isset( $_SESSION['nonce'] ) ) {
$_SESSION['nonce'] = $ct['gen']->rand_hash(32); // 32 byte
}
	
	
// Flag this as a fast runtime if it is, to skip certain logic later in the runtime
// (among other things, skips setting $ct['system_info'] / some secured cache vars, and skips doing system resource usage alerts)
if ( $is_csv_export || $is_charts || $is_logs || $ct['runtime_mode'] == 'captcha' || $ct['runtime_mode'] == 'qr_code' ) {
$is_fast_runtime = true;
}


$fetched_feeds = 'fetched_feeds_' . $ct['runtime_mode']; // Unique feed fetch telemetry SESSION KEY (so related runtime BROWSER SESSION logic never accidentally clashes)


// If upgrade check enabled / cached var set, set the runtime var for any configured alerts
if ( file_exists($ct['base_dir'] . '/cache/vars/state-tracking/upgrade_check_latest_version.dat') ) {
$upgrade_check_latest_version = trim( file_get_contents($ct['base_dir'] . '/cache/vars/state-tracking/upgrade_check_latest_version.dat') );
}


// If CACHED app version set, set the runtime var, AND FLAG ANY UPGRADE FOR
// NON-HIGH SECURITY MODE'S CACHED CONFIG (IF IT DOESN'T MATCH THE CURRENT VERSION NUMBER)
if ( file_exists($ct['base_dir'] . '/cache/vars/state-tracking/app_version.dat') ) {
     
$cached_app_version = trim( file_get_contents($ct['base_dir'] . '/cache/vars/state-tracking/app_version.dat') );

     // Check version number against cached value
     if ( trim($cached_app_version) != trim($ct['app_version']) ) {
     $upgraded_install = true;
     }
     
}
// Otherwise cache the app version for new installations
else {
$ct['cache']->save_file($ct['base_dir'] . '/cache/vars/state-tracking/app_version.dat', $ct['app_version']);
}


// Early security logic
// #MUST# run BEFORE any heavy init logic (for good security), #AFTER# directory creation (for error logging), and AFTER system checks
require_once($ct['base_dir'] . '/app-lib/php/inline/security/early-security-logic.php');

// Load any 3RD PARTY classes WITHOUT CONFIGS (MUST run after system-config / early-security-logic)
require_once($ct['base_dir'] . '/app-lib/php/classes/3rd-party-classes-loader.php');

// Get / check system info for debugging / stats (MUST run AFTER cache-directories [for error logging], AND AFTER core-classes-loader.php)
require_once($ct['base_dir'] . '/app-lib/php/inline/system/system-info.php');


//////////////////////////////////////////////////////////////////
// END PRIMARY INIT 
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

 
 ?>