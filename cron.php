<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


error_reporting(0); // Turn off all error reporting (0), or enable (1)

// Forbid direct access to this file
if ( realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']) ) {
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
    exit;
}

$runtime_mode = 'cron';

require("config.php");

$btc_usd = get_btc_usd($btc_exchange)['last_trade'];

foreach ( $price_alerts as $key => $value ) {
	
$value = explode("|",$value); // Convert $value into an array

$asset = strtoupper($key);
$exchange = $value[0];
$pairing = $value[1];

asset_alert_check($asset, $exchange, $pairing, 'decreased');

asset_alert_check($asset, $exchange, $pairing, 'increased');

}


if ( $proxy_alerts != 'none' ) {
	
	foreach ( $_SESSION['proxy_checkup'] as $problem_proxy ) {
	test_proxy($problem_proxy);
	sleep(1);
	}

}


error_logs();
session_destroy();
?>