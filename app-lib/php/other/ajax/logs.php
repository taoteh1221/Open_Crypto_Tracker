<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// Logs library

if ( !isset($_SESSION['admin_logged_in']) ) {
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

?>