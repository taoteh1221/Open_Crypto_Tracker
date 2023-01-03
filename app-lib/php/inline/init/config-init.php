<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// CONFIG INIT 
//////////////////////////////////////////////////////////////////


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

// Chart sub-directory creation (if needed...MUST RUN AFTER app config auto-adjust / preflight-security-checks)
require_once('app-lib/php/inline/directory/chart-directories.php');

// Primary Bitcoin markets (MUST RUN AFTER app config auto-adjust / preflight-security-checks)
require_once('app-lib/php/inline/config/primary-bitcoin-markets-config.php');

// Misc dynamic interface vars (MUST RUN AFTER app config auto-adjust / primary bitcoin markets conf / preflight-security-checks)
require_once('app-lib/php/inline/init/interface-sub-init.php');

// Misc cron logic (MUST RUN AFTER app config auto-adjust / primary bitcoin markets conf / preflight-security-checks)
require_once('app-lib/php/inline/init/cron-sub-init.php');

// Final configuration checks (MUST RUN AFTER app config auto-adjust / preflight-security-checks / primary bitcoin markets conf / sub inits)
require_once('app-lib/php/inline/config/final-preflight-config-checks.php');


//////////////////////////////////////////////////////////////////
// END CONFIG INIT 
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

 
 ?>