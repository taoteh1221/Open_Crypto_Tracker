<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


error_reporting(0); // Turn off all error reporting (0), or enable (1)

// Forbid direct INTERNET access to this file
if ( realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']) ) {
    header('HTTP/1.0 403 Forbidden', TRUE, 403);
    exit;
}

$runtime_mode = 'cron';

require("config.php");


// Delete ANY old zip archive backups
delete_old_files($base_dir . '/backups/', $delete_old_backups, 'zip');



// Chart backups...run before any price checks to avoid any potential file lock issues
if ( $charts_page == 'on' && $chart_data_backups != 'off' ) {

$secure_128bit_hash = random_hash(16); // 128-bit (16-byte) hash converted to hexadecimal, used for suffix

	// We only want to store backup files with suffixes that can't be guessed, 
	// otherwise halt the application if an issue is detected safely creating a random hash
	if ( $secure_128bit_hash == false ) {
	$_SESSION['other_error'] .= date('Y-m-d H:i:s') . ' UTC | runtime mode: ' . $runtime_mode . ' | Error: Cryptographically secure pseudo-random bytes could not be generated for chart backup filename suffix, backup aborted to preserve backups directory privacy' . "<br /> \n";
	}
	else {
	
	$backup_file = 'charts_'.date( "Y-M-d", time() ).'_'.$secure_128bit_hash.'.zip';
	$backup_location = 'backups/' . $backup_file;
			
			
		// If it's time to backup charts...
		if ( $chart_data_backups == 'daily' ) {
		$backups_freq = 1;
		}
		elseif ( $chart_data_backups == 'weekly' ) {
		$backups_freq = 7;
		}
	
	
		if ( $backups_freq > 0 && update_cache_file('cache/events/backup_charts.dat', ( $backups_freq * 1440 ) ) == true ) {
		
			
			$backup_results = zip_recursively('cache/charts/', $backup_location);
			
			if ( $backup_results == 1 ) {
				
			file_put_contents('cache/events/backup_charts.dat', time(), LOCK_EX);
				
			$download_location = 'backups/?file=' . $backup_file;
			
			$message = " ".ucfirst($chart_data_backups)." chart backups have completed. Here is a link to download the backup to your computer: " . $base_url . $download_location;
			
			// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
			$send_params = array(
									'email' => array(
														'subject' => 'DFD Cryptocoin Values - Charts Backup',
														'message' => $message
														)
									);
						
			// Send notifications
			@send_notifications($send_params);
			
			}
			else {
			$_SESSION['other_error'] .= date('Y-m-d H:i:s') . ' UTC | runtime mode: ' . $runtime_mode . ' | Error: Backup zip archive creation failed with '.$backup_results . "<br /> \n";
			}
			
			
		}
		

	}
	

}




// Price alerts checking
$btc_usd = get_btc_usd($btc_exchange)['last_trade'];

foreach ( $exchange_price_alerts as $key => $value ) {
	
$value = explode("||",$value); // Convert $value into an array

$exchange = $value[0];
$pairing = $value[1];

asset_alert_check($key, $exchange, $pairing, 'decreased');
asset_alert_check($key, $exchange, $pairing, 'increased');

}




// Checkup on each failed proxy
if ( $proxy_alerts != 'none' ) {
	
	foreach ( $_SESSION['proxy_checkup'] as $problem_proxy ) {
	test_proxy($problem_proxy);
	sleep(1);
	}

}



error_logs();
session_destroy();

?>