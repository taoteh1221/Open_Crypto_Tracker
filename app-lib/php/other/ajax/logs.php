<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


// Logs library

if ( !$pt_gen->admin_logged_in() ) {
exit;
}

$filename = $base_dir . '/cache/logs/' . $_GET['logfile'];

$line_numbers = ( intval($_GET['lines']) > 0 ? $_GET['lines'] : 100 );


if ( is_readable($filename) ) {
	
	$file = file($filename);
	for ($i = max(0, count($file)-$line_numbers); $i < count($file); $i++) {
   $lines[] = $file[$i];
	}

}


if( sizeof($lines) < 1 ){
$lines[] = 'No logs yet for log file: ' . $filename;
}

echo json_encode($lines);

flush(); // Clean memory output buffer for echo
gc_collect_cycles(); // Clean memory cache

?>