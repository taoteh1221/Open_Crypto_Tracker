<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
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


function htaccess_directory_defaults() {
	
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
    	
    	$htaccess_contents = htaccess_directory_defaults() . 
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

global $ocpt_conf, $base_dir, $base_url, $ocpt_cache;


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
					
				$ocpt_cache->save_file($base_dir . '/cache/events/backup-'.$backup_prefix.'.dat', time_date_format(false, 'pretty_date_time') );
					
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


function test_proxy($problem_proxy_array) {

global $base_dir, $ocpt_conf, $ocpt_cache, $runtime_mode, $proxies_checked;


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
	app_logging('ext_data_error', 'proxy '.$problem_proxy.' is not a valid format');
	return false;
	}

// Create cache filename / session var
$cache_filename = $problem_proxy;
$cache_filename = preg_replace("/\./", "-", $cache_filename);
$cache_filename = preg_replace("/:/", "_", $cache_filename);

	if ( $ocpt_conf['comms']['proxy_alert_runtime'] == 'all' ) {
	$run_alerts = 1;
	}
	elseif ( $ocpt_conf['comms']['proxy_alert_runtime'] == 'cron' && $runtime_mode == 'cron' ) {
	$run_alerts = 1;
	}
	elseif ( $ocpt_conf['comms']['proxy_alert_runtime'] == 'ui' && $runtime_mode == 'ui' ) {
	$run_alerts = 1;
	}
	else {
	$run_alerts = null;
	}

	if ( $run_alerts == 1 && update_cache('cache/alerts/proxy-check-'.$cache_filename.'.dat', ( $ocpt_conf['comms']['proxy_alert_freq_max'] * 60 ) ) == true
	&& in_array($cache_filename, $proxies_checked) == false ) {
	
		
	// SESSION VAR first, to avoid duplicate alerts at runtime (and longer term cache file locked for writing further down, after logs creation)
	$proxies_checked[] = $cache_filename;
		
	$jsondata = @$ocpt_cache->ext_data('proxy-check', $proxy_test_url, 0, '', '', $problem_proxy);
	
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
		app_logging('ext_data_error', 'proxy '.$problem_proxy.' connection failed', $cached_logs);
		}
	

		// Update alerts cache for this proxy (to prevent running alerts for this proxy too often)
		$ocpt_cache->save_file($base_dir . '/cache/alerts/proxy-check-'.$cache_filename.'.dat', $cached_logs);
			
      
      $email_alert = " The proxy " . $problem_proxy . " recently did not receive data when accessing this endpoint: \n " . $obfuscated_url_data . " \n \n A check on this proxy was performed at " . $proxy_test_url . ", and results logged: \n ============================================================== \n " . $cached_logs . " \n ============================================================== \n \n ";
                    
		
		// Send out alerts
		if ( $misconfigured == 1 || $ocpt_conf['comms']['proxy_alert_checkup_ok'] == 'include' ) {
                    
                    
  				// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
  				if ( $ocpt_conf['comms']['proxy_alert'] == 'all' ) {
  				
  				// Minimize function calls
  				$encoded_text_alert = $ocpt_gen->charset_encode($text_alert); // Unicode support included for text messages (emojis / asian characters / etc )
  					
          	$send_params = array(
          								'notifyme' => $notifyme_alert,
          								'telegram' => $email_alert,
          								'text' => array(
          														'message' => $encoded_text_alert['content_output'],
          														'charset' => $encoded_text_alert['charset']
          														),
          								'email' => array(
          														'subject' => 'A Proxy Was Unresponsive',
          														'message' => $email_alert
          														)
          								);
          	
          	}
  				elseif ( $ocpt_conf['comms']['proxy_alert'] == 'email' ) {
  					
          	$send_params['email'] = array(
          											'subject' => 'A Proxy Was Unresponsive',
          											'message' => $email_alert
          											);
          	
          	}
  				elseif ( $ocpt_conf['comms']['proxy_alert'] == 'text' ) {
  				
  				// Minimize function calls
  				$encoded_text_alert = $ocpt_gen->charset_encode($text_alert); // Unicode support included for text messages (emojis / asian characters / etc )
  				
          	$send_params['text'] = array(
          											'message' => $encoded_text_alert['content_output'],
          											'charset' => $encoded_text_alert['charset']
          											);
          	
          	}
  				elseif ( $ocpt_conf['comms']['proxy_alert'] == 'notifyme' ) {
          	$send_params['notifyme'] = $notifyme_alert;
          	}
  				elseif ( $ocpt_conf['comms']['proxy_alert'] == 'telegram' ) {
          	$send_params['telegram'] = $email_alert;
          	}
          	
          	
          	// Send notifications
          	@$ocpt_cache->queue_notify($send_params);
          	
           
       }
          
          
		
	}



}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function update_lite_chart($archive_path, $newest_archival_data=false, $days_span=1) {

global $ocpt_conf, $base_dir, $ocpt_var, $ocpt_cache;

$archival_data = array();
$queued_archival_lines = array();
$new_lite_data = null;
// Lite chart file path
$lite_path = preg_replace("/archival/i", 'lite/' . $days_span . '_days', $archive_path);


	// Hash of lite path, AND random X hours update threshold, to spread out and event-track 'all' chart rebuilding
	if ( $days_span == 'all' ) {
	$lite_path_hash = md5($lite_path);
	$threshold_range = explode(',', $ocpt_conf['dev']['all_chart_rebuild_min_max']);
	$all_chart_rebuild_threshold = rand($threshold_range[0], $threshold_range[1]); // Randomly within the min/max range, to spead the load across multiple runtimes
	}


	// Get LAST line of lite chart data (determines newest lite timestamp)
	if ( file_exists($lite_path) ) {
	$last_lite_line = tail_custom($lite_path);
	$last_lite_array = explode("||", $last_lite_line);
	$newest_lite_timestamp = ( isset($last_lite_array[0]) ? $ocpt_var->num_to_str($last_lite_array[0]) : false );
	}
	else {
	$newest_lite_timestamp = false;
	}


// Get LAST line of archival chart data (we save SIGNIFICANTLY on runtime / resource usage, if this var is passed into this function already)
// (determines newest archival timestamp)
$last_archival_line = ( $newest_archival_data != false ? $newest_archival_data : tail_custom($archive_path) );
$last_archival_array = explode("||", $last_archival_line);
$newest_archival_timestamp = $ocpt_var->num_to_str($last_archival_array[0]);
			
			
// Get FIRST line of archival chart data (determines oldest archival timestamp)
$fopen_archive = fopen($archive_path, 'r');

	if ($fopen_archive) {
	$first_archival_line = fgets($fopen_archive);
	fclose($fopen_archive);
	gc_collect_cycles(); // Clean memory cache
	}
	
$first_archival_array = explode("||", $first_archival_line);
$oldest_archival_timestamp = $ocpt_var->num_to_str($first_archival_array[0]);
	
			
	// Oldest base timestamp we can use (only applies for x days charts, not 'all')
	if ( $days_span != 'all' ) {
	$base_min_timestamp = $ocpt_var->num_to_str( strtotime('-'.$days_span.' day', $newest_archival_timestamp) );
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
	$min_data_interval = round( ($newest_archival_timestamp - $oldest_archival_timestamp) / $ocpt_conf['power']['lite_chart_data_points_max'] ); // Dynamic
	}
	else {
	$min_data_interval = round( ($days_span * 86400) / $ocpt_conf['power']['lite_chart_data_points_max'] ); // Fixed X days (86400 seconds per day)
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
$min_data_interval = $ocpt_var->num_to_str($min_data_interval); 
$lite_data_update_threshold = $ocpt_var->num_to_str($lite_data_update_threshold); 


   // If we are queued to update an existing lite chart, get the data points we want to add 
   // (may be multiple data points, if the last update had network errors / system reboot / etc)
   if ( isset($newest_lite_timestamp) && $lite_data_update_threshold <= $newest_archival_timestamp ) {
   
    	// If we are only adding the newest archival data point (passed into this function), 
    	// #we save BIGTIME on resource usage# (used EVERYTIME, other than very rare FALLBACKS)
    	// CHECKS IF UPDATE THRESHOLD IS GREATER THAN NEWEST ARCHIVAL DATA POINT TIMESTAMP, 
    	// #WHEN ADDING AN EXTRA# $min_data_interval (so we know to only add one data point)
    	if ( $ocpt_var->num_to_str($lite_data_update_threshold + $min_data_interval) > $newest_archival_timestamp ) {
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
   	 	$archival_line_array[0] = $ocpt_var->num_to_str($archival_line_array[0]);
   	 	 
   	 	 	if ( !$added_archival_timestamp && $lite_data_update_threshold <= $archival_line_array[0]
   	 	 	|| isset($added_archival_timestamp) && $ocpt_var->num_to_str($added_archival_timestamp + $min_data_interval) <= $archival_line_array[0] ) {
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
	// If no lite chart exists yet, OR it's time to RESET intervals in the 'all' chart, rebuild from scratch
	// (we STILL check $queued_archival_lines for new data, to see if we should SKIP an 'all' charts full rebuild now)
	////////////////////////////////////////////////////////////////////////////////////////////////
	elseif ( !$newest_lite_timestamp 
	|| $days_span == 'all' && sizeof($queued_archival_lines) > 0 && update_cache($base_dir . '/cache/events/lite_chart_rebuilds/all_days_chart_'.$lite_path_hash.'.dat', (60 * $all_chart_rebuild_threshold) ) == true ) {

	$archive_file_data = file($archive_path);
	$archive_file_data = array_reverse($archive_file_data); // Save time, only loop / read last lines needed
	
	
		foreach($archive_file_data as $line) {
			
		$line_array = explode("||", $line);
		$line_array[0] = $ocpt_var->num_to_str($line_array[0]);
		
			if ( $line_array[0] >= $oldest_allowed_timestamp ) {
			$archival_data[] = $line;
			}
			
		}
	
		
		// We are looping IN REVERSE ODER, to ALWAYS include the latest data
		$loop = 0;
		$data_points = 0;
		// $data_points <= is INTENTIONAL, as we can have max data points slightly under without it
		while ( isset($archival_data[$loop]) && $data_points <= $ocpt_conf['power']['lite_chart_data_points_max'] ) {
			
		$data_point_array = explode("||", $archival_data[$loop]);
		$data_point_array[0] = $ocpt_var->num_to_str($data_point_array[0]);
				
			if ( !$next_timestamp || isset($next_timestamp) && $data_point_array[0] <= $next_timestamp ) {
			$new_lite_data = $archival_data[$loop] . $new_lite_data;// WITHOUT newline, since file() maintains those by default
			$next_timestamp = $data_point_array[0] - $min_data_interval;
			$data_points = $data_points + 1;
			}
		
		$loop = $loop + 1;
		}
		
	
	// Store the lite chart data (rebuild)
	$result = $ocpt_cache->save_file($lite_path, $new_lite_data);  // WITHOUT newline, since file() maintains those by default (file write)
	$lite_mode_logging = 'REBUILD';
	
		// Update the 'all' lite chart rebuild event tracking, IF THE LITE CHART UPDATED SUCESSFULLY
		if ( $days_span == 'all' && $result == true ) {
		$ocpt_cache->save_file($base_dir . '/cache/events/lite_chart_rebuilds/all_days_chart_'.$lite_path_hash.'.dat', time_date_format(false, 'pretty_date_time') );
		}
		

	}
	////////////////////////////////////////////////////////////////////////////////////////////////
	// If the lite chart has existing data, then $queued_archival_lines should be populated (IF we have new data to append to it).
	// We also trim out X first lines of stale data (earlier then the X days time range)
	////////////////////////////////////////////////////////////////////////////////////////////////
	elseif ( sizeof($queued_archival_lines) > 0 ) {
		
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
	$oldest_lite_timestamp = $ocpt_var->num_to_str($first_lite_array[0]);
		
		// If our oldest lite timestamp is older than allowed, remove the stale data points
		if ( $oldest_lite_timestamp < $oldest_allowed_timestamp ) {
		$lite_data_removed_outdated_lines = prune_first_lines($lite_path, 0, $oldest_allowed_timestamp);
		
		// ONLY APPEND A LINE BREAK TO THE NEW ARCHIVAL DATA, since prune_first_lines() maintains the existing line breaks
		$result = $ocpt_cache->save_file($lite_path, $lite_data_removed_outdated_lines['data'] . $queued_archival_data . "\n");  // WITH newline for NEW data (file write)
		$lite_mode_logging = 'OVERWRITE_' . $lite_data_removed_outdated_lines['lines_removed'] . '_OUTDATED_PRUNED_' . $added_archival_mode;
		}
		// If we're clear to just append the latest data
		else {
		$result = $ocpt_cache->save_file($lite_path, $queued_archival_data . "\n", "append");  // WITH newline (file write)
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
			
		if ( $ocpt_conf['dev']['debug'] == 'all' || $ocpt_conf['dev']['debug'] == 'all_telemetry' || $ocpt_conf['dev']['debug'] == 'lite_chart_telemetry' ) {
		app_logging( 'cache_debugging', 'Lite chart ' . $lite_mode_logging . ' COMPLETED ('.$_SESSION['lite_charts_updated'].') for ' . $lite_path);
		}
			
		if ( $ocpt_conf['dev']['debug'] == 'all' || $ocpt_conf['dev']['debug'] == 'all_telemetry' || $ocpt_conf['dev']['debug'] == 'memory_usage_telemetry' ) {
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

global $base_dir, $ocpt_conf, $ocpt_var, $ocpt_cache, $processed_messages, $possible_http_users, $http_runtime_user, $current_runtime_user, $telegram_user_data, $telegram_activated;


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
		if ( !isset($processed_messages['notifyme_count']) && update_cache($base_dir . '/cache/events/throttling/notifyme-alerts-sent.dat', 6) == true ) {
		$processed_messages['notifyme_count'] = 0;
		}
		// If it hasn't been well over 5 minutes since the last notifyme send
		// (we use 6 minutes, safely over the 5 minute limit for the maximum 5 requests), and there is no session count, 
		// use the file-cached count for the session count starting point
		elseif ( !isset($processed_messages['notifyme_count']) && update_cache($base_dir . '/cache/events/throttling/notifyme-alerts-sent.dat', 6) == false ) {
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
		
		
			// Sleep for 2 seconds before starting ANY consecutive message send, to help avoid being blocked / throttled by external server
			if ( $processed_messages['notifications_count'] > 0 ) {
			sleep(2);
			}
			
		
		
		$notifyme_params = array(
									 'notification' => null, // Setting this right before sending
									 'accessCode' => $ocpt_conf['comms']['notifyme_accesscode']
									   );
						
						
		$textbelt_params = array(
									 'message' => null, // Setting this right before sending
									 'phone' => text_number($ocpt_conf['comms']['to_mobile_text']),
									 'key' => $ocpt_conf['comms']['textbelt_apikey']
									);
						
						
		$textlocal_params = array(
									  'message' => null, // Setting this right before sending
									  'username' => $ocpt_var->str_to_array($ocpt_conf['comms']['textlocal_account'])[0],
									  'hash' => $ocpt_var->str_to_array($ocpt_conf['comms']['textlocal_account'])[1],
									  'numbers' => text_number($ocpt_conf['comms']['to_mobile_text'])
									   );
		
		
		
			
			// Send messages
			foreach ( $messages_queue as $queued_cache_file ) {
				
			
			
			$message_data = trim( file_get_contents($base_dir . '/cache/secured/messages/' . $queued_cache_file) );
			
			
				
				// If 0 bytes from system / network issues, just delete it to keep the directory contents clean
				if ( filesize($base_dir . '/cache/secured/messages/' . $queued_cache_file) == 0 ) {
				unlink($base_dir . '/cache/secured/messages/' . $queued_cache_file);
				}
				// Notifyme
			   elseif ( $message_data != '' && trim($ocpt_conf['comms']['notifyme_accesscode']) != '' && preg_match("/notifyme/i", $queued_cache_file) ) { 
			   
			   $notifyme_params['notification'] = $message_data;
			   
				// Sleep for 1 second EXTRA on EACH consecutive notifyme message, to throttle MANY outgoing messages, to help avoid being blocked / throttled by external server
				$notifyme_sleep = 1 * $processed_messages['notifyme_count'];
				sleep($notifyme_sleep);
				
					
					// Only 5 notifyme messages allowed per minute
					if ( $processed_messages['notifyme_count'] < 5 ) {
					
					$notifyme_response = @$ocpt_cache->ext_data('params', $notifyme_params, 0, 'https://api.notifymyecho.com/v1/NotifyMe');
				
					$processed_messages['notifyme_count'] = $processed_messages['notifyme_count'] + 1;
					
					$message_sent = 1;
					
					$ocpt_cache->save_file($base_dir . '/cache/events/throttling/notifyme-alerts-sent.dat', $processed_messages['notifyme_count']); 
					
						if ( $ocpt_conf['dev']['debug'] == 'all' || $ocpt_conf['dev']['debug'] == 'all_telemetry' || $ocpt_conf['dev']['debug'] == 'api_comms_telemetry' ) {
						$ocpt_cache->save_file($base_dir . '/cache/logs/debugging/external_api/last-response-notifyme.log', $notifyme_response);
						}
					
					unlink($base_dir . '/cache/secured/messages/' . $queued_cache_file);
					
					}
				
				
				
			   }
			  
			  
			  
			   // Textbelt
				// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
				// Only run if textlocal API isn't being used to avoid double texts
			   if ( $message_data != '' && trim($ocpt_conf['comms']['textbelt_apikey']) != '' && $ocpt_conf['comms']['textlocal_account'] == '' && preg_match("/textbelt/i", $queued_cache_file) ) {  
			   
			   $textbelt_params['message'] = $message_data;
			   
				// Sleep for 1 second EXTRA on EACH consecutive text message, to throttle MANY outgoing messages, to help avoid being blocked / throttled by external server
				$text_sleep = 1 * $processed_messages['text_count'];
				sleep($text_sleep);
			   
			   $textbelt_response = @$ocpt_cache->ext_data('params', $textbelt_params, 0, 'https://textbelt.com/text', 2);
			   
			   $processed_messages['text_count'] = $processed_messages['text_count'] + 1;
				
				$message_sent = 1;
			   
			   	if ( $ocpt_conf['dev']['debug'] == 'all' || $ocpt_conf['dev']['debug'] == 'all_telemetry' || $ocpt_conf['dev']['debug'] == 'api_comms_telemetry' ) {
					$ocpt_cache->save_file($base_dir . '/cache/logs/debugging/external_api/last-response-textbelt.log', $textbelt_response);
					}
				
				unlink($base_dir . '/cache/secured/messages/' . $queued_cache_file);
				
			   }
			  
			  
			  
			   // Textlocal
				// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
				// Only run if textbelt API isn't being used to avoid double texts
			   if ( $message_data != '' && $ocpt_conf['comms']['textlocal_account'] != '' && trim($ocpt_conf['comms']['textbelt_apikey']) == '' && preg_match("/textlocal/i", $queued_cache_file) ) {  
			   
			   $textlocal_params['message'] = $message_data;
			   
				// Sleep for 1 second EXTRA on EACH consecutive text message, to throttle MANY outgoing messages, to help avoid being blocked / throttled by external server
				$text_sleep = 1 * $processed_messages['text_count'];
				sleep($text_sleep);
			   
			   $textlocal_response = @$ocpt_cache->ext_data('params', $textlocal_params, 0, 'https://api.txtlocal.com/send/', 1);
			   
			   $processed_messages['text_count'] = $processed_messages['text_count'] + 1;
				
				$message_sent = 1;
			   
			   	if ( $ocpt_conf['dev']['debug'] == 'all' || $ocpt_conf['dev']['debug'] == 'all_telemetry' || $ocpt_conf['dev']['debug'] == 'api_comms_telemetry' ) {
					$ocpt_cache->save_file($base_dir . '/cache/logs/debugging/external_api/last-response-textlocal.log', $textlocal_response);
					}
				
				unlink($base_dir . '/cache/secured/messages/' . $queued_cache_file);
				
			   }
			  
			  
			  
			   // Telegram
			   if ( $telegram_activated == 1 && preg_match("/telegram/i", $queued_cache_file) ) {  
			   
				// Sleep for 1 second EXTRA on EACH consecutive telegram message, to throttle MANY outgoing messages, to help avoid being blocked / throttled by external server
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
			   		
			   
			   	if ( $ocpt_conf['dev']['debug'] == 'all' || $ocpt_conf['dev']['debug'] == 'all_telemetry' || $ocpt_conf['dev']['debug'] == 'api_comms_telemetry' ) {
					$ocpt_cache->save_file($base_dir . '/cache/logs/debugging/external_api/last-response-telegram.log', $telegram_response);
					}
				
			   }
			   
					   
					   
			   // SEND EMAILS LAST, IN CASE OF SMTP METHOD FAILURE AND RUNTIME EXIT
			  
			  
			  
			   // Text email
				// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
				// Only use text-to-email if other text services aren't configured
			   if ( validate_email( text_email($ocpt_conf['comms']['to_mobile_text']) ) == 'valid' && trim($ocpt_conf['comms']['textbelt_apikey']) == '' && $ocpt_conf['comms']['textlocal_account'] == '' && preg_match("/textemail/i", $queued_cache_file) ) { 
			   
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
						
					// Sleep for 1 second EXTRA on EACH consecutive text message, to throttle MANY outgoing messages, to help avoid being blocked / throttled by external server
					$text_sleep = 1 * $processed_messages['text_count'];
					sleep($text_sleep);
			   
					$result = @safe_mail( text_email($ocpt_conf['comms']['to_mobile_text']) , $textemail_array['subject'], $textemail_array['message'], $textemail_array['content_type'], $textemail_array['charset']);
			   
			   		if ( $result == true ) {
			   		
			   		$processed_messages['text_count'] = $processed_messages['text_count'] + 1;
			   	
						$message_sent = 1;
					
						unlink($base_dir . '/cache/secured/messages/' . $queued_cache_file);
			   		
			   		}
			   		else {
			   		app_logging( 'system_error', 'Email-to-mobile-text sending failed', 'to_text_email: ' . text_email($ocpt_conf['comms']['to_mobile_text']) . '; from: ' . $ocpt_conf['comms']['from_email'] . '; subject: ' . $textemail_array['subject'] . '; function_response: ' . $result . ';');
			   		}
					
					
					}
				
				
			   }
					  
					  
					  
			   // Normal email
			   if ( validate_email($ocpt_conf['comms']['to_email']) == 'valid' && preg_match("/normalemail/i", $queued_cache_file) ) {
			   
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
			   
					// Sleep for 1 second EXTRA on EACH consecutive email message, to throttle MANY outgoing messages, to help avoid being blocked / throttled by external server
					$email_sleep = 1 * $processed_messages['email_count'];
					sleep($email_sleep);
			   
					$result = @safe_mail($ocpt_conf['comms']['to_email'], $email_array['subject'], $email_array['message'], $email_array['content_type'], $email_array['charset']);
			   
			   		if ( $result == true ) {
			   		
			   		$processed_messages['email_count'] = $processed_messages['email_count'] + 1;
			   	
						$message_sent = 1;
					
						unlink($base_dir . '/cache/secured/messages/' . $queued_cache_file);
			   		
			   		}
			   		else {
			   		app_logging( 'system_error', 'Email sending failed', 'to_email: ' . $ocpt_conf['comms']['to_email'] . '; from: ' . $ocpt_conf['comms']['from_email'] . '; subject: ' . $email_array['subject'] . '; function_response: ' . $result . ';');
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
		
		$chmod_setting = octdec($ocpt_conf['dev']['chmod_cache_file']);
		
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


?>