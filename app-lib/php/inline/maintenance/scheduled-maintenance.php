<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


//////////////////////////////////////////////////////////////////
// Scheduled maintenance (run every ~2 hours if NOT cron runtime, OR if runtime is cron every ~30 minutes)
//////////////////////////////////////////////////////////////////
if ( $ct['runtime_mode'] != 'cron' && $ct['cache']->update_cache($ct['base_dir'] . '/cache/events/scheduled-maintenance.dat', 120) == true 
|| $ct['runtime_mode'] == 'cron' && $ct['cache']->update_cache($ct['base_dir'] . '/cache/events/scheduled-maintenance.dat', 30) == true  ) {
//////////////////////////////////////////////////////////////////


	////////////////////////////////////////////////////////////
	// Maintenance to run only if cron is setup and running
	////////////////////////////////////////////////////////////
	if ( $ct['runtime_mode'] == 'cron' ) {
	
	// User (cached) config backups (done once per day, encrypted with backup password)...
	$ct['cache']->backup_archive('config-data', $ct['cached_conf_path'], 1, $ct['conf']['sec']['backup_archive_password']);
		
		
		// Chart backups...run before any price checks to avoid any potential file lock issues
		if ( $ct['conf']['charts_alerts']['enable_price_charts'] == 'on' && $ct['conf']['power']['backup_archive_frequency'] > 0 ) {
		$ct['cache']->backup_archive('charts-data', $ct['base_dir'] . '/cache/charts/', $ct['conf']['power']['backup_archive_frequency']); // No $backup_archive_password extra param here (waste of time / energy to encrypt charts data backups)
		}
    
    
         	// If coinmarketcap API key is added, re-cache data for faster UI runtimes later
         	if ( trim($ct['conf']['ext_apis']['coinmarketcap_api_key']) != '' ) {
         	$ct['coinmarketcap_api'] = $ct['api']->mcap_data_coinmarketcap();
         	}
    	 
    
     // Re-cache marketcap data for faster UI runtimes later
     $ct['coingecko_api'] = $ct['api']->mcap_data_coingecko();
    	 
    	 
     // Re-cache chain data for faster UI runtimes later
    
     // Bitcoin
     $ct['api']->bitcoin('getblockcount');
     $ct['api']->bitcoin('getdifficulty');
    
     // Ethereum
     //$ct['api']->etherscan('gasLimit');
    
     // Chain data END
     
     // Cleanup price charts (delete charts for removed assets)
     $ct['cache']->price_chart_cleanup();
   
	}
	////////////////////////////////////////////////////////////
	// END cron-only maintenance routines
	////////////////////////////////////////////////////////////
	

// Upgrade check
require($ct['base_dir'] . '/app-lib/php/inline/maintenance/upgrade-check.php');


// Update cached vars...

// Determine / store portfolio cache size
$ct['cache']->save_file($ct['base_dir'] . '/cache/vars/cache_size.dat', $ct['gen']->conv_bytes( $ct['gen']->dir_size($ct['base_dir'] . '/cache/') , 3) );
     
     
// Cache files cleanup...

// Cleanup access stats (prune entries older than X days)
$ct['cache']->prune_access_stats();


// Delete ANY old zip archive backups scheduled to be purged
// ONLY .zip FILES
$ct['cache']->delete_old_files($ct['base_dir'] . '/cache/secured/backups', $ct['conf']['power']['backup_archive_delete_old'], 'zip');


// Stale cache files deletion...

/////////////////////////
// Variable days
/////////////////////////

// Delete OLD visitor stats event tracking cache files
// ONLY .dat FILES
$ct['cache']->delete_old_files($ct['base_dir'] . '/cache/secured/access_stats', $ct['conf']['power']['access_stats_delete_old'], 'dat'); 

/////////////////////////
// Every 30 days
/////////////////////////

// Delete OLD external API cache files
$ct['cache']->delete_old_files($ct['base_dir'] . '/cache/secured/external_data', 30, 'dat'); // ONLY .dat FILES

// Delete OLD external API cookie files
$ct['cache']->delete_old_files($ct['base_dir'] . '/cache/secured/external_data/cookies', 30, 'dat'); // ONLY .dat FILES

/////////////////////////
// Every 7 days
/////////////////////////

// Delete OLD light chart rebuild event tracking cache files
$ct['cache']->delete_old_files($ct['base_dir'] . '/cache/events/light_chart_rebuilds', 7); 

// Delete OLD market error tracking cache files
$ct['cache']->delete_old_files($ct['base_dir'] . '/cache/events/market_error_tracking', 7); 

// Delete OLD cached invalid XML response files
$ct['cache']->delete_old_files($ct['base_dir'] . '/cache/other/xml_error_parsing', 7); 

/////////////////////////
// Every day
/////////////////////////

// Delete OLD UNSENT message queue files
$ct['cache']->delete_old_files($ct['base_dir'] . '/cache/secured/messages', 1, 'queue'); // ONLY .queue FILES

// Delete OLD recent search cache files
$ct['cache']->delete_old_files($ct['base_dir'] . '/cache/secured/other_data', 1, 'dat'); // ONLY .dat FILES

// Delete OLD activation cache files
$ct['cache']->delete_old_files($ct['base_dir'] . '/cache/secured/activation', 1, 'dat'); // ONLY .dat FILES

// Delete OLD throttling event tracking cache files
$ct['cache']->delete_old_files($ct['base_dir'] . '/cache/events/throttling', 1); 

// Delete OLD internal API cache files
$ct['cache']->delete_old_files($ct['base_dir'] . '/cache/internal_api', 1); 

// Delete OLD alert cache files
$ct['cache']->delete_old_files($ct['base_dir'] . '/cache/alerts', 1); 


// Secondary logs cleanup
$logs_cache_cleanup = array(
					   $ct['base_dir'] . '/cache/logs/debug/external_data',
					   $ct['base_dir'] . '/cache/logs/error/external_data',
					  );
////								
// Purge app LOG cache files older than $ct['conf']['power']['logs_purge'] day(s)
$ct['cache']->delete_old_files($logs_cache_cleanup, $ct['conf']['power']['logs_purge']); 


    // Purge any excessive logging in the PHPdesktop version every 48 hours
    if ( $ct['app_container'] == 'phpdesktop' && $ct['cache']->update_cache($ct['base_dir'] . '/cache/events/phpdesktop-logs-purge.dat', 2880) == true ) {
    @unlink($ct['base_dir'] . '/../temp-other/php_errors.log');
    @unlink($ct['base_dir'] . '/../temp-other/debugging.log');
    @unlink($ct['base_dir'] . '/../temp-other/phpdesktop.log');
    $ct['cache']->save_file($ct['base_dir'] . '/cache/events/phpdesktop-logs-purge.dat', $ct['gen']->time_date_format(false, 'pretty_date_time') );
    }
    

// Update the maintenance event tracking
$ct['cache']->save_file($ct['base_dir'] . '/cache/events/scheduled-maintenance.dat', $ct['gen']->time_date_format(false, 'pretty_date_time') );

}
//////////////////////////////////////////////////////////////////
// END SCHEDULED MAINTENANCE
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>