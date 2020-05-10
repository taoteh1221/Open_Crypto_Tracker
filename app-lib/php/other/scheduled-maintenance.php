<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// Scheduled maintenance (run every ~3 hours if NOT cron runtime, OR if runtime is cron every ~1 hours)
//////////////////////////////////////////////////////////////////
if ( $runtime_mode != 'cron' && update_cache_file($base_dir . '/cache/events/scheduled-maintenance.dat', (60 * 3) ) == true 
|| $runtime_mode == 'cron' && update_cache_file($base_dir . '/cache/events/scheduled-maintenance.dat', (60 * 1) ) == true  ) {
//////////////////////////////////////////////////////////////////



	////////////////////////////////////////////////////////////
	// Maintenance to run only if cron is setup and running
	////////////////////////////////////////////////////////////
	if ( $runtime_mode == 'cron' ) {
	
		// Chart backups...run before any price checks to avoid any potential file lock issues
		if ( $app_config['general']['charts_toggle'] == 'on' && $app_config['charts_alerts']['charts_backup_freq'] > 0 ) {
		backup_archive('charts-data', $base_dir . '/cache/charts/', $app_config['charts_alerts']['charts_backup_freq']); // No $backup_archive_password extra param here (waste of time / energy to encrypt charts data backups)
		}


	
		////////////////////////////////////////////////////////////
	   // Re-check the average time interval between chart data points
	   // If we just started collecting data, check frequently
	   // (placeholder is always set to 1 to keep chart buttons from acting weird until we have enough data)
		////////////////////////////////////////////////////////////
	   if ( $app_config['general']['charts_toggle'] == 'on' || !is_numeric(trim(file_get_contents($base_dir . '/cache/vars/chart_interval.dat'))) || trim(file_get_contents($base_dir . '/cache/vars/chart_interval.dat')) == 1 ) {
			
			foreach ( $app_config['charts_alerts']['tracked_markets'] as $key => $value ) {
			
				if ( trim($find_first_filename) == '' ) {
					
				// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
				$find_first_asset = ( stristr($key, "-") == false ? $key : substr( $key, 0, mb_strpos($key, "-", 0, 'utf-8') ) );
				$find_first_asset = strtoupper($find_first_asset);
			
				$find_first_chart = explode("||", $value);
		
					if ( $find_first_chart[2] == 'both' || $find_first_chart[2] == 'chart' ) {
					$find_first_filename = $base_dir . '/cache/charts/spot_price_24hr_volume/archival/'.$find_first_asset.'/'.$key.'_chart_'.$find_first_chart[1].'.dat';
					}
		
				}
				
			}
		
		// Dynamically determine average time interval with the last 500 lines (or max available if less), presume an average max characters length of ~40
		$charts_update_freq = chart_time_interval($find_first_filename, 500, 40);
		
		store_file_contents($base_dir . '/cache/vars/chart_interval.dat', $charts_update_freq);
		
	   }
   
	
	}
	////////////////////////////////////////////////////////////
	// END cron-only maintenance routines
	////////////////////////////////////////////////////////////
	

// Upgrade check
require($base_dir . '/app-lib/php/other/upgrade-check.php');


// Update cached vars...

// Current default primary currency stored to flat file (for checking if we need to reconfigure things for a changed value here)
store_file_contents($base_dir . '/cache/vars/default_btc_primary_currency_pairing.dat', $default_btc_primary_currency_pairing);
	

// Current app version stored to flat file (for the bash auto-install/upgrade script to easily determine the currently-installed version)
store_file_contents($base_dir . '/cache/vars/app_version.dat', $app_version);


// Determine / store portfolio cache size
store_file_contents($base_dir . '/cache/vars/cache_size.dat', convert_bytes( directory_size($base_dir . '/cache/') , 3) );


// Cache files cleanup...

// Delete ANY old zip archive backups scheduled to be purged
delete_old_files($base_dir . '/cache/secured/backups', $app_config['general']['backup_archive_delete_old'], 'zip');


// Stale cache files cleanup...

delete_old_files($base_dir . '/cache/events/throttling', 1, 'dat'); // Delete throttling cache files older than 1 day

delete_old_files($base_dir . '/cache/secured/activation', 1, 'dat'); // Delete activation cache files older than 1 day

delete_old_files($base_dir . '/cache/secured/external_api', 1, 'dat'); // Delete external API cache files older than 1 day

delete_old_files($base_dir . '/internal-api', 1, 'dat'); // Delete internal API cache files older than 1 day


// Secondary logs cleanup
$logs_cache_cleanup = array(
									$base_dir . '/cache/logs/debugging/external_api',
									$base_dir . '/cache/logs/errors/external_api',
									);
									
delete_old_files($logs_cache_cleanup, $app_config['developer']['log_purge'], 'dat'); // Delete LOGS API cache files older than $app_config['developer']['log_purge'] day(s)


// Update the maintenance event tracking
store_file_contents($base_dir . '/cache/events/scheduled-maintenance.dat', time_date_format(false, 'pretty_date_time') );


}
//////////////////////////////////////////////////////////////////
// END SCHEDULED MAINTENANCE
//////////////////////////////////////////////////////////////////

 
 ?>