<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


/////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////// A P P   V E R S I O N  /  E D I T I O N  /  P L A T F O R M  //////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////


// Application version
$app_version = '6.00.1';  // 2022/AUGUST/2ND


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
///////// S Y S T E M  /  C O N F I G   I N I T   S E T T I N G S  /  L O G I C /////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////


// Load app classes VERY EARLY (before loading cached conf)
require_once('app-lib/php/core-classes-loader.php');

// System config VERY EARLY (after classes loader)
require_once('app-lib/php/other/config/system-config.php');


//////////////////////////////////////////////////////////
// ESSENTIAL VARS / ARRAYS
//////////////////////////////////////////////////////////


$log_array = array();

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


//////////////////////////////////////////////////////////
// ESSENTIAL INIT LOGIC
//////////////////////////////////////////////////////////


// #MUST# BE SET BEFORE cache-directory checks
require_once('app-lib/php/other/empty-vars.php');


// Create cache directories AS EARLY AS POSSIBLE
// (#MUST# RUN BEFORE plugins-config-check.php / cached-global-config.php
// Uses HARD-CODED $ct_conf['dev']['chmod_cache_dir'], BUT IF THE DIRECTORIES DON'T EXIST YET, A CACHED CONFIG PROBABLY DOESN'T EXIST EITHER
require_once('app-lib/php/other/directory-creation/cache-directories.php');


// Toggle to enable / disable the BETA V6 ADMIN INTERFACES, if 'set_v6_beta' from authenticated admin is verified
// (#MUST# BE SET BEFORE BOTH cached-global-config.php AND plugins-config-check.php)
if ( isset($_POST['set_v6_beta']) && $ct_gen->admin_hashed_nonce('toggle_v6_beta') != false && $_POST['admin_hashed_nonce'] == $ct_gen->admin_hashed_nonce('toggle_v6_beta') ) {
$beta_v6_admin_pages = $_POST['set_v6_beta'];
$ct_cache->save_file($base_dir . '/cache/vars/beta_v6_admin_pages.dat', $_POST['set_v6_beta']);
}
// If not updating, and cached var already exists
elseif ( file_exists($base_dir . '/cache/vars/beta_v6_admin_pages.dat') ) {
$beta_v6_admin_pages = trim( file_get_contents($base_dir . '/cache/vars/beta_v6_admin_pages.dat') );
}
// Else, default to off
else {
$beta_v6_admin_pages = 'off';
$ct_cache->save_file($base_dir . '/cache/vars/beta_v6_admin_pages.dat', $beta_v6_admin_pages);
}


// Default config, used for upgrade checks
// (#MUST# BE SET BEFORE BOTH cached-global-config.php AND plugins-config-check.php)
// (SEE NOTES IN THIS FILE, RELEATED TO THE V6 SWITCHOVER)
// WE MODIFY / RUN THIS AND UPGRADE LOGIC, AT THE END OF plugins-config-check.php
// $default_ct_conf #SHOULD# BE COMPLETELY REMOVED FROM ALL LOGIC #EXCEPT# CONFIG UPGRADING LOGIC,
// #WHEN WE SWITCH ON PERMENENTLY USING THE CACHED USER EDITED CONFIG, AFTER BETA TESTING IS DONE#
$default_ct_conf = $ct_conf; 
////
// Used for quickening runtimes on app config upgrading checks
// (#MUST# BE SET BEFORE BOTH cached-global-config.php AND plugins-config-check.php)
$check_default_ct_conf = trim( file_get_contents('cache/vars/default_ct_conf_md5.dat') );


// plugins-config-check.php
// (MUST RUN #BEFORE# cached-global-config.php, #UNTIL WE SWITCH ON USING THE CACHED USER EDITED CONFIG#,
// THE WE MUST RUN IT #AFTER# INSTEAD)
////
// cached-global-config.php (SEE NOTES IN THIS FILE, RELEATED TO THE V6 SWITCHOVER)
////
if ( $beta_v6_admin_pages == 'on' ) {
require_once('app-lib/php/other/config/cached-global-config.php');
require_once('app-lib/php/other/config/plugins-config-check.php');
}
else {
require_once('app-lib/php/other/config/plugins-config-check.php');
require_once('app-lib/php/other/config/cached-global-config.php');
}

// Dynamic app config auto-adjust (MUST RUN AS EARLY AS POSSIBLE AFTER ct_conf setup)
require_once('app-lib/php/other/config/config-auto-adjust.php');

// Load any activated 3RD PARTY classes (MUST RUN AS EARLY AS POSSIBLE AFTER APP config auto-adjust#)
require_once('app-lib/php/3rd-party-classes-loader.php');


///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////// A P P   I N I T   L O G I C /////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////


// Set / populate primary app vars / arrays FIRST
require_once('app-lib/php/other/primary-vars.php');

// Logins, protection from different types of attacks, #MUST# run BEFORE any heavy init logic, AFTER setting primary vars
require_once('app-lib/php/other/security/attack-protection.php');

// Fast runtimes, MUST run AFTER attack protection, BUT EARLY AS POSSIBLE
require_once('app-lib/php/other/fast-runtimes.php');

// Chart sub-directory creation (if needed...MUST RUN AFTER app config auto-adjust)
require_once('app-lib/php/other/directory-creation/chart-directories.php');

// Directory security check (MUST run AFTER directory structure creation check, AND BEFORE system checks)
require_once('app-lib/php/other/security/directory-security.php');

// Get / check system info for debugging / stats (MUST run AFTER directory structure creation check, AND BEFORE system checks)
require_once('app-lib/php/other/system-info.php');

// Basic system checks (before allowing app to run ANY FURTHER, MUST RUN AFTER directory creation check / http server user vars / user agent var)
require_once('app-lib/php/other/debugging/system-checks.php');

// SECURED cache files management (MUST RUN AFTER system checks)
require_once('app-lib/php/other/security/secure-cache-files.php');

// Password protection management (MUST RUN AFTER system checks / secure cache files)
require_once('app-lib/php/other/security/password-protection.php');

// Primary Bitcoin markets (MUST RUN AFTER app config auto-adjust)
require_once('app-lib/php/other/primary-bitcoin-markets.php');

// Misc dynamic interface vars (MUST RUN AFTER app config auto-adjust)
require_once('app-lib/php/other/sub-init/interface-sub-init.php');

// Misc cron logic (MUST RUN AFTER app config auto-adjust)
require_once('app-lib/php/other/sub-init/cron-sub-init.php');

// App configuration checks (MUST RUN AFTER app config auto-adjust / primary bitcoin markets / sub inits)
require_once('app-lib/php/other/debugging/config-checks.php');

// Scheduled maintenance  (MUST RUN AFTER EVERYTHING IN INIT.PHP, EXCEPT DEBUGGING)
require_once('app-lib/php/other/scheduled-maintenance.php');


// Unit tests to run in debug mode (MUST RUN AT THE VERY END OF INIT.PHP)
if ( $ct_conf['dev']['debug'] != 'off' ) {
require_once('app-lib/php/other/debugging/tests.php');
require_once('app-lib/php/other/debugging/exchange-and-pair-info.php');
}


// DON'T CREATE ANY WHITESPACE AFTER CLOSING PHP TAG, A WE ARE STILL IN INIT! (NO HEADER ESTABLISHED YET)

?>