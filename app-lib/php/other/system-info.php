<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


$system_info = $ct_gen->system_info(); // MUST RUN AFTER SETTING $base_dir
    			
$system_load = $system_info['system_load'];
$system_load = preg_replace("/ \(15 min avg\)(.*)/i", "", $system_load);
$system_load = preg_replace("/(.*)\(5 min avg\) /i", "", $system_load); // Use 15 minute average
    		
$system_temp = preg_replace("/° Celsius/i", "", $system_info['system_temp']);

$system_free_space_mb = $ct_gen->in_megabytes($system_info['free_partition_space'])['in_megs'];

$portfolio_cache_size_mb = $ct_gen->in_megabytes($system_info['portfolio_cache'])['in_megs'];

$system_memory_total_mb = $ct_gen->in_megabytes($system_info['memory_total'])['in_megs'];
    		
$system_memory_free_mb = $ct_gen->in_megabytes($system_info['memory_free'])['in_megs'];
    		
// Percent difference (!MUST BE! absolute value)
$memory_percent_free = abs( ($system_memory_free_mb - $system_memory_total_mb) / abs($system_memory_total_mb) * 100 );
$memory_percent_free = round( 100 - $memory_percent_free, 2);
    		
$system_load_redline = ( $system_info['cpu_threads'] > 1 ? ($system_info['cpu_threads'] * 2) : 2 );


// Interface alert messages (UI / email / etc)
if ( substr($system_info['uptime'], 0, 6) == '0 days' ) {
$system_alerts['uptime'] = 'Low uptime';
$system_alerts_cron_interval['uptime'] = 12; // 12 hours
}
	
	
if ( $system_load > $system_load_redline ) {
$system_alerts['system_load'] = 'High CPU load';
$system_alerts_cron_interval['system_load'] = 4; // 4 hours
}

	
if ( $system_temp > 79 ) {
$system_alerts['system_temp'] = 'High temperature';
$system_alerts_cron_interval['system_temp'] = 1; // 1 hours
}

	
if ( $system_info['memory_used_percent'] > 89 ) {
$system_alerts['memory_used_megabytes'] = 'High memory usage';
$system_alerts_cron_interval['memory_used_megabytes'] = 4; // 4 hours
}

	
if ( $system_free_space_mb < 500 ) {
$system_alerts['free_partition_space'] = 'High disk storage usage';
$system_alerts_cron_interval['free_partition_space'] = 4; // 4 hours
}

	
if ( $portfolio_cache_size_mb > 10000 ) {
$system_alerts['portfolio_cache'] = 'High app cache disk storage usage';
$system_alerts_cron_interval['portfolio_cache'] = 72; // 72 hours
}


?>