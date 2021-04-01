<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */
 
 
////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function remove_dir($dir) { 
  foreach(glob($dir . '/*') as $file) {
    if(is_dir($file)) remove_dir($file); else unlink($file); 
  }
  rmdir($dir);
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function update_cache($cache_file, $minutes) {

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
	
global $base_dir, $ocpt_conf;

$ui_exec_time = $ocpt_conf['dev']['ui_max_exec_time']; // Don't overwrite globals

	// If the UI timeout var wasn't set properly / is not a whole number 3600 or less
	if ( !ctype_digit($ui_exec_time) || $ui_exec_time > 3600 ) {
	$ui_exec_time = 120; // Default
	}

return preg_replace("/\[PHP_TIMEOUT\]/i", $ui_exec_time, file_get_contents($base_dir . '/templates/back-end/root-app-directory-user-ini.template') );

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function htaccess_dir_defaults() {
	
global $base_dir, $ocpt_conf;

$ui_exec_time = $ocpt_conf['dev']['ui_max_exec_time']; // Don't overwrite globals

	// If the UI timeout var wasn't set properly / is not a whole number 3600 or less
	if ( !ctype_digit($ui_exec_time) || $ui_exec_time > 3600 ) {
	$ui_exec_time = 120; // Default
	}

return preg_replace("/\[PHP_TIMEOUT\]/i", $ui_exec_time, file_get_contents($base_dir . '/templates/back-end/root-app-directory-htaccess.template') );

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function delete_old_files($dir_data, $days, $ext) {
	
	
	// Support for string OR array in the calls, for directory data
	if ( !is_array($dir_data) ) {
	$dir_data = array($dir_data);
	}
	
	
	// Process each directory
	foreach ( $dir_data as $dir ) {
	
		
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


function htaccess_dir_protection() {

global $base_dir, $ocpt_cache, $ocpt_conf, $htaccess_username, $htaccess_password;

$valid_username = valid_username($htaccess_username);

// Password must be exactly 8 characters long for good htaccess security (htaccess only checks the first 8 characters for a match)
$password_strength = password_strength($htaccess_password, 8, 8); 


    if ( $htaccess_username == '' || $htaccess_password == '' ) {
    return false;
    }
    elseif ( $valid_username != 'valid' ) {
    app_logging('security_error', 'ocpt_conf\'s "interface_login" username value does not meet minimum valid username requirements' , $valid_username);
    return false;
    }
    elseif ( $password_strength != 'valid' ) {
    app_logging('security_error', 'ocpt_conf\'s "interface_login" password value does not meet minimum password strength requirements' , $password_strength);
    return false;
    }
    else {
    
    $htaccess_password = crypt( $htaccess_password, base64_encode($htaccess_password) );
    
    $password_set = $ocpt_cache->save_file($base_dir . '/cache/secured/.app_htpasswd', $htaccess_username . ':' . $htaccess_password);
    
    	if ( $password_set == true ) {
    	
    	$htaccess_contents = htaccess_dir_defaults() . 
		preg_replace("/\[BASE_DIR\]/i", $base_dir, file_get_contents($base_dir . '/templates/back-end/enable-password-htaccess.template') );
    
    	$htaccess_set = $ocpt_cache->save_file($base_dir . '/.htaccess', $htaccess_contents);
    
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

global $ocpt_conf, $ocpt_gen, $ocpt_cache, $base_dir, $base_url;


	if ( update_cache('cache/events/backup-'.$backup_prefix.'.dat', ( $interval * 1440 ) ) == true ) {

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
					
				$ocpt_cache->save_file($base_dir . '/cache/events/backup-'.$backup_prefix.'.dat', $ocpt_gen->time_date_format(false, 'pretty_date_time') );
					
				$backup_url = $base_url . 'download.php?backup=' . $backup_file;
				
				$message = "A backup archive has been created for: ".$backup_prefix."\n\nHere is a link to download the backup to your computer: " . $backup_url . "\n\n(backup archives are purged after " . $ocpt_conf['power']['backup_arch_del_old'] . " days)";
				
				// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
				$send_params = array(
										'email' => array(
															'subject' => 'Open Crypto Portfolio Tracker - Backup Archive For: ' . $backup_prefix,
															'message' => $message
															)
										);
							
				// Send notifications
				@$ocpt_cache->queue_notify($send_params);
				
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

global $ocpt_conf, $base_dir, $ocpt_cache, $logs_array;

	if ( $ocpt_conf['dev']['debug'] == 'off' ) {
	return false;
	}

// Combine all debugging logged
$debugging_logs .= strip_tags($logs_array['system_debugging']); // Remove any HTML formatting used in UI alerts

$debugging_logs .= strip_tags($logs_array['config_debugging']); // Remove any HTML formatting used in UI alerts

$debugging_logs .= strip_tags($logs_array['security_debugging']); // Remove any HTML formatting used in UI alerts

$debugging_logs .= strip_tags($logs_array['ext_data_debugging']); // Remove any HTML formatting used in UI alerts

$debugging_logs .= strip_tags($logs_array['int_api_debugging']); // Remove any HTML formatting used in UI alerts

$debugging_logs .= strip_tags($logs_array['market_debugging']); // Remove any HTML formatting used in UI alerts

$debugging_logs .= strip_tags($logs_array['other_debugging']); // Remove any HTML formatting used in UI alerts


	foreach ( $logs_array['cache_debugging'] as $debugging ) {
	$debugging_logs .= strip_tags($debugging); // Remove any HTML formatting used in UI alerts
	}

	foreach ( $logs_array['notify_debugging'] as $debugging ) {
	$debugging_logs .= strip_tags($debugging); // Remove any HTML formatting used in UI alerts
	}


	// If it's time to email debugging logs...
	if ( $ocpt_conf['power']['logs_email'] > 0 && update_cache('cache/events/email-debugging-logs.dat', ( $ocpt_conf['power']['logs_email'] * 1440 ) ) == true ) {
		
	$emailed_logs = "\n\n ------------------debugging.log------------------ \n\n" . file_get_contents('cache/logs/debugging.log') . "\n\n ------------------smtp_debugging.log------------------ \n\n" . file_get_contents('cache/logs/smtp_debugging.log');
		
	$message = " Here are the current debugging logs from the ".$base_dir."/cache/logs/ directory: \n =========================================================================== \n \n"  . ( $emailed_logs != '' ? $emailed_logs : 'No debugging logs currently.' );
	
  	// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
   $send_params = array(
          					'email' => array(
          											'subject' => 'Open Crypto Portfolio Tracker - Debugging Logs Report',
     													'message' => $message
          											)
          					);
          	
   // Send notifications
   @$ocpt_cache->queue_notify($send_params);
          	
	$ocpt_cache->save_file($base_dir . '/cache/events/email-debugging-logs.dat', date('Y-m-d H:i:s')); // Track this emailing event, to determine next time to email logs again.
	
	}
	
	
	// Log debugging...Purge old logs before storing new logs, if it's time to...otherwise just append.
	if ( update_cache('cache/events/purge-debugging-logs.dat', ( $ocpt_conf['power']['logs_purge'] * 1440 ) ) == true ) {
	
	unlink($base_dir . '/cache/logs/smtp_debugging.log');
	unlink($base_dir . '/cache/logs/debugging.log');
	
	$ocpt_cache->save_file('cache/events/purge-debugging-logs.dat', date('Y-m-d H:i:s'));
	
	sleep(1);
	
	}
	
	
	if ( $debugging_logs != null ) {
		
	$store_file_contents = $ocpt_cache->save_file($base_dir . '/cache/logs/debugging.log', $debugging_logs, "append");
		
			if ( $store_file_contents != true ) {
			return 'Debugging logs write error for "' . $base_dir . '/cache/logs/debugging.log" (MAKE SURE YOUR DISK ISN\'T FULL), data_size_bytes: ' . strlen($debugging_logs) . ' bytes';
			}
			// DEBUGGING ONLY (rules out issues other than full disk)
			elseif ( $ocpt_conf['dev']['debug'] == 'all' || $ocpt_conf['dev']['debug'] == 'all_telemetry' ) {
			return 'Debugging logs write success for "' . $base_dir . '/cache/logs/debugging.log", data_size_bytes: ' . strlen($debugging_logs) . ' bytes';
			}
		
	}
	
	
return true;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function error_logs() {

global $ocpt_conf, $base_dir, $ocpt_cache, $logs_array;

// Combine all errors logged
$error_logs .= strip_tags($logs_array['system_error']); // Remove any HTML formatting used in UI alerts

$error_logs .= strip_tags($logs_array['config_error']); // Remove any HTML formatting used in UI alerts

$error_logs .= strip_tags($logs_array['security_error']); // Remove any HTML formatting used in UI alerts

$error_logs .= strip_tags($logs_array['ext_data_error']); // Remove any HTML formatting used in UI alerts

$error_logs .= strip_tags($logs_array['int_api_error']); // Remove any HTML formatting used in UI alerts

$error_logs .= strip_tags($logs_array['market_error']); // Remove any HTML formatting used in UI alerts

$error_logs .= strip_tags($logs_array['other_error']); // Remove any HTML formatting used in UI alerts


	foreach ( $logs_array['cache_error'] as $error ) {
	$error_logs .= strip_tags($error); // Remove any HTML formatting used in UI alerts
	}
	
	foreach ( $logs_array['notify_error'] as $error ) {
	$error_logs .= strip_tags($error); // Remove any HTML formatting used in UI alerts
	}


	// If it's time to email error logs...
	if ( $ocpt_conf['power']['logs_email'] > 0 && update_cache('cache/events/email-error-logs.dat', ( $ocpt_conf['power']['logs_email'] * 1440 ) ) == true ) {
		
	$emailed_logs = "\n\n ------------------errors.log------------------ \n\n" . file_get_contents('cache/logs/errors.log') . "\n\n ------------------smtp_errors.log------------------ \n\n" . file_get_contents('cache/logs/smtp_errors.log');
		
	$message = " Here are the current error logs from the ".$base_dir."/cache/logs/ directory: \n =========================================================================== \n \n"  . ( $emailed_logs != '' ? $emailed_logs : 'No error logs currently.' );
	
  	// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
   $send_params = array(
          					'email' => array(
          											'subject' => 'Open Crypto Portfolio Tracker - Error Logs Report',
     													'message' => $message
          											)
          					);
          	
   // Send notifications
   @$ocpt_cache->queue_notify($send_params);
          	
	$ocpt_cache->save_file($base_dir . '/cache/events/email-error-logs.dat', date('Y-m-d H:i:s')); // Track this emailing event, to determine next time to email logs again.
	
	}
	
	
	// Log errors...Purge old logs before storing new logs, if it's time to...otherwise just append.
	if ( update_cache('cache/events/purge-error-logs.dat', ( $ocpt_conf['power']['logs_purge'] * 1440 ) ) == true ) {
	
	unlink($base_dir . '/cache/logs/smtp_errors.log');
	unlink($base_dir . '/cache/logs/errors.log');
	
	$ocpt_cache->save_file('cache/events/purge-error-logs.dat', date('Y-m-d H:i:s'));
	
	sleep(1);
	
	}
	
	
	if ( $error_logs != null ) {
		
	$store_file_contents = $ocpt_cache->save_file($base_dir . '/cache/logs/errors.log', $error_logs, "append");
		
			if ( $store_file_contents != true ) {
			return 'Error logs write error for "' . $base_dir . '/cache/logs/errors.log" (MAKE SURE YOUR DISK ISN\'T FULL), data_size_bytes: ' . strlen($error_logs) . ' bytes';
			}
			// DEBUGGING ONLY (rules out issues other than full disk)
			elseif ( $ocpt_conf['dev']['debug'] == 'all' || $ocpt_conf['dev']['debug'] == 'all_telemetry' ) {
			return 'Error logs write success for "' . $base_dir . '/cache/logs/errors.log", data_size_bytes: ' . strlen($error_logs) . ' bytes';
			}
	
	}
	
	
return true;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


?>