<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// Forbid direct INTERNET access to this file
if ( isset($_SERVER['REQUEST_METHOD']) && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']) ) {
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
    exit;
}


// Assure CLI runtime is in install directory (server compatibility required for some PHP setups)
chdir( dirname(__FILE__) );

$runtime_mode = 'cron';

require("config.php");


// Delete ANY old zip archive backups
delete_old_files($base_dir . '/backups/', $delete_old_backups, 'zip');


// Chart backups...run before any price checks to avoid any potential file lock issues
if ( $charts_page == 'on' && $charts_backup_freq > 0 ) {
backup_archive('charts-data', $base_dir . '/cache/charts/', $charts_backup_freq);
}


// Charts and price alerts
foreach ( $asset_charts_and_alerts as $key => $value ) {
	
// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
$asset = ( stristr($key, "-") == false ? $key : substr( $key, 0, strpos($key, "-") ) );
$asset = strtoupper($asset);

$value = explode("||",$value); // Convert $value into an array

$exchange = $value[0];
$pairing = $value[1];
$mode = $value[2];
	
	
$result = asset_charts_and_alerts($key, $exchange, $pairing, $mode);

	if ( $result == FALSE ) {
	app_error( 'other_error', 'Charts / alerts update failure', $key . ' (' . $asset . ' / ' . strtoupper($pairing) . ' @ ' . $exchange . ')' );
	}

}


// Checkup on each failed proxy
if ( $proxy_alerts != 'none' ) {
	
	foreach ( $_SESSION['proxy_checkup'] as $problem_proxy ) {
	test_proxy($problem_proxy);
	sleep(1);
	}

}


// Log errors, send notifications, destroy session data
error_logs();
send_notifications();

if ( $debug_mode == 'on' ) {
	
	// Email admin cron.php runtime stats
	if ( validate_email($to_email) == 'valid' ) {
			
	$stats_message = 'Stats for cron.php runtime: Runtime lasted ' . script_runtime('finish') . ' seconds.';
		
	@safe_mail($to_email, 'Cron Job Runtime Stats', $stats_message);
		
	}
	
}
    	
hardy_session_clearing();

?>