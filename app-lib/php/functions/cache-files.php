<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function update_cache_file($cache_file, $minutes) {

	if (  file_exists($cache_file) && filemtime($cache_file) > ( time() - ( 60 * $minutes ) )  ) {
	   return false; 
	} 
	else {
	   // Our cache is out-of-date
	   return true;
	}

}



////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function delete_old_files($directory_data, $days, $ext) {
	
	
	// Support for string OR array in the calls, for directory data
	if ( !is_array($directory_data) ) {
	$directory_data = array($directory_data);
	}
	
	
	// Process each directory
	foreach ( $directory_data as $dir ) {
	
		
	$files = glob($dir."/*.".$ext);
	
	
      foreach ($files as $file) {
       
        if ( is_file($file) ) {
          
          if ( time() - filemtime($file) >= 60 * 60 * 24 * $days ) {
          	
          $result = unlink($file);
          
          	if ( $result == false ) {
          	app_logging('system_error', 'File deletion failed for file "' . $file . '" (check permissions for "' . basename($file) . '")');
          	}
          
          }
          
        }
        else {
        app_logging('system_error', 'File deletion failed, file not found: "' . $file . '"');
        }
        
      }
  
	
	}


 }
 

////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function htaccess_directory_protection() {

global $base_dir, $app_config, $htaccess_username, $htaccess_password;

$valid_username = valid_username($htaccess_username);

// Password must be exactly 8 characters long for good htaccess security (htaccess only checks the first 8 characters for a match)
$password_strength = password_strength($htaccess_password, 8, 8); 


    if ( $htaccess_username == '' || $htaccess_password == '' ) {
    return false;
    }
    elseif ( $valid_username != 'valid' ) {
    app_logging('security_error', 'app_config\'s "interface_login" username value does not meet minimum valid username requirements' , $valid_username);
    return false;
    }
    elseif ( $password_strength != 'valid' ) {
    app_logging('security_error', 'app_config\'s "interface_login" password value does not meet minimum password strength requirements' , $password_strength);
    return false;
    }
    else {
    
    $htaccess_password = crypt( $htaccess_password, base64_encode($htaccess_password) );
    
    $password_set = store_file_contents($base_dir . '/cache/secured/.app_htpasswd', $htaccess_username . ':' . $htaccess_password);
    
    	if ( $password_set == true ) {
    	
    	$htaccess_contents = file_get_contents($base_dir . '/templates/back-end/root-app-directory-htaccess.template') . 
		preg_replace("/\[BASE_DIR\]/i", $base_dir, file_get_contents($base_dir . '/templates/back-end/enable-password-htaccess.template') );
    
    	$htaccess_set = store_file_contents($base_dir . '/.htaccess', $htaccess_contents);
    
    	return $htaccess_set;
    	
    	}
    	else {
    	return false;
    	}
    
    
    }

 
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function lite_chart($archive_file, $days_span) {

global $base_dir, $app_config;

$lite_path = preg_replace("/archival/i", 'lite/' . $days_span . '_day', $archive_file);

$chart_data = array();
$lite_chart = null;

$file_data = file($archive_file);
$file_data = array_reverse($file_data); // Save time, only loop / read last lines needed


	foreach($file_data as $line) {
		
	$line_array = explode("||", $line);
	
		if ( !$newest_timestamp ) {
		$newest_timestamp = $line_array[0];
		$oldest_timestamp = strtotime('-'.$days_span.' day', $newest_timestamp); // Timestamp for oldest data point 
		}
	
		if ( $line_array[0] >= $oldest_timestamp ) {
		$chart_data[] = $line;
		}
		
	}
	
$chart_data = array_reverse($chart_data);

	
	// If we have more data points than permitted per lite chart
	if ( sizeof($chart_data) > $app_config['power_user']['chart_data_points_max'] ) {
	
	// Minimum time interval between data points
	$min_data_interval = round( ($newest_timestamp - $oldest_timestamp) / $app_config['power_user']['chart_data_points_max'] );
	
		$loop = 0;
		foreach ($chart_data as $data_point) {
		
		$data_point_array = explode("||", $data_point);
		
			if ( !$next_timestamp && $loop < $app_config['power_user']['chart_data_points_max'] 
			|| isset($next_timestamp) && $next_timestamp <= $data_point_array[0] && $loop < $app_config['power_user']['chart_data_points_max'] ) {
			$lite_chart .= $data_point;
			$next_timestamp = $data_point_array[0] + $min_data_interval;
			$loop = $loop + 1;
			}
			
		}
	
	}
	else {
		foreach ($chart_data as $data_point) {
		$lite_chart .= $data_point;
		}
	}



// Store the lite chart data
store_file_contents($base_dir . '/' . $lite_path, $lite_chart);

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function store_file_contents($file, $data, $mode=false) {

global $app_config, $current_runtime_user, $possible_http_users, $http_runtime_user;


	// If no data was passed on to write to file, log it and return false early for runtime speed sake
	if ( strlen($data) == 0 ) {
		
	app_logging('system_error', 'No bytes of data received to write to file "' . obfuscated_path_data($file) . '" (aborting useless file write)');
	
		// API timeouts are a confirmed cause for write errors of 0 bytes, so we want to alert end users that they may need to adjust their API timeout settings to get associated API data
		if ( preg_match("/cache\/secured\/apis/i", $file) ) {
		app_logging('ext_api_error', 'POSSIBLE api timeout' . ( $app_config['power_user']['remote_api_strict_ssl'] == 'on' ? ' or strict_ssl' : '' ) . ' issue for cache file "' . obfuscated_path_data($file) . '" (IF THIS ISSUE PERSISTS #LONG TERM#, TRY INCREASING "remote_api_timeout"' . ( $app_config['power_user']['remote_api_strict_ssl'] == 'on' ? ' OR SETTING "remote_api_strict_ssl" to "off"' : '' ) . ' IN THE POWER USER SECTION in config.php)', 'remote_api_timeout: '.$app_config['power_user']['remote_api_timeout'].' seconds; remote_api_strict_ssl: ' . $app_config['power_user']['remote_api_strict_ssl'] . ';');
		}
	
	return false;
	
	}


$path_parts = pathinfo($file);

$file_owner_info = posix_getpwuid(fileowner($file));


	// Does the current runtime user own this file (or will they own it after creating a non-existent file)?
	if ( file_exists($file) == false || isset($current_runtime_user) && $current_runtime_user == $file_owner_info['name'] ) {
	$is_file_owner = 1;
	}
	
	
	// We ALWAYS set .htaccess files to a more secure $app_config['developer']['chmod_index_security'] permission AFTER EDITING, 
	// so we TEMPORARILY set .htaccess to $app_config['developer']['chmod_cache_files'] for NEW EDITING...
	if ( strstr($file, '.htaccess') != false || strstr($file, 'index.php') != false ) {
		
	$chmod_setting = octdec($app_config['developer']['chmod_cache_files']);
	
	
		// Run chmod compatibility on certain PHP setups (if we can because we are running as the file owner)
		// In this case only if the file exists, as we are chmod BEFORE editing it (.htaccess files)
		if ( file_exists($file) == true && $is_file_owner == 1 && !$http_runtime_user 
		|| file_exists($file) == true && $is_file_owner == 1 && isset($http_runtime_user) && in_array($http_runtime_user, $possible_http_users) ) {
			
		$oldmask = umask(0);
		
		$did_chmod = chmod($file, $chmod_setting);
		
			if ( !$did_chmod ) {
			app_logging('system_error', 'Chmod failed for file "' . obfuscated_path_data($file) . '" (check permissions for the path "' . obfuscated_path_data($path_parts['dirname']) . '", and the file "' . obfuscate_string($path_parts['basename'], 5) . '")', 'chmod_setting: ' . $chmod_setting . '; current_runtime_user: ' . $current_runtime_user . '; file_owner: ' . $file_owner_info['name'] . ';');
			}
		
		umask($oldmask);
		
		}
	
	}
	


	// Write to the file
	if ( $mode == 'append' ) {
	$result = file_put_contents($file, $data, FILE_APPEND | LOCK_EX);
	}
	else {
	$result = file_put_contents($file, $data, LOCK_EX);
	}
	
	// Log any write error
	if ( $result == false ) {
	app_logging('system_error', 'File write failed storing '.strlen($data).' bytes of data to file "' . obfuscated_path_data($file) . '" (MAKE SURE YOUR DISK ISN\'T FULL. Check permissions for the path "' . obfuscated_path_data($path_parts['dirname']) . '", and the file "' . obfuscate_string($path_parts['basename'], 5) . '")');
	}
	
	
	
	// For security, NEVER make an .htaccess file writable by any user not in the group
	if ( strstr($file, '.htaccess') != false || strstr($file, 'index.php') != false ) {
	$chmod_setting = octdec($app_config['developer']['chmod_index_security']);
	}
	// All other files
	else {
	$chmod_setting = octdec($app_config['developer']['chmod_cache_files']);
	}
	
	// Run chmod compatibility on certain PHP setups (if we can because we are running as the file owner)
	if ( $is_file_owner == 1 && !$http_runtime_user || $is_file_owner == 1 && isset($http_runtime_user) && in_array($http_runtime_user, $possible_http_users) ) {
		
	$oldmask = umask(0);
	
	$did_chmod = chmod($file, $chmod_setting);
		
		if ( !$did_chmod ) {
		app_logging('system_error', 'Chmod failed for file "' . obfuscated_path_data($file) . '" (check permissions for the path "' . obfuscated_path_data($path_parts['dirname']) . '", and the file "' . obfuscate_string($path_parts['basename'], 5) . '")', 'chmod_setting: ' . $chmod_setting . '; current_runtime_user: ' . $current_runtime_user . '; file_owner: ' . $file_owner_info['name'] . ';');
		}
		
	umask($oldmask);
	
	}
	
	
return $result;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function backup_archive($backup_prefix, $backup_target, $interval, $password=false) {

global $app_config, $base_dir, $base_url;


	if ( update_cache_file('cache/events/backup-'.$backup_prefix.'.dat', ( $interval * 1440 ) ) == true ) {

	$secure_128bit_hash = random_hash(16); // 128-bit (16-byte) hash converted to hexadecimal, used for suffix
	
	
		// We only want to store backup files with suffixes that can't be guessed, 
		// otherwise halt the application if an issue is detected safely creating a random hash
		if ( $secure_128bit_hash == false ) {
		app_logging('security_error', 'Cryptographically secure pseudo-random bytes could not be generated for '.$backup_prefix.' backup archive filename suffix, backup aborted to preserve backups directory privacy');
		}
		else {
			
			$backup_file = $backup_prefix . '_'.date( "Y-M-d", time() ).'_'.$secure_128bit_hash.'.zip';
			$backup_dest = $base_dir . '/cache/secured/backups/' . $backup_file;
			
			// Zip archive
			$backup_results = zip_recursively($backup_target, $backup_dest, $password);
			
			
				if ( $backup_results == 1 ) {
					
				store_file_contents($base_dir . '/cache/events/backup-'.$backup_prefix.'.dat', time_date_format(false, 'pretty_date_time') );
					
				$backup_url = 'download.php?backup=' . $backup_file;
				
				$message = "A backup archive has been created for: ".$backup_prefix."\n\nHere is a link to download the backup to your computer: " . $base_url . $backup_url . "\n\n(backup archives are purged after " . $app_config['general']['backup_archive_delete_old'] . " days)";
				
				// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
				$send_params = array(
										'email' => array(
															'subject' => 'DFD Cryptocoin Values - Backup Archive For: ' . $backup_prefix,
															'message' => $message
															)
										);
							
				// Send notifications
				@queue_notifications($send_params);
				
				}
				else {
				app_logging('system_error', 'Backup zip archive creation failed with ' . $backup_results);
				}
				
		
		}
	

	}


}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function debugging_logs() {

global $app_config, $base_dir, $logs_array;

	if ( $app_config['developer']['debug_mode'] == 'off' ) {
	return false;
	}

// Combine all debugging logged
$debugging_logs .= strip_tags($logs_array['system_debugging']); // Remove any HTML formatting used in UI alerts

$debugging_logs .= strip_tags($logs_array['config_debugging']); // Remove any HTML formatting used in UI alerts

$debugging_logs .= strip_tags($logs_array['security_debugging']); // Remove any HTML formatting used in UI alerts

$debugging_logs .= strip_tags($logs_array['ext_api_debugging']); // Remove any HTML formatting used in UI alerts

$debugging_logs .= strip_tags($logs_array['int_api_debugging']); // Remove any HTML formatting used in UI alerts

$debugging_logs .= strip_tags($logs_array['market_debugging']); // Remove any HTML formatting used in UI alerts

$debugging_logs .= strip_tags($logs_array['other_debugging']); // Remove any HTML formatting used in UI alerts


	foreach ( $logs_array['cache_debugging'] as $debugging ) {
	$debugging_logs .= strip_tags($debugging); // Remove any HTML formatting used in UI alerts
	}


	// If it's time to email debugging logs...
	if ( $app_config['comms']['email_logs'] > 0 && update_cache_file('cache/events/email-debugging-logs.dat', ( $app_config['comms']['email_logs'] * 1440 ) ) == true ) {
		
	$emailed_logs = "\n\n ------------------debugging.log------------------ \n\n" . file_get_contents('cache/logs/debugging.log') . "\n\n ------------------smtp_debugging.log------------------ \n\n" . file_get_contents('cache/logs/smtp_debugging.log');
		
	$message = " Here are the current debugging logs from the ".$base_dir."/cache/logs/ directory: \n =========================================================================== \n \n"  . ( $emailed_logs != '' ? $emailed_logs : 'No debugging logs currently.' );
	
  	// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
   $send_params = array(
          					'email' => array(
          											'subject' => 'DFD Cryptocoin Values - Debugging Logs Report',
     													'message' => $message
          											)
          					);
          	
   // Send notifications
   @queue_notifications($send_params);
          	
	store_file_contents($base_dir . '/cache/events/email-debugging-logs.dat', date('Y-m-d H:i:s')); // Track this emailing event, to determine next time to email logs again.
	
	}
	
	
	// Log debugging...Purge old logs before storing new logs, if it's time to...otherwise just append.
	if ( update_cache_file('cache/events/purge-debugging-logs.dat', ( $app_config['developer']['log_purge'] * 1440 ) ) == true ) {
		
	store_file_contents($base_dir . '/cache/logs/smtp_debugging.log', null);
	
	store_file_contents('cache/events/purge-debugging-logs.dat', date('Y-m-d H:i:s'));
	
	$store_file_contents = store_file_contents($base_dir . '/cache/logs/debugging.log', $debugging_logs); // NULL if no new debugging, but that's OK because we are purging any old entries 
		
			if ( $store_file_contents != true ) {
			return 'Debugging logs write error for "' . $base_dir . '/cache/logs/debugging.log" (MAKE SURE YOUR DISK ISN\'T FULL), data_size_bytes: ' . strlen($debugging_logs) . ' bytes';
			}
			// DEBUGGING ONLY (rules out issues other than full disk)
			elseif ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'telemetry' ) {
			return 'Debugging logs write success for "' . $base_dir . '/cache/logs/debugging.log", data_size_bytes: ' . strlen($debugging_logs) . ' bytes';
			}
	
	}
	elseif ( $debugging_logs != null ) {
		
	$store_file_contents = store_file_contents($base_dir . '/cache/logs/debugging.log', $debugging_logs, "append");
		
			if ( $store_file_contents != true ) {
			return 'Debugging logs write error for "' . $base_dir . '/cache/logs/debugging.log" (MAKE SURE YOUR DISK ISN\'T FULL), data_size_bytes: ' . strlen($debugging_logs) . ' bytes';
			}
			// DEBUGGING ONLY (rules out issues other than full disk)
			elseif ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'telemetry' ) {
			return 'Debugging logs write success for "' . $base_dir . '/cache/logs/debugging.log", data_size_bytes: ' . strlen($debugging_logs) . ' bytes';
			}
		
	}
	
	
return true;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function error_logs() {

global $app_config, $base_dir, $logs_array;

// Combine all errors logged
$error_logs .= strip_tags($logs_array['system_error']); // Remove any HTML formatting used in UI alerts

$error_logs .= strip_tags($logs_array['config_error']); // Remove any HTML formatting used in UI alerts

$error_logs .= strip_tags($logs_array['security_error']); // Remove any HTML formatting used in UI alerts

$error_logs .= strip_tags($logs_array['ext_api_error']); // Remove any HTML formatting used in UI alerts

$error_logs .= strip_tags($logs_array['int_api_error']); // Remove any HTML formatting used in UI alerts

$error_logs .= strip_tags($logs_array['market_error']); // Remove any HTML formatting used in UI alerts

$error_logs .= strip_tags($logs_array['other_error']); // Remove any HTML formatting used in UI alerts


	foreach ( $logs_array['cache_error'] as $error ) {
	$error_logs .= strip_tags($error); // Remove any HTML formatting used in UI alerts
	}


	// If it's time to email error logs...
	if ( $app_config['comms']['email_logs'] > 0 && update_cache_file('cache/events/email-error-logs.dat', ( $app_config['comms']['email_logs'] * 1440 ) ) == true ) {
		
	$emailed_logs = "\n\n ------------------errors.log------------------ \n\n" . file_get_contents('cache/logs/errors.log') . "\n\n ------------------smtp_errors.log------------------ \n\n" . file_get_contents('cache/logs/smtp_errors.log');
		
	$message = " Here are the current error logs from the ".$base_dir."/cache/logs/ directory: \n =========================================================================== \n \n"  . ( $emailed_logs != '' ? $emailed_logs : 'No error logs currently.' );
	
  	// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
   $send_params = array(
          					'email' => array(
          											'subject' => 'DFD Cryptocoin Values - Error Logs Report',
     													'message' => $message
          											)
          					);
          	
   // Send notifications
   @queue_notifications($send_params);
          	
	store_file_contents($base_dir . '/cache/events/email-error-logs.dat', date('Y-m-d H:i:s')); // Track this emailing event, to determine next time to email logs again.
	
	}
	
	
	// Log errors...Purge old logs before storing new logs, if it's time to...otherwise just append.
	if ( update_cache_file('cache/events/purge-error-logs.dat', ( $app_config['developer']['log_purge'] * 1440 ) ) == true ) {
		
	store_file_contents($base_dir . '/cache/logs/smtp_errors.log', null);
	
	store_file_contents('cache/events/purge-error-logs.dat', date('Y-m-d H:i:s'));
	
	$store_file_contents = store_file_contents($base_dir . '/cache/logs/errors.log', $error_logs); // NULL if no new errors, but that's OK because we are purging any old entries 
		
			if ( $store_file_contents != true ) {
			return 'Error logs write error for "' . $base_dir . '/cache/logs/errors.log" (MAKE SURE YOUR DISK ISN\'T FULL), data_size_bytes: ' . strlen($error_logs) . ' bytes';
			}
			// DEBUGGING ONLY (rules out issues other than full disk)
			elseif ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'telemetry' ) {
			return 'Error logs write success for "' . $base_dir . '/cache/logs/errors.log", data_size_bytes: ' . strlen($error_logs) . ' bytes';
			}
	
	}
	elseif ( $error_logs != null ) {
		
	$store_file_contents = store_file_contents($base_dir . '/cache/logs/errors.log', $error_logs, "append");
		
			if ( $store_file_contents != true ) {
			return 'Error logs write error for "' . $base_dir . '/cache/logs/errors.log" (MAKE SURE YOUR DISK ISN\'T FULL), data_size_bytes: ' . strlen($error_logs) . ' bytes';
			}
			// DEBUGGING ONLY (rules out issues other than full disk)
			elseif ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'telemetry' ) {
			return 'Error logs write success for "' . $base_dir . '/cache/logs/errors.log", data_size_bytes: ' . strlen($error_logs) . ' bytes';
			}
	
	}
	
	
return true;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function queue_notifications($send_params) {

global $base_dir, $app_config, $telegram_activated;


	// Queue messages
	
	// RANDOM HASH SHOULD BE CALLED PER-STATEMENT, OTHERWISE FOR SOME REASON SEEMS TO REUSE SAME HASH FOR THE WHOLE RUNTIME INSTANCE (if set as a variable beforehand)
	
	// Notifyme
   if ( $send_params['notifyme'] != '' && trim($app_config['comms']['notifyme_accesscode']) != '' ) {
	store_file_contents($base_dir . '/cache/secured/messages/notifyme-' . random_hash(8) . '.queue', $send_params['notifyme']);
   }
  
   // Textbelt
	// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
   if ( $send_params['text']['message'] != '' && trim($app_config['comms']['textbelt_apikey']) != '' && $app_config['comms']['textlocal_account'] == '' ) { // Only run if textlocal API isn't being used to avoid double texts
	store_file_contents($base_dir . '/cache/secured/messages/textbelt-' . random_hash(8) . '.queue', $send_params['text']['message']);
   }
  
   // Textlocal
	// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
   if ( $send_params['text']['message'] != '' && $app_config['comms']['textlocal_account'] != '' && trim($app_config['comms']['textbelt_apikey']) == '' ) { // Only run if textbelt API isn't being used to avoid double texts
	store_file_contents($base_dir . '/cache/secured/messages/textlocal-' . random_hash(8) . '.queue', $send_params['text']['message']);
   }
	
	// Telegram
   if ( $send_params['telegram'] != '' && $telegram_activated == 1 ) {
	store_file_contents($base_dir . '/cache/secured/messages/telegram-' . random_hash(8) . '.queue', $send_params['telegram']);
   }
   
           
   // Text email
	// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
	// Only use text-to-email if other text services aren't configured
   if ( $send_params['text']['message'] != '' && validate_email( text_email($app_config['comms']['to_mobile_text']) ) == 'valid' && trim($app_config['comms']['textbelt_apikey']) == '' && $app_config['comms']['textlocal_account'] == '' ) { 
   
   // $send_params['text_charset'] SHOULD ALWAYS BE SET FROM THE CALL TO HERE (for emojis, or other unicode characters to send via text message properly)
   // SUBJECT !!MUST BE SET!! OR SOME TEXT SERVICES WILL NOT ACCEPT THE MESSAGE!
   $textemail_array = array('subject' => 'Text Notify', 'message' => $send_params['text']['message'], 'content_type' => 'text', 'charset' => $send_params['text']['charset'] );
   
   	// json_encode() only accepts UTF-8, SO TEMPORARILY CONVERT TO THAT FOR MESSAGE QUEUE STORAGE
   	if ( strtolower($send_params['text']['charset']) != 'utf-8' ) {
   		
   		foreach( $textemail_array as $textemail_key => $textemail_value ) {
   		$textemail_array[$textemail_key] = mb_convert_encoding($textemail_value, 'UTF-8', mb_detect_encoding($textemail_value, "auto") );
   		}
   	
   	}
   
	store_file_contents($base_dir . '/cache/secured/messages/textemail-' . random_hash(8) . '.queue', json_encode($textemail_array) );
	
   }
          
   // Normal email
   if ( $send_params['email']['message'] != '' && validate_email($app_config['comms']['to_email']) == 'valid' ) {
   
   $email_array = array('subject' => $send_params['email']['subject'], 'message' => $send_params['email']['message'], 'content_type' => ( $send_params['email']['content_type'] ? $send_params['email']['content_type'] : 'text' ), 'charset' => ( $send_params['email']['charset'] ? $send_params['email']['charset'] : $app_config['developer']['charset_default'] ) );
   
   	// json_encode() only accepts UTF-8, SO TEMPORARILY CONVERT TO THAT FOR MESSAGE QUEUE STORAGE
   	if ( strtolower($send_params['email']['charset']) != 'utf-8' ) {
   		
   		foreach( $email_array as $email_key => $email_value ) {
   		$email_array[$email_key] = mb_convert_encoding($email_value, 'UTF-8', mb_detect_encoding($email_value, "auto") );
   		}
   	
   	}
   
	store_file_contents($base_dir . '/cache/secured/messages/normalemail-' . random_hash(8) . '.queue', json_encode($email_array) );
	
   }
  

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function test_proxy($problem_proxy_array) {

global $base_dir, $app_config, $runtime_mode, $proxies_checked;


// Endpoint to test proxy connectivity: https://www.myip.com/api-docs/
$proxy_test_url = 'https://api.myip.com/';


$problem_endpoint = $problem_proxy_array['endpoint'];

$obfuscated_url_data = obfuscated_url_data($problem_endpoint); // Automatically removes sensitive URL data

$problem_proxy = $problem_proxy_array['proxy'];

$ip_port = explode(':', $problem_proxy);

$ip = $ip_port[0];
$port = $ip_port[1];

	// If no ip/port detected in data string, cancel and continue runtime
	if ( !$ip || !$port ) {
	app_logging('ext_api_error', 'proxy '.$problem_proxy.' is not a valid format');
	return false;
	}

// Create cache filename / session var
$cache_filename = $problem_proxy;
$cache_filename = preg_replace("/\./", "-", $cache_filename);
$cache_filename = preg_replace("/:/", "_", $cache_filename);

	if ( $app_config['comms']['proxy_alerts_runtime'] == 'all' ) {
	$run_alerts = 1;
	}
	elseif ( $app_config['comms']['proxy_alerts_runtime'] == 'cron' && $runtime_mode == 'cron' ) {
	$run_alerts = 1;
	}
	elseif ( $app_config['comms']['proxy_alerts_runtime'] == 'ui' && $runtime_mode == 'ui' ) {
	$run_alerts = 1;
	}
	else {
	$run_alerts = null;
	}

	if ( $run_alerts == 1 && update_cache_file('cache/alerts/proxy-check-'.$cache_filename.'.dat', ( $app_config['comms']['proxy_alerts_freq_max'] * 60 ) ) == true
	&& in_array($cache_filename, $proxies_checked) == false ) {
	
		
	// SESSION VAR first, to avoid duplicate alerts at runtime (and longer term cache file locked for writing further down, after logs creation)
	$proxies_checked[] = $cache_filename;
		
	$jsondata = @external_api_data('proxy-check', $proxy_test_url, 0, '', '', $problem_proxy);
	
	$data = json_decode($jsondata, true);
	
		if ( sizeof($data) > 0 ) {

			
			// Look for the IP in the response
			if ( strstr($data['ip'], $ip) == false ) {
				
			$misconfigured = 1;
			
			$notifyme_alert = 'A checkup on proxy ' . $ip . ', port ' . $port . ' detected a misconfiguration. Remote address ' . $data['ip'] . ' does not match the proxy address. Runtime mode is ' . $runtime_mode . '.';
			
			$text_alert = 'Proxy ' . $problem_proxy . ' remote address mismatch (detected as: ' . $data['ip'] . '). runtime: ' . $runtime_mode;
		
			}
			
			
		$cached_logs = ( $misconfigured == 1 ? 'runtime: ' . $runtime_mode . "; \n " . 'Proxy ' . $problem_proxy . ' checkup status = MISCONFIGURED (test endpoint ' . $proxy_test_url . ' detected the incoming ip as: ' . $data['ip'] . ')' . "; \n " . 'Remote address DOES NOT match proxy address;' : 'runtime: ' . $runtime_mode . "; \n " . 'Proxy ' . $problem_proxy . ' checkup status = OK (test endpoint ' . $proxy_test_url . ' detected the incoming ip as: ' . $data['ip'] . ');' );
		
		
		}
		else {
			
		$misconfigured = 1;
		
		$notifyme_alert = 'A checkup on proxy ' . $ip . ', port ' . $port . ' resulted in a failed data request. No endpoint connection could be established. Runtime mode is ' . $runtime_mode . '.';
			
		$text_alert = 'Proxy ' . $problem_proxy . ' failed, no endpoint connection. runtime: ' . $runtime_mode;
		
		$cached_logs = 'runtime: ' . $runtime_mode . "; \n " . 'Proxy ' . $problem_proxy . ' checkup status = DATA REQUEST FAILED' . "; \n " . 'No connection established at test endpoint ' . $proxy_test_url . ';';

		}
		
		
		// Log to error logs
		if ( $misconfigured == 1 ) {
		app_logging('ext_api_error', 'proxy '.$problem_proxy.' connection failed', $cached_logs);
		}
	

		// Update alerts cache for this proxy (to prevent running alerts for this proxy too often)
		store_file_contents($base_dir . '/cache/alerts/proxy-check-'.$cache_filename.'.dat', $cached_logs);
			
      
      $email_alert = " The proxy " . $problem_proxy . " recently did not receive data when accessing this endpoint: \n " . $obfuscated_url_data . " \n \n A check on this proxy was performed at " . $proxy_test_url . ", and results logged: \n ============================================================== \n " . $cached_logs . " \n ============================================================== \n \n ";
                    
		
		// Send out alerts
		if ( $misconfigured == 1 || $app_config['comms']['proxy_alerts_checkup_ok'] == 'include' ) {
                    
                    
  				// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
  				if ( $app_config['comms']['proxy_alerts'] == 'all' ) {
  				
  				// Minimize function calls
  				$encoded_text_alert = content_data_encoding($text_alert);
  					
          	$send_params = array(
          								'notifyme' => $notifyme_alert,
          								'telegram' => $email_alert,
          								'text' => array(
          														// Unicode support included for text messages (emojis / asian characters / etc )
          														'message' => $encoded_text_alert['content_output'],
          														'charset' => $encoded_text_alert['charset']
          														),
          								'email' => array(
          														'subject' => 'A Proxy Was Unresponsive',
          														'message' => $email_alert
          														)
          								);
          	
          	}
  				elseif ( $app_config['comms']['proxy_alerts'] == 'email' ) {
  					
          	$send_params['email'] = array(
          											'subject' => 'A Proxy Was Unresponsive',
          											'message' => $email_alert
          											);
          	
          	}
  				elseif ( $app_config['comms']['proxy_alerts'] == 'text' ) {
  				
  				// Minimize function calls
  				$encoded_text_alert = content_data_encoding($text_alert);
  				
          	$send_params['text'] = array(
          											// Unicode support included for text messages (emojis / asian characters / etc )
          											'message' => $encoded_text_alert['content_output'],
          											'charset' => $encoded_text_alert['charset']
          											
          											);
          	
          	}
  				elseif ( $app_config['comms']['proxy_alerts'] == 'notifyme' ) {
          	$send_params['notifyme'] = $notifyme_alert;
          	}
  				elseif ( $app_config['comms']['proxy_alerts'] == 'telegram' ) {
          	$send_params['telegram'] = $email_alert;
          	}
          	
          	
          	// Send notifications
          	@queue_notifications($send_params);
          	
           
       }
          
          
		
	}



}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function send_notifications() {

global $base_dir, $app_config, $processed_messages, $possible_http_users, $http_runtime_user, $current_runtime_user, $telegram_user_data, $telegram_activated;


// Array of currently queued messages in the cache
$messages_queue = sort_files($base_dir . '/cache/secured/messages', 'queue', 'asc');
	
//var_dump($messages_queue); // DEBUGGING ONLY
//return false; // DEBUGGING ONLY


	// If queued messages exist, proceed
	if ( sizeof($messages_queue) > 0 ) {
	
	
	
		if ( !isset($processed_messages['notifications_count']) ) {
		$processed_messages['notifications_count'] = 0;
		}
		
		
		
		// If it's been well over 5 minutes since a notifyme alert was sent 
		// (we use 6 minutes, safely over the 5 minute limit for the maximum 5 requests), 
		// and no session count is set, set session count to zero
		// Don't update the file-cached count here, that will happen automatically from resetting the session count to zero 
		// (if there are notifyme messages queued to send)
		if ( !isset($processed_messages['notifyme_count']) && update_cache_file($base_dir . '/cache/events/throttling/notifyme-alerts-sent.dat', 6) == true ) {
		$processed_messages['notifyme_count'] = 0;
		}
		// If it hasn't been well over 5 minutes since the last notifyme send
		// (we use 6 minutes, safely over the 5 minute limit for the maximum 5 requests), and there is no session count, 
		// use the file-cached count for the session count starting point
		elseif ( !isset($processed_messages['notifyme_count']) && update_cache_file($base_dir . '/cache/events/throttling/notifyme-alerts-sent.dat', 6) == false ) {
		$processed_messages['notifyme_count'] = trim( file_get_contents($base_dir . '/cache/events/throttling/notifyme-alerts-sent.dat') );
		}
		
		
		
		if ( !isset($processed_messages['text_count']) ) {
		$processed_messages['text_count'] = 0;
		}
		
		
		
		if ( !isset($processed_messages['telegram_count']) ) {
		$processed_messages['telegram_count'] = 0;
		}
		
		
		
		if ( !isset($processed_messages['email_count']) ) {
		$processed_messages['email_count'] = 0;
		}
	
		
	
		// ONLY process queued messages IF they are NOT already being processed by another runtime instance
		// Use file locking with flock() to do this
		
		$queued_messages_processing_lock_file = $base_dir . '/cache/events/notifications-queue-processing.dat';
		
		$fp = fopen($queued_messages_processing_lock_file, "w+");
		
		if ( flock($fp, LOCK_EX) ) {  // If we are allowed a file lock, we can proceed
		
		////////////START//////////////////////
		
		
			// Sleep for 2 seconds before starting ANY consecutive message send, to help avoid being blacklisted
			if ( $processed_messages['notifications_count'] > 0 ) {
			sleep(2);
			}
			
		
		
		$notifyme_params = array(
									 'notification' => null, // Setting this right before sending
									 'accessCode' => $app_config['comms']['notifyme_accesscode']
									   );
						
						
		$textbelt_params = array(
									 'message' => null, // Setting this right before sending
									 'phone' => text_number($app_config['comms']['to_mobile_text']),
									 'key' => $app_config['comms']['textbelt_apikey']
									);
						
						
		$textlocal_params = array(
									  'message' => null, // Setting this right before sending
									  'username' => string_to_array($app_config['comms']['textlocal_account'])[0],
									  'hash' => string_to_array($app_config['comms']['textlocal_account'])[1],
									  'numbers' => text_number($app_config['comms']['to_mobile_text'])
									   );
		
		
		
			
			// Send messages
			foreach ( $messages_queue as $queued_cache_file ) {
				
			
			
			$message_data = trim( file_get_contents($base_dir . '/cache/secured/messages/' . $queued_cache_file) );
			
			
				
				// If 0 bytes from system / network issues, just delete it to keep the directory contents clean
				if ( filesize($base_dir . '/cache/secured/messages/' . $queued_cache_file) == 0 ) {
				unlink($base_dir . '/cache/secured/messages/' . $queued_cache_file);
				}
				// Notifyme
			   elseif ( $message_data != '' && trim($app_config['comms']['notifyme_accesscode']) != '' && preg_match("/notifyme/i", $queued_cache_file) ) { 
			   
			   $notifyme_params['notification'] = $message_data;
			   
				// Sleep for 1 second EXTRA on EACH consecutive notifyme message, to throttle MANY outgoing messages, to help avoid being blacklisted
				$notifyme_sleep = 1 * $processed_messages['notifyme_count'];
				sleep($notifyme_sleep);
				
					
					// Only 5 notifyme messages allowed per minute
					if ( $processed_messages['notifyme_count'] < 5 ) {
					
					$notifyme_response = @external_api_data('array', $notifyme_params, 0, 'https://api.notifymyecho.com/v1/NotifyMe');
				
					$processed_messages['notifyme_count'] = $processed_messages['notifyme_count'] + 1;
					
					$message_sent = 1;
					
					store_file_contents($base_dir . '/cache/events/throttling/notifyme-alerts-sent.dat', $processed_messages['notifyme_count']); 
					
						if ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'telemetry' ) {
						store_file_contents($base_dir . '/cache/logs/debugging/external_api/last-response-notifyme.log', $notifyme_response);
						}
					
					unlink($base_dir . '/cache/secured/messages/' . $queued_cache_file);
					
					}
				
				
				
			   }
			  
			  
			  
			   // Textbelt
				// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
				// Only run if textlocal API isn't being used to avoid double texts
			   if ( $message_data != '' && trim($app_config['comms']['textbelt_apikey']) != '' && $app_config['comms']['textlocal_account'] == '' && preg_match("/textbelt/i", $queued_cache_file) ) {  
			   
			   $textbelt_params['message'] = $message_data;
			   
				// Sleep for 1 second EXTRA on EACH consecutive text message, to throttle MANY outgoing messages, to help avoid being blacklisted
				$text_sleep = 1 * $processed_messages['text_count'];
				sleep($text_sleep);
			   
			   $textbelt_response = @external_api_data('array', $textbelt_params, 0, 'https://textbelt.com/text', 2);
			   
			   $processed_messages['text_count'] = $processed_messages['text_count'] + 1;
				
				$message_sent = 1;
			   
			   	if ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'telemetry' ) {
					store_file_contents($base_dir . '/cache/logs/debugging/external_api/last-response-textbelt.log', $textbelt_response);
					}
				
				unlink($base_dir . '/cache/secured/messages/' . $queued_cache_file);
				
			   }
			  
			  
			  
			   // Textlocal
				// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
				// Only run if textbelt API isn't being used to avoid double texts
			   if ( $message_data != '' && $app_config['comms']['textlocal_account'] != '' && trim($app_config['comms']['textbelt_apikey']) == '' && preg_match("/textlocal/i", $queued_cache_file) ) {  
			   
			   $textlocal_params['message'] = $message_data;
			   
				// Sleep for 1 second EXTRA on EACH consecutive text message, to throttle MANY outgoing messages, to help avoid being blacklisted
				$text_sleep = 1 * $processed_messages['text_count'];
				sleep($text_sleep);
			   
			   $textlocal_response = @external_api_data('array', $textlocal_params, 0, 'https://api.txtlocal.com/send/', 1);
			   
			   $processed_messages['text_count'] = $processed_messages['text_count'] + 1;
				
				$message_sent = 1;
			   
			   	if ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'telemetry' ) {
					store_file_contents($base_dir . '/cache/logs/debugging/external_api/last-response-textlocal.log', $textlocal_response);
					}
				
				unlink($base_dir . '/cache/secured/messages/' . $queued_cache_file);
				
			   }
			  
			  
			  
			   // Telegram
			   if ( $telegram_activated == 1 && preg_match("/telegram/i", $queued_cache_file) ) {  
			   
				// Sleep for 1 second EXTRA on EACH consecutive telegram message, to throttle MANY outgoing messages, to help avoid being blacklisted
				$telegram_sleep = 1 * $processed_messages['telegram_count'];
				sleep($telegram_sleep);
			   
			   
			   $telegram_response = telegram_message($message_data, $telegram_user_data['message']['chat']['id']);
				
				
			   	if ( $telegram_response != false ) {
			   		
			   	$processed_messages['telegram_count'] = $processed_messages['telegram_count'] + 1;
			   	
					$message_sent = 1;
					
					unlink($base_dir . '/cache/secured/messages/' . $queued_cache_file);
			   		
			   	}
			   	else {
			   	app_logging( 'system_error', 'Telegram sending failed', $telegram_response);
			   	}
			   		
			   
			   	if ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'telemetry' ) {
					store_file_contents($base_dir . '/cache/logs/debugging/external_api/last-response-telegram.log', $telegram_response);
					}
				
				
			   }
			   
					   
					   
			   // SEND EMAILS LAST, IN CASE OF SMTP METHOD FAILURE AND RUNTIME EXIT
			  
			  
			  
			   // Text email
				// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
				// Only use text-to-email if other text services aren't configured
			   if ( validate_email( text_email($app_config['comms']['to_mobile_text']) ) == 'valid' && trim($app_config['comms']['textbelt_apikey']) == '' && $app_config['comms']['textlocal_account'] == '' && preg_match("/textemail/i", $queued_cache_file) ) { 
			   
			   $textemail_array = json_decode($message_data, true);
			   
			   $restore_text_charset = $textemail_array['charset'];
			   
   				// json_encode() only accepts UTF-8, SO CONVERT BACK TO ORIGINAL CHARSET
   				if ( strtolower($restore_text_charset) != 'utf-8' ) {
   					
   					foreach( $textemail_array as $textemail_key => $textemail_value ) {
   					// Leave charset / content_type vars UTF-8
   					$textemail_array[$textemail_key] = ( $textemail_key == 'charset' || $textemail_key == 'content_type' ? $textemail_value : mb_convert_encoding($textemail_value, $restore_text_charset, 'UTF-8') );
   					}
   	
   				}
   
			   
					if ( $textemail_array['subject'] != '' && $textemail_array['message'] != '' ) {
						
					// Sleep for 1 second EXTRA on EACH consecutive text message, to throttle MANY outgoing messages, to help avoid being blacklisted
					$text_sleep = 1 * $processed_messages['text_count'];
					sleep($text_sleep);
			   
					$result = @safe_mail( text_email($app_config['comms']['to_mobile_text']) , $textemail_array['subject'], $textemail_array['message'], $textemail_array['content_type'], $textemail_array['charset']);
			   
			   		if ( $result == true ) {
			   		
			   		$processed_messages['text_count'] = $processed_messages['text_count'] + 1;
			   	
						$message_sent = 1;
					
						unlink($base_dir . '/cache/secured/messages/' . $queued_cache_file);
			   		
			   		}
			   		else {
			   		app_logging( 'system_error', 'Email-to-mobile-text sending failed', 'to_text_email: ' . text_email($app_config['comms']['to_mobile_text']) . '; from: ' . $app_config['comms']['from_email'] . '; subject: ' . $textemail_array['subject'] . '; function_response: ' . $result . ';');
			   		}
					
					
					}
				
				
			   }
					  
					  
					  
			   // Normal email
			   if ( validate_email($app_config['comms']['to_email']) == 'valid' && preg_match("/normalemail/i", $queued_cache_file) ) {
			   
			   $email_array = json_decode($message_data, true);
			   
			   $restore_email_charset = $email_array['charset'];
			   
   				// json_encode() only accepts UTF-8, SO CONVERT BACK TO ORIGINAL CHARSET
   				if ( strtolower($restore_email_charset) != 'utf-8' ) {
   					
   					foreach( $email_array as $email_key => $email_value ) {
   					// Leave charset / content_type vars UTF-8
   					$email_array[$email_key] = ( $email_key == 'charset' || $email_key == 'content_type' ? $email_value : mb_convert_encoding($email_value, $restore_email_charset, 'UTF-8') );
   					}
   	
   				}
			   
			   
					if ( $email_array['subject'] != '' && $email_array['message'] != '' ) {
			   
					// Sleep for 1 second EXTRA on EACH consecutive email message, to throttle MANY outgoing messages, to help avoid being blacklisted
					$email_sleep = 1 * $processed_messages['email_count'];
					sleep($email_sleep);
			   
					$result = @safe_mail($app_config['comms']['to_email'], $email_array['subject'], $email_array['message'], $email_array['content_type'], $email_array['charset']);
			   
			   		if ( $result == true ) {
			   		
			   		$processed_messages['email_count'] = $processed_messages['email_count'] + 1;
			   	
						$message_sent = 1;
					
						unlink($base_dir . '/cache/secured/messages/' . $queued_cache_file);
			   		
			   		}
			   		else {
			   		app_logging( 'system_error', 'Email sending failed', 'to_email: ' . $app_config['comms']['to_email'] . '; from: ' . $app_config['comms']['from_email'] . '; subject: ' . $email_array['subject'] . '; function_response: ' . $result . ';');
			   		}
			   		
					
					}
				
				
			   }
			   
		   
			
			
		   }
	  
	  
	  
			if ( $message_sent == 1 ) {
			$processed_messages['notifications_count'] = $processed_messages['notifications_count'] + 1;
			}
		
		
		
		////////////END//////////////////////
		
		
		
		// We are done processing the queue, so we can release the lock
	   fwrite($fp, time_date_format(false, 'pretty_date_time'). " UTC (with file lock)\n");
	   fflush($fp);            // flush output before releasing the lock
	   flock($fp, LOCK_UN);    // release the lock
		$result = true;
		} 
		else {
	   fwrite($fp, time_date_format(false, 'pretty_date_time'). " UTC (no file lock)\n");
	   $result = false; // Another runtime instance was already processing the queue, so skip processing and return false
		}
		
		fclose($fp);
	
	
	   // MAKE SURE we have good chmod file permissions for less-sophisticated server setups
	   $path_parts = pathinfo($queued_messages_processing_lock_file);
	   $file_owner_info = posix_getpwuid(fileowner($queued_messages_processing_lock_file));
	   
	   // Does the current runtime user own this file?
		if ( isset($current_runtime_user) && $current_runtime_user == $file_owner_info['name'] ) {
		
		$chmod_setting = octdec($app_config['developer']['chmod_cache_files']);
		
			// Run chmod compatibility on certain PHP setups
			if ( !$http_runtime_user || isset($http_runtime_user) && in_array($http_runtime_user, $possible_http_users) ) {
				
			$oldmask = umask(0);
			
			$did_chmod = chmod($queued_messages_processing_lock_file, $chmod_setting);
		
				if ( !$did_chmod ) {
				app_logging('system_error', 'Chmod failed for file "' . $queued_messages_processing_lock_file . '" (check permissions for the path "' . $path_parts['dirname'] . '", and the file "' . $path_parts['basename'] . '")', 'chmod_setting: ' . $chmod_setting . '; current_runtime_user: ' . $current_runtime_user . '; file_owner: ' . $file_owner_info['name'] . ';');
				}
		
			umask($oldmask);
			
			}
		
		}
	   
	return $result;
	
	}
	else {
	return false; // No messages are queued to send, so skip and return false
	}



}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function external_api_data($mode, $request, $ttl, $api_server=null, $post_encoding=3, $test_proxy=null, $headers=null) { // Default to JSON encoding post requests (most used)

// $app_config['general']['btc_primary_currency_pairing'] / $app_config['general']['btc_primary_exchange'] / $selected_btc_primary_currency_value USED FOR TRACE DEBUGGING (TRACING)
global $base_dir, $proxy_checkup, $logs_array, $limited_api_calls, $app_config, $api_runtime_cache, $selected_btc_primary_currency_value, $user_agent, $base_url, $htaccess_username, $htaccess_password;


$cookie_jar = tempnam('/tmp','cookie');
	
	
// To cache duplicate requests based on a data hash, during runtime update session (AND persist cache to flat files)
$hash_check = ( $mode == 'array' ? md5(serialize($request)) : md5($request) );

	
$api_endpoint = ( $mode == 'array' ? $api_server : $request );
		
	
	// If we are encoding the url (not sure as useful / functional, for other than debugging?)
	if ( $mode == 'encoded_url' ) {

	$api_endpoint_parts = parse_url($api_endpoint);

	$api_endpoint_encoded = $api_endpoint_parts['scheme'] . '://' . $api_endpoint_parts['host'] . ( $api_endpoint_parts['port'] ? ':' . $api_endpoint_parts['port'] : '' ) . $api_endpoint_parts['path'] . ( $api_endpoint_parts['query'] ? '?' . urlencode($api_endpoint_parts['query']) : '' );

	}
	

	// Cache API data if set to cache...runtime cache is only for runtime cache (deleted at end of runtime)
	// ...persistent cache is the file cache (which only reliably updates near end of a runtime session because of file locking)


	//////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////
	// If flagged for FILE cache deletion with -1 as $ttl
	//////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////
	if ( $ttl < 0 ) {
	unlink('cache/secured/external_api/'.$hash_check.'.dat');
	}
	//////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////
	// FIRST, see if we have data in the RUNTIME cache (the MEMORY cache, NOT the FILE cache), for the quickest data retrieval time
	// Only use runtime cache if $ttl greater than zero (set as 0 NEVER wants cached data, -1 is flag for deleting cache data)
	//////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////
	elseif ( isset($api_runtime_cache[$hash_check]) && $ttl > 0 ) {
	
	$data = $api_runtime_cache[$hash_check];
	
		
		if ( $data == 'none' ) {
		
			if ( !$logs_array['error_duplicates'][$hash_check] ) {
			$logs_array['error_duplicates'][$hash_check] = 1; 
			}
			else {
			$logs_array['error_duplicates'][$hash_check] = $logs_array['error_duplicates'][$hash_check] + 1;
			}
			
		// Don't log this error again during THIS runtime, as it would be a duplicate...just overwrite same error message, BUT update the error count in it
		
		app_logging( 'cache_error', 'no RUNTIME CACHE data from failure with ' . ( $mode == 'array' ? 'server at ' : 'endpoint at ' ) . obfuscated_url_data($api_endpoint), 'request attempt(s) from: cache ('.$logs_array['error_duplicates'][$hash_check].' runtime instances); mode: ' . $mode . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; hash_check: ' . obfuscate_string($hash_check, 4) . ';', $hash_check );
			
		}
		elseif ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'telemetry' || $app_config['developer']['debug_mode'] == 'api_cache_only' ) {
		
			if ( !$logs_array['debugging_duplicates'][$hash_check] ) {
			$logs_array['debugging_duplicates'][$hash_check] = 1; 
			}
			else {
			$logs_array['debugging_duplicates'][$hash_check] = $logs_array['debugging_duplicates'][$hash_check] + 1;
			}
			
		// Don't log this debugging again during THIS runtime, as it would be a duplicate...just overwrite same debugging message, BUT update the debugging count in it
		
		app_logging( 'cache_debugging', 'RUNTIME CACHE request for ' . ( $mode == 'array' ? 'server at ' : 'endpoint at ' ) . obfuscated_url_data($api_endpoint), 'request(s) from: cache ('.$logs_array['debugging_duplicates'][$hash_check].' runtime instances); mode: ' . $mode . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; hash_check: ' . obfuscate_string($hash_check, 4) . ';', $hash_check );
		
		}
	
	
	}
	//////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////
	// Live data retrieval 
	//////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////
	elseif ( update_cache_file('cache/secured/external_api/'.$hash_check.'.dat', $ttl) == true || $ttl == 0 ) {
	
	// Time the request
	$api_time = microtime();
	$api_time = explode(' ', $api_time);
	$api_time = $api_time[1] + $api_time[0];
	$api_start_time = $api_time;
	
	$ch = curl_init( ( $mode == 'array' ? $api_endpoint : '' ) );
	
	
		// If this is an API service that requires multiple calls (for each market), 
		// and a request to it has been made consecutively, we throttle it to avoid being blacklisted
		$endpoint_tld_or_ip = get_tld_or_ip($api_endpoint);
		
		// Throttled endpoints
		if ( in_array($endpoint_tld_or_ip, $app_config['developer']['limited_apis']) ) {
		
		$tld_session_prefix = preg_replace("/\./i", "_", $endpoint_tld_or_ip);
		
			if ( !$limited_api_calls[$tld_session_prefix . '_calls'] ) {
			$limited_api_calls[$tld_session_prefix . '_calls'] = 1;
			}
			elseif ( $limited_api_calls[$tld_session_prefix . '_calls'] == 1 ) {
			usleep(1550000); // Throttle 1.55 seconds
			}

		}
		
		
		// If header data is being passed in
		if ( $headers != null ) {
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}
		
		
		// If proxies are configured
		if ( sizeof($app_config['proxy']['proxy_list']) > 0 ) {
			
		$current_proxy = ( $mode == 'proxy-check' && $test_proxy != null ? $test_proxy : random_array_var($app_config['proxy']['proxy_list']) );
		
		// Check for valid proxy config
		$ip_port = explode(':', $current_proxy);

		$ip = $ip_port[0];
		$port = $ip_port[1];

			// If no ip/port detected in data string, cancel and continue runtime
			if ( !$ip || !$port ) {
			app_logging('ext_api_error', 'proxy '.$current_proxy.' is not a valid format');
			return false;
			}

		
		curl_setopt($ch, CURLOPT_PROXY, trim($current_proxy) );     
		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, true);  
		
			// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
			if ( $app_config['proxy']['proxy_login'] != ''  ) {
		
			$user_pass = explode('||', $app_config['proxy']['proxy_login']);
				
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $user_pass[0] . ':' . $user_pass[1]); // DO NOT ENCAPSULATE PHP USER/PASS VARS IN QUOTES, IT BREAKS THE FEATURE
			
			}
		
		} 
		else {
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
		}
		
		if ( $mode == 'array' && $post_encoding == 1 ) {
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request); // Works fine so far not encoded
		}
		elseif ( $mode == 'array' && $post_encoding == 2 ) {
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($request) ); // Encode post data with http_build_query()
		}
		elseif ( $mode == 'array' && $post_encoding == 3 ) {
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request) ); // json encoded
		}
		elseif ( $mode == 'url' || $mode == 'proxy-check' ) {
		curl_setopt($ch, CURLOPT_URL, $request); // Not encoded
		}
		elseif ( $mode == 'encoded_url' ) {
		curl_setopt($ch, CURLOPT_URL, $api_endpoint_encoded); // Encoded (not sure as useful / functional, for other than debugging?)
		}
	
	
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $app_config['power_user']['remote_api_timeout']);
	curl_setopt($ch, CURLOPT_TIMEOUT, $app_config['power_user']['remote_api_timeout']);
	
		
		// Medium / Reddit (and maybe whatbitcoindid) are a bit funky with allowed user agents, so we need to let them know this is a real feed parser (not just a spammy bot)
		$strict_feed_servers = array(
											'medium.com',
											'reddit.com',
											'whatbitcoindid.com',
											);
		
		if ( in_array($endpoint_tld_or_ip, $strict_feed_servers) ) {
		curl_setopt($ch, CURLOPT_USERAGENT, 'Custom_Feed_Parser/1.0 (compatible; DFD_Cryptocoin_Values/' . $app_version . '; +https://github.com/taoteh1221/DFD_Cryptocoin_Values)');
		}
		else {
		curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
		}
	
	
		// If this is an SSL connection, add SSL parameters
		if ( preg_match("/https:\/\//i", $api_endpoint) ) {
		
		
			// We don't want strict SSL checks if this is our app calling itself (as we may be running our own self-signed certificate)
			// (app running an external check on its htaccess, etc)
			$regex_base_url = regex_compat_url($base_url);
			
			// Secure random hash to nullify any preg_match() below, as we are submitting out htaccess user/pass if setup
			$scan_base_url = ( $regex_base_url != '' ? $regex_base_url : random_hash(8) );
			
			if ( isset($scan_base_url) && preg_match("/".$scan_base_url."/i", $api_endpoint) ) {
			
			$is_self_security_test = 1;
				
				// If we have password protection on in the app
				if ( $htaccess_username != '' && $htaccess_password != '' ) {
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
				curl_setopt($ch, CURLOPT_USERPWD, $htaccess_username . ':' . $htaccess_password); // DO NOT ENCAPSULATE PHP USER/PASS VARS IN QUOTES, IT BREAKS THE FEATURE
				}
				
			$remote_api_strict_ssl = 'off';
			
			}
			else {
			$remote_api_strict_ssl = $app_config['power_user']['remote_api_strict_ssl'];
			}
			
		
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, ( $remote_api_strict_ssl == 'on' ? true : false ) ); 
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, ( $remote_api_strict_ssl == 'on' ? 2 : 0 ) );
		
		
			if ( PHP_VERSION_ID >= 70700 && CURL_VERSION_ID >= 7410 ) {
			curl_setopt ($ch, CURLOPT_SSL_VERIFYSTATUS, ( $remote_api_strict_ssl == 'on' ? true : false ) ); 
			}


		}
		
	
		
		// DEBUGGING FOR PROBLEM ENDPOINT (DEVELOPER ONLY, #DISABLE THIS SECTION# AFTER DEBUGGING)
		// USAGE: $endpoint_tld_or_ip == 'domain.com' || preg_match("/domain\.com\/endpoint\/var/i", $api_endpoint)
		/*
		if ( $endpoint_tld_or_ip == 'lakebtc.com' ) {
		$debug_problem_endpoint_data = 1;
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		}
		*/
		
	
	// Get response data
	$data = curl_exec($ch);
	
	
		// IF DEBUGGING FOR PROBLEM ENDPOINT IS ENABLED
		if ( $debug_problem_endpoint_data ) {
		
		// Response data
		$debug_header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$debug_header = substr($data, 0, $debug_header_size);
		$debug_body = substr($data, $debug_header_size);
		
		// Debugging output
		$debug_data = "\n\n\n" . 'header_size: ' . $debug_header_size . ' bytes' . "\n\n\n" . 'header: ' . "\n\n\n" . $debug_header . "\n\n\n" . 'body: ' . "\n\n\n" . $debug_body . "\n\n\n";
		
		$debug_response_log = '/cache/logs/debugging/external_api/problem-endpoint-'.preg_replace("/\./", "_", $endpoint_tld_or_ip).'-hash-'.$hash_check.'-timestamp-'.time().'.log';
		
		// Store to file
		store_file_contents($base_dir . $debug_response_log, $debug_data);
		
		// Reset $data value to use the $debug_body value (to parse the json values out), 
		// SINCE WE INCLUDED HEADER DATA WITH CURLOPT_HEADER FOR DEBUGGING
		$data = $debug_body;
		
		}
	
	
	// Close connection
	curl_close($ch);
		
	
	// Calculate length of time the request took
	$api_time = microtime();
	$api_time = explode(' ', $api_time);
	$api_time = $api_time[1] + $api_time[0];
	$api_total_time = round( ($api_time - $api_start_time) , 3);
		
		
		// No data error logging, ONLY IF THIS IS #NOT# A SELF SECURITY TEST
		// NEW INSTALLS WILL RUN
		// !!!!!!!!!!!!!!!!!NEVER RUN $data THROUGH trim() FOR CHECKS ETC, AS trim() CAN FLIP OUT AND RETURN NULL IF OBSCURE SYMBOLS ARE PRESENT!!!!!!!!!!!!!!!!!
		if ( $data == '' && $is_self_security_test !=1 ) {
			
			
			// FALLBACK TO CACHE DATA, IF AVAILABLE (WE STILL LOG THE FAILURE, SO THIS OS OK)
			// Use runtime cache if it exists. Remember file cache doesn't update until session is nearly over because of file locking, so only reliable for persisting a cache long term
			// Run from runtime cache if requested again (for runtime speed improvements)
			if ( $api_runtime_cache[$hash_check] != '' && $api_runtime_cache[$hash_check] != 'none' ) {
			$data = $api_runtime_cache[$hash_check];
			$fallback_cache_data = 1;
			}
			else {
					
			$data = trim( file_get_contents('cache/secured/external_api/'.$hash_check.'.dat') );
				
				if ( $data != '' && $data != 'none' ) {
				$api_runtime_cache[$hash_check] = $data; // Create a runtime cache from the file cache, for any additional requests during runtime for this data set
				$fallback_cache_data = 1;
				}
				
			}
			
			if ( isset($fallback_cache_data) ) {
			$log_append = ' (cache fallback SUCCEEDED)';
			}
			else {
			$log_append = ' (cache fallback FAILED)';
			}
	
		
		// LOG-SAFE VERSION (no post data with API keys etc)
		app_logging('ext_api_error', 'connection failed for ' . ( $mode == 'array' ? 'server at ' : 'endpoint at ' ) . obfuscated_url_data($api_endpoint) . $log_append, 'requested from: server (' . $app_config['power_user']['remote_api_timeout'] . ' second timeout); live_request_time: ' . $api_total_time . ' seconds; mode: ' . $mode . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; hash_check: ' . obfuscate_string($hash_check, 4) . ';' );
		
		
			if ( sizeof($app_config['proxy']['proxy_list']) > 0 && $current_proxy != '' && $mode != 'proxy-check' ) { // Avoid infinite loops doing proxy checks

			$proxy_checkup[] = array(
															'endpoint' => ( $mode == 'array' ? 'server at ' : 'endpoint at ' ) . obfuscated_url_data($api_endpoint),
															'proxy' => $current_proxy
															);
															
			}
		
		
		}
		// Log this latest live data response, 
		// ONLY IF WE DETECT AN $endpoint_tld_or_ip, AND TTL IS !NOT! ZERO (TTL==0 usually means too many unique requests that would bloat the cache)
		elseif ( isset($data) && $endpoint_tld_or_ip != '' && $ttl != 0 ) {
		
		
			////////////////////////////////////////////////////////////////	
			// Checks for error false positives, BEFORE CHECKING FOR A POSSIBLE ERROR
			// https://www.php.net/manual/en/regexp.reference.meta.php
			// DON'T ADD TOO MANY CHECKS HERE, OR RUNTIME WILL SLOW SIGNIFICANTLY!!
			if ( preg_match("/xml version/i", $data) // RSS feeds (that are likely intact)
			|| preg_match("/\"error\":\[\],/i", $data) // kraken.com / generic
			|| preg_match("/\"error_code\":0/i", $data) ) { // coinmarketcap.com / generic
			$false_positive = 1;
			}
			
			
			// DON'T FLAG as a possible error if detected as a false positive already
			// (THIS LOGIC IS FOR STORING THE POSSIBLE ERROR IN /cache/logs/errors/external_api FOR REVIEW)
			if ( $false_positive != 1 ) {
				
				// MUST RUN BEFORE FALLBACK ATTEMPT TO CACHED DATA
				// If response seems to contain an error message ('error' only found once, no words containing 'error')
				// DON'T ADD TOO MANY CHECKS HERE, OR RUNTIME WILL SLOW SIGNIFICANTLY!!
				if ( substri_count($data, 'error') == 1 && !preg_match("/terror/i", $data) ) {
					
				// Log full results to file, WITH UNIQUE TIMESTAMP IN FILENAME TO AVOID OVERWRITES (FOR ADEQUATE DEBUGGING REVIEW)
				$error_response_log = '/cache/logs/errors/external_api/error-response-'.preg_replace("/\./", "_", $endpoint_tld_or_ip).'-hash-'.$hash_check.'-timestamp-'.time().'.log';
				
				// LOG-SAFE VERSION (no post data with API keys etc)
					app_logging('ext_api_error', 'POSSIBLE error for ' . ( $mode == 'array' ? 'server at ' : 'endpoint at ' ) . obfuscated_url_data($api_endpoint), 'requested from: server (' . $app_config['power_user']['remote_api_timeout'] . ' second timeout); live_request_time: ' . $api_total_time . ' seconds; mode: ' . $mode . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; debug_file: ' . $error_response_log . '; btc_primary_currency_pairing: ' . $app_config['general']['btc_primary_currency_pairing'] . '; btc_primary_exchange: ' . $app_config['general']['btc_primary_exchange'] . '; btc_primary_currency_value: ' . number_to_string($selected_btc_primary_currency_value) . '; hash_check: ' . obfuscate_string($hash_check, 4) . ';' );
				
				// Log this error response from this data request
				store_file_contents($base_dir . $error_response_log, $data);
					
				}
			
			
			}
			////////////////////////////////////////////////////////////////
			
			////////////////////////////////////////////////////////////////
			// FALLBACK ATTEMPT TO CACHED DATA, IF AVAILABLE (WE STILL LOG THE FAILURE, SO THIS OS OK)
			// WE DON'T WANT TO SLOW DOWN THE RUNTIME TOO MUCH, BUT WE WANT AS MUCH FALLBACK AS IS REASONABLE
			// If response is seen to NOT contain USUAL data, use cache if available
			
			// Check that we didn't detect as a false positive already
			if ( $false_positive != 1 ) {
			
				
				// DON'T ADD TOO MANY CHECKS HERE, OR RUNTIME WILL SLOW SIGNIFICANTLY!!
				if ( preg_match("/cf-error-type/i", $data) // Cloudflare (DDOS protection service)
				|| preg_match("/cf-browser-verification/i", $data) // Cloudflare (DDOS protection service)
				|| preg_match("/\"result\":{}/i", $data) // Kraken.com / generic
				|| preg_match("/\"result\":null/i", $data) // Bittrex.com / generic
				|| preg_match("/\"data\":null/i", $data) // Bitflyer.com / generic
				|| preg_match("/\"success\":false/i", $data) // BTCturk.com / Bittrex.com / generic
				|| preg_match("/EService:Unavailable/i", $data) // Kraken.com / generic
				|| preg_match("/\"reason\":\"Maintenance\"/i", $data) // Gemini.com / generic
				|| preg_match("/scheduled maintenance/i", $data) // Bittrex.com / generic
				|| preg_match("/site is down/i", $data) // Blockchain.info / generic
				|| preg_match("/something went wrong/i", $data) // Bitbns.com / generic
				|| preg_match("/temporarily unavailable/i", $data) // Bitfinex.com / generic
				|| preg_match("/Server Error/i", $data) // Kucoin.com / generic
				|| preg_match("/An error has occurred/i", $data) // Bitflyer.com / generic
				|| preg_match("/too many requests/i", $data) // reddit.com / generic
				|| $endpoint_tld_or_ip == 'bittrex.com' && !preg_match("/Volume/i", $data)
				|| $endpoint_tld_or_ip == 'lakebtc.com' && !preg_match("/volume/i", $data)
				|| $endpoint_tld_or_ip == 'localbitcoins.com' && !preg_match("/volume_btc/i", $data)
				|| $endpoint_tld_or_ip == 'coinmarketcap.com' && !preg_match("/last_updated/i", $data) ) {
				
				
					if ( $api_runtime_cache[$hash_check] != '' && $api_runtime_cache[$hash_check] != 'none' ) {
					$data = $api_runtime_cache[$hash_check];
					$fallback_cache_data = 1;
					}
					else {
						
					$data = trim( file_get_contents('cache/secured/external_api/'.$hash_check.'.dat') );
					
						if ( $data != '' && $data != 'none' ) {
						$api_runtime_cache[$hash_check] = $data; // Create a runtime cache from the file cache, for any additional requests during runtime for this data set
						$fallback_cache_data = 1;
						}
						
					}
				
				
					if ( isset($fallback_cache_data) ) {
					$log_append = ' (cache fallback SUCCEEDED)';
					}
					else {
					$log_append = ' (cache fallback FAILED)';
					}
					
					
				// LOG-SAFE VERSION (no post data with API keys etc)
				app_logging('ext_api_error', 'CONFIRMED error for ' . ( $mode == 'array' ? 'server at ' : 'endpoint at ' ) . obfuscated_url_data($api_endpoint) . $log_append, 'requested from: server (' . $app_config['power_user']['remote_api_timeout'] . ' second timeout); live_request_time: ' . $api_total_time . ' seconds; mode: ' . $mode . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; btc_primary_currency_pairing: ' . $app_config['general']['btc_primary_currency_pairing'] . '; btc_primary_exchange: ' . $app_config['general']['btc_primary_exchange'] . '; btc_primary_currency_value: ' . number_to_string($selected_btc_primary_currency_value) . '; hash_check: ' . obfuscate_string($hash_check, 4) . ';' );
					
			
				}

			
			}
			
			////////////////////////////////////////////////////////////////
			
		
		
			// Data debugging telemetry
			if ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'telemetry' || $app_config['developer']['debug_mode'] == 'api_live_only' ) {
				
			// LOG-SAFE VERSION (no post data with API keys etc)
			app_logging('ext_api_debugging', 'LIVE request for ' . ( $mode == 'array' ? 'server at ' : 'endpoint at ' ) . obfuscated_url_data($api_endpoint), 'request from: server (' . $app_config['power_user']['remote_api_timeout'] . ' second timeout); live_request_time: ' . $api_total_time . ' seconds; mode: ' . $mode . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; hash_check: ' . obfuscate_string($hash_check, 4) . ';' );
			
			// Log this as the latest response from this data request
			store_file_contents($base_dir . '/cache/logs/debugging/external_api/last-response-'.preg_replace("/\./", "_", $endpoint_tld_or_ip).'-'.$hash_check.'.log', $data);
			
			}
			
			
		}
	
		
		
		
		// Cache data to the file cache, EVEN IF WE HAVE NO DATA, TO AVOID CONSECUTIVE TIMEOUT HANGS (during page reloads etc) FROM A NON-RESPONSIVE API ENDPOINT
		// Cache API data for this runtime session AFTER PERSISTENT FILE CACHE UPDATE, file cache doesn't reliably update until runtime session is ending because of file locking
		// WE RE-CACHE DATA EVEN IF THIS WAS A FALLBACK TO CACHED DATA, AS WE WANT TO RESET THE TTL UNTIL NEXT LIVE API CHECK
		if ( $ttl > 0 && $mode != 'proxy-check' ) {
		
		// DON'T USE isset(), use != '' to store as 'none' reliably (so we don't keep hitting a server that may be throttling us, UNTIL cache TTL runs out)
		$api_runtime_cache[$hash_check] = ( $data != '' ? $data : 'none' ); 
		
			if ( isset($fallback_cache_data) ) {
			$store_file_contents = touch($base_dir . '/cache/secured/external_api/'.$hash_check.'.dat');
			}
			else {
			$store_file_contents = store_file_contents($base_dir . '/cache/secured/external_api/'.$hash_check.'.dat', $api_runtime_cache[$hash_check]);
			}
			
		
			if ( $store_file_contents == false && isset($fallback_cache_data) ) {
			app_logging('ext_api_error', 'Cache file touch() error for ' . ( $mode == 'array' ? 'server at ' : 'endpoint at ' ) . obfuscated_url_data($api_endpoint), 'data_size_bytes: ' . strlen($api_runtime_cache[$hash_check]) . ' bytes');
			}
			elseif ( $store_file_contents == false && !isset($fallback_cache_data) ) {
			app_logging('ext_api_error', 'Cache file write error for ' . ( $mode == 'array' ? 'server at ' : 'endpoint at ' ) . obfuscated_url_data($api_endpoint), 'data_size_bytes: ' . strlen($api_runtime_cache[$hash_check]) . ' bytes');
			}
		
		}
		// NEVER cache proxy checking data, OR TTL == 0
		elseif ( $mode == 'proxy-check' || $ttl == 0 ) {
		$api_runtime_cache[$hash_check] = null; 
		}
		

	
	}
	//////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////
	// IF --FILE-- CACHE DATA WITHIN IT'S TTL EXISTS
	//////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////
	else {
	
	
		// Use runtime cache if it exists. Remember file cache doesn't update until session is nearly over because of file locking, so only reliable for persisting a cache long term
		// If no API data was received, add error notices to UI / error logs (we don't try fetching the data again until cache TTL expiration, so as to NOT hang the app)
		// Run from runtime cache if requested again (for runtime speed improvements)
		if ( $api_runtime_cache[$hash_check] != '' && $api_runtime_cache[$hash_check] != 'none' ) {
		$data = $api_runtime_cache[$hash_check];
		$fallback_cache_data = 1;
		}
		else {
		$data = trim( file_get_contents('cache/secured/external_api/'.$hash_check.'.dat') );
			if ( $data != '' && $data != 'none' ) {
			$api_runtime_cache[$hash_check] = $data; // Create a runtime cache from the file cache, for any additional requests during runtime for this data set
			$fallback_cache_data = 1;
			}
		}
	
		
		if ( $data == 'none' || !isset($fallback_cache_data) ) {
		
			if ( !$logs_array['error_duplicates'][$hash_check] ) {
			$logs_array['error_duplicates'][$hash_check] = 1; 
			}
			else {
			$logs_array['error_duplicates'][$hash_check] = $logs_array['error_duplicates'][$hash_check] + 1;
			}
			
		// Don't log this error again during THIS runtime, as it would be a duplicate...just overwrite same error message, BUT update the error count in it
		
		app_logging( 'cache_error', 'no FILE CACHE data from failure with ' . ( $mode == 'array' ? 'server at ' : 'endpoint at ' ) . obfuscated_url_data($api_endpoint), 'request attempt(s) from: cache ('.$logs_array['error_duplicates'][$hash_check].' runtime instances); mode: ' . $mode . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; hash_check: ' . obfuscate_string($hash_check, 4) . ';', $hash_check );
			
		}
		elseif ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'telemetry' || $app_config['developer']['debug_mode'] == 'api_cache_only' ) {
		
			if ( !$logs_array['debugging_duplicates'][$hash_check] ) {
			$logs_array['debugging_duplicates'][$hash_check] = 1; 
			}
			else {
			$logs_array['debugging_duplicates'][$hash_check] = $logs_array['debugging_duplicates'][$hash_check] + 1;
			}
			
		// Don't log this debugging again during THIS runtime, as it would be a duplicate...just overwrite same debugging message, BUT update the debugging count in it
		
		app_logging( 'cache_debugging', 'FILE CACHE request for ' . ( $mode == 'array' ? 'server at ' : 'endpoint at ' ) . obfuscated_url_data($api_endpoint), 'request(s) from: cache ('.$logs_array['debugging_duplicates'][$hash_check].' runtime instances); mode: ' . $mode . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; hash_check: ' . obfuscate_string($hash_check, 4) . ';', $hash_check );
		
		}
	
	
	}
	
	

return $data;


}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


?>