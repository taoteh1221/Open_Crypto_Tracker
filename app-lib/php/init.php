<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


/////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////// A P P   V E R S I O N  /  E D I T I O N  /  P L A T F O R M  //////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////


// Application version
$app_version = '5.15.6';  // 2022/JULY/29TH


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
// #END# ESSENTIAL VARS / ARRAYS
//////////////////////////////////////////////////////////


// Create cache directories AS EARLY AS POSSIBLE
// (#MUST# RUN BEFORE plugins-config.php / app-config.php, OR IT THROWS A FATAL ERROR ON WIN11 / PHP 8.X)
// Uses HARD-CODED $ct_conf['dev']['chmod_cache_dir'], BUT IF THE DIRECTORIES DON'T EXIST YET, A CACHED CONFIG PROBABLY DOESN'T EITHER
require_once('app-lib/php/other/directory-creation/cache-directories.php');


// Plugins config
// (MUST RUN #BEFORE# app-config.php, #UNTIL WE SWITCH ON USING THE CACHED USER EDITED CONFIG#,
// THE WE MUST RUN IT #AFTER# INSTEAD)
// RE-ENABLE $refresh_cached_ct_conf IN THIS FILE, #WHEN WE SWITCH ON USING THE CACHED USER EDITED CONFIG#
require_once('app-lib/php/other/config/plugins-config.php');


// App config (SEE NOTES IN THIS FILE, RELEATED TO THE V6 SWITCHOVER)
require_once('app-lib/php/other/config/app-config.php');


///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////// A P P   I N I T   L O G I C /////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////


// Set / populate primary app vars / arrays FIRST
require_once('app-lib/php/other/primary-vars.php');

// Logins, protection from different types of attacks, #MUST# run BEFORE any heavy init logic, AFTER setting vars
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
require_once('app-lib/php/other/config/app-config-management.php');

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

// Scheduled maintenance  (MUST RUN AFTER EVERYTHING IN INIT.PHP, EXCEPT DEBUGGING)
require_once('app-lib/php/other/scheduled-maintenance.php');


// Unit tests to run in debug mode (MUST RUN AT THE VERY END OF INIT.PHP)
if ( $ct_conf['dev']['debug'] != 'off' ) {
require_once('app-lib/php/other/debugging/tests.php');
require_once('app-lib/php/other/debugging/exchange-and-pair-info.php');
}


// DON'T CREATE ANY WHITESPACE AFTER CLOSING PHP TAG, A WE ARE STILL IN INIT! (NO HEADER ESTABLISHED YET)

?>