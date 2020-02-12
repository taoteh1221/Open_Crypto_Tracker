<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// Scheduled maintenance (run ~hourly, or if runtime is cron)
//////////////////////////////////////////////////////////////////
if ( update_cache_file($base_dir . '/cache/events/scheduled_maintenance.dat', 60) == true || $runtime_mode == 'cron' ) {
//////////////////////////////////////////////////////////////////



	////////////////////////////////////////////////////////////
	// Maintenance to run only if cron is setup and running
	////////////////////////////////////////////////////////////
	if ( $runtime_mode == 'cron' ) {
	
		// Chart backups...run before any price checks to avoid any potential file lock issues
		if ( $app_config['charts_page'] == 'on' && $app_config['charts_backup_freq'] > 0 ) {
		backup_archive('charts-data', $base_dir . '/cache/charts/', $app_config['charts_backup_freq']);
		}


	
		////////////////////////////////////////////////////////////
	   // Re-check the average time interval between chart data points
	   // If we just started collecting data, check frequently
	   // (placeholder is always set to 1 to keep chart buttons from acting weird until we have enough data)
		////////////////////////////////////////////////////////////
	   if ( $app_config['charts_page'] == 'on' || !is_numeric(trim(file_get_contents($base_dir . '/cache/vars/chart_interval.dat'))) || trim(file_get_contents($base_dir . '/cache/vars/chart_interval.dat')) == 1 ) {
			
			foreach ( $app_config['charts_and_price_alerts'] as $key => $value ) {
			
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
	
	
	
	////////////////////////////////////////////////////////////
	// If upgrade check is enabled, check daily for upgrades
	////////////////////////////////////////////////////////////
	if ( isset($app_config['upgrade_check']) && $app_config['upgrade_check'] != 'off' && update_cache_file($base_dir . '/cache/vars/upgrade_check_latest_version.dat', 1440) == true ) {
	
	
	$upgrade_check_jsondata = @api_data('url', 'https://api.github.com/repos/taoteh1221/DFD_Cryptocoin_Values/releases/latest', 0); // Don't cache API data
	
	$upgrade_check_data = json_decode($upgrade_check_jsondata, true);
	
	$upgrade_check_latest_version = trim($upgrade_check_data["tag_name"]);
	
	store_file_contents($base_dir . '/cache/vars/upgrade_check_latest_version.dat', $upgrade_check_latest_version);
	
		
		// If the latest release is a newer version then what we are running
		if ( preg_replace("/\./", "", $upgrade_check_latest_version) > preg_replace("/\./", "", $app_version) ) {
		
		
			// Is this a bug fix release?
			$bug_fix_check_array = explode('.', $upgrade_check_latest_version);
			if ( $bug_fix_check_array[2] > 0 ) {
			$is_upgrade_bug_fix = 1;
			$bug_fix_subject_extension = ' (bug fix release)';
			$bug_fix_message_extension = ' This latest version is a bug fix release.';
			}

		
			// Email / text / alexa notification reminders (if it's been $app_config['upgrade_check_reminder'] days since any previous reminder)
			if ( update_cache_file($base_dir . '/cache/events/upgrade_check_reminder.dat', ( $app_config['upgrade_check_reminder'] * 1440 ) ) == true ) {
			
			
				if ( file_exists($base_dir . '/cache/events/upgrade_check_reminder.dat') ) {
				$another_reminder = 'Reminder: ';
				}
				
	
			$upgrade_check_message = $another_reminder . 'An upgrade for DFD Cryptocoin Values to version ' . $upgrade_check_latest_version . ' is available. You are running version ' . $app_version . '.' . $bug_fix_message_extension;
			
			
			$email_notifyme_message = $upgrade_check_message . ' (you have upgrade reminders sent out every '.$app_config['upgrade_check_reminder'].' days in the configuration settings)';
			
						
					// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
					if ( $app_config['upgrade_check'] == 'all' ) {
					
					// Minimize function calls
					$encoded_text_alert = content_data_encoding($upgrade_check_message);
						
					$upgrade_check_send_params = array(
											'notifyme' => $email_notifyme_message,
											'telegram' => $email_notifyme_message,
											'text' => array(
																	// Unicode support included for text messages (emojis / asian characters / etc )
																	'message' => $encoded_text_alert['content_output'],
																	'charset' => $encoded_text_alert['charset']
																	),
											'email' => array(
																	'subject' => $another_reminder . 'DFD Cryptocoin Values v'.$upgrade_check_latest_version.' Upgrade Available' . $bug_fix_subject_extension,
																	'message' => $email_notifyme_message
																	)
											);
				
					}
					elseif ( $app_config['upgrade_check'] == 'email' ) {
						
					$upgrade_check_send_params['email'] = array(
														'subject' => $another_reminder . 'DFD Cryptocoin Values v'.$upgrade_check_latest_version.' Upgrade Available' . $bug_fix_subject_extension,
														'message' => $email_notifyme_message
														);
				
					}
					elseif ( $app_config['upgrade_check'] == 'text' ) {
					
					// Minimize function calls
					$encoded_text_alert = content_data_encoding($upgrade_check_message);
					
					$upgrade_check_send_params['text'] = array(
														// Unicode support included for text messages (emojis / asian characters / etc )
														'message' => $encoded_text_alert['content_output'],
														'charset' => $encoded_text_alert['charset']
														
														);
				
					}
					elseif ( $app_config['upgrade_check'] == 'notifyme' ) {
					$upgrade_check_send_params['notifyme'] = $email_notifyme_message;
					}
					elseif ( $app_config['upgrade_check'] == 'telegram' ) {
					$upgrade_check_send_params['telegram'] = $email_notifyme_message;
					}
				
				
			
			// Send notifications
			@queue_notifications($upgrade_check_send_params);
			
			// Track upgrade check reminder event occurrence			
			store_file_contents($base_dir . '/cache/events/upgrade_check_reminder.dat', time_date_format(false, 'pretty_date_time') );
			
			} // END sending reminder 
			
		
		
		} // END latest release notice
		// Delete any old upgrade reminder event, if user has now upgraded
		else {
		unlink($base_dir . '/cache/events/upgrade_check_reminder.dat');  
		}
	

	
	} 
	////////////////////////////////////////////////////////////
	// END upgrade check
	////////////////////////////////////////////////////////////
	

// Current default primary currency stored to flat file (for checking if we need to reconfigure things for a changed value here)
store_file_contents($base_dir . '/cache/vars/default_btc_primary_currency_pairing.dat', $default_btc_primary_currency_pairing);
	

// Current app version stored to flat file (for the bash auto-install/upgrade script to easily determine the currently-installed version)
store_file_contents($base_dir . '/cache/vars/app_version.dat', $app_version);


// Determine / store portfolio cache size
store_file_contents($base_dir . '/cache/vars/cache_size.dat', convert_bytes( directory_size($base_dir . '/cache/') , 3) );


// Delete ANY old zip archive backups scheduled to be purged
delete_old_files($base_dir . '/cache/secured/backups', $app_config['delete_old_backups'], 'zip');


// Stale cache files cleanup
delete_old_files($base_dir . '/cache/secured/apis', 1, 'dat'); // Delete API cache files older than 1 day


// Secondary logs cleanup
$logs_cache_cleanup = array(
									$base_dir . '/cache/logs/debugging/api',
									$base_dir . '/cache/logs/errors/api',
									);
									
delete_old_files($logs_cache_cleanup, $app_config['log_purge'], 'dat'); // Delete LOGS API cache files older than $app_config['log_purge'] day(s)


// Update the maintenance event tracking
store_file_contents($base_dir . '/cache/events/scheduled_maintenance.dat', time_date_format(false, 'pretty_date_time') );


}
//////////////////////////////////////////////////////////////////
// END SCHEDULED MAINTENANCE
//////////////////////////////////////////////////////////////////

 
 ?>