<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */



	////////////////////////////////////////////////////////////
	// If upgrade check is enabled, check daily for upgrades
	////////////////////////////////////////////////////////////
	// With offset, to try keeping daily recurrences at same exact runtime (instead of moving up the runtime daily)
	if ( isset($ct['conf']['comms']['upgrade_alert']) && $ct['conf']['comms']['upgrade_alert'] != 'off' && $ct['cache']->update_cache($ct['base_dir'] . '/cache/vars/state-tracking/upgrade_check_latest_version.dat', (1440 + $ct['dev']['tasks_time_offset']) ) == true ) {
	
	
	$upgrade_check_jsondata = @$ct['cache']->ext_data('url', 'https://api.github.com/repos/taoteh1221/Open_Crypto_Tracker/releases/latest', 0); // Don't cache API data
	
	$upgrade_check_data = json_decode($upgrade_check_jsondata, true);
	
	$upgrade_check_latest_version = trim($upgrade_check_data["tag_name"]);
	
	// Remove any formatted links etc, that may exist AFTER description, and trim whitespace
	$upgrade_description = preg_replace("/\[\!(.*)/i", "", $upgrade_check_data["body"]); 
	$upgrade_description = trim($upgrade_description);
	
	$upgrade_download_array = $upgrade_check_data["assets"];
	
	$upgrade_download = null;
	$upgrade_download_html = null;
	
	   foreach ( $upgrade_download_array as $asset ) {
	       
	       if ( isset($asset['browser_download_url']) ) {
    	   $upgrade_download .= $asset['browser_download_url'] . "\n\n";
    	   $upgrade_download_html .= $ct['gen']->html_url($asset['browser_download_url']) . "<br /><br />";
	       }
	       
	   }
	
	$upgrade_download = trim($upgrade_download);
	$upgrade_download_html = trim($upgrade_download_html);
	
	$ct['cache']->save_file($ct['base_dir'] . '/cache/vars/state-tracking/upgrade_check_latest_version.dat', $upgrade_check_latest_version);
	
	
	// Parse latest version
	$latest_version_array = explode(".", $upgrade_check_latest_version);
	
	$latest_major_minor = $ct['var']->num_to_str($latest_version_array[0] . '.' . $latest_version_array[1]);
	
	$latest_bug_fixes = $latest_version_array[2];
	
	
	// Parse currently installed version
	$app_version_array = explode(".", $ct['app_version']);
	
	$app_major_minor = $ct['var']->num_to_str($app_version_array[0] . '.' . $app_version_array[1]);
	
	$app_bug_fixes = $app_version_array[2];
	
	
		
		// If the latest release is a newer version then what we are running
		if ( $latest_major_minor > $app_major_minor || $latest_major_minor == $app_major_minor && $latest_bug_fixes > $app_bug_fixes ) {
		
		
			// Is this a bug fix release?
			if ( $latest_bug_fixes > 0 ) {
			$is_upgrade_bug_fix = 1;
			$bug_fix_subject_extension = ' (bug fix release)';
			$bug_fix_msg_extension = ' This latest version is a bug fix release.';
			}
			
		
		// EVENTUALLY PUT UI ALERT LOGIC HERE

		
			// Email / text / alexa notification reminders (if it's been $ct['conf']['comms']['upgrade_alert_reminder'] days since any previous reminder)
			// With offset, to try keeping daily recurrences at same exact runtime (instead of moving up the runtime daily)
			if ( $ct['cache']->update_cache($ct['base_dir'] . '/cache/events/upgrading/upgrade_check_reminder.dat', ( $ct['conf']['comms']['upgrade_alert_reminder'] * 1440 ) + $ct['dev']['tasks_time_offset'] ) == true ) {
			
			
				if ( file_exists($ct['base_dir'] . '/cache/events/upgrading/upgrade_check_reminder.dat') ) {
				$another_reminder = 'Reminder: ';
				}
				
	
			$upgrade_check_msg = $another_reminder . 'An upgrade for Open Crypto Tracker to version ' . $upgrade_check_latest_version . ' is available. You are running version ' . $ct['app_version'] . '.' . $bug_fix_msg_extension;
			
			
			$email_notifyme_msg = $upgrade_check_msg . ' (you have upgrade reminders triggered every '.$ct['conf']['comms']['upgrade_alert_reminder'].' days in the configuration settings)';
			
			$email_only_with_upgrade_command = $email_notifyme_msg . "\n\n" . 'Quick / easy upgrading for the SERVER EDITION can be done by copying / pasting / running this command, using the "Terminal" app in your Debian / Ubuntu / DietPi OS / RaspberryPi OS / Armbian system menu (Windows 10 requires manual upgrading), or logging in remotely from another device via SSH (user must have sudo privileges):' . "\n\n" . 'wget --no-cache -O FOLIO-INSTALL.bash https://tinyurl.com/install-crypto-tracker;chmod +x FOLIO-INSTALL.bash;sudo ./FOLIO-INSTALL.bash' . "\n\nIF ANYTHING STOPS WORKING AFTER UPGRADING, CLEAR YOUR BROWSER CACHE (temporary files), AND RELOAD OR RESTART THE APP. This will load the latest Javascript / Style Sheet upgrades properly. Otherwise, you MAY encounter visual styling / app functionality errors (until your browser cache refreshes on it's own).\n\nUpgrade Description:\n\n" . $upgrade_description . "\n\n";
			
			$download_link = "Manual Download Links (SERVER and DESKTOP edition upgrading):\n\n" . $upgrade_download . "\n\n";
			
			$download_link_html = "Manual Download Links (SERVER and DESKTOP edition upgrading):\n\n" . $upgrade_download_html . "\n\n";
			
			$changelog_link = "Changelog:\nhttps://raw.githubusercontent.com/taoteh1221/Open_Crypto_Tracker/main/DOCUMENTATION-ETC/changelog.txt\n\n";
			
			$changelog_link_html = "Changelog:\n" . $ct['gen']->html_url('https://raw.githubusercontent.com/taoteh1221/Open_Crypto_Tracker/main/DOCUMENTATION-ETC/changelog.txt') . "\n\n";
	
        	// Minimize function calls
        	$text_alert = $ct['gen']->detect_unicode($upgrade_check_msg); 
			
        	$upgrade_check_send_params = array(
                                    			'notifyme' => $email_notifyme_msg,
                                    			// We don't want to go over telegram's 4096 character limit,
                                    			// so we don't include anymore than the basics for content
                                    			'telegram' => $email_notifyme_msg . "\n\n" . $download_link . $changelog_link,
                                    			'text' => array(
                                    			               'message' => $text_alert['content'],
                                    			               'charset' => $text_alert['charset']
                                    			               ),
                                    			'email' => array(
                                                    			'subject' => $another_reminder . 'Open Crypto Tracker v'.$upgrade_check_latest_version.' Upgrade Available' . $bug_fix_subject_extension,
                                                    			'message' => $email_only_with_upgrade_command . $download_link . $changelog_link
                                                    			)
                                    			);
				
		    
		    // Only send to comm channels the user prefers, based off the config setting $ct['conf']['comms']['upgrade_alert']
		    $preferred_comms = $ct['gen']->preferred_comms($ct['conf']['comms']['upgrade_alert'], $upgrade_check_send_params);
			
			// Queue notifications
			@$ct['cache']->queue_notify($preferred_comms);
					
					
					// UI alert logic
					if ( $ct['conf']['comms']['upgrade_alert'] == 'all' || $ct['conf']['comms']['upgrade_alert'] == 'ui' ) {
						
					$ui_upgrade_alert_data = array(
											  'run' => 'yes',
											  'message' => $email_only_with_upgrade_command . $download_link_html . $changelog_link_html
											  );
						
					$ct['cache']->save_file($ct['base_dir'] . '/cache/events/upgrading/ui_upgrade_alert.dat', json_encode($ui_upgrade_alert_data, JSON_PRETTY_PRINT) );
					
					}
					
			
			// Track upgrade check reminder event occurrence			
			$ct['cache']->save_file($ct['base_dir'] . '/cache/events/upgrading/upgrade_check_reminder.dat', $ct['gen']->time_date_format(false, 'pretty_date_time') );
			
			} // END sending reminder (NEVER DELETE REMINDER EVENT, FOR UX NOT BUGGING ABOUT UPGRADES MORE THAN DESIRED IN THE SETTINGS)
			
		
		
		} // END latest release notice
	

	
	} 
	////////////////////////////////////////////////////////////
	// END upgrade check
	////////////////////////////////////////////////////////////
	

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>