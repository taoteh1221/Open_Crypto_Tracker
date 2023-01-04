<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// PRIMARY INIT 
//////////////////////////////////////////////////////////////////


error_reporting($dev_debug_php_errors); // PHP error reporting


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


// ESSENTIAL REQUIRED LIB FILES...

// REQUIRED #BEFORE# config.php
$ct_conf = array(); 

// Load the hard-coded (default) config BEFORE #ANYTHING AT ALL#
require_once("config.php");

// Load app classes VERY EARLY (before loading cached conf, after config.php)
require_once('app-lib/php/classes/extended-classes-loader.php');
require_once('app-lib/php/classes/core-classes-loader.php');

// Vars, #MUST# BE SET IMMEADIATELY AFTER loading core classes
require_once('app-lib/php/inline/vars/empty-vars.php');
require_once('app-lib/php/inline/vars/static-vars.php');

// System config VERY EARLY (after loading vars)
require_once('app-lib/php/inline/config/system-config.php');

// Sessions config (MUST RUN BEFORE starting the PHP session)
require_once('app-lib/php/inline/config/sessions-config.php');


// ESSENTIAL VARS / ARRAYS / INITS SET #BEFORE# config-init.php...

// Set $ct_app_id as a global (MUST BE SET AFTER system-config.php)
// (a 10 character install ID hash, created from the base URL or base dir [if cron])
// AFTER THIS IS SET, WE CAN USE EITHER $ct_app_id OR $ct_gen->id() RELIABLY / EFFICIENTLY ANYWHERE
// $ct_gen->id() can then be used in functions WITHOUT NEEDING ANY $ct_app_id GLOBAL DECLARED.
$ct_app_id = $ct_gen->id();


// Give our session a unique name (TO SUPPORT MULTIPLE INSTALLS ON SAME DOMAIN HAVING SEPERATE SESSION DATA SETS)
// MUST BE SET AFTER $ct_app_id, AND BEFORE session_start()
session_name($ct_app_id);
////
// Session start
session_start(); // New session start


// Nonce (CSRF attack protection) for user GET links (downloads etc) / admin login session logic WHEN NOT RUNNING AS CRON
if ( $runtime_mode != 'cron' && !isset( $_SESSION['nonce'] ) ) {
$_SESSION['nonce'] = $ct_gen->rand_hash(32); // 32 byte
}
	
	
// Flag this as a fast runtime if it is, to skip certain logic later in the runtime
if ( $is_csv_export || $is_charts || $is_logs || $runtime_mode == 'captcha' ) {
$is_fast_runtime = true;
}


// Nonce for unique runtime logic (avoids potential clashes between multiple runtimes on the same machine)
$runtime_nonce = $ct_gen->rand_hash(16); // 16 byte

$fetched_feeds = 'fetched_feeds_' . $runtime_mode; // Unique feed fetch telemetry SESSION KEY (so related runtime BROWSER SESSION logic never accidentally clashes)

// If upgrade check enabled / cached var set, set the runtime var for any configured alerts
$upgrade_check_latest_version = trim( file_get_contents('cache/vars/upgrade_check_latest_version.dat') );


// ESSENTIAL INIT LOGIC...

// Create cache directories AS EARLY AS POSSIBLE
// (#MUST# RUN BEFORE load-config-by-security-level.php
// Uses HARD-CODED $ct_conf['sec']['chmod_cache_dir'], BUT IF THE DIRECTORIES DON'T EXIST YET, A CACHED CONFIG PROBABLY DOESN'T EXIST EITHER
require_once('app-lib/php/inline/other/cache-directories.php');

// Basic system checks (MUST RUN *FIRST* IN ESSENTIAL INIT LOGIC *AFTER* CACHE DIRECTORIES [FOR ERROR LOGGING])
require_once('app-lib/php/inline/system/system-checks.php');

// Logouts, protection from different types of attacks
// #MUST# run BEFORE any heavy init logic (for good security), #AFTER# directory creation (for error logging), and AFTER system checks
require_once('app-lib/php/inline/security/early-security-logic.php');

// Get / check system info for debugging / stats (MUST run AFTER directory creation [for error logging], AND AFTER system checks)
require_once('app-lib/php/inline/system/system-info.php');


//////////////////////////////////////////////////////////////////
// END PRIMARY INIT 
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

 
 ?>