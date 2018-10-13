<?php
/*
 * Copyright 2014-2018 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


error_reporting(0); // Turn off all error reporting (0), or enable (1)

// Forbid direct access to this file
if ( realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']) ) {
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
    exit;
}

require("config.php");

$btc_usd = get_btc_usd($btc_in_usd);

foreach ( $cron_alerts as $key => $value ) {
	
$value = explode("|",$value); // Convert $value into an array

$asset = strtoupper($key);
$exchange = $value[0];
$pairing = $value[1];
$alert_level = $value[2];

asset_alert($asset, $exchange, $pairing, $alert_level);

}

?>