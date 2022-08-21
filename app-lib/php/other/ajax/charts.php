<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


// Charts library
 

header('Content-type: text/html; charset=' . $ct_conf['dev']['charset_default']);

header('Access-Control-Allow-Headers: *'); // Allow ALL headers

// Allow access from ANY SERVER (primarily in case the end-user has a server misconfiguration)
if ( $ct_conf['sec']['access_control_origin'] == 'any' ) {
header('Access-Control-Allow-Origin: *');
}
// Strict access from THIS APP SERVER ONLY (provides tighter security)
else {
header('Access-Control-Allow-Origin: ' . $app_host_address);
}


$font_width = 9; // NOT MONOSPACE, SO WE GUESS AN AVERAGE
$link_spacer = 65; // Space beetween light chart links


// ASSET PRICE CHARTS
if ( $_GET['mode'] == 'asset_price' ) {
require_once($base_dir . '/app-lib/php/other/ajax/charts/asset_price.php');
}
// ASSET BALANCE CHART
elseif ( $_GET['mode'] == 'asset_balance' ) {
require_once($base_dir . '/app-lib/php/other/ajax/charts/asset_balance.php');
}
// ASSET PERFORMANCE CHART
elseif ( $_GET['mode'] == 'asset_performance' ) {
require_once($base_dir . '/app-lib/php/other/ajax/charts/asset_performance.php');
}
// MARKETCAP DATA
elseif ( $_GET['mode'] == 'marketcap_data' ) {
require_once($base_dir . '/app-lib/php/other/ajax/charts/marketcap_data.php');
}
// SYSTEM CHARTS
elseif ( $_GET['mode'] == 'system' ) {
require_once($base_dir . '/app-lib/php/other/ajax/charts/system.php');
}
 
 
// Log errors / debugging, send notifications
$ct_cache->error_log();
$ct_cache->debug_log();
$ct_cache->send_notifications();

flush(); // Clean memory output buffer for echo
gc_collect_cycles(); // Clean memory cache


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>