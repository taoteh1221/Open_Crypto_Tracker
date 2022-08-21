<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// PRIMARY INIT 
//////////////////////////////////////////////////////////////////


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
require_once('app-lib/php/core-classes-loader.php');

// #MUST# BE SET AFTER loading core classes
require_once('app-lib/php/other/empty-vars.php');

// System config VERY EARLY (after loading empty vars)
require_once('app-lib/php/other/config/system-config.php');


// ESSENTIAL VARS / ARRAYS / INITS SET #BEFORE# config-init.php...

// Register the base directory of this app (MUST BE SET BEFORE !ANY! init logic calls)
$file_loc = str_replace('\\', '/', dirname(__FILE__) ); // Windows compatibility (convert backslashes)
$base_dir = preg_replace("/\/app-lib(.*)/i", "", $file_loc );
////
//!!!!!!!!!! IMPORTANT, ALWAYS LEAVE THIS HERE !!!!!!!!!!!!!!!
// FOR #UI LOGIN / LOGOUT SECURITY#, WE NEED THIS SET #VERY EARLY# IN INIT FOR APP ID / ETC,
// EVEN THOUGH WE RUN LOGIC AGAIN FURTHER DOWN IN INIT TO SET THIS UNDER
// ALL CONDITIONS (EVEN CRON RUNTIMES), AND REFRESH VAR CACHE FOR CRON LOGIC
if ( $runtime_mode != 'cron' ) {
$base_url = $ct_gen->base_url();
}
else {
$base_url = trim( file_get_contents('cache/vars/base_url.dat') );
}


// Our FINAL $base_url logic has run, so set app host var
if ( isset($base_url) ) {
    
$parse_temp = parse_url($base_url);

    if ( isset($parse_temp['port']) ) {
    $app_port = ':' . $parse_temp['port'];
    }

$app_host = $parse_temp['host'];
$app_host_address = $parse_temp['scheme'] . "://" . $app_host . $app_port;

}


// Set $ct_app_id as a global (MUST BE SET AFTER $base_url / $base_dir)
// (a 10 character install ID hash, created from the base URL or base dir [if cron])
// AFTER THIS IS SET, WE CAN USE EITHER $ct_app_id OR $ct_gen->id() RELIABLY / EFFICIENTLY ANYWHERE
// $ct_gen->id() can then be used in functions WITHOUT NEEDING ANY $ct_app_id GLOBAL DECLARED.
$ct_app_id = $ct_gen->id();


// Session start
session_start(); // New session start
////
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

// System info
$system_info = $ct_gen->system_info(); // MUST RUN AFTER SETTING $base_dir

// Remote IP
$remote_ip = ( isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'localhost' );

$fetched_feeds = 'fetched_feeds_' . $runtime_mode; // Unique feed fetch telemetry SESSION KEY (so related runtime BROWSER SESSION logic never accidentally clashes)

// If upgrade check enabled / cached var set, set the runtime var for any configured alerts
$upgrade_check_latest_version = trim( file_get_contents('cache/vars/upgrade_check_latest_version.dat') );


// ESSENTIAL INIT LOGIC...

// Create cache directories AS EARLY AS POSSIBLE
// (#MUST# RUN BEFORE plugins-config-check.php / cached-global-config.php
// Uses HARD-CODED $ct_conf['sec']['chmod_cache_dir'], BUT IF THE DIRECTORIES DON'T EXIST YET, A CACHED CONFIG PROBABLY DOESN'T EXIST EITHER
require_once('app-lib/php/other/directory-creation/cache-directories.php');

// Logins, protection from different types of attacks, #MUST# run BEFORE any heavy init logic, #AFTER# directory creation (for error logging)
require_once('app-lib/php/other/security/attack-protection.php');

// Directory security check (MUST run AFTER directory structure creation check, AND BEFORE system checks)
require_once('app-lib/php/other/security/directory-security.php');

// Get / check system info for debugging / stats (MUST run AFTER directory structure creation check, AND BEFORE system checks)
require_once('app-lib/php/other/system-info.php');


// Toggle to set the admin interface security level, if 'opt_admin_sec' from authenticated admin is verified
// (#MUST# BE SET BEFORE BOTH cached-global-config.php AND plugins-config-check.php)
if ( isset($_POST['opt_admin_sec']) && $ct_gen->admin_hashed_nonce('toggle_admin_security') != false && $_POST['admin_hashed_nonce'] == $ct_gen->admin_hashed_nonce('toggle_admin_security') ) {
$admin_area_sec_level = $_POST['opt_admin_sec'];
$ct_cache->save_file($base_dir . '/cache/vars/admin_area_sec_level.dat', $_POST['opt_admin_sec']);
}
// If not updating, and cached var already exists
elseif ( file_exists($base_dir . '/cache/vars/admin_area_sec_level.dat') ) {
$admin_area_sec_level = trim( file_get_contents($base_dir . '/cache/vars/admin_area_sec_level.dat') );
}
// Else, default to high admin security
else {
$admin_area_sec_level = 'high';
$ct_cache->save_file($base_dir . '/cache/vars/admin_area_sec_level.dat', $admin_area_sec_level);
}


//////////////////////////////////////////////////////////////////
// END PRIMARY INIT 
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

 
 ?>