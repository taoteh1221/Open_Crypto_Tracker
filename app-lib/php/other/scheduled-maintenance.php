<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// Scheduled maintenance (run ~hourly, or if runtime is cron)
//////////////////////////////////////////////////////////////////
if ( update_cache_file($base_dir . '/cache/events/scheduled_maintenance.dat', (60 * 1) ) == true || $runtime_mode == 'cron' ) {
//////////////////////////////////////////////////////////////////



// Determine / store portfolio cache size
store_file_contents($base_dir . '/cache/vars/cache_size.dat', convert_bytes( directory_size($base_dir . '/cache/') , 3) );


	
	// If upgrade check is enabled, check daily for upgrades
	if ( isset($app_config['upgrade_check']) && $app_config['upgrade_check'] != 'off' && update_cache_file('cache/vars/upgrade_check_latest_version.dat', (60 * 24) ) == true ) {
	
	
	$upgrade_check_jsondata = @api_data('url', 'https://api.github.com/repos/taoteh1221/DFD_Cryptocoin_Values/releases/latest', 0); // Don't cache API data
	
	$upgrade_check_data = json_decode($upgrade_check_jsondata, true);
	
	$upgrade_check_latest_version = $upgrade_check_data["tag_name"];
	
	store_file_contents($base_dir . '/cache/vars/upgrade_check_latest_version.dat', $upgrade_check_latest_version);
	

	$bug_fix_check_array = explode('.', $upgrade_check_latest_version);
	
		if ( $bug_fix_check_array[2] > 0 ) {
		$is_bug_fix = 1;
		}
	
	
		// Email / text / alexa notification reminders
		if ( update_cache_file($base_dir . '/cache/events/upgrade_check_reminder.dat', ( $app_config['upgrade_check_remind'] * 1440 ) ) == true ) {
		

		$upgrade_check_message = 'An upgrade for DFD Cryptocoin Values to version ' . $upgrade_check_latest_version . ' is available. You are running version ' . $app_version . '.' . ( $is_bug_fix == 1 ? ' This latest version is a bug fix release.' : '');


                    
  				// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
  				if ( $app_config['upgrade_check'] == 'all' ) {
  				
  				// Minimize function calls
  				$encoded_text_alert = content_data_encoding($upgrade_check_message);
  					
          	$upgrade_check_send_params = array(
          								'notifyme' => $upgrade_check_message,
          								'text' => array(
          														// Unicode support included for text messages (emojis / asian characters / etc )
          														'message' => $encoded_text_alert['content_output'],
          														'charset' => $encoded_text_alert['charset']
          														),
          								'email' => array(
          														'subject' => 'DFD Cryptocoin Values Upgrade Available',
          														'message' => $upgrade_check_message
          														)
          								);
          	
          	}
  				elseif ( $app_config['upgrade_check'] == 'email' ) {
  					
          	$upgrade_check_send_params['email'] = array(
          											'subject' => 'DFD Cryptocoin Values Upgrade Available',
          											'message' => $upgrade_check_message
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
          	$upgrade_check_send_params['notifyme'] = $upgrade_check_message;
          	}
          	
          	
          	// Send notifications
          	@queue_notifications($upgrade_check_send_params);
          	

		
		store_file_contents($base_dir . '/cache/events/upgrade_check_reminder.dat', time_date_format(false, 'pretty_date_time') );
		
		}
		
	
	}



	// Stuff to run only if cron is setup and running
	if ( $runtime_mode == 'cron' ) {
	
		// Chart backups...run before any price checks to avoid any potential file lock issues
		if ( $app_config['charts_page'] == 'on' && $app_config['charts_backup_freq'] > 0 ) {
		backup_archive('charts-data', $base_dir . '/cache/charts/', $app_config['charts_backup_freq']);
		}
	
	}

	
	
// Delete ANY old zip archive backups scheduled to be purged
delete_old_files($base_dir . '/cache/secured/backups/', $app_config['delete_old_backups'], 'zip');



// Stale cache files cleanup
delete_old_files($base_dir . '/cache/apis/', 1, 'dat'); // Delete MARKETS / CHAIN DATA API cache files older than 1 day



// Secondary logs cleanup
$logs_cache_cleanup = array(
									$base_dir . '/cache/logs/debugging/api/',
									$base_dir . '/cache/logs/errors/api/',
									);
									
delete_old_files($logs_cache_cleanup, $app_config['purge_logs'], 'dat'); // Delete LOGS API cache files older than $app_config['purge_logs'] day(s)



    // Re-check the average time interval between chart data points
    // If we just started collecting data, check frequently
    // (placeholder is always set to 1 to keep chart buttons from acting weird until we have enough data)
    if ( $app_config['charts_page'] == 'on' || !is_numeric(trim(file_get_contents('cache/vars/chart_interval.dat'))) || trim(file_get_contents('cache/vars/chart_interval.dat')) == 1 ) {  
        
        foreach ( $app_config['asset_charts_and_alerts'] as $key => $value ) {
        
            if ( trim($find_first_filename) == '' ) {
                
            // Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
            $find_first_asset = ( stristr($key, "-") == false ? $key : substr( $key, 0, mb_strpos($key, "-", 0, 'utf-8') ) );
            $find_first_asset = strtoupper($find_first_asset);
        
            $find_first_chart = explode("||", $value);
    
                if ( $find_first_chart[2] == 'both' || $find_first_chart[2] == 'chart' ) {
                $find_first_filename = 'cache/charts/spot_price_24hr_volume/archival/'.$find_first_asset.'/'.$key.'_chart_'.$find_first_chart[1].'.dat';
                }
    
            }
            
        }
    
    // Dynamically determine average time interval with the last 500 lines (or max available if less), presume an average max characters length of ~40
    $charts_update_freq = chart_time_interval($find_first_filename, 500, 40);
    
    store_file_contents($base_dir . '/cache/vars/chart_interval.dat', $charts_update_freq);
    
    }



// Update the maintenance event tracking
store_file_contents($base_dir . '/cache/events/scheduled_maintenance.dat', time_date_format(false, 'pretty_date_time') );


}
//////////////////////////////////////////////////////////////////
// END SCHEDULED MAINTENANCE
//////////////////////////////////////////////////////////////////

 
 ?>