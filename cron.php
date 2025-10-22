<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// Forbid direct INTERNET access to this file, UNLESS IT'S EMULATED CRON IN THE DESKTOP EDITION
if ( !isset($_GET['cron_emulate']) && isset($_SERVER['REQUEST_METHOD']) && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']) ) {
header('HTTP/1.0 403 Forbidden', TRUE, 403);
exit;
}


// Make sure the cron job will always finish running completely
ignore_user_abort(true); 

// Assure CLI runtime is in install directory (server compatibility required for some PHP setups)
chdir( dirname(__FILE__) );

// Runtime mode
$runtime_mode = 'cron';

// Load app config / etc
require("app-lib/php/init.php");


//////////////////////////////////////////////
/// CRON LOGIC #START#
//////////////////////////////////////////////

  
// ONLY run cron if it is allowed
if ( $run_cron == true ) {
     

    if ( $ct['conf']['power']['debug_mode'] == 'cron_telemetry' ) {

    $cron_runtime_id = $ct['sec']->rand_hash(8);         
         
    // WITH newline (UNLOCKED file write)
    $ct['cache']->save_file($ct['base_dir'] . '/cache/logs/debug/cron/cron_runtime_telemetry.log', 'STARTED cron.php runtime (runtime_id = ' . $cron_runtime_id . ') on: ' . $ct['gen']->time_date_format(false, 'pretty_date_time') . ' (UTC) ' . "\n ........running........ \n", "append", false);     

    }
    
     
$cron_run_lock_file = $ct['base_dir'] . '/cache/events/cron-runtime-lock.dat';
    
    
    // If we find no file lock (OR if there is a stale file lock [OVER 'cron_max_exec_time' SECONDS OLD]), we can proceed
    // (we don't want Desktop Editions to run multiple cron runtimes at the same time, if they are also
    // viewing in a regular browser on localhost port 22345, OR duplicates on Server Edition from taking
    // very long to finish running on low power hardware)
    if ( $ct['cache']->update_cache($cron_run_lock_file, ceil($ct['dev']['cron_max_exec_time'] / 60) ) == true ) {
    
    // Re-save new file lock
    $ct['cache']->save_file($cron_run_lock_file, $ct['gen']->time_date_format(false, 'pretty_date_time') );
    
    
        // If we are running EMULATED cron, we track when to run it with /cache/events/emulated-cron.dat
        if ( isset($_GET['cron_emulate']) && $ct['conf']['power']['desktop_cron_interval'] > 0 ) {
            
            
            if ( $ct['cache']->update_cache($ct['base_dir'] . '/cache/events/emulated-cron.dat', $ct['conf']['power']['desktop_cron_interval']) == false ) {
            
            $exit_result = array('result' => "Too early to re-run EMULATED cron job");
            
            // Log errors / debugging, send notifications
            $ct['cache']->app_log();
            $ct['cache']->send_notifications();
            
            // We are done running cron, so we can release the lock
            unlink($cron_run_lock_file);
            
            echo json_encode($exit_result, JSON_PRETTY_PRINT);
            exit; // Force exit runtime now
        
            }
            else {
            // We run this EARLY in the cron logic, so we have fairly consistant emulated cron job intervals
            $ct['cache']->save_file($ct['base_dir'] . '/cache/events/emulated-cron.dat', $ct['gen']->time_date_format(false, 'pretty_date_time') );
            }
        
        
        }
      

        ///////////////////////////////////////////////////////////////////////////////////////
        // Only run below logic if cron has run for the first time already (for better new install UX)
        ///////////////////////////////////////////////////////////////////////////////////////
        if ( file_exists($ct['base_dir'] . '/cache/events/first_run/cron-first-run.dat') ) {
        
            
            // Only run if charts / alerts has run for the first time already (for better new install UX)
            // #MUST# BE ABOVE CHARTS / ALERTS LOGIC!
            if ( file_exists($ct['base_dir'] . '/cache/events/first_run/charts-first-run.dat') ) {
            
            	// Re-cache RSS feeds for faster UI runtimes later
            	foreach($ct['conf']['news']['feeds'] as $feed_item) {
            	    
            		if ( isset($feed_item["url"]) && trim($feed_item["url"]) != '' ) {
            	 	$ct['api']->rss($feed_item["url"], 'no_theme', 0, true);
            	 	}
            	 	
            	}
        	
            	// News feeds - new posts email
            	if ( $ct['conf']['news']['news_feed_email_frequency'] > 0 ) {
            	$ct['gen']->news_feed_email($ct['conf']['news']['news_feed_email_frequency']);
            	}
        	
            }
        
        
            // Charts and price alerts
            foreach ( $ct['conf']['charts_alerts']['tracked_markets'] as $val ) {
            
            $val = array_map( "trim", explode("||", $val) ); // Convert $val into an array
            
            $key = $val[0];
            
            $exchange = $val[1];

            $pair = $val[2];

            $mode = $val[3];
            
            // ALWAYS RUN even if $mode != 'none' etc, as charts_price_alerts() is optimized to run UX logic scanning
            // (such as as removing STALE EXISTING ALERT CACHE FILES THAT WERE PREVIOUSLY-ENABLED,
            // THEN USER-DISABLED...IN CASE USER RE-ENABLES, THE ALERT STATS / ETC REMAIN UP-TO-DATE)
            $ct['asset']->charts_price_alerts($key, $exchange, $pair, $mode);
            
            }
        
        
            // Flag if we have run the first alerts / charts job (for logic to improve speed of first time run of cron tasks, skipping uneeded pre-caching etc)
            if ( !file_exists($ct['base_dir'] . '/cache/events/first_run/charts-first-run.dat') ) {
            $ct['cache']->save_file($ct['base_dir'] . '/cache/events/first_run/charts-first-run.dat', $ct['gen']->time_date_format(false, 'pretty_date_time') );
            }
        
        
            // Checkup on each failed proxy
            if ( $ct['conf']['proxy']['proxy_alert_channels'] != 'off' ) {
            	
            	foreach ( $ct['proxy_checkup'] as $problem_proxy ) {
            	$ct['gen']->test_proxy($problem_proxy);
            	sleep(1);
            	}
            
            }
            
        
        // Queue notifications if there were any price alert resets, BEFORE $ct['cache']->send_notifications() runs
        $ct['gen']->reset_price_alert_notice();
        
        
        }
        ///////////////////////////////////////////////////////////////////////////////////////
        // END after first-run only
        ///////////////////////////////////////////////////////////////////////////////////////
        
        
        // Calculate script runtime length (BEFORE system stats logging)
        $time = microtime();
        $time = explode(' ', $time);
        $time = $time[1] + $time[0];
        $total_runtime = round( ($time - $start_runtime) , 3);
        
        
        // System stats, chart the 15 min load avg / temperature / free partition space / free memory [mb/percent] / portfolio cache size / runtime length
        // RUN BEFORE plugins (in case custom plugin crashes)
        
        if ( isset($ct['system_info']['system_load']) ) {
        $chart_data_set .= '||' . trim($system_load);
        }
        else {
        $chart_data_set .= '||NO_DATA';
        }
        
        
        if ( isset($ct['system_info']['system_temp']) ) {
        $chart_data_set .= '||' . trim($system_temp);
        }
        else {
        $chart_data_set .= '||NO_DATA';
        }
        
        
        if ( isset($ct['system_info']['memory_used_megabytes']) ) {
        $chart_data_set .= '||' . round( $ct['system_info']['memory_used_megabytes'] / 1000 , 4); // Gigabytes, for chart UX
        }
        else {
        $chart_data_set .= '||NO_DATA';
        }
        
        
        if ( isset($ct['system_info']['memory_used_percent']) ) {
        $chart_data_set .= '||' . $ct['system_info']['memory_used_percent'];
        }
        else {
        $chart_data_set .= '||NO_DATA';
        }
        
        
        if ( isset($ct['system_info']['free_partition_space']) ) {
        $chart_data_set .= '||' . round( trim($system_free_space_mb) / 1000000 , 4); // Terabytes, for chart stats UX
        }
        else {
        $chart_data_set .= '||NO_DATA';
        }
        
        
        if ( isset($ct['system_info']['portfolio_cache']) ) {
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
        	
        
        // In case a rare error occurred from power outage / corrupt memory / etc, we'll check the timestamp (in a non-resource-intensive way)
        // (#SEEMED# TO BE A REAL ISSUE ON A RASPI ZERO AFTER MULTIPLE POWER OUTAGES [ONE TIMESTAMP HAD PREPENDED CORRUPT DATA])
        $now = time();
        
        
        // (WE DON'T WANT TO STORE DATA WITH A CORRUPT TIMESTAMP)
        if ( $now > 0 ) {
        
        // Store system data to archival / light charts
        $sys_stats_path = $ct['base_dir'] . '/cache/charts/system/archival/system_stats.dat';
        $sys_stats_data = $now . $chart_data_set;
        
        $ct['cache']->save_file($sys_stats_path, $sys_stats_data . "\n", "append", false); // WITH newline (UNLOCKED file write)
            		
        // Light charts (update time dynamically determined in $ct['cache']->update_light_chart() logic)
        // Try to assure file locking from archival chart updating has been released, wait 0.12 seconds before updating light charts
        usleep(120000); // Wait 0.12 seconds
        		
        	foreach ( $ct['light_chart_day_intervals'] as $light_chart_days ) {
        	    
        	    // If we reset light charts, just skip the rest of this update session
        	    if ( $system_light_chart_result == 'reset' ) {
        	    continue;
        	    }
        	           
        	$system_light_chart_result = $ct['cache']->update_light_chart($sys_stats_path, $sys_stats_data, $light_chart_days); // WITHOUT newline (var passing)
        	
        	}
        		
        }
        else {
        	
        $ct['gen']->log(
        			'system_error',
        			'time() returned a corrupt value (from power outage / corrupt memory / etc), chart updating canceled',
        			'chart_type: system stats'
        			);
        
        }
        		
        // SYSTEM STATS END
        		
        
        // If debug mode is on
        // RUN BEFORE plugins (in case custom plugin crashes)
        if ( $ct['conf']['power']['debug_mode'] == 'stats' ) {
        		
        	foreach ( $ct['system_info'] as $key => $val ) {
        	$system_telemetry .= $key . ': ' . $val . '; ';
        	}
        			
        // Log system stats
        $ct['gen']->log(
        			'system_debug',
        			'Hardware / software stats (requires log_verbosity set to verbose)',
        			$system_telemetry
        			);
        			
        // Log runtime stats
        $ct['gen']->log(
        			'system_debug',
        			strtoupper($ct['runtime_mode']).' runtime was ' . $total_runtime . ' seconds'
        			);
        
        }
        
        
        // Log errors / debugging, send notifications
        // RUN BEFORE any activated plugins (in case a custom plugin crashes)
        $ct['cache']->app_log();
        $ct['cache']->send_notifications();
        
        
        // Give a bit of time for the "core runtime" error / debugging logs to 
        // close their file locks, before we append "plugin runtime" log data
        if ( is_array($plug['activated']['cron']) && sizeof($plug['activated']['cron']) > 0 ) {
        sleep(1); 
        }
        
        
        // DEBUGGING ONLY (checking logging capability)
        //$ct['cache']->check_log('cron.php:pre-plugin-runtime');
        
        
        // Run any cron-designated plugins activated in ct_conf
        // ALWAYS KEEP PLUGIN RUNTIME LOGIC INLINE (NOT ISOLATED WITHIN A FUNCTION), 
        // SO WE DON'T NEED TO WORRY ABOUT IMPORTING GLOBALS!
        foreach ( $plug['activated']['cron'] as $plugin_key => $plugin_init ) {
        		
        $this_plug = $plugin_key;
        	
        // This plugin's plug-init.php file (runs the plugin)
        include($plugin_init);
        	
        // Reset $this_plug at end of loop
        unset($this_plug); 
        
        }
        
        
        // DEBUGGING ONLY (checking logging capability)
        //$ct['cache']->check_log('cron.php:post-plugin-runtime');
        
        
        // Log errors / debugging, send notifications
        // (IF ANY PLUGINS ARE ACTIVATED, RAN AGAIN SEPERATELY FOR PLUGIN LOGGING / ALERTS ONLY)
        if ( is_array($plug['activated']['cron']) && sizeof($plug['activated']['cron']) > 0 ) {
        $ct['cache']->app_log();
        $ct['cache']->send_notifications();
        }
        
        
        // Flag if we have run the first cron job (for logic to improve speed of first time run of cron tasks, skipping uneeded pre-caching etc)
        if ( !file_exists($ct['base_dir'] . '/cache/events/first_run/cron-first-run.dat') ) {
        $ct['cache']->save_file($ct['base_dir'] . '/cache/events/first_run/cron-first-run.dat', $ct['gen']->time_date_format(false, 'pretty_date_time') );
        }
        
              
    $exit_result = array('result' => "Emulated cron job has finished running");
      
    // We are done running cron, so we can release the lock
    unlink($cron_run_lock_file);
    
    }
    else {
    
    $exit_result_text = 'another instance of cron is already running, skipping this additional instance';
    
    $ct['gen']->log('other_error', $exit_result_text);
    
    $ct['cache']->app_log();
    $ct['cache']->send_notifications();
    
    $exit_result = array('display_error' => 1, 'result' => $exit_result_text);
    
    }



// Access stats logging / etc
$ct['cache']->log_access_stats();
$ct['cache']->api_throttle_cache();

gc_collect_cycles(); // Clean memory cache
    
    
    // If emulated cron, show a result in json (for interface / console log)
    if ( isset($_GET['cron_emulate']) ) {
    echo json_encode($exit_result, JSON_PRETTY_PRINT);
    }

    if ( $ct['conf']['power']['debug_mode'] == 'cron_telemetry' ) {
    // WITH newline (UNLOCKED file write)
    $ct['cache']->save_file($ct['base_dir'] . '/cache/logs/debug/cron/cron_runtime_telemetry.log', 'FULLY COMPLETED cron.php runtime (runtime_id = ' . $cron_runtime_id . ') on: ' . $ct['gen']->time_date_format(false, 'pretty_date_time') . ' (UTC) ' . "\n\n\n\n", "append", false);     
    }


}
  

//////////////////////////////////////////////
/// CRON LOGIC #END#
//////////////////////////////////////////////


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>