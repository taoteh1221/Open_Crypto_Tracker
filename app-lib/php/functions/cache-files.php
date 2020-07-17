<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */
 
 
////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function remove_directory($dir) { 
  foreach(glob($dir . '/*') as $file) {
    if(is_dir($file)) remove_directory($file); else unlink($file); 
  }
  rmdir($dir);
}


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


function user_ini_defaults() {
	
global $base_dir, $app_config;

$ui_execution_time = $app_config['developer']['ui_max_execution_time']; // Don't overwrite globals

	// If the UI timeout var wasn't set properly / is not a whole number 3600 or less
	if ( !ctype_digit($ui_execution_time) || $ui_execution_time > 3600 ) {
	$ui_execution_time = 120; // Default
	}

return preg_replace("/\[PHP_TIMEOUT\]/i", $ui_execution_time, file_get_contents($base_dir . '/templates/back-end/root-app-directory-user-ini.template') );

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function htaccess_directory_defaults() {
	
global $base_dir, $app_config;

$ui_execution_time = $app_config['developer']['ui_max_execution_time']; // Don't overwrite globals

	// If the UI timeout var wasn't set properly / is not a whole number 3600 or less
	if ( !ctype_digit($ui_execution_time) || $ui_execution_time > 3600 ) {
	$ui_execution_time = 120; // Default
	}

return preg_replace("/\[PHP_TIMEOUT\]/i", $ui_execution_time, file_get_contents($base_dir . '/templates/back-end/root-app-directory-htaccess.template') );

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
    	
    	$htaccess_contents = htaccess_directory_defaults() . 
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


/**
	 * Slightly modified version of http://www.geekality.net/2011/05/28/php-tail-tackling-large-files/
	 * @author Torleif Berger, Lorenzo Stanco
	 * @link http://stackoverflow.com/a/15025877/995958
	 * @license http://creativecommons.org/licenses/by/3.0/
	 Usage: $last_line = tail_custom($file_path);
*/

function tail_custom($filepath, $lines = 1, $adaptive = true) {

		// Open file
		$f = @fopen($filepath, "rb");
		if ($f === false) return false;

		// Sets buffer size, according to the number of lines to retrieve.
		// This gives a performance boost when reading a few lines from the file.
		if (!$adaptive) $buffer = 4096;
		else $buffer = ($lines < 2 ? 64 : ($lines < 10 ? 512 : 4096));

		// Jump to last character
		fseek($f, -1, SEEK_END);

		// Read it and adjust line number if necessary
		// (Otherwise the result would be wrong if file doesn't end with a blank line)
		if (fread($f, 1) != "\n") $lines -= 1;
		
		// Start reading
		$output = '';
		$chunk = '';

		// While we would like more
		while (ftell($f) > 0 && $lines >= 0) {

			// Figure out how far back we should jump
			$seek = min(ftell($f), $buffer);

			// Do the jump (backwards, relative to where we are)
			fseek($f, -$seek, SEEK_CUR);

			// Read a chunk and prepend it to our output
			$output = ($chunk = fread($f, $seek)) . $output;

			// Jump back to where we started reading
			fseek($f, -mb_strlen($chunk, '8bit'), SEEK_CUR);

			// Decrease our line counter
			$lines -= substr_count($chunk, "\n");

		}

		// While we have too many lines
		// (Because of buffer size we might have read too many)
		while ($lines++ < 0) {

			// Find first newline and remove all text before that
			$output = substr($output, strpos($output, "\n") + 1);

		}


fclose($f); // Close file

gc_collect_cycles(); // Clean memory cache

return trim($output);

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
				
				$message = "A backup archive has been created for: ".$backup_prefix."\n\nHere is a link to download the backup to your computer: " . $base_url . $backup_url . "\n\n(backup archives are purged after " . $app_config['power_user']['backup_archive_delete_old'] . " days)";
				
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
	if ( $app_config['power_user']['email_logs'] > 0 && update_cache_file('cache/events/email-debugging-logs.dat', ( $app_config['power_user']['email_logs'] * 1440 ) ) == true ) {
		
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
	if ( update_cache_file('cache/events/purge-debugging-logs.dat', ( $app_config['power_user']['log_purge'] * 1440 ) ) == true ) {
		
	store_file_contents($base_dir . '/cache/logs/smtp_debugging.log', null);
	
	store_file_contents('cache/events/purge-debugging-logs.dat', date('Y-m-d H:i:s'));
	
	$store_file_contents = store_file_contents($base_dir . '/cache/logs/debugging.log', $debugging_logs); // NULL if no new debugging, but that's OK because we are purging any old entries 
		
			if ( $store_file_contents != true ) {
			return 'Debugging logs write error for "' . $base_dir . '/cache/logs/debugging.log" (MAKE SURE YOUR DISK ISN\'T FULL), data_size_bytes: ' . strlen($debugging_logs) . ' bytes';
			}
			// DEBUGGING ONLY (rules out issues other than full disk)
			elseif ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'all_telemetry' ) {
			return 'Debugging logs write success for "' . $base_dir . '/cache/logs/debugging.log", data_size_bytes: ' . strlen($debugging_logs) . ' bytes';
			}
	
	}
	elseif ( $debugging_logs != null ) {
		
	$store_file_contents = store_file_contents($base_dir . '/cache/logs/debugging.log', $debugging_logs, "append");
		
			if ( $store_file_contents != true ) {
			return 'Debugging logs write error for "' . $base_dir . '/cache/logs/debugging.log" (MAKE SURE YOUR DISK ISN\'T FULL), data_size_bytes: ' . strlen($debugging_logs) . ' bytes';
			}
			// DEBUGGING ONLY (rules out issues other than full disk)
			elseif ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'all_telemetry' ) {
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
	if ( $app_config['power_user']['email_logs'] > 0 && update_cache_file('cache/events/email-error-logs.dat', ( $app_config['power_user']['email_logs'] * 1440 ) ) == true ) {
		
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
	if ( update_cache_file('cache/events/purge-error-logs.dat', ( $app_config['power_user']['log_purge'] * 1440 ) ) == true ) {
		
	store_file_contents($base_dir . '/cache/logs/smtp_errors.log', null);
	
	store_file_contents('cache/events/purge-error-logs.dat', date('Y-m-d H:i:s'));
	
	$store_file_contents = store_file_contents($base_dir . '/cache/logs/errors.log', $error_logs); // NULL if no new errors, but that's OK because we are purging any old entries 
		
			if ( $store_file_contents != true ) {
			return 'Error logs write error for "' . $base_dir . '/cache/logs/errors.log" (MAKE SURE YOUR DISK ISN\'T FULL), data_size_bytes: ' . strlen($error_logs) . ' bytes';
			}
			// DEBUGGING ONLY (rules out issues other than full disk)
			elseif ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'all_telemetry' ) {
			return 'Error logs write success for "' . $base_dir . '/cache/logs/errors.log", data_size_bytes: ' . strlen($error_logs) . ' bytes';
			}
	
	}
	elseif ( $error_logs != null ) {
		
	$store_file_contents = store_file_contents($base_dir . '/cache/logs/errors.log', $error_logs, "append");
		
			if ( $store_file_contents != true ) {
			return 'Error logs write error for "' . $base_dir . '/cache/logs/errors.log" (MAKE SURE YOUR DISK ISN\'T FULL), data_size_bytes: ' . strlen($error_logs) . ' bytes';
			}
			// DEBUGGING ONLY (rules out issues other than full disk)
			elseif ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'all_telemetry' ) {
			return 'Error logs write success for "' . $base_dir . '/cache/logs/errors.log", data_size_bytes: ' . strlen($error_logs) . ' bytes';
			}
	
	}
	
	
return true;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function store_file_contents($file, $data, $mode=false, $lock=true) {

global $app_config, $current_runtime_user, $possible_http_users, $http_runtime_user;


	// If no data was passed on to write to file, log it and return false early for runtime speed sake
	if ( strlen($data) == 0 ) {
		
	app_logging('system_error', 'No bytes of data received to write to file "' . obfuscated_path_data($file) . '" (aborting useless file write)');
	
		// API timeouts are a confirmed cause for write errors of 0 bytes, so we want to alert end users that they may need to adjust their API timeout settings to get associated API data
		if ( preg_match("/cache\/secured\/apis/i", $file) ) {
		app_logging('ext_api_error', 'POSSIBLE api timeout' . ( $app_config['developer']['remote_api_strict_ssl'] == 'on' ? ' or strict_ssl' : '' ) . ' issue for cache file "' . obfuscated_path_data($file) . '" (IF THIS ISSUE PERSISTS #LONG TERM#, TRY INCREASING "remote_api_timeout"' . ( $app_config['developer']['remote_api_strict_ssl'] == 'on' ? ' OR SETTING "remote_api_strict_ssl" to "off"' : '' ) . ' IN THE DEVELOPER SECTION in config.php)', 'remote_api_timeout: '.$app_config['developer']['remote_api_timeout'].' seconds; remote_api_strict_ssl: ' . $app_config['developer']['remote_api_strict_ssl'] . ';');
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
	if ( strstr($file, '.htaccess') != false || strstr($file, '.user.ini') != false || strstr($file, 'index.php') != false ) {
		
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
	if ( $mode == 'append' && $lock ) {
	$result = file_put_contents($file, $data, FILE_APPEND | LOCK_EX);
	}
	elseif ( $mode == 'append' && !$lock ) {
	$result = file_put_contents($file, $data, FILE_APPEND);
	}
	elseif ( !$mode && $lock ) {
	$result = file_put_contents($file, $data, LOCK_EX);
	}
	else {
	$result = file_put_contents($file, $data);
	}
	
	// Log any write error
	if ( $result == false ) {
	app_logging('system_error', 'File write failed storing '.strlen($data).' bytes of data to file "' . obfuscated_path_data($file) . '" (MAKE SURE YOUR DISK ISN\'T FULL. Check permissions for the path "' . obfuscated_path_data($path_parts['dirname']) . '", and the file "' . obfuscate_string($path_parts['basename'], 5) . '")');
	}
	
	
	
	// For security, NEVER make an .htaccess file writable by any user not in the group
	if ( strstr($file, '.htaccess') != false || strstr($file, '.user.ini') != false || strstr($file, 'index.php') != false ) {
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


function update_lite_chart($archive_path, $newest_archival_data=false, $days_span=1) {

global $app_config, $base_dir;

$archival_data = array();
$queued_archival_lines = array();
$new_lite_data = null;
// Lite chart file path
$lite_path = preg_replace("/archival/i", 'lite/' . $days_span . '_days', $archive_path);


	// Hash of lite path, AND random X hours update threshold, to spread out and event-track 'all' chart rebuilding
	if ( $days_span == 'all' ) {
	$lite_path_hash = md5($lite_path);
	$all_chart_rebuild_threshold = rand(6, 12); // Randomly between 6 and 12 hours (to spead the load across multiple runtimes)
	}


	// Get LAST line of lite chart data (determines newest lite timestamp)
	if ( file_exists($lite_path) ) {
	$last_lite_line = tail_custom($lite_path);
	$last_lite_array = explode("||", $last_lite_line);
	$newest_lite_timestamp = ( isset($last_lite_array[0]) ? number_to_string($last_lite_array[0]) : false );
	}
	else {
	$newest_lite_timestamp = false;
	}


// Get LAST line of archival chart data (we save SIGNIFICANTLY on runtime / resource usage, if this var is passed into this function already)
// (determines newest archival timestamp)
$last_archival_line = ( $newest_archival_data != false ? $newest_archival_data : tail_custom($archive_path) );
$last_archival_array = explode("||", $last_archival_line);
$newest_archival_timestamp = number_to_string($last_archival_array[0]);
			
			
// Get FIRST line of archival chart data (determines oldest archival timestamp)
$fopen_archive = fopen($archive_path, 'r');

	if ($fopen_archive) {
	$first_archival_line = fgets($fopen_archive);
	fclose($fopen_archive);
	gc_collect_cycles(); // Clean memory cache
	}
	
$first_archival_array = explode("||", $first_archival_line);
$oldest_archival_timestamp = number_to_string($first_archival_array[0]);
	
			
	// Oldest base timestamp we can use (only applies for x days charts, not 'all')
	if ( $days_span != 'all' ) {
	$base_min_timestamp = number_to_string( strtotime('-'.$days_span.' day', $newest_archival_timestamp) );
	}
	
	// If it's the 'all' lite chart, OR the oldest archival timestamp is newer than oldest base timestamp we can use
	if ( $days_span == 'all' || $days_span != 'all' && $oldest_archival_timestamp > $base_min_timestamp ) {
	$oldest_allowed_timestamp = $oldest_archival_timestamp;
	}
	// If it's an X days lite chart (not 'all'), and we have archival timestamps that are older than oldest base timestamp we can use
	elseif ( $days_span != 'all' ) {
	$oldest_allowed_timestamp = $base_min_timestamp;  
	}
	
	
	// Minimum time interval between data points in lite chart
	if ( $days_span == 'all' ) {
	$min_data_interval = round( ($newest_archival_timestamp - $oldest_archival_timestamp) / $app_config['developer']['lite_chart_data_points_max'] ); // Dynamic
	}
	else {
	$min_data_interval = round( ($days_span * 86400) / $app_config['developer']['lite_chart_data_points_max'] ); // Fixed X days (86400 seconds per day)
	}


	// #INITIALLY# (if no lite data exists yet) we randomly spread the load across multiple cron jobs
	// THEN IT #REMAINS RANDOMLY SPREAD# ACROSS CRON JOBS #WITHOUT DOING ANYTHING AFTER# THE INITIAL RANDOMNESS
	if ( $newest_lite_timestamp == false ) {
	$lite_data_update_threshold = rand( (time() - 3333) , (time() + 6666) ); // 1/3 of all lite charts REBUILDS update on average, per runtime
	}
	// Update threshold calculated from pre-existing lite data
	else {
	$lite_data_update_threshold = $newest_lite_timestamp + $min_data_interval;
	}


// Large number support (NOT scientific format), since we manipulated these
$min_data_interval = number_to_string($min_data_interval); 
$lite_data_update_threshold = number_to_string($lite_data_update_threshold); 


   // If we are queued to update an existing lite chart, get the data points we want to add 
   // (may be multiple data points, if the last update had network errors / system reboot / etc)
   if ( isset($newest_lite_timestamp) && $lite_data_update_threshold <= $newest_archival_timestamp ) {
   
    	// If we are only adding the newest archival data point (passed into this function), 
    	// #we save BIGTIME on resource usage# (used EVERYTIME, other than very rare FALLBACKS)
    	// CHECKS IF UPDATE THRESHOLD IS GREATER THAN NEWEST ARCHIVAL DATA POINT TIMESTAMP, 
    	// #WHEN ADDING AN EXTRA# $min_data_interval (so we know to only add one data point)
    	if ( number_to_string($lite_data_update_threshold + $min_data_interval) > $newest_archival_timestamp ) {
    	$queued_archival_lines[] = $last_archival_line;
    	}
   	// If multiple lite chart data points missing (from any very rare FALLBACK instances, like network / load / disk / runtime issues, etc)
    	else {
    	
   	$tail_archival_lines = tail_custom($archive_path, 20); // Grab last 20 lines, to be safe
   	$tail_archival_lines_array = explode("\n", $tail_archival_lines);
   	// Remove all null / false / empty strings, and reindex
   	$tail_archival_lines_array = array_values( array_filter( $tail_archival_lines_array, 'strlen' ) ); 
   	 	
   	 	foreach( $tail_archival_lines_array as $archival_line ) {
   	 	$archival_line_array = explode('||', $archival_line);
   	 	$archival_line_array[0] = number_to_string($archival_line_array[0]);
   	 	 
   	 	 	if ( !$added_archival_timestamp && $lite_data_update_threshold <= $archival_line_array[0]
   	 	 	|| isset($added_archival_timestamp) && number_to_string($added_archival_timestamp + $min_data_interval) <= $archival_line_array[0] ) {
   	 	 	$queued_archival_lines[] = $archival_line;
   	 	 	$added_archival_timestamp = $archival_line_array[0];
   	 	 	}
   	 	 
   	 	}
    	 
    	}
    	
    	
   // DEBUGGING data
   $added_archival_mode = sizeof($queued_archival_lines) . '_ADDED';
   
   }



	////////////////////////////////////////////////////////////////////////////////////////////////
	// Not time to update / rebuild this lite chart yet
	////////////////////////////////////////////////////////////////////////////////////////////////
	if ( $lite_data_update_threshold > $newest_archival_timestamp ) {
	gc_collect_cycles(); // Clean memory cache
	return false;
	}
	////////////////////////////////////////////////////////////////////////////////////////////////
	// If no lite chart exists yet OR it's time to prune the 'all' chart, rebuild from scratch
	////////////////////////////////////////////////////////////////////////////////////////////////
	elseif ( !$newest_lite_timestamp 
	|| $days_span == 'all' && update_cache_file($base_dir . '/cache/events/lite_chart_rebuilds/all_days_chart_'.$lite_path_hash.'.dat', (60 * $all_chart_rebuild_threshold) ) == true ) {

	$archive_file_data = file($archive_path);
	$archive_file_data = array_reverse($archive_file_data); // Save time, only loop / read last lines needed
	
	
		foreach($archive_file_data as $line) {
			
		$line_array = explode("||", $line);
		$line_array[0] = number_to_string($line_array[0]);
		
			if ( $line_array[0] >= $oldest_allowed_timestamp ) {
			$archival_data[] = $line;
			}
			
		}
	
		
		// We are looping IN REVERSE ODER, to ALWAYS include the latest data
		$loop = 0;
		$data_points = 0;
		// $data_points <= is INTENTIONAL, as we can have max data points slightly under without it
		while ( isset($archival_data[$loop]) && $data_points <= $app_config['developer']['lite_chart_data_points_max'] ) {
			
		$data_point_array = explode("||", $archival_data[$loop]);
		$data_point_array[0] = number_to_string($data_point_array[0]);
				
			if ( !$next_timestamp || isset($next_timestamp) && $data_point_array[0] <= $next_timestamp ) {
			$new_lite_data = $archival_data[$loop] . $new_lite_data;// WITHOUT newline, since file() maintains those by default
			$next_timestamp = $data_point_array[0] - $min_data_interval;
			$data_points = $data_points + 1;
			}
		
		$loop = $loop + 1;
		}
		
	
	// Store the lite chart data (rebuild)
	$result = store_file_contents($lite_path, $new_lite_data);  // WITHOUT newline, since file() maintains those by default (file write)
	$lite_mode_logging = 'REBUILD';
	
		// Update the 'all' lite chart rebuild event tracking, IF THE LITE CHART UPDATED SUCESSFULLY
		if ( $days_span == 'all' && $result == true ) {
		store_file_contents($base_dir . '/cache/events/lite_chart_rebuilds/all_days_chart_'.$lite_path_hash.'.dat', time_date_format(false, 'pretty_date_time') );
		}
		

	}
	////////////////////////////////////////////////////////////////////////////////////////////////
	// If the lite chart has existing data, AND we have new data to append to it / trim out 
	// X first lines of stale data (earlier then the X days time range)
	////////////////////////////////////////////////////////////////////////////////////////////////
	elseif ( $newest_lite_timestamp && sizeof($queued_archival_lines) > 0 ) {
		
	$queued_archival_data = implode("\n", $queued_archival_lines);
	
	// Current lite chart lines, plus new archival lines queued to be added
	$check_lite_data_lines = get_lines($lite_path) + sizeof($queued_archival_lines);
		
	// Get FIRST line of lite chart data (determines oldest lite timestamp)
	$fopen_lite = fopen($lite_path, 'r');
	
		if ($fopen_lite) {
		$first_lite_line = fgets($fopen_lite);
		fclose($fopen_lite);
		usleep(20000); // Wait 0.02 seconds, since we'll be writing data to this file momentarily
		gc_collect_cycles(); // Clean memory cache
		}
				
	$first_lite_array = explode("||", $first_lite_line);
	$oldest_lite_timestamp = number_to_string($first_lite_array[0]);
		
		// If our oldest lite timestamp is older than allowed, remove the stale data points
		if ( $oldest_lite_timestamp < $oldest_allowed_timestamp ) {
		$lite_data_removed_outdated_lines = prune_first_lines($lite_path, 0, $oldest_allowed_timestamp);
		
		// ONLY APPEND A LINE BREAK TO THE NEW ARCHIVAL DATA, since prune_first_lines() maintains the existing line breaks
		$result = store_file_contents($lite_path, $lite_data_removed_outdated_lines['data'] . $queued_archival_data . "\n");  // WITH newline for NEW data (file write)
		$lite_mode_logging = 'OVERWRITE_' . $lite_data_removed_outdated_lines['lines_removed'] . '_OUTDATED_PRUNED_' . $added_archival_mode;
		}
		// If we're clear to just append the latest data
		else {
		$result = store_file_contents($lite_path, $queued_archival_data . "\n", "append");  // WITH newline (file write)
		$lite_mode_logging = 'APPEND_' . $added_archival_mode;
		}
		

	}
	// No lite data to update
	else {
	$result = false;
	}
	


	// Logging results
	if ( $result == true ) {
		
	$_SESSION['lite_charts_updated'] = $_SESSION['lite_charts_updated'] + 1;
			
		if ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'all_telemetry' || $app_config['developer']['debug_mode'] == 'lite_chart' ) {
		app_logging( 'cache_debugging', 'Lite chart ' . $lite_mode_logging . ' COMPLETED ('.$_SESSION['lite_charts_updated'].') for ' . $lite_path);
		}
			
		if ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'all_telemetry' || $app_config['developer']['debug_mode'] == 'memory' ) {
		app_logging('system_debugging', $_SESSION['lite_charts_updated'] . ' lite charts updated, CURRENT script memory usage is ' . convert_bytes(memory_get_usage(), 1) . ', PEAK script memory usage is ' . convert_bytes(memory_get_peak_usage(), 1) . ', php_sapi_name is "' . php_sapi_name() . '"' );
		}
			
	}
	else {
	app_logging( 'cache_error', 'Lite chart ' . $lite_mode_logging . ' FAILED for ' . $lite_path);
	}

	
gc_collect_cycles(); // Clean memory cache

return $result;

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
					
					$notifyme_response = @external_api_data('params', $notifyme_params, 0, 'https://api.notifymyecho.com/v1/NotifyMe');
				
					$processed_messages['notifyme_count'] = $processed_messages['notifyme_count'] + 1;
					
					$message_sent = 1;
					
					store_file_contents($base_dir . '/cache/events/throttling/notifyme-alerts-sent.dat', $processed_messages['notifyme_count']); 
					
						if ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'all_telemetry' || $app_config['developer']['debug_mode'] == 'comms_telemetry' ) {
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
			   
			   $textbelt_response = @external_api_data('params', $textbelt_params, 0, 'https://textbelt.com/text', 2);
			   
			   $processed_messages['text_count'] = $processed_messages['text_count'] + 1;
				
				$message_sent = 1;
			   
			   	if ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'all_telemetry' || $app_config['developer']['debug_mode'] == 'comms_telemetry' ) {
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
			   
			   $textlocal_response = @external_api_data('params', $textlocal_params, 0, 'https://api.txtlocal.com/send/', 1);
			   
			   $processed_messages['text_count'] = $processed_messages['text_count'] + 1;
				
				$message_sent = 1;
			   
			   	if ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'all_telemetry' || $app_config['developer']['debug_mode'] == 'comms_telemetry' ) {
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
			   		
			   
			   	if ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'all_telemetry' || $app_config['developer']['debug_mode'] == 'comms_telemetry' ) {
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

		gc_collect_cycles(); // Clean memory cache
	
	
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


function external_api_data($mode, $request_params, $ttl, $api_server=null, $post_encoding=3, $test_proxy=null, $headers=null) { // Default to JSON encoding post requests (most used)

// $app_config['general']['btc_primary_currency_pairing'] / $app_config['general']['btc_primary_exchange'] / $selected_btc_primary_currency_value USED FOR TRACE DEBUGGING (TRACING)
global $base_dir, $proxy_checkup, $logs_array, $limited_api_calls, $app_config, $api_runtime_cache, $selected_btc_primary_currency_value, $user_agent, $base_url, $htaccess_username, $htaccess_password;


$cookie_jar = tempnam('/tmp','cookie');
	
	
// To cache duplicate requests based on a data hash, during runtime update session (AND persist cache to flat files)
$hash_check = ( $mode == 'params' ? md5(serialize($request_params)) : md5($request_params) );

$api_endpoint = ( $mode == 'params' ? $api_server : $request_params );
			
$endpoint_tld_or_ip = get_tld_or_ip($api_endpoint);
		
	
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
	unlink($base_dir . '/cache/secured/external_api/'.$hash_check.'.dat');
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
		
		app_logging( 'cache_error', 'no RUNTIME CACHE data from failure with ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . obfuscated_url_data($api_endpoint), 'request attempt(s) from: cache ('.$logs_array['error_duplicates'][$hash_check].' runtime instances); mode: ' . $mode . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; hash_check: ' . obfuscate_string($hash_check, 4) . ';', $hash_check );
			
		}
		elseif ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'all_telemetry' || $app_config['developer']['debug_mode'] == 'api_cache_only' ) {
		
			if ( !$logs_array['debugging_duplicates'][$hash_check] ) {
			$logs_array['debugging_duplicates'][$hash_check] = 1; 
			}
			else {
			$logs_array['debugging_duplicates'][$hash_check] = $logs_array['debugging_duplicates'][$hash_check] + 1;
			}
			
		// Don't log this debugging again during THIS runtime, as it would be a duplicate...just overwrite same debugging message, BUT update the debugging count in it
		
		app_logging('cache_debugging', 'RUNTIME CACHE request for ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . obfuscated_url_data($api_endpoint), 'request(s) from: cache ('.$logs_array['debugging_duplicates'][$hash_check].' runtime instances); mode: ' . $mode . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; hash_check: ' . obfuscate_string($hash_check, 4) . ';', $hash_check );
		
		}
	
	
	}
	//////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////
	// Live data retrieval 
	//////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////
	elseif ( update_cache_file($base_dir . '/cache/secured/external_api/'.$hash_check.'.dat', $ttl) == true || $ttl == 0 ) {
	
	// Time the request
	$api_time = microtime();
	$api_time = explode(' ', $api_time);
	$api_time = $api_time[1] + $api_time[0];
	$api_start_time = $api_time;
		
	
	// Initiate the curl external data request
	$ch = curl_init( ( $mode == 'params' ? $api_server : '' ) );
	
	
		// Throttled endpoints
		// If this is an API service that requires multiple calls (for each market), 
		// and a request to it has been made consecutively, we throttle it to avoid being blacklisted
		if ( in_array($endpoint_tld_or_ip, $app_config['developer']['limited_apis']) ) {
		
		$tld_session_prefix = preg_replace("/\./i", "_", $endpoint_tld_or_ip);
		
			if ( !$limited_api_calls[$tld_session_prefix . '_calls'] ) {
			$limited_api_calls[$tld_session_prefix . '_calls'] = 1;
			}
			elseif ( $limited_api_calls[$tld_session_prefix . '_calls'] == 1 ) {
			usleep(150000); // Throttle 0.15 seconds
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
		
		if ( $mode == 'params' && $post_encoding == 1 ) {
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request_params); // Works fine so far not encoded
		}
		elseif ( $mode == 'params' && $post_encoding == 2 ) {
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($request_params) ); // Encode post data with http_build_query()
		}
		elseif ( $mode == 'params' && $post_encoding == 3 ) {
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request_params) ); // json encoded
		}
		elseif ( $mode == 'url' || $mode == 'proxy-check' ) {
		curl_setopt($ch, CURLOPT_URL, $api_endpoint); // Not encoded
		}
		elseif ( $mode == 'encoded_url' ) {
		curl_setopt($ch, CURLOPT_URL, $api_endpoint_encoded); // Encoded (not sure as useful / functional, for other than debugging?)
		}
	
	
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $app_config['developer']['remote_api_timeout']);
	curl_setopt($ch, CURLOPT_TIMEOUT, $app_config['developer']['remote_api_timeout']);
	
		
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
			$remote_api_strict_ssl = $app_config['developer']['remote_api_strict_ssl'];
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
		
		$debug_response_log = $base_dir . '/cache/logs/debugging/external_api/problem-endpoint-'.preg_replace("/\./", "_", $endpoint_tld_or_ip).'-hash-'.$hash_check.'-timestamp-'.time().'.log';
		
		// Store to file
		store_file_contents($debug_response_log, $debug_data);
		
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
					
			$data = trim( file_get_contents($base_dir . '/cache/secured/external_api/'.$hash_check.'.dat') );
				
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
		app_logging('ext_api_error', 'connection failed for ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . obfuscated_url_data($api_endpoint) . $log_append, 'requested from: server (' . $app_config['developer']['remote_api_timeout'] . ' second timeout); live_request_time: ' . $api_total_time . ' seconds; mode: ' . $mode . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; hash_check: ' . obfuscate_string($hash_check, 4) . ';' );
		
		
			if ( sizeof($app_config['proxy']['proxy_list']) > 0 && $current_proxy != '' && $mode != 'proxy-check' ) { // Avoid infinite loops doing proxy checks

			$proxy_checkup[] = array(
															'endpoint' => ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . obfuscated_url_data($api_endpoint),
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
			|| preg_match("/invalid vs_currency/i", $data) // Coingecko (we fallback to USD in this case anyways, and error would repeat every cache refresh cluttering logs)
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
					app_logging('ext_api_error', 'POSSIBLE error for ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . obfuscated_url_data($api_endpoint), 'requested from: server (' . $app_config['developer']['remote_api_timeout'] . ' second timeout); live_request_time: ' . $api_total_time . ' seconds; mode: ' . $mode . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; debug_file: ' . $error_response_log . '; btc_primary_currency_pairing: ' . $app_config['general']['btc_primary_currency_pairing'] . '; btc_primary_exchange: ' . $app_config['general']['btc_primary_exchange'] . '; btc_primary_currency_value: ' . number_to_string($selected_btc_primary_currency_value) . '; hash_check: ' . obfuscate_string($hash_check, 4) . ';' );
				
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
				if ( // Errors / unavailable / null / throttled / maintenance
				preg_match("/cf-error/i", $data) // Cloudflare (DDOS protection service)
				|| preg_match("/cf-browser/i", $data) // Cloudflare (DDOS protection service)
				|| preg_match("/\"result\":{}/i", $data) // Kraken.com / generic
				|| preg_match("/\"result\":null/i", $data) // Bittrex.com / generic
				|| preg_match("/\"data\":null/i", $data) // Bitflyer.com / generic
				|| preg_match("/\"success\":false/i", $data) // BTCturk.com / Bittrex.com / generic
				|| preg_match("/EService:Unavailable/i", $data) // Kraken.com / generic
				|| preg_match("/EService:Busy/i", $data) // Kraken.com / generic
				|| preg_match("/temporarily unavailable/i", $data) // Bitfinex.com / generic
				|| preg_match("/\"reason\":\"Maintenance\"/i", $data) // Gemini.com / generic
				|| preg_match("/scheduled maintenance/i", $data) // Bittrex.com / generic
				|| preg_match("/site is down/i", $data) // Blockchain.info / generic
				|| preg_match("/something went wrong/i", $data) // Bitbns.com / generic
				|| preg_match("/Server Error/i", $data) // Kucoin.com / generic
				|| preg_match("/An error has occurred/i", $data) // Bitflyer.com / generic
				|| preg_match("/too many requests/i", $data) // reddit.com / generic
				// APIs famous for returning no data frequently
				|| $endpoint_tld_or_ip == 'lakebtc.com' && !preg_match("/volume/i", $data)
				|| $endpoint_tld_or_ip == 'localbitcoins.com' && !preg_match("/volume_btc/i", $data)
				|| $endpoint_tld_or_ip == 'coinmarketcap.com' && !preg_match("/last_updated/i", $data) ) {
				
				
					if ( $api_runtime_cache[$hash_check] != '' && $api_runtime_cache[$hash_check] != 'none' ) {
					$data = $api_runtime_cache[$hash_check];
					$fallback_cache_data = 1;
					}
					else {
						
					$data = trim( file_get_contents($base_dir . '/cache/secured/external_api/'.$hash_check.'.dat') );
					
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
				app_logging('ext_api_error', 'CONFIRMED error for ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . obfuscated_url_data($api_endpoint) . $log_append, 'requested from: server (' . $app_config['developer']['remote_api_timeout'] . ' second timeout); live_request_time: ' . $api_total_time . ' seconds; mode: ' . $mode . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; btc_primary_currency_pairing: ' . $app_config['general']['btc_primary_currency_pairing'] . '; btc_primary_exchange: ' . $app_config['general']['btc_primary_exchange'] . '; btc_primary_currency_value: ' . number_to_string($selected_btc_primary_currency_value) . '; hash_check: ' . obfuscate_string($hash_check, 4) . ';' );
					
			
				}

			
			}
			
			////////////////////////////////////////////////////////////////
			
		
		
			// Data debugging telemetry
			if ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'all_telemetry' || $app_config['developer']['debug_mode'] == 'api_live_only' ) {
				
			// LOG-SAFE VERSION (no post data with API keys etc)
			app_logging('ext_api_debugging', 'LIVE request for ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . obfuscated_url_data($api_endpoint), 'request from: server (' . $app_config['developer']['remote_api_timeout'] . ' second timeout); live_request_time: ' . $api_total_time . ' seconds; mode: ' . $mode . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; hash_check: ' . obfuscate_string($hash_check, 4) . ';' );
			
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
			app_logging('ext_api_error', 'Cache file touch() error for ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . obfuscated_url_data($api_endpoint), 'data_size_bytes: ' . strlen($api_runtime_cache[$hash_check]) . ' bytes');
			}
			elseif ( $store_file_contents == false && !isset($fallback_cache_data) ) {
			app_logging('ext_api_error', 'Cache file write error for ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . obfuscated_url_data($api_endpoint), 'data_size_bytes: ' . strlen($api_runtime_cache[$hash_check]) . ' bytes');
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
		$data = trim( file_get_contents($base_dir . '/cache/secured/external_api/'.$hash_check.'.dat') );
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
		
		app_logging('cache_error', 'no FILE CACHE data from failure with ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . obfuscated_url_data($api_endpoint), 'request attempt(s) from: cache ('.$logs_array['error_duplicates'][$hash_check].' runtime instances); mode: ' . $mode . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; hash_check: ' . obfuscate_string($hash_check, 4) . ';', $hash_check );
			
		}
		elseif ( $app_config['developer']['debug_mode'] == 'all' || $app_config['developer']['debug_mode'] == 'all_telemetry' || $app_config['developer']['debug_mode'] == 'api_cache_only' ) {
		
			if ( !$logs_array['debugging_duplicates'][$hash_check] ) {
			$logs_array['debugging_duplicates'][$hash_check] = 1; 
			}
			else {
			$logs_array['debugging_duplicates'][$hash_check] = $logs_array['debugging_duplicates'][$hash_check] + 1;
			}
			
		// Don't log this debugging again during THIS runtime, as it would be a duplicate...just overwrite same debugging message, BUT update the debugging count in it
		
		app_logging('cache_debugging', 'FILE CACHE request for ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . obfuscated_url_data($api_endpoint), 'request(s) from: cache ('.$logs_array['debugging_duplicates'][$hash_check].' runtime instances); mode: ' . $mode . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; hash_check: ' . obfuscate_string($hash_check, 4) . ';', $hash_check );
		
		}
	
	
	}
	
	

gc_collect_cycles(); // Clean memory cache
return $data;


}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


?>