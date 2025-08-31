<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */

// ONLY RUN IF NOT FAST RUNTIMES
if ( !$ct['fast_runtime'] ) {

// NEVER USE REQUIRE ONCE IN THIS FILE!
// (OVERWRITES / RESETS CLEANLY, EVEN IF RELOADING AFTER A CONFIG RESET / USER UPDATE)
// Interface alert messages (UI / email / etc), and error logging

$system_uptime_warning = array_map( "trim", explode('||', $ct['conf']['power']['system_uptime_warning']) ); 


     if ( substr($ct['system_info']['uptime'], 0, 6) == $system_uptime_warning[0] . ' days' ) {
     $ct['system_warnings']['uptime'] = 'Low uptime (' . $ct['system_info']['uptime'] . ')';
     $ct['system_warnings_cron_interval']['uptime'] = $system_uptime_warning[1];
     }
     	
     	
$system_load_warning = array_map( "trim", explode('||', $ct['conf']['power']['system_load_warning']) ); 


     if ( $system_load > ($system_load_redline * $system_load_warning[0]) ) {
     $ct['system_warnings']['system_load'] = 'High 15 minute CPU load [' . $system_load_all . ']';
     $ct['system_warnings_cron_interval']['system_load'] = $system_load_warning[1];
     }
     
     	
$system_temperature_warning = array_map( "trim", explode('||', $ct['conf']['power']['system_temperature_warning']) ); 
     

     if ( $system_temp >= $system_temperature_warning[0] ) {
     $ct['system_warnings']['system_temp'] = 'High temperature (' . $system_temp . ' degrees celcius)';
     $ct['system_warnings_cron_interval']['system_temp'] = $system_temperature_warning[1];
     }

	
$memory_used_percent_warning = array_map( "trim", explode('||', $ct['conf']['power']['memory_used_percent_warning']) ); 


     if ( $ct['system_info']['memory_used_percent'] >= $memory_used_percent_warning[0] ) {
     $ct['system_warnings']['memory_used_percent'] = 'High memory usage (' . $ct['system_info']['memory_used_percent'] . ' percent used)';
     $ct['system_warnings_cron_interval']['memory_used_percent'] = $memory_used_percent_warning[1];
     }

	
$free_partition_space_warning = array_map( "trim", explode('||', $ct['conf']['power']['free_partition_space_warning']) ); 


     if ( $system_free_space_mb <= $free_partition_space_warning[0] ) {
     $ct['system_warnings']['free_partition_space'] = 'High disk storage usage (only ' . $ct['var']->num_pretty($system_free_space_mb, 1) . ' megabytes free space left)';
     $ct['system_warnings_cron_interval']['free_partition_space'] = $free_partition_space_warning[1];
     }

	
$portfolio_cache_warning = array_map( "trim", explode('||', $ct['conf']['power']['portfolio_cache_warning']) ); 


     if ( $portfolio_cache_size_mb >= $portfolio_cache_warning[0] ) {
     $ct['system_warnings']['portfolio_cache_size'] = 'High app cache disk storage usage (' . $ct['var']->num_pretty($portfolio_cache_size_mb, 1) . ' megabytes in app cache)';
     $ct['system_warnings_cron_interval']['portfolio_cache_size'] = $portfolio_cache_warning[1];
     }

	
$cookies_size_warning = array_map( "trim", explode('||', $ct['conf']['power']['cookies_size_warning']) ); 


     if ( $ct['system_info']['portfolio_cookies'] >= $cookies_size_warning[0] ) {
     $ct['system_warnings']['portfolio_cookies_size'] = 'High app cookie storage usage (' . $ct['var']->num_pretty( ($ct['system_info']['portfolio_cookies'] / 1000) , 2) . ' kilobytes in app cookies), try UNSELECTING a few coins / news feeds / price charts, OR delete ALL browser cookies if the app crashes with a "header too large" error';
     $ct['system_warnings_cron_interval']['portfolio_cookies_size'] = $cookies_size_warning[1];
     }


}


// DON'T CREATE ANY WHITESPACE AFTER CLOSING PHP TAG, A WE ARE STILL IN INIT! (NO HEADER ESTABLISHED YET)

?>