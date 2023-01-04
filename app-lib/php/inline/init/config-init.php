<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// CONFIG INIT 
//////////////////////////////////////////////////////////////////


// Toggle to set the admin interface security level, if 'opt_admin_sec' from authenticated admin is verified
// (#MUST# BE SET BEFORE load-config-by-security-level.php)
if ( isset($_POST['opt_admin_sec']) && $ct_gen->pass_sec_check($_POST['admin_hashed_nonce'], 'toggle_admin_security') ) {
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


// If a ct_conf reset from authenticated admin is verified, refresh CACHED ct_conf with the DEFAULT ct_conf
// (!!MUST RUN *BEFORE* load-config-by-security-level.php ADDS PLUGIN CONFIGS TO $default_ct_conf AND $ct_conf!!)
if ( $_POST['reset_ct_conf'] == 1 && $ct_gen->pass_sec_check($_POST['admin_hashed_nonce'], 'reset_ct_conf') ) {
$reset_ct_conf = true;
}


// Load config type based on admin security level
require_once('app-lib/php/inline/config/load-config-by-security-level.php');

// Dynamic app config auto-adjust (MUST RUN AS EARLY AS POSSIBLE AFTER #FULL# ct_conf setup)
require_once('app-lib/php/inline/config/config-auto-adjust.php');

// Load any activated 3RD PARTY classes (MUST RUN AS EARLY AS POSSIBLE AFTER app config auto-adjust)
require_once('app-lib/php/classes/3rd-party-classes-loader.php');


// Essential vars / arrays / inits that can only be dynamically set AFTER config-auto-adjust...

// PHP error logging on / off, VIA END-USER CONFIG SETTING, *ONLY IF* THE HARD-CODED DEV PHP DEBUGGING IN INIT.PHP IS OFF
if ( $dev_debug_php_errors == 0 ) {
error_reporting($ct_conf['dev']['php_error_reporting']); 
}


// Set a max execution time (if the system lets us), TO AVOID RUNAWAY PROCESSES FREEZING THE SERVER
if ( $ct_conf['dev']['debug_mode'] != 'off' ) {
$max_exec_time = 1320; // 22 minutes in debug mode
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
if ( !$ct_var->whole_int($max_exec_time) || $max_exec_time > 3600 ) {
$max_exec_time = 250; // 250 seconds default
}


// Maximum time script can run (may OR may not be overridden by operating system values, BUT we want this if the system allows it)
set_time_limit($max_exec_time); // Doc suggest this may be more reliable than ini_set max_exec_time?


// htaccess login...SET BEFORE system checks
$interface_login_array = explode("||", $ct_conf['sec']['interface_login']);
$htaccess_username = $interface_login_array[0];
$htaccess_password = $interface_login_array[1];


// User agent (MUST BE SET VERY EARLY [AFTER primary-init / CONFIG-AUTO-ADJUST], 
// FOR ANY API CALLS WHERE USER AGENT IS REQUIRED BY THE API SERVER)
if ( trim($ct_conf['dev']['override_curl_user_agent']) != '' ) {
$curl_user_agent = $ct_conf['dev']['override_curl_user_agent'];  // Custom user agent
}
elseif ( is_array($ct_conf['proxy']['proxy_list']) && sizeof($ct_conf['proxy']['proxy_list']) > 0 ) {
$curl_user_agent = 'Curl/' .$curl_setup["version"]. ' ('.PHP_OS.'; compatible;)';  // If proxies in use, preserve some privacy
}
else {
$curl_user_agent = 'Curl/' .$curl_setup["version"]. ' ('.PHP_OS.'; ' . $system_info['software'] . '; +https://github.com/taoteh1221/Open_Crypto_Tracker)';
}


// Final preflight checks (MUST RUN AFTER app config auto-adjust / htaccess user login / user agent)
// (AS WE ARE RUNNING SELF-TESTS WITH $ct_cache->ext_data() ETC)
// (as we may need to refresh MAIN .htaccess / user.ini)
require_once('app-lib/php/inline/security/final-preflight-security-checks.php');

// Primary Bitcoin markets (MUST RUN AFTER app config auto-adjust / preflight-security-checks)
require_once('app-lib/php/inline/config/primary-bitcoin-markets-config.php');

// Chart sub-directory creation (if needed...MUST RUN AFTER app config auto-adjust / preflight-security-checks)
require_once('app-lib/php/inline/config/chart-directories-config.php');


//////////////////////////////////////////////////////////////////
// END CONFIG INIT 
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

 
 ?>