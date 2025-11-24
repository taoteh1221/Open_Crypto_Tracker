<?php
/*
 * Copyright 2014-2026 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


header('Content-type: text/html; charset=' . $ct['dev']['charset_default']);

header('Access-Control-Allow-Headers: *'); // Allow ALL headers

// Allow access from ANY SERVER (primarily in case the end-user has a server misconfiguration)
if ( $ct['conf']['sec']['access_control_origin'] == 'any' ) {
header('Access-Control-Allow-Origin: *');
}
// Strict access from THIS APP SERVER ONLY (provides tighter security)
else {
header('Access-Control-Allow-Origin: ' . $ct['app_host_address']);
}


// Charts library
 

// ASSET PRICE CHARTS
if ( $_GET['mode'] == 'asset_price' ) {
require_once($ct['base_dir'] . '/app-lib/php/inline/ajax/charts/types/asset_price.php');
}
// ASSET BALANCE CHART
elseif ( $_GET['mode'] == 'asset_balance' ) {
require_once($ct['base_dir'] . '/app-lib/php/inline/ajax/charts/types/asset_balance.php');
}
// ASSET PERFORMANCE CHART
elseif ( $_GET['mode'] == 'asset_performance' ) {
require_once($ct['base_dir'] . '/app-lib/php/inline/ajax/charts/types/asset_performance.php');
}
// MARKETCAP DATA
elseif ( $_GET['mode'] == 'marketcap_data' ) {
require_once($ct['base_dir'] . '/app-lib/php/inline/ajax/charts/types/marketcap_data.php');
}
// SYSTEM CHARTS
elseif ( $_GET['mode'] == 'system' ) {
require_once($ct['base_dir'] . '/app-lib/php/inline/ajax/charts/types/system.php');
}
 
	    
// v6.01.01 MIGRATIONS...
// Javascript-based cookie deleting MAY not be as reliable
if ( isset($_COOKIE['show_charts']) ) {
$ct['sec']->store_cookie('show_charts', '', time()-3600);
unset($_COOKIE['show_charts']);
}
 
 
// Access stats logging / etc
$ct['cache']->log_access_stats();
$ct['cache']->api_throttle_cache();
$ct['cache']->registered_light_charts_cache();

// Log errors / debugging, send notifications
$ct['cache']->app_log();
$ct['cache']->send_notifications();

flush(); // Clean memory output buffer for echo
gc_collect_cycles(); // Clean memory cache


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>