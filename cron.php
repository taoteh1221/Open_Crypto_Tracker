<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */

// Calculate script runtime length
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start_runtime = $time;

// Forbid direct INTERNET access to this file
if ( isset($_SERVER['REQUEST_METHOD']) && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']) ) {
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
    exit;
}


// Assure CLI runtime is in install directory (server compatibility required for some PHP setups)
chdir( dirname(__FILE__) );

// Runtime mode
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
	app_logging( 'other_error', 'Charts / alerts update failure', $key . ' (' . $asset . ' / ' . strtoupper($pairing) . ' @ ' . $exchange . ')' );
	}

}


// Checkup on each failed proxy
if ( $proxy_alerts != 'none' ) {
	
	foreach ( $_SESSION['proxy_checkup'] as $problem_proxy ) {
	test_proxy($problem_proxy);
	sleep(1);
	}

}


// Log errors, send notifications BEFORE runtime stats
error_logs();
send_notifications();


// Calculate script runtime length
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$total_runtime = round( ($time - $start_runtime) , 3);



// If this is running on a Raspberry Pi, chart the 15 min load avg / temperature / free partition space
if ( preg_match("/raspberry/i", $system_info['model']) ) {
    			
// Raspi system data
$raspi_load = $system_info['system_load'];
$raspi_load = preg_replace("/ \(15 min avg\)(.*)/i", "", $raspi_load);
$raspi_load = preg_replace("/(.*)\(5 min avg\) /i", "", $raspi_load); // Use 15 minute average
    		
$raspi_temp = preg_replace("/° Celsius/i", "", $system_info['system_temp']);

$raspi_free_space = preg_replace("/ (.*)/i", "", $system_info['free_partition_space']);

	// Always in megabytes
	if ( preg_match("/kilo/i", $system_info['free_partition_space']) ) {
	$raspi_free_space = $raspi_free_space / 1000;
	}
	elseif ( preg_match("/mega/i", $system_info['free_partition_space']) ) {
	$raspi_free_space = $raspi_free_space / 1;
	}
	elseif ( preg_match("/giga/i", $system_info['free_partition_space']) ) {
	$raspi_free_space = $raspi_free_space / 0.001;
	}
	elseif ( preg_match("/tera/i", $system_info['free_partition_space']) ) {
	$raspi_free_space = $raspi_free_space / 0.000001;
	}
	elseif ( preg_match("/peta/i", $system_info['free_partition_space']) ) {
	$raspi_free_space = $raspi_free_space / 0.000000001;
	}
	elseif ( preg_match("/exa/i", $system_info['free_partition_space']) ) {
	$raspi_free_space = $raspi_free_space / 0.000000000001;
	}
	elseif ( preg_match("/zetta/i", $system_info['free_partition_space']) ) {
	$raspi_free_space = $raspi_free_space / 0.000000000000001;
	}
	elseif ( preg_match("/yotta/i", $system_info['free_partition_space']) ) {
	$raspi_free_space = $raspi_free_space / 0.000000000000000001;
	}
    		
// Store system data to chart 
store_file_contents($base_dir . '/cache/charts/system/rasberry_pi.dat', time() . '||' . trim($raspi_load) . '||' . trim($raspi_temp) . '||' . trim($raspi_free_space) . "\n", "append");
    		
    		
}

		

// If debug mode is on
if ( $debug_mode == 'all' || $debug_mode == 'telemetry' || $debug_mode == 'stats' ) {
		
	foreach ( $system_info as $key => $value ) {
	$system_telemetry .= $key . ': ' . $value . '; ';
	}
			
// Log system stats
app_logging('other_debugging', 'Stats for hardware / software', $system_telemetry);
			
// Log runtime stats
app_logging('other_debugging', 'Stats for '.$runtime_mode.' runtime', $runtime_mode.'_runtime: ' . $total_runtime . ' seconds');

}


// Process debugging logs / destroy session data AFTER runtime stats
debugging_logs();
hardy_session_clearing();


?>