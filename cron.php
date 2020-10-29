<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// Forbid direct INTERNET access to this file
if ( isset($_SERVER['REQUEST_METHOD']) && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']) ) {
header('HTTP/1.0 403 Forbidden', TRUE, 403);
exit;
}


// Calculate script runtime length
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start_runtime = $time;


// Runtime mode
$runtime_mode = 'cron';


// Assure CLI runtime is in install directory (server compatibility required for some PHP setups)
chdir( dirname(__FILE__) );


// Load app config / etc
require("config.php");


// Charts and price alerts
$_SESSION['lite_charts_updated'] = 0;

foreach ( $app_config['charts_alerts']['tracked_markets'] as $key => $value ) {
	
// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
$asset = ( stristr($key, "-") == false ? $key : substr( $key, 0, mb_strpos($key, "-", 0, 'utf-8') ) );
$asset = strtoupper($asset);

$value = explode("||",$value); // Convert $value into an array

$exchange = $value[0];
$pairing = $value[1];
$mode = $value[2];

	if ( $mode != 'none' ) {
	charts_and_price_alerts($key, $exchange, $pairing, $mode);
	}

}



// Checkup on each failed proxy
if ( $app_config['comms']['proxy_alerts'] != 'off' ) {
	
	foreach ( $proxy_checkup as $problem_proxy ) {
	test_proxy($problem_proxy);
	sleep(1);
	}

}



// Queue notifications if there were any price alert resets, BEFORE send_notifications() runs
reset_price_alerts_notice();


// Log errors, send notifications
// RUN BEFORE plugins (in case custom plugin crashes)
error_logs();
send_notifications();



// Calculate script runtime length
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$total_runtime = round( ($time - $start_runtime) , 3);



// SYSTEM STATS START
// System stats, chart the 15 min load avg / temperature / free partition space / free memory [mb/percent] / portfolio cache size / runtime length
// RUN BEFORE plugins (in case custom plugin crashes)
    			
// System data
$system_load = $system_info['system_load'];
$system_load = preg_replace("/ \(15 min avg\)(.*)/i", "", $system_load);
$system_load = preg_replace("/(.*)\(5 min avg\) /i", "", $system_load); // Use 15 minute average
    		
$system_temp = preg_replace("/° Celsius/i", "", $system_info['system_temp']);

$system_free_space_mb = in_megabytes($system_info['free_partition_space'])['in_megs'];
         
$portfolio_cache_size_mb = in_megabytes($system_info['portfolio_cache'])['in_megs'];


	if ( trim($system_load) >= 0 ) {
	$chart_data_set .= '||' . trim($system_load);
	}
	else {
	$chart_data_set .= '||NO_DATA';
	}
	
	
	if ( trim($system_temp) > 0 ) {
	$chart_data_set .= '||' . trim($system_temp);
	}
	else {
	$chart_data_set .= '||NO_DATA';
	}
	
	
	if ( $system_info['memory_used_megabytes'] >= 0 ) {
	$chart_data_set .= '||' . round( $system_info['memory_used_megabytes'] / 1000 , 4); // Gigabytes, for chart UX
	}
	else {
	$chart_data_set .= '||NO_DATA';
	}
	
	
	if ( $system_info['memory_used_percent'] >= 0 ) {
	$chart_data_set .= '||' . $system_info['memory_used_percent'];
	}
	else {
	$chart_data_set .= '||NO_DATA';
	}
	
	
	if ( trim($system_free_space_mb) >= 0 ) {
	$chart_data_set .= '||' . round( trim($system_free_space_mb) / 1000000 , 4); // Terabytes, for chart stats UX
	}
	else {
	$chart_data_set .= '||NO_DATA';
	}
	
	
	if ( trim($portfolio_cache_size_mb) >= 0 ) {
	$chart_data_set .= '||' . round( trim($portfolio_cache_size_mb) / 1000 , 4); // Gigabytes, for chart UX
	}
	else {
	$chart_data_set .= '||NO_DATA';
	}
	
	
	if ( trim($total_runtime) >= 0 ) {
	$chart_data_set .= '||' . trim($total_runtime);
	}
	else {
	$chart_data_set .= '||NO_DATA';
	}
	

// In case a rare error occured from power outage / corrupt memory / etc, we'll check the timestamp (in a non-resource-intensive way)
// (#SEEMED# TO BE A REAL ISSUE ON A RASPI ZERO AFTER MULTIPLE POWER OUTAGES [ONE TIMESTAMP HAD PREPENDED CORRUPT DATA])
$now = time();

// (WE DON'T WANT TO STORE DATA WITH A CORRUPT TIMESTAMP)
if ( $now > 0 ) {

// Store system data to archival / lite charts
$system_stats_path = $base_dir . '/cache/charts/system/archival/system_stats.dat';
$system_stats_data = $now . $chart_data_set;

store_file_contents($system_stats_path, $system_stats_data . "\n", "append", false); // WITH newline (UNLOCKED file write)
    		
// Lite charts (update time dynamically determined in update_lite_chart() logic)
// Try to assure file locking from archival chart updating has been released, wait 0.12 seconds before updating lite charts
usleep(120000); // Wait 0.12 seconds
		
	foreach ( $app_config['power_user']['lite_chart_day_intervals'] as $light_chart_days ) {
	update_lite_chart($system_stats_path, $system_stats_data, $light_chart_days); // WITHOUT newline (var passing)
	}
		
}
else {
app_logging('system_error', 'time() returned a corrupt value (from power outage / corrupt memory / etc), chart updating canceled', 'chart_type: system stats');
}
		
// SYSTEM STATS END

		

// If debug mode is on
// RUN BEFORE plugins (in case custom plugin crashes)
if ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'all_telemetry' || $app_config['developer']['debug_mode'] == 'stats' ) {
		
	foreach ( $system_info as $key => $value ) {
	$system_telemetry .= $key . ': ' . $value . '; ';
	}
			
// Log system stats
app_logging('system_debugging', 'Hardware / software stats (requires log_verbosity set to verbose)', $system_telemetry);
			
// Log runtime stats
app_logging('system_debugging', strtoupper($runtime_mode).' runtime was ' . $total_runtime . ' seconds');

}



// Process debugging logs 
// RUN BEFORE plugins (in case custom plugin crashes)
debugging_logs();



// Run any cron-designated plugins activated in app_config
foreach ( $plugin_apps['cron'] as $key => $value ) {
	
	if ( file_exists($value) ) {
		
	$this_plugin = $key;
	
	$plugin_config[$this_plugin] = $app_config['plugin_config'][$this_plugin]; // Import this plugin's config from the global app config
	
	require_once($value);
	
	$this_plugin = null; // Reset
	
	}
	
}

// Run again after plugins
error_logs();
debugging_logs();
send_notifications();


gc_collect_cycles(); // Clean memory cache


?>