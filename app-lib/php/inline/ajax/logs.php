<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// Logs library
 

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
if ( !$ct['gen']->admin_logged_in() || !$ct['gen']->pass_sec_check($_GET['token'], 'logs_csrf_security') ) {
// Log errors / debugging, send notifications
$ct['cache']->app_log();
$ct['cache']->send_notifications();
exit;
}

$lines = array();

$filename = $ct['base_dir'] . '/cache/logs/' . $_GET['logfile'];

$line_numbers = ( intval($_GET['lines']) > 0 ? $_GET['lines'] : 100 );


if ( is_readable($filename) ) {
	
	$file = file($filename);
	for ($i = max(0, count($file)-$line_numbers); $i < count($file); $i++) {
     $lines[] = $file[$i];
	}

}


if( sizeof($lines) == 0 ){
$lines[] = 'No logs yet for log file: ' . $filename;
}


echo json_encode($lines);
 
 
// Access stats logging
$ct['cache']->log_access_stats();

// Log errors / debugging, send notifications
$ct['cache']->app_log();
$ct['cache']->send_notifications();

flush(); // Clean memory output buffer for echo
gc_collect_cycles(); // Clean memory cache


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>