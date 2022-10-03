<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


// Forbid direct INTERNET access to this file
if ( isset($_SERVER['REQUEST_METHOD']) && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']) ) {
header('HTTP/1.0 403 Forbidden', TRUE, 403);
exit;
}


// #DEV# DEBUGGING
$dev_debug_php_errors = 0; // 0 = off, -1 = on (IF SET TO -1, THIS #OVERRIDES# PHP ERROR DEBUG SETTINGS IN THE APP'S CONFIG)
error_reporting($dev_debug_php_errors); // PHP errror reporting


// Application version
$app_version = '6.00.4';  // 2022/OCTOBER/3RD


// App init libraries...

// Primary init logic (#MUST# RUN #BEFORE# #EVERYTHING# ELSE)
require_once('app-lib/php/other/init/primary-init.php');

// Config init logic (#MUST# RUN IMMEADIATELY #AFTER# primary-init.php)
require_once('app-lib/php/other/init/config-init.php');

// Fast runtimes, MUST run AFTER config-init.php, AND AS EARLY AS POSSIBLE
require_once('app-lib/php/other/fast-runtimes.php');

// Basic system checks (MUST RUN AFTER config-init.php)
require_once('app-lib/php/other/debugging/system-checks.php');

// SECURED cache files management (MUST RUN AFTER system checks)
require_once('app-lib/php/other/security/secure-cache-files.php');

// Load any activated 3RD PARTY classes (MUST RUN AS EARLY AS POSSIBLE AFTER secure-cache-files.php)
require_once('app-lib/php/3rd-party-classes-loader.php');

// Set / populate secondary app vars / arrays IMMEADIATELY AFTER loading 3rd party classes
require_once('app-lib/php/other/secondary-vars.php');

// Password protection management (MUST RUN AFTER secure cache files)
require_once('app-lib/php/other/security/password-protection.php');

// Scheduled maintenance  (MUST RUN AFTER EVERYTHING IN INIT.PHP, #EXCEPT# DEBUGGING)
require_once('app-lib/php/other/scheduled-maintenance.php');


// Unit tests to run in debug mode (MUST RUN AT THE VERY END OF INIT.PHP)
if ( $ct_conf['dev']['debug'] != 'off' ) {
require_once('app-lib/php/other/debugging/tests.php');
require_once('app-lib/php/other/debugging/exchange-and-pair-info.php');
}


// DON'T CREATE ANY WHITESPACE AFTER CLOSING PHP TAG, A WE ARE STILL IN INIT! (NO HEADER ESTABLISHED YET)

?>