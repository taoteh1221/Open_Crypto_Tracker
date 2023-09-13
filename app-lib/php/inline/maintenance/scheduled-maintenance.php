<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
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
	
	
		// Chart backups...run before any price checks to avoid any potential file lock issues
		if ( $ct['conf']['gen']['asset_charts_toggle'] == 'on' && $ct['conf']['gen']['charts_backup_freq'] > 0 ) {
		$ct['cache']->backup_archive('charts-data', $ct['base_dir'] . '/cache/charts/', $ct['conf']['gen']['charts_backup_freq']); // No $backup_arch_pass extra param here (waste of time / energy to encrypt charts data backups)
		}
    
    
         	// If coinmarketcap API key is added, re-cache data for faster UI runtimes later
         	if ( trim($ct['conf']['ext_apis']['coinmarketcap_key']) != null ) {
         	$coinmarketcap_api = $ct['api']->coinmarketcap();
         	}
    	 
    
     // Re-cache marketcap data for faster UI runtimes later
     $coingecko_api = $ct['api']->coingecko();
    	 
    	 
     // Re-cache chain data for faster UI runtimes later
    
     // Bitcoin
     $ct['api']->bitcoin('height');
     $ct['api']->bitcoin('difficulty');
    
     // Ethereum
     $ct['api']->etherscan('number');
     $ct['api']->etherscan('difficulty');
     $ct['api']->etherscan('gasLimit');
    
     // Hive
     $ct['api']->market('HIVE', 'bittrex', 'BTC-HIVE');
    
     // Chain data END
   
	}
	////////////////////////////////////////////////////////////
	// END cron-only maintenance routines
	////////////////////////////////////////////////////////////
	

// Upgrade check
require($ct['base_dir'] . '/app-lib/php/inline/maintenance/upgrade-check.php');


// Update cached vars...

// Current default primary currency stored to flat file (for checking if we need to reconfigure things for a changed value here)
$ct['cache']->save_file($ct['base_dir'] . '/cache/vars/default_btc_prim_currency_pair.dat', $default_btc_prim_currency_pair);
	

// Current app version stored to flat file (for the bash auto-install/upgrade script to easily determine the currently-installed version)
$ct['cache']->save_file($ct['base_dir'] . '/cache/vars/app_version.dat', $ct['app_version']);


// Determine / store portfolio cache size
$ct['cache']->save_file($ct['base_dir'] . '/cache/vars/cache_size.dat', $ct['gen']->conv_bytes( $ct['gen']->dir_size($ct['base_dir'] . '/cache/') , 3) );


// Cache files cleanup...

// Delete ANY old zip archive backups scheduled to be purged
$ct['cache']->delete_old_files($ct['base_dir'] . '/cache/secured/backups', $ct['conf']['power']['backup_arch_del_old'], 'zip');


// Stale cache files cleanup...

$ct['cache']->delete_old_files($ct['base_dir'] . '/cache/events/light_chart_rebuilds', 4, 'dat'); // Delete light chart rebuild event tracking cache files older than 4 days

$ct['cache']->delete_old_files($ct['base_dir'] . '/cache/secured/messages', 4, 'queue'); // Delete UNSENT message queue files older than 4 days

$ct['cache']->delete_old_files($ct['base_dir'] . '/cache/events/throttling', 1, 'dat'); // Delete throttling event tracking cache files older than 1 day

$ct['cache']->delete_old_files($ct['base_dir'] . '/cache/secured/activation', 1, 'dat'); // Delete activation cache files older than 1 day

$ct['cache']->delete_old_files($ct['base_dir'] . '/cache/secured/external_data', 1, 'dat'); // Delete external API cache files older than 1 day

$ct['cache']->delete_old_files($ct['base_dir'] . '/internal-api', 1, 'dat'); // Delete internal API cache files older than 1 day


// Secondary logs cleanup
$logs_cache_cleanup = array(
							$ct['base_dir'] . '/cache/logs/debug/external_data',
							$ct['base_dir'] . '/cache/logs/error/external_data',
							);
////								
$ct['cache']->delete_old_files($logs_cache_cleanup, $ct['conf']['power']['logs_purge'], 'log'); // Purge app LOG cache files older than $ct['conf']['power']['logs_purge'] day(s)


    // Purge any excessive logging in the PHPdesktop version every 48 hours
    if ( $ct['app_container'] == 'phpdesktop' && $ct['cache']->update_cache($ct['base_dir'] . '/cache/events/phpdesktop-logs-purge.dat', 2880) == true ) {
    @unlink($ct['base_dir'] . '/../temp-other/php_errors.log');
    @unlink($ct['base_dir'] . '/../temp-other/debugging.log');
    @unlink($ct['base_dir'] . '/../temp-other/phpdesktop.log');
    $ct['cache']->save_file($ct['base_dir'] . '/cache/events/phpdesktop-logs-purge.dat', $ct['gen']->time_date_format(false, 'pretty_date_time') );
    }
    
    
    // Get root CA certificates for PHPdesktop windows desktop edition if we haven't yet, as we need them...
    // (IF app container is PHPdesktop, it does NOT have CURL certs installed)
    
    $save_file = $ct['base_dir'] . '/cache/other/win_curl_cacert.pem';
    
    if ( $ct['app_platform'] == 'windows' && $ct['app_container'] == 'phpdesktop' && !file_exists($save_file) ) {
    
    $get_file = 'https://curl.se/ca/cacert.pem';
    

        if ( !copy($get_file, $save_file) ) {
         
        $ct['gen']->log(
               	'system_error',
               	'Error copying file "' . $get_file . '" into "' . $save_file . '"'
               	);
               				
        }

    }


// Update the maintenance event tracking
$ct['cache']->save_file($ct['base_dir'] . '/cache/events/scheduled-maintenance.dat', $ct['gen']->time_date_format(false, 'pretty_date_time') );

}
//////////////////////////////////////////////////////////////////
// END SCHEDULED MAINTENANCE
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>