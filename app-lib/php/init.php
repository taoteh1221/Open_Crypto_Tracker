<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


// Forbid direct INTERNET access to this file
if ( isset($_SERVER['REQUEST_METHOD']) && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']) ) {
header('HTTP/1.0 403 Forbidden', TRUE, 403);
exit;
}


// Application version
$app_version = '6.00.19';  // 2023/JANUARY/27TH


// #PHP# ERROR LOGGING
// Can take any setting shown here: https://www.php.net/manual/en/function.error-reporting.php
// 0 = off, -1 = on (IF *NOT* SET TO ZERO HERE, THIS #OVERRIDES# PHP ERROR DEBUG SETTINGS IN THE APP'S USER CONFIG SETTINGS)
// WRAP VALUE(S) IN PARENTHESIS, SO MUTIPLE VALUES CAN BE USED: (0) / (-1) / (E_ERROR | E_PARSE)
$dev_debug_php_errors = (0); 


// Calculate script runtime length
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start_runtime = $time;


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
if ( $ct_conf['dev']['debug_mode'] != 'off' ) {
require_once('app-lib/php/inline/debugging/tests.php');
require_once('app-lib/php/inline/debugging/exchange-and-pair-info.php');
}


// DON'T CREATE ANY WHITESPACE AFTER CLOSING PHP TAG, A WE ARE STILL IN INIT! (NO HEADER ESTABLISHED YET)

?>