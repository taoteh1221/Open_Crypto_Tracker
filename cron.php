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
foreach ( $app_config['charts_alerts']['tracked_markets'] as $key => $value ) {
	
// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
$asset = ( stristr($key, "-") == false ? $key : substr( $key, 0, mb_strpos($key, "-", 0, 'utf-8') ) );
$asset = strtoupper($asset);

$value = explode("||",$value); // Convert $value into an array

$exchange = $value[0];
$pairing = $value[1];
$mode = $value[2];

charts_and_price_alerts($key, $exchange, $pairing, $mode);

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
// RUN BEFORE cron plugins (in case custom plugin crashes)
error_logs();
send_notifications();



// Calculate script runtime length
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$total_runtime = round( ($time - $start_runtime) , 3);



// SYSTEM STATS START
// System stats, chart the 15 min load avg / temperature / free partition space / free memory [mb/percent] / portfolio cache size / runtime length
// RUN BEFORE cron plugins (in case custom plugin crashes)
    			
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
	

         
// Store system data to archival / lite charts
$system_stats_path = $base_dir . '/cache/charts/system/archival/system_stats.dat';
$system_stats_data = time() . $chart_data_set . "\n";

store_file_contents($system_stats_path, $system_stats_data, "append");
    		
// Lite charts (update time dynamically determined in update_lite_chart() logic)
// Try to assure file locking from archival chart updating has been released, wait 0.12 seconds before updating lite charts
usleep(120000); // Wait 0.12 seconds
		
foreach ( $app_config['power_user']['lite_chart_day_intervals'] as $light_chart_days ) {
update_lite_chart($system_stats_path, $system_stats_data, $light_chart_days);
}
		
		
// SYSTEM STATS END

		

// If debug mode is on
// RUN BEFORE cron plugins (in case custom plugin crashes)
if ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'telemetry' || $app_config['developer']['debug_mode'] == 'stats' ) {
		
	foreach ( $system_info as $key => $value ) {
	$system_telemetry .= $key . ': ' . $value . '; ';
	}
			
// Log system stats
app_logging('system_debugging', 'Hardware / software stats (requires log_verbosity set to verbose)', $system_telemetry);
			
// Log runtime stats
app_logging('system_debugging', strtoupper($runtime_mode).' runtime was ' . $total_runtime . ' seconds');

}



// Process debugging logs 
// RUN BEFORE cron plugins (in case custom plugin crashes)
debugging_logs();



// Run any cron plugins activated in app_config
if ( sizeof($app_config['power_user']['activate_cron_plugins']) > 0 ) {

	foreach ( $app_config['power_user']['activate_cron_plugins'] as $cron_plugin ) {
	$cron_plugin = trim($cron_plugin);
	$cron_plugin_file = $base_dir . '/cron-plugins/' . $cron_plugin . '/' . $cron_plugin . '.php';
	
		if ( file_exists($cron_plugin_file) ) {
		require_once($cron_plugin_file);
		}
	
	}

// Run again after cron plugins
error_logs();
debugging_logs();
send_notifications();

}



?>