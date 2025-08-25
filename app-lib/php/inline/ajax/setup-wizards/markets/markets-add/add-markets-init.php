<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// Add markets ajax call


// 'Wizard' steps
if ( $_GET['step'] == 1 ) {
require($ct['base_dir'] . '/app-lib/php/inline/ajax/setup-wizards/markets/market-steps-init.php');
}
elseif ( $_GET['step'] == 2 ) {
require($ct['base_dir'] . '/app-lib/php/inline/ajax/setup-wizards/markets/markets-add/add-markets-step-2.php');
}
elseif ( $_GET['step'] == 3 ) {
require($ct['base_dir'] . '/app-lib/php/inline/ajax/setup-wizards/markets/markets-add/add-markets-step-3.php');
}
elseif ( $_GET['step'] == 4 ) {
require($ct['base_dir'] . '/app-lib/php/inline/ajax/setup-wizards/markets/markets-add/add-markets-step-4.php');
}
elseif ( $_GET['step'] == 5 ) {
require($ct['base_dir'] . '/app-lib/php/inline/ajax/setup-wizards/markets/markets-add/add-markets-step-5.php');
}


// Access stats logging / etc
$ct['cache']->log_access_stats();
$ct['cache']->api_throttle_cache();
 
// Log errors / debugging, send notifications
$ct['cache']->app_log();
$ct['cache']->send_notifications();

flush(); // Clean memory output buffer for echo
gc_collect_cycles(); // Clean memory cache

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>