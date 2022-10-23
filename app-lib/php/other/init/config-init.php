<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// CONFIG INIT 
//////////////////////////////////////////////////////////////////


// Default config, used for upgrade checks
// (#MUST# BE SET BEFORE BOTH cached-global-config.php AND plugins-config-check.php)
// WE MODIFY / RUN THIS AND UPGRADE LOGIC, AT THE END OF plugins-config-check.php
$default_ct_conf = $ct_conf; 
////
// Used for quickening runtimes on app config upgrading checks
// (#MUST# BE SET BEFORE BOTH cached-global-config.php AND plugins-config-check.php)
if ( file_exists($base_dir . '/cache/vars/default_ct_conf_md5.dat') ) {
$check_default_ct_conf = trim( file_get_contents($base_dir . '/cache/vars/default_ct_conf_md5.dat') );
}
else {
$check_default_ct_conf = null;
}


// plugins-config-check.php MUST RUN #BEFORE# cached-global-config.php, #IN HIGH ADMIN SECURITY MODE#
// (AND THE OPPOSITE WAY AROUND #IN NORMAL ADMIN SECURITY MODE#)
if ( $admin_area_sec_level == 'normal' ) {
require_once('app-lib/php/other/config/cached-global-config.php');
require_once('app-lib/php/other/config/plugins-config-check.php');
}
else {
require_once('app-lib/php/other/config/plugins-config-check.php');
require_once('app-lib/php/other/config/cached-global-config.php');
}


// Dynamic app config auto-adjust (MUST RUN AS EARLY AS POSSIBLE AFTER #FULL# ct_conf setup)
require_once('app-lib/php/other/config/config-auto-adjust.php');

// Load any activated 3RD PARTY classes (MUST RUN AS EARLY AS POSSIBLE AFTER app config auto-adjust)
require_once('app-lib/php/classes/3rd-party-classes-loader.php');


// Essential vars / arrays / inits that can only be set AFTER config-auto-adjust...


// If debugging is enabled, turn on all PHP error reporting (BEFORE ANYTHING ELSE RUNS)
if ( $ct_conf['dev']['debug'] != 'off' || $dev_debug_php_errors == -1 ) {
error_reporting(-1); 
}
else {
error_reporting($ct_conf['dev']['error_reporting']); 
}


// Set a max execution time (if the system lets us), TO AVOID RUNAWAY PROCESSES FREEZING THE SERVER
if ( $ct_conf['dev']['debug'] != 'off' ) {
$max_exec_time = 900; // 15 minutes in debug mode
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
////
// If the script timeout var wasn't set properly / is not a whole number 3600 or less
if ( !ctype_digit($max_exec_time) || $max_exec_time > 3600 ) {
$max_exec_time = 250; // 250 seconds default
}
////
// Maximum time script can run (may OR may not be overridden by operating system values, BUT we want this if the system allows it)
set_time_limit($max_exec_time); // Doc suggest this may be more reliable than ini_set max_exec_time?


// htaccess login...SET BEFORE system checks
$interface_login_array = explode("||", $ct_conf['sec']['interface_login']);
$htaccess_username = $interface_login_array[0];
$htaccess_password = $interface_login_array[1];


// User agent (MUST BE SET VERY EARLY [AFTER primary-init / CONFIG-AUTO-ADJUST], 
// FOR ANY API CALLS WHERE USER AGENT IS REQUIRED BY THE API SERVER)
if ( trim($ct_conf['dev']['override_user_agent']) != '' ) {
$user_agent = $ct_conf['dev']['override_user_agent'];  // Custom user agent
}
elseif ( is_array($ct_conf['proxy']['proxy_list']) && sizeof($ct_conf['proxy']['proxy_list']) > 0 ) {
$user_agent = 'Curl/' .$curl_setup["version"]. ' ('.PHP_OS.'; compatible;)';  // If proxies in use, preserve some privacy
}
else {
$user_agent = 'Curl/' .$curl_setup["version"]. ' ('.PHP_OS.'; ' . $_SERVER['SERVER_SOFTWARE'] . '; PHP/' .phpversion(). '; Open_Crypto_Tracker/' . $app_version . '; +https://github.com/taoteh1221/Open_Crypto_Tracker)';
}


// Final preflight checks (MUST RUN AFTER app config auto-adjust / htaccess user login / user agent)
require_once('app-lib/php/other/debugging/final-preflight-checks.php');

// Sessions config (MUST RUN AFTER app config auto-adjust / final-preflight-checks)
require_once('app-lib/php/other/config/sessions-config.php');

// Chart sub-directory creation (if needed...MUST RUN AFTER app config auto-adjust / final-preflight-checks)
require_once('app-lib/php/other/directory-creation/chart-directories.php');

// Primary Bitcoin markets (MUST RUN AFTER app config auto-adjust / final-preflight-checks)
require_once('app-lib/php/other/primary-bitcoin-markets.php');

// Misc dynamic interface vars (MUST RUN AFTER app config auto-adjust / final-preflight-checks)
require_once('app-lib/php/other/init/interface-sub-init.php');

// Misc cron logic (MUST RUN AFTER app config auto-adjust / final-preflight-checks)
require_once('app-lib/php/other/init/cron-sub-init.php');

// Final configuration checks (MUST RUN AFTER app config auto-adjust / final-preflight-checks / primary bitcoin markets / sub inits)
require_once('app-lib/php/other/debugging/final-config-checks.php');

// Password protection management (MUST RUN AFTER setting htaccess user login vars / final-config-checks)
require_once('app-lib/php/other/security/password-protection.php');


//////////////////////////////////////////////////////////////////
// END CONFIG INIT 
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

 
 ?>