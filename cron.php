<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


error_reporting(0); // Turn off all error reporting (0), or enable (1)

// Forbid direct INTERNET access to this file
if ( realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']) ) {
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
    exit;
}

$runtime_mode = 'cron';

require("config.php");


// Delete ANY old zip archive backups
delete_old_files($base_dir . '/backups/', $delete_old_backups, 'zip');


// Chart backups...run before any price checks to avoid any potential file lock issues
if ( $charts_page == 'on' && $charts_backup_freq > 0 ) {
backup_archive('charts-data', $base_dir . '/cache/charts/', $charts_backup_freq);
}


// Price alerts checking
$btc_usd = get_btc_usd($btc_exchange)['last_trade'];

foreach ( $exchange_price_alerts as $key => $value ) {
	
$value = explode("||",$value); // Convert $value into an array

$exchange = $value[0];
$pairing = $value[1];

asset_alert_check($key, $exchange, $pairing, 'decreased');
asset_alert_check($key, $exchange, $pairing, 'increased');

}


// Checkup on each failed proxy
if ( $proxy_alerts != 'none' ) {
	
	foreach ( $_SESSION['proxy_checkup'] as $problem_proxy ) {
	test_proxy($problem_proxy);
	sleep(1);
	}

}


// Log errors, destroy session data
error_logs();
session_destroy();

?>