<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
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

foreach ( $pt_conf['charts_alerts']['tracked_markets'] as $key => $val ) {

$val = explode("||",$val); // Convert $val into an array

$exchange = $val[0];
$pairing = $val[1];
$mode = $val[2];

// ALWAYS RUN for UX, even if $mode != 'none', 
// as charts_price_alerts() is optimized for this so it can run some UX logic
// (such as as removing STALE EXISTING ALERT CACHE FILES THAT WERE PREVIOUSLY-ENABLED,
// THEN USER-DISABLED...IN CASE USER RE-ENABLES, THE ALERT STATS / ETC REMAIN UP-TO-DATE)
$pt_asset->charts_price_alerts($key, $exchange, $pairing, $mode);

}



// Checkup on each failed proxy
if ( $pt_conf['comms']['proxy_alert'] != 'off' ) {
	
	foreach ( $proxy_checkup as $problem_proxy ) {
	$pt_gen->test_proxy($problem_proxy);
	sleep(1);
	}

}



// Queue notifications if there were any price alert resets, BEFORE $pt_cache->send_notifications() runs
$pt_gen->reset_price_alert_notice();



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

$system_free_space_mb = $pt_gen->in_megabytes($system_info['free_partition_space'])['in_megs'];
         
$portfolio_cache_size_mb = $pt_gen->in_megabytes($system_info['portfolio_cache'])['in_megs'];


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

$pt_cache->save_file($system_stats_path, $system_stats_data . "\n", "append", false); // WITH newline (UNLOCKED file write)
    		
// Lite charts (update time dynamically determined in $pt_cache->update_lite_chart() logic)
// Try to assure file locking from archival chart updating has been released, wait 0.12 seconds before updating lite charts
usleep(120000); // Wait 0.12 seconds
		
	foreach ( $pt_conf['power']['lite_chart_day_intervals'] as $light_chart_days ) {
	$pt_cache->update_lite_chart($system_stats_path, $system_stats_data, $light_chart_days); // WITHOUT newline (var passing)
	}
		
}
else {
	
$pt_gen->log(
							'system_error',
							'time() returned a corrupt value (from power outage / corrupt memory / etc), chart updating canceled',
							'chart_type: system stats'
							);

}
		
// SYSTEM STATS END

		

// If debug mode is on
// RUN BEFORE plugins (in case custom plugin crashes)
if ( $pt_conf['dev']['debug'] == 'all' || $pt_conf['dev']['debug'] == 'all_telemetry' || $pt_conf['dev']['debug'] == 'stats' ) {
		
	foreach ( $system_info as $key => $val ) {
	$system_telemetry .= $key . ': ' . $val . '; ';
	}
			
// Log system stats
$pt_gen->log(
							'system_debug',
							'Hardware / software stats (requires log_verbosity set to verbose)',
							$system_telemetry
							);
			
// Log runtime stats
$pt_gen->log(
							'system_debug',
							strtoupper($runtime_mode).' runtime was ' . $total_runtime . ' seconds'
							);

}



// Log errors / debugging, send notifications
// RUN BEFORE any activated plugins (in case a custom plugin crashes)
$pt_cache->error_logs();
$pt_cache->debug_logs();
$pt_cache->send_notifications();


// If any plugins are activated, RESET $logs_array for plugin logging, SO WE DON'T GET DUPLICATE LOGGING
if ( sizeof($activated_plugins['cron']) > 0 ) {
$logs_array = array();
}


// Run any cron-designated plugins activated in pt_conf
// ALWAYS KEEP PLUGIN RUNTIME LOGIC INLINE (NOT ISOLATED WITHIN A FUNCTION), 
// SO WE DON'T NEED TO WORRY ABOUT IMPORTING GLOBALS!
foreach ( $activated_plugins['cron'] as $plugin_key => $plugin_val ) {
	
	if ( file_exists($plugin_val) ) {
		
	$this_plug = $plugin_key;
	
	// This plugin's config (from the global app config)
	$plug_conf[$this_plug] = $pt_conf['plug_conf'][$this_plug]; 
	
		// This plugin's default class (only if the file exists)
		if ( file_exists($base_dir . '/plugins/'.$this_plug.'/plug-lib/plug-class.php') ) {
      include($base_dir . '/plugins/'.$this_plug.'/plug-lib/plug-class.php');
		}
	
	// This plugin's plug-init.php file (runs the plugin)
	include($plugin_val);
	
	// Reset $this_plug at end of loop
	$this_plug = null; 
	
	}
	
}


// Log errors / debugging, send notifications
// (IF ANY PLUGINS ARE ACTIVATED, RAN AGAIN SEPERATELY FOR PLUGIN LOGGING / ALERTS ONLY)
if ( sizeof($activated_plugins['cron']) > 0 ) {
$pt_cache->error_logs();
$pt_cache->debug_logs();
$pt_cache->send_notifications();
}


gc_collect_cycles(); // Clean memory cache


?>