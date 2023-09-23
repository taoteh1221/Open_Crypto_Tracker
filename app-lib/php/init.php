<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */
 
 
// IMPORTANT NOTICE: DEVELOPER-ONLY APP CONFIGS ARE BELOW INITIAL LOGIC *FURTHER DOWN IN THIS FILE*


// REQUIRED #BEFORE# ANYTHING!
$ct = array(); 


// Developer-only configs
$dev_only_configs_mode = 'init'; // Flag to only run 'init' section
require('developer-config.php');


// Forbid direct INTERNET access to this file
if ( isset($_SERVER['REQUEST_METHOD']) && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']) ) {
header('HTTP/1.0 403 Forbidden', TRUE, 403);
exit;
}


error_reporting($ct['dev']['debug_php_errors']); // PHP error reporting


// Calculate script runtime length
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start_runtime = $time;


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


// App init libraries...

// Primary init logic (#MUST# RUN #BEFORE# #EVERYTHING# ELSE)
require_once('app-lib/php/inline/init/primary-init.php');

// Config init logic (#MUST# RUN IMMEADIATELY #AFTER# primary-init.php)
require_once('app-lib/php/inline/init/config-init.php');

// Inits based on runtime type (MUST RUN AFTER config-init.php)
require_once('app-lib/php/inline/init/runtime-type-init.php');

// Fast runtimes, MUST run AFTER runtime-type-init.php, AND AS EARLY AS POSSIBLE
require_once('app-lib/php/inline/other/fast-runtimes.php');

// Final configuration checks (MUST RUN AFTER runtime-type inits run checks / clear stale data,
// AND after fast-runtimes.php [to not slow fast runtimes down])
require_once('app-lib/php/inline/config/final-preflight-config-checks.php');

// Scheduled maintenance  (MUST RUN AFTER EVERYTHING IN INIT.PHP, #EXCEPT# DEBUGGING)
require_once('app-lib/php/inline/maintenance/scheduled-maintenance.php');


// Unit tests to run in debug mode (MUST RUN AT THE VERY END OF INIT.PHP)
if ( $ct['conf']['power']['debug_mode'] != 'off' ) {
require_once('app-lib/php/inline/debugging/tests.php');
require_once('app-lib/php/inline/debugging/exchange-and-pair-info.php');
}


// DON'T CREATE ANY WHITESPACE AFTER CLOSING PHP TAG, A WE ARE STILL IN INIT! (NO HEADER ESTABLISHED YET)

?>