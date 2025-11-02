<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */

 
// Runtime mode
$runtime_mode = 'ajax';

// Running BEFORE calling config.php

if ( !isset($_GET['type']) ) {
exit;
}

// Change directory
chdir("../../../");

require('app-lib/php/init.php');

// Access control headers MUST be AFTER init.php!!!
 
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


// Running AFTER calling init.php
if ( $_GET['type'] == 'chart' && $_GET['mode'] == 'sol_nodes' ) {
require_once($ct['plug']->plug_dir(false, 'on-chain-stats') . '/plug-assets/ajax/charts/solana-nodes-chart.php');
}
elseif ( $_GET['type'] == 'map' && $_GET['mode'] == 'geolocation' && $_GET['map_key'] == 'solana_map' ) {
require_once($ct['plug']->plug_dir(false, 'on-chain-stats') . '/plug-assets/ajax/maps/solana-nodes-map.php');
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