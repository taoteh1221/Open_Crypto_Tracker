<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// Add markets ajax call
 

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


// If we are not admin logged in, OR fail the CSRF security token check, exit
if ( !$ct['gen']->admin_logged_in() || !$ct['gen']->pass_sec_check($_GET['token'], 'general_csrf_security') ) {
// Log errors / debugging, send notifications
$ct['cache']->app_log();
$ct['cache']->send_notifications();
exit;
}


// 'Wizard' steps
if ( $_GET['step'] == 1 ) {
     
     // ALL / specific exchange
     if ( $_POST['add_markets_search_exchange'] != 'all_exchanges' ) {
     $specific_exchange = $_POST['add_markets_search_exchange'];
     }
     else {
     $specific_exchange = false;
     }
     
$search_results = $ct['api']->ticker_markets_search($_POST['add_markets_search'], $specific_exchange);

var_dump($search_results); // DEBUGGING

}
elseif ( $_GET['step'] == 2 ) {
// LOGIC HERE
}


// Access stats logging
$ct['cache']->log_access_stats();
 
// Log errors / debugging, send notifications
$ct['cache']->app_log();
$ct['cache']->send_notifications();

flush(); // Clean memory output buffer for echo
gc_collect_cycles(); // Clean memory cache


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>