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


// Load app config / etc
require("config.php");


// Charts and price alerts
foreach ( $app_config['charts_and_price_alerts'] as $key => $value ) {
	
// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
$asset = ( stristr($key, "-") == false ? $key : substr( $key, 0, mb_strpos($key, "-", 0, 'utf-8') ) );
$asset = strtoupper($asset);

$value = explode("||",$value); // Convert $value into an array

$exchange = $value[0];
$pairing = $value[1];
$mode = $value[2];
	
	
$result = charts_and_price_alerts($key, $exchange, $pairing, $mode);

	if ( $result != true ) {
	app_logging('other_error', 'charts_and_price_alerts() update failure', $key . ' (' . $asset . ' / ' . strtoupper($pairing) . ' @ ' . $exchange . ')' );
	}

}



// Checkup on each failed proxy
if ( $app_config['proxy_alerts'] != 'off' ) {
	
	foreach ( $proxy_checkup as $problem_proxy ) {
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



// If hardware stats are enabled, chart the 15 min load avg / temperature / free partition space / free memory [mb/percent] / portfolio cache size / runtime length
if ( $app_config['system_stats'] == 'on' || $app_config['system_stats'] == 'raspi' && $is_raspi == 1 ) {
    			
// Raspi system data
$system_load = $system_info['system_load'];
$system_load = preg_replace("/ \(15 min avg\)(.*)/i", "", $system_load);
$system_load = preg_replace("/(.*)\(5 min avg\) /i", "", $system_load); // Use 15 minute average
    		
$system_temp = preg_replace("/° Celsius/i", "", $system_info['system_temp']);

$system_free_space_mb = in_megabytes($system_info['free_partition_space'])['in_megs'];
         
$portfolio_cache_size_mb = in_megabytes($system_info['portfolio_cache'])['in_megs'];
    		
$system_memory_total_mb = in_megabytes($system_info['memory_total'])['in_megs'];
    		
$system_memory_free_mb = in_megabytes($system_info['memory_free'])['in_megs'];
    		
    		
// Percent difference (!MUST BE! absolute value)
$memory_percent_free = abs( ($system_memory_free_mb - $system_memory_total_mb) / abs($system_memory_total_mb) * 100 );
$memory_percent_free = round( 100 - $memory_percent_free, 2);



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
	
	
	if ( trim($system_memory_free_mb) >= 0 ) {
	$chart_data_set .= '||' . round( trim($system_memory_free_mb) / 1000 , 4); // Gigabytes, for chart UX
	}
	else {
	$chart_data_set .= '||NO_DATA';
	}
	
	
	if ( trim($memory_percent_free) >= 0 ) {
	$chart_data_set .= '||' . trim($memory_percent_free);
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
	

         
// Store system data to chart 
store_file_contents($base_dir . '/cache/charts/system/archival/system_stats.dat', time() . $chart_data_set . "\n", "append");
    		
    		
}

		

// If debug mode is on
if ( $app_config['debug_mode'] == 'all' || $app_config['debug_mode'] == 'telemetry' || $app_config['debug_mode'] == 'stats' ) {
		
	foreach ( $system_info as $key => $value ) {
	$system_telemetry .= $key . ': ' . $value . '; ';
	}
			
// Log system stats
app_logging('system_debugging', 'Stats for hardware / software', $system_telemetry);
			
// Log runtime stats
app_logging('system_debugging', 'Stats for '.$runtime_mode.' runtime', $runtime_mode.'_runtime: ' . $total_runtime . ' seconds');

}


// Process debugging logs / destroy session data AFTER runtime stats
debugging_logs();
hardy_session_clearing();


?>