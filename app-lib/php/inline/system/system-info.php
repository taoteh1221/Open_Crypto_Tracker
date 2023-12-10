<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */

// ONLY RUN IF NOT FAST RUNTIMES
if ( !$is_fast_runtime ) {


// Fetch ALL system info
$ct['system_info'] = $ct['gen']->system_info(); // MUST RUN AFTER SETTING $ct['base_dir']
////
    			
$system_load_all = $ct['system_info']['system_load'];

// Use 15 minute average
$system_load = $system_load_all;
$system_load = preg_replace("/ \(15 min avg\)(.*)/i", "", $system_load);
$system_load = preg_replace("/(.*)\(5 min avg\) /i", "", $system_load); 
    		
$system_temp = preg_replace("/° Celsius/i", "", $ct['system_info']['system_temp']);

$system_free_space_mb = $ct['gen']->in_megabytes($ct['system_info']['free_partition_space'])['in_megs'];

$portfolio_cache_size_mb = $ct['gen']->in_megabytes($ct['system_info']['portfolio_cache'])['in_megs'];

$system_memory_total_mb = $ct['gen']->in_megabytes($ct['system_info']['memory_total'])['in_megs'];
    		
$system_memory_free_mb = $ct['gen']->in_megabytes($ct['system_info']['memory_free'])['in_megs'];
    		
    		
    // Percent difference (!MUST BE! absolute value)
    if ( ( abs($system_memory_total_mb) * 100 ) > 0 ) {
    $memory_percent_free = abs( ($system_memory_free_mb - $system_memory_total_mb) / abs($system_memory_total_mb) * 100 );
    $memory_percent_free = round( 100 - $memory_percent_free, 2);
    }
    else {
    $memory_percent_free = null;
    }
    		
    		
$system_load_redline = ( $ct['system_info']['cpu_threads'] > 1 ? ($ct['system_info']['cpu_threads'] * 2) : 2 );


// Interface alert messages (UI / email / etc), and error logging


$system_uptime_warning = explode('||', $ct['conf']['power']['system_uptime_warning']);


     if ( substr($ct['system_info']['uptime'], 0, 6) == $system_uptime_warning[0] . ' days' ) {
     $ct['system_warnings']['uptime'] = 'Low uptime (' . $ct['system_info']['uptime'] . ')';
     $ct['system_warnings_cron_interval']['uptime'] = $system_uptime_warning[1];
     }
     	
     	
$system_load_warning = explode('||', $ct['conf']['power']['system_load_warning']);
     

     if ( $system_load > ($system_load_redline * $system_load_warning[0]) ) {
     $ct['system_warnings']['system_load'] = 'High 15 minute CPU load [' . $system_load_all . ']';
     $ct['system_warnings_cron_interval']['system_load'] = $system_load_warning[1];
     }
     
     	
$system_temperature_warning = explode('||', $ct['conf']['power']['system_temperature_warning']);
     

     if ( $system_temp >= $system_temperature_warning[0] ) {
     $ct['system_warnings']['system_temp'] = 'High temperature (' . $system_temp . ' degrees celcius)';
     $ct['system_warnings_cron_interval']['system_temp'] = $system_temperature_warning[1];
     }

	
$memory_used_percent_warning = explode('||', $ct['conf']['power']['memory_used_percent_warning']);


     if ( $ct['system_info']['memory_used_percent'] >= $memory_used_percent_warning[0] ) {
     $ct['system_warnings']['memory_used_percent'] = 'High memory usage (' . $ct['system_info']['memory_used_percent'] . ' percent used)';
     $ct['system_warnings_cron_interval']['memory_used_percent'] = $memory_used_percent_warning[1];
     }

	
$free_partition_space_warning = explode('||', $ct['conf']['power']['free_partition_space_warning']);


     if ( $system_free_space_mb <= $free_partition_space_warning[0] ) {
     $ct['system_warnings']['free_partition_space'] = 'High disk storage usage (only ' . $ct['var']->num_pretty($system_free_space_mb, 1) . ' megabytes free space left)';
     $ct['system_warnings_cron_interval']['free_partition_space'] = $free_partition_space_warning[1];
     }

	
$portfolio_cache_warning = explode('||', $ct['conf']['power']['portfolio_cache_warning']);


     if ( $portfolio_cache_size_mb >= $portfolio_cache_warning[0] ) {
     $ct['system_warnings']['portfolio_cache_size'] = 'High app cache disk storage usage (' . $ct['var']->num_pretty($portfolio_cache_size_mb, 1) . ' megabytes in app cache)';
     $ct['system_warnings_cron_interval']['portfolio_cache_size'] = $portfolio_cache_warning[1];
     }

	
$cookies_size_warning = explode('||', $ct['conf']['power']['cookies_size_warning']);


     if ( $ct['system_info']['portfolio_cookies'] >= $cookies_size_warning[0] ) {
     $ct['system_warnings']['portfolio_cookies_size'] = 'High app cookie storage usage (' . $ct['var']->num_pretty( ($ct['system_info']['portfolio_cookies'] / 1000) , 2) . ' kilobytes in app cookies), try UNSELECTING a few coins / news feeds / price charts, OR delete ALL browser cookies if the app crashes with a "header too large" error';
     $ct['system_warnings_cron_interval']['portfolio_cookies_size'] = $cookies_size_warning[1];
     }


}
else {
$ct['system_info'] = array(); // BLANK if fast runtimes
}


// DON'T CREATE ANY WHITESPACE AFTER CLOSING PHP TAG, A WE ARE STILL IN INIT! (NO HEADER ESTABLISHED YET)

?>