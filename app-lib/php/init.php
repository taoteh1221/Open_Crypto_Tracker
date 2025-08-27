<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */
 

// Calculate script runtime length
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start_runtime = $time;


// Forbid direct INTERNET access to this file
if ( isset($_SERVER['REQUEST_METHOD']) && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']) ) {
header('HTTP/1.0 403 Forbidden', TRUE, 403);
exit;
}


// REQUIRED #BEFORE# ANYTHING!
$ct = array(); // Main app 

$plug = array(); // Plugins


// Subsections

$ct['opt_conf'] = array(); // Optimized config (from interface-friendly over to logic-friendly)

$ct['dev'] = array(); // Developer config


// Developer-only configs
$dev_only_configs_mode = 'init'; // Flag to only run 'init' section
require('developer-config.php');


// Assure our array of attack signatures (for scanning user input for malware injection attacks)
// is ALL LOWERCASE, since we temporarily convert user input to lowercase in the scanning algorithm
$ct['dev']['script_injection_checks'] = array_map('strtolower', $ct['dev']['script_injection_checks']);


error_reporting($ct['dev']['debug_php_errors']); // PHP error reporting


// Reset PHP cache, IF DEBUG MODE IS ENABLED, *AND* PHP WAS INSTALLED WITH THE opcache_reset EXTENSION ENABLED
if ( $ct['dev']['debug_php_errors'] != 0 && function_exists('opcache_reset') ) {
opcache_reset();
}


// Include runtime mode in our $ct array (for easily importing globals into functions, etc)
$ct['runtime_mode'] = $runtime_mode;


// Detect the edition / platform we are running in
// (MUST BE SET #AFTER# APP VERSION NUMBER, AND #BEFORE# EVERYTHING ELSE!)
if ( file_exists('../libcef.so') ) {
$ct['app_edition'] = 'desktop';  // 'desktop' (LOWERCASE)
$ct['app_platform'] = 'linux';
}
else if ( file_exists('../libcef.dll') || file_exists('../bin/bbserver.exe') ) {
$ct['app_edition'] = 'desktop';  // 'desktop' (LOWERCASE)
$ct['app_platform'] = 'windows';
}
else {
$ct['app_edition'] = 'server';  // 'server' (LOWERCASE)
$ct['app_platform'] = 'web';
}


// Detect the container we are running in
// (MUST BE SET #AFTER# APP VERSION NUMBER, AND #BEFORE# EVERYTHING ELSE!)
if ( file_exists('../libcef.dll') || file_exists('../libcef.so') ) {
$ct['app_container'] = 'phpdesktop';
}
else if ( file_exists('../bin/bbserver.exe') ) {
$ct['app_container'] = 'phpbrowserbox';
}
else {
$ct['app_container'] = 'browser';
}


// The operating system family PHP was built for.
// One of 'Windows', 'BSD', 'Darwin', 'Solaris', 'Linux' or 'Unknown'. Available as of PHP 7.2.0.
// https://www.php.net/manual/en/reserved.constants.php
if ( preg_match("/windows/i", PHP_OS_FAMILY) ) {
$ct['ms_windows_server'] = true;
}
elseif ( preg_match("/darwin/i", PHP_OS_FAMILY) ) {
$ct['mac_darwin_server'] = true;
}


// Internal API / webhook URLs vary by edition...

$ct['int_api_base_endpoint'] = ( $ct['app_edition'] == 'server' || $ct['app_container'] == 'phpbrowserbox' ? 'api/' : 'internal-api.php?data_set=' );

$ct['int_webhook_base_endpoint'] = ( $ct['app_edition'] == 'server' || $ct['app_container'] == 'phpbrowserbox' ? 'hook/' : 'web-hook.php?webhook_params=' );


// Remote IP
$ct['remote_ip'] = ( isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != '' ? $_SERVER['REMOTE_ADDR'] : 'localhost' );

// Remote HOST
$ct['remote_host'] = ( isset($_SERVER['REMOTE_HOST']) && $_SERVER['REMOTE_HOST'] != '' ? $_SERVER['REMOTE_HOST'] : 'localhost' );

// User Agent
$ct['user_agent'] = ( isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT'] != '' ? $_SERVER['HTTP_USER_AGENT'] : 'Unknown' );


// Register the base directory of this app (MUST BE SET BEFORE !ANY! init logic calls)
$ct['base_dir'] = str_replace('\\', '/', dirname(__FILE__) ); // Windows compatibility (convert backslashes)
// WITH dirname(__FILE__), WE ONLY NEED TO COVER THE app-lib/php PATH (AS WE ALWAYS GET THE PATH TO THIS CURRENT FILE)
$ct['base_dir'] = preg_replace("/\/app-lib\/php(.*)/i", "", $ct['base_dir']);


// Date timestamp (for backup files etc)
$ct['year_month_day'] = date( "Y-M-d", time() );


// App init libraries...

// Primary init logic (#MUST# RUN #BEFORE# #EVERYTHING# ELSE)
require_once($ct['base_dir'] . '/app-lib/php/inline/init/primary-init.php');

// Config init logic (#MUST# RUN IMMEADIATELY #AFTER# primary-init.php)
require_once($ct['base_dir'] . '/app-lib/php/inline/init/config-init.php');

// Inits based on runtime type (MUST RUN AFTER config-init.php)
require_once($ct['base_dir'] . '/app-lib/php/inline/init/runtime-type-init.php');

// Fast runtimes, MUST run AFTER runtime-type-init.php, AND AS EARLY AS POSSIBLE
require_once($ct['base_dir'] . '/app-lib/php/inline/other/fast-runtimes.php');

// Final configuration checks (MUST RUN AFTER runtime-type inits run checks / clear stale data,
// AND after fast-runtimes.php [to not slow fast runtimes down])
require_once($ct['base_dir'] . '/app-lib/php/inline/config/final-preflight-config-checks.php');

// Scheduled maintenance  (MUST RUN AFTER EVERYTHING IN INIT.PHP, #EXCEPT# DEBUGGING)
require_once($ct['base_dir'] . '/app-lib/php/inline/maintenance/scheduled-maintenance.php');


// Unit tests to run in debug mode (MUST RUN AT THE VERY END OF INIT.PHP)
if ( $ct['conf']['power']['debug_mode'] != 'off' ) {
require_once($ct['base_dir'] . '/app-lib/php/inline/debugging/tests.php');
// We use this for getting config data too, so SKIP require_once
require($ct['base_dir'] . '/app-lib/php/inline/debugging/exchange-and-pair-info.php');
}


// DON'T CREATE ANY WHITESPACE AFTER CLOSING PHP TAG, A WE ARE STILL IN INIT! (NO HEADER ESTABLISHED YET)

?>