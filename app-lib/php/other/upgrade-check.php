<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */



	////////////////////////////////////////////////////////////
	// If upgrade check is enabled, check daily for upgrades
	////////////////////////////////////////////////////////////
	// 1439 minutes instead (minus 1 minute), to try keeping daily recurrences at same exact runtime (instead of moving up the runtime daily)
	if ( isset($ct_conf['comms']['upgrade_alert']) && $ct_conf['comms']['upgrade_alert'] != 'off' && $ct_cache->update_cache($base_dir . '/cache/vars/upgrade_check_latest_version.dat', 1439) == true ) {
	
	
	$upgrade_check_jsondata = @$ct_cache->ext_data('url', 'https://api.github.com/repos/taoteh1221/Open_Crypto_Tracker/releases/latest', 0); // Don't cache API data
	
	$upgrade_check_data = json_decode($upgrade_check_jsondata, true);
	
	$upgrade_check_latest_version = trim($upgrade_check_data["tag_name"]);
	
	$ct_cache->save_file($base_dir . '/cache/vars/upgrade_check_latest_version.dat', $upgrade_check_latest_version);
	
	
	// Parse latest version
	$latest_version_array = explode(".", $upgrade_check_latest_version);
	
	$latest_major_minor = $latest_version_array[0] . $latest_version_array[1];
	
	$latest_bug_fixes = $latest_version_array[2];
	
	
	// Parse currently installed version
	$app_version_array = explode(".", $app_version);
	
	$app_major_minor = $app_version_array[0] . $app_version_array[1];
	
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

		
			// Email / text / alexa notification reminders (if it's been $ct_conf['comms']['upgrade_alert_reminder'] days since any previous reminder)
			// 1439 minutes instead (minus 1 minute), to try keeping daily recurrences at same exact runtime (instead of moving up the runtime daily)
			if ( $ct_cache->update_cache($base_dir . '/cache/events/upgrade_check_reminder.dat', ( $ct_conf['comms']['upgrade_alert_reminder'] * 1439 ) ) == true ) {
			
			
				if ( file_exists($base_dir . '/cache/events/upgrade_check_reminder.dat') ) {
				$another_reminder = 'Reminder: ';
				}
				
	
			$upgrade_check_msg = $another_reminder . 'An upgrade for Open Crypto Tracker to version ' . $upgrade_check_latest_version . ' is available. You are running version ' . $app_version . '.' . $bug_fix_msg_extension;
			
			
			$email_notifyme_msg = $upgrade_check_msg . ' (you have upgrade reminders triggered every '.$ct_conf['comms']['upgrade_alert_reminder'].' days in the configuration settings)';
			
			$email_only_with_upgrade_command = $email_notifyme_msg . "\n\n" . 'Quick / easy upgrading can be done by copying / pasting / running this command, using the "Terminal" app in your Ubuntu / Raspberry Pi system menu, or logging in remotely from another device via SSH (user must have sudo privileges):' . "\n\n" . 'wget --no-cache -O FOLIO-INSTALL.bash https://git.io/JoDFD;chmod +x FOLIO-INSTALL.bash;sudo ./FOLIO-INSTALL.bash';
			
						
					// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
					if ( $ct_conf['comms']['upgrade_alert'] == 'all' ) {
						
					$ui_upgrade_alert = array(
														'run' => 'yes',
														'message' => nl2br($email_only_with_upgrade_command)
														);
						
					$ct_cache->save_file($base_dir . '/cache/events/ui_upgrade_alert.dat', json_encode($ui_upgrade_alert, JSON_PRETTY_PRINT) );
					
					// Minimize function calls
					$encoded_text_alert = $ct_gen->charset_encode($upgrade_check_msg); // Unicode support included for text messages (emojis / asian characters / etc )
						
					$upgrade_check_send_params = array(
											'notifyme' => $email_notifyme_msg,
											'telegram' => $email_only_with_upgrade_command,
											'text' => array(
																	'message' => $encoded_text_alert['content_output'],
																	'charset' => $encoded_text_alert['charset']
																	),
											'email' => array(
																	'subject' => $another_reminder . 'Open Crypto Tracker v'.$upgrade_check_latest_version.' Upgrade Available' . $bug_fix_subject_extension,
																	'message' => $email_only_with_upgrade_command
																	)
											);
				
					}
					elseif ( $ct_conf['comms']['upgrade_alert'] == 'email' ) {
						
					$upgrade_check_send_params['email'] = array(
														'subject' => $another_reminder . 'Open Crypto Tracker v'.$upgrade_check_latest_version.' Upgrade Available' . $bug_fix_subject_extension,
														'message' => $email_only_with_upgrade_command
														);
				
					}
					elseif ( $ct_conf['comms']['upgrade_alert'] == 'text' ) {
					
					// Minimize function calls
					$encoded_text_alert = $ct_gen->charset_encode($upgrade_check_msg); // Unicode support included for text messages (emojis / asian characters / etc )
					
					$upgrade_check_send_params['text'] = array(
														'message' => $encoded_text_alert['content_output'],
														'charset' => $encoded_text_alert['charset']
														);
				
					}
					elseif ( $ct_conf['comms']['upgrade_alert'] == 'notifyme' ) {
					$upgrade_check_send_params['notifyme'] = $email_notifyme_msg;
					}
					elseif ( $ct_conf['comms']['upgrade_alert'] == 'telegram' ) {
					$upgrade_check_send_params['telegram'] = $email_only_with_upgrade_command;
					}
					elseif ( $ct_conf['comms']['upgrade_alert'] == 'ui' ) {
						
					$ui_upgrade_alert = array(
														'run' => 'yes',
														'message' => nl2br($email_only_with_upgrade_command)
														);
						
					$ct_cache->save_file($base_dir . '/cache/events/ui_upgrade_alert.dat', json_encode($ui_upgrade_alert, JSON_PRETTY_PRINT) );
					
					}
				
				
			
			// Send notifications
			@$ct_cache->queue_notify($upgrade_check_send_params);
			
			// Track upgrade check reminder event occurrence			
			$ct_cache->save_file($base_dir . '/cache/events/upgrade_check_reminder.dat', $ct_gen->time_date_format(false, 'pretty_date_time') );
			
			} // END sending reminder (NEVER DELETE REMINDER EVENT, FOR UX NOT BUGGING ABOUT UPGRADES MORE THAN DESIRED IN THE SETTINGS)
			
		
		
		} // END latest release notice
	

	
	} 
	////////////////////////////////////////////////////////////
	// END upgrade check
	////////////////////////////////////////////////////////////
	


?>