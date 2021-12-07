<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */
 


class ct_cache {
	
// Class variables / arrays
var $ct_var1;
var $ct_var2;
var $ct_var3;
var $ct_array1 = array();

  
  
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
  
  
  function check_log($loc) {
  
  global $ct_gen;
      
  $ct_gen->log(
    		'other_error',
    		'CHECK ('.$loc.') for plugin error logs @ ' . time()
    		);
    			
      
  sleep(1); 
  
  $this->error_log();
  $this->debug_log();
  
  $log_array = array(); // Clear queued log array after processing
  
  }
   
  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function user_ini_defaults() {
   
  global $base_dir, $ct_conf;
  
  $ui_exec_time = $ct_conf['dev']['ui_max_exec_time']; // Don't overwrite globals
  
    // If the UI timeout var wasn't set properly / is not a whole number 3600 or less
    if ( !ctype_digit($ui_exec_time) || $ui_exec_time > 3600 ) {
    $ui_exec_time = 120; // Default
    }
  
  return preg_replace("/\[PHP_TIMEOUT\]/i", $ui_exec_time, file_get_contents($base_dir . '/templates/back-end/root-app-directory-user-ini.template') );
  
  }
  
  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function htaccess_dir_defaults() {
   
  global $base_dir, $ct_conf;
  
  $ui_exec_time = $ct_conf['dev']['ui_max_exec_time']; // Don't overwrite globals
  
    // If the UI timeout var wasn't set properly / is not a whole number 3600 or less
    if ( !ctype_digit($ui_exec_time) || $ui_exec_time > 3600 ) {
    $ui_exec_time = 120; // Default
    }
  
  return preg_replace("/\[PHP_TIMEOUT\]/i", $ui_exec_time, file_get_contents($base_dir . '/templates/back-end/root-app-directory-htaccess.template') );
  
  }
  
  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function remove_dir($dir) {
  	
    foreach ( glob($dir . '/*') as $file ) {
    
        if ( is_dir($file) ) {
        $this->remove_dir($file);
        }
        else {
        unlink($file);
        }
    	
    }
  
  rmdir($dir);
  
  }
  
  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function delete_old_files($dir_arr, $days, $ext) {
  
  global $ct_gen;
   
      // Support for string OR array in the calls, for directory data
      if ( !is_array($dir_arr) ) {
      $dir_arr = array($dir_arr);
      }
     
     
      // Process each directory
      foreach ( $dir_arr as $dir ) {
      
      $files = glob($dir."/*.".$ext);
     
          foreach ($files as $file) {
           
            if ( is_file($file) ) {
              
              if ( time() - filemtime($file) >= 60 * 60 * 24 * $days ) {
               
              $result = unlink($file);
              
               	if ( $result == false ) {
               		
               	$ct_gen->log(
               				'system_error',
               				'File deletion failed for file "' . $file . '" (check permissions for "' . basename($file) . '")'
               				);
               	
               	}
              
              }
              
            }
            else {
            $ct_gen->log('system_error', 'File deletion failed, file not found: "' . $file . '"');
            }
            
          }
     
      }
  
  
  }
   
  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function htaccess_dir_protection() {
  
  global $base_dir, $ct_conf, $ct_gen, $htaccess_username, $htaccess_password;
  
  $valid_username = $ct_gen->valid_username($htaccess_username);
  
  // Password must be exactly 8 characters long for good htaccess security (htaccess only checks the first 8 characters for a match)
  $password_strength = $ct_gen->pass_strength($htaccess_password, 8, 8); 
  
  
      if ( $htaccess_username == '' || $htaccess_password == '' ) {
      return false;
      }
      elseif ( $valid_username != 'valid' ) {
      	
      $ct_gen->log(
      			'security_error',
      			'ct_conf\'s "interface_login" username value does not meet minimum valid username requirements',
      			$valid_username
      			);
      
      return false;
      
      }
      elseif ( $password_strength != 'valid' ) {
      	
      $ct_gen->log(
      			'security_error',
      			'ct_conf\'s "interface_login" password value does not meet minimum password strength requirements',
      			$password_strength
      			);
      
      return false;
      
      }
      else {
      
      $htaccess_password = crypt( $htaccess_password, base64_encode($htaccess_password) );
      
      $password_set = $this->save_file($base_dir . '/cache/secured/.app_htpasswd', $htaccess_username . ':' . $htaccess_password);
      
       	if ( $password_set == true ) {
       
       	$htaccess_contents = $this->htaccess_dir_defaults() . 
    		preg_replace("/\[BASE_DIR\]/i", $base_dir, file_get_contents($base_dir . '/templates/back-end/enable-password-htaccess.template') );
      
       	$htaccess_set = $this->save_file($base_dir . '/.htaccess', $htaccess_contents);
      
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
    Usage: $last_line = $ct_cache->tail_custom($file_path);
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
  
  global $ct_conf, $ct_gen, $base_dir, $base_url;
  
  
	  // 1439 minutes instead (minus 1 minute), to try keeping daily recurrences at same exact runtime (instead of moving up the runtime daily)
      if ( $this->update_cache($base_dir . '/cache/events/backup-'.$backup_prefix.'.dat', ( $interval * 1439 ) ) == true ) {
     
      $secure_128bit_hash = $ct_gen->rand_hash(16); // 128-bit (16-byte) hash converted to hexadecimal, used for suffix
      
      
          // We only want to store backup files with suffixes that can't be guessed, 
          // otherwise halt the application if an issue is detected safely creating a random hash
          if ( $secure_128bit_hash == false ) {
          	
          $ct_gen->log(
          			'security_error',
          			'Cryptographically secure pseudo-random bytes could not be generated for ' . $backup_prefix . ' backup archive filename suffix, backup aborted to preserve backups directory privacy'
          			);
          
          }
          else {
           
          $backup_file = $backup_prefix . '_'.date( "Y-M-d", time() ).'_'.$secure_128bit_hash.'.zip';
          $backup_dest = $base_dir . '/cache/secured/backups/' . $backup_file;
           
          // Zip archive
          $backup_results = $ct_gen->zip_recursively($backup_target, $backup_dest, $password);
           
           
              if ( $backup_results == 1 ) {
               
              $this->save_file($base_dir . '/cache/events/backup-'.$backup_prefix.'.dat', $ct_gen->time_date_format(false, 'pretty_date_time') );
               
              $backup_url = $base_url . 'download.php?backup=' . $backup_file;
              
              $msg = "A backup archive has been created for: ".$backup_prefix."\n\nHere is a link to download the backup to your computer:\n\n" . $backup_url . "\n\n(backup archives are purged after " . $ct_conf['power']['backup_arch_del_old'] . " days)";
              
              // Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
              $send_params = array(
                                  'email' => array(
                                                  'subject' => 'Open Crypto Tracker - Backup Archive For: ' . $backup_prefix,
                                                  'message' => $msg
                                                  )
                    );
                 
              // Send notifications
              @$this->queue_notify($send_params);
              
              }
              else {
              $ct_gen->log('system_error', 'Backup zip archive creation failed with ' . $backup_results);
              }
            
          
          }
      
     
      }
  
  
  }
  
  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function queue_notify($send_params) {
  
  global $base_dir, $ct_conf, $ct_gen, $telegram_activated;
  
     
     // Abort queueing comms for sending out notifications, if comms pause is on
     if ( $ct_conf['comms']['comms_pause'] == 'on' ) {
     return;
     }
  
  
   // Queue messages
   
   // RANDOM HASH SHOULD BE CALLED PER-STATEMENT, OTHERWISE FOR SOME REASON SEEMS TO REUSE SAME HASH FOR THE WHOLE RUNTIME INSTANCE (if set as a variable beforehand)
   
     // Notifyme
     if ( $send_params['notifyme'] != '' && trim($ct_conf['comms']['notifyme_accesscode']) != '' ) {
   	 $this->save_file($base_dir . '/cache/secured/messages/notifyme-' . $ct_gen->rand_hash(8) . '.queue', $send_params['notifyme']);
     }
    
     // Textbelt
     // To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
     // Only run if textlocal API isn't being used to avoid double texts
     if ( $send_params['text']['message'] != '' && trim($ct_conf['comms']['textbelt_apikey']) != '' && $ct_conf['comms']['textlocal_account'] == '' ) { 
     $this->save_file($base_dir . '/cache/secured/messages/textbelt-' . $ct_gen->rand_hash(8) . '.queue', $send_params['text']['message']);
     }
    
     // Textlocal
     // To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
     // Only run if textbelt API isn't being used to avoid double texts
     if ( $send_params['text']['message'] != '' && $ct_conf['comms']['textlocal_account'] != '' && trim($ct_conf['comms']['textbelt_apikey']) == '' ) { 
     $this->save_file($base_dir . '/cache/secured/messages/textlocal-' . $ct_gen->rand_hash(8) . '.queue', $send_params['text']['message']);
     }
   
     // Telegram
     if ( $send_params['telegram'] != '' && $telegram_activated == 1 ) {
     $this->save_file($base_dir . '/cache/secured/messages/telegram-' . $ct_gen->rand_hash(8) . '.queue', $send_params['telegram']);
     }
     
             
     // Text email
     // To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
     // Only use text-to-email if other text services aren't configured
     if ( $send_params['text']['message'] != '' && $ct_gen->valid_email( $ct_gen->text_email($ct_conf['comms']['to_mobile_text']) ) == 'valid' && trim($ct_conf['comms']['textbelt_apikey']) == '' && $ct_conf['comms']['textlocal_account'] == '' ) { 
     
     // $send_params['text_charset'] SHOULD ALWAYS BE SET FROM THE CALL TO HERE (for emojis, or other unicode characters to send via text message properly)
     // SUBJECT !!MUST BE SET!! OR SOME TEXT SERVICES WILL NOT ACCEPT THE MESSAGE!
     $textemail_array = array('subject' => 'Text Notify', 'message' => $send_params['text']['message'], 'content_type' => 'text/plain', 'charset' => $send_params['text']['charset'] );
     
      	// json_encode() only accepts UTF-8, SO TEMPORARILY CONVERT TO THAT FOR MESSAGE QUEUE STORAGE
      	if ( strtolower($send_params['text']['charset']) != 'utf-8' ) {
      	 
      	 	foreach( $textemail_array as $textemail_key => $textemail_val ) {
      	 	$textemail_array[$textemail_key] = mb_convert_encoding($textemail_val, 'UTF-8', mb_detect_encoding($textemail_val, "auto") );
      	 	}
      
      	}
     
     $this->save_file($base_dir . '/cache/secured/messages/textemail-' . $ct_gen->rand_hash(8) . '.queue', json_encode($textemail_array) );
   
     }
     
            
     // Normal email
     if ( $send_params['email']['message'] != '' && $ct_gen->valid_email($ct_conf['comms']['to_email']) == 'valid' ) {
     
     $email_array = array('subject' => $send_params['email']['subject'], 'message' => $send_params['email']['message'], 'content_type' => ( $send_params['email']['content_type'] ? $send_params['email']['content_type'] : 'text/plain' ), 'charset' => ( $send_params['email']['charset'] ? $send_params['email']['charset'] : $ct_conf['dev']['charset_default'] ) );
     
      	// json_encode() only accepts UTF-8, SO TEMPORARILY CONVERT TO THAT FOR MESSAGE QUEUE STORAGE
      	if ( strtolower($send_params['email']['charset']) != 'utf-8' ) {
      	 
      	 	foreach( $email_array as $email_key => $email_val ) {
      	 	$email_array[$email_key] = mb_convert_encoding($email_val, 'UTF-8', mb_detect_encoding($email_val, "auto") );
      	 	}
      
      	}
     
   	 $this->save_file($base_dir . '/cache/secured/messages/normalemail-' . $ct_gen->rand_hash(8) . '.queue', json_encode($email_array) );
   
     }
    
  
  }
  
  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function debug_log() {
  
  global $base_dir, $ct_conf, $log_array;
  
      if ( $ct_conf['dev']['debug'] == 'off' ) {
      return false;
      }
  
  // Combine all debugging logged
  $debug_log .= strip_tags($log_array['system_debug']); // Remove any HTML formatting used in UI alerts
  
  $debug_log .= strip_tags($log_array['conf_debug']); // Remove any HTML formatting used in UI alerts
  
  $debug_log .= strip_tags($log_array['security_debug']); // Remove any HTML formatting used in UI alerts
  
  $debug_log .= strip_tags($log_array['ext_data_debug']); // Remove any HTML formatting used in UI alerts
  
  $debug_log .= strip_tags($log_array['int_api_debug']); // Remove any HTML formatting used in UI alerts
  
  $debug_log .= strip_tags($log_array['market_debug']); // Remove any HTML formatting used in UI alerts
  
  $debug_log .= strip_tags($log_array['other_debug']); // Remove any HTML formatting used in UI alerts
  
  
      foreach ( $log_array['cache_debug'] as $debugging ) {
      $debug_log .= strip_tags($debugging); // Remove any HTML formatting used in UI alerts
      }
    
      foreach ( $log_array['notify_debug'] as $debugging ) {
      $debug_log .= strip_tags($debugging); // Remove any HTML formatting used in UI alerts
      }
  
  
      // If it's time to email debugging logs...
	  // 1439 minutes instead (minus 1 minute), to try keeping daily recurrences at same exact runtime (instead of moving up the runtime daily)
      if ( $ct_conf['comms']['logs_email'] > 0 && $this->update_cache('cache/events/email-debugging-logs.dat', ( $ct_conf['comms']['logs_email'] * 1439 ) ) == true ) {
       
      $emailed_logs = "\n\n ------------------debug.log------------------ \n\n" . file_get_contents('cache/logs/debug.log') . "\n\n ------------------smtp_debug.log------------------ \n\n" . file_get_contents('cache/logs/smtp_debug.log');
       
      $msg = " Here are the current debugging logs from the ".$base_dir."/cache/logs/ directory. \n\n You can disable receiving log emails in the Admin Config \"Communications\" section. \n =========================================================================== \n \n"  . ( $emailed_logs != '' ? $emailed_logs : 'No debugging logs currently.' );
      
        // Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
        $send_params = array(
                            'email' => array(
                                            'subject' => 'Open Crypto Tracker - Debugging Logs Report',
                                            'message' => $msg
                                            )
                             );
                
      // Send notifications
      @$this->queue_notify($send_params);
                
      $this->save_file($base_dir . '/cache/events/email-debugging-logs.dat', date('Y-m-d H:i:s')); // Track this emailing event, to determine next time to email logs again.
      
      }
      
      
      // Log debugging...Purge old logs before storing new logs, if it's time to...otherwise just append.
	  // 1439 minutes instead (minus 1 minute), to try keeping daily recurrences at same exact runtime (instead of moving up the runtime daily)
      if ( $this->update_cache('cache/events/purge-debugging-logs.dat', ( $ct_conf['power']['logs_purge'] * 1439 ) ) == true ) {
      
      unlink($base_dir . '/cache/logs/smtp_debug.log');
      unlink($base_dir . '/cache/logs/debug.log');
      
      $this->save_file('cache/events/purge-debugging-logs.dat', date('Y-m-d H:i:s'));
      
      sleep(1);
      
      }
      
      
      if ( $debug_log != null ) {
        
      $store_file_contents = $this->save_file($base_dir . '/cache/logs/debug.log', $debug_log, "append");
        
          if ( $store_file_contents != true ) {
          return 'Debugging logs write error for "' . $base_dir . '/cache/logs/debug.log" (MAKE SURE YOUR DISK ISN\'T FULL), data_size_bytes: ' . strlen($debug_log) . ' bytes';
          }
          // DEBUGGING ONLY (rules out issues other than full disk)
          elseif ( $ct_conf['dev']['debug'] == 'all' || $ct_conf['dev']['debug'] == 'all_telemetry' ) {
          return 'Debugging logs write success for "' . $base_dir . '/cache/logs/debug.log", data_size_bytes: ' . strlen($debug_log) . ' bytes';
          }
        
      }
   
   
  return true;
  
  }
  
  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function error_log() {
  
  global $base_dir, $ct_conf, $log_array;
  
  // Combine all errors logged
  $error_log .= strip_tags($log_array['system_error']); // Remove any HTML formatting used in UI alerts
  
  $error_log .= strip_tags($log_array['conf_error']); // Remove any HTML formatting used in UI alerts
  
  $error_log .= strip_tags($log_array['security_error']); // Remove any HTML formatting used in UI alerts
  
  $error_log .= strip_tags($log_array['ext_data_error']); // Remove any HTML formatting used in UI alerts
  
  $error_log .= strip_tags($log_array['int_api_error']); // Remove any HTML formatting used in UI alerts
  
  $error_log .= strip_tags($log_array['market_error']); // Remove any HTML formatting used in UI alerts
  
  $error_log .= strip_tags($log_array['other_error']); // Remove any HTML formatting used in UI alerts
  
  
      foreach ( $log_array['cache_error'] as $error ) {
      $error_log .= strip_tags($error); // Remove any HTML formatting used in UI alerts
      }
     
      foreach ( $log_array['notify_error'] as $error ) {
      $error_log .= strip_tags($error); // Remove any HTML formatting used in UI alerts
      }
    
    
      // If it's time to email error logs...
	  // 1439 minutes instead (minus 1 minute), to try keeping daily recurrences at same exact runtime (instead of moving up the runtime daily)
      if ( $ct_conf['comms']['logs_email'] > 0 && $this->update_cache('cache/events/email-error-logs.dat', ( $ct_conf['comms']['logs_email'] * 1439 ) ) == true ) {
       
      $emailed_logs = "\n\n ------------------error.log------------------ \n\n" . file_get_contents('cache/logs/error.log') . "\n\n ------------------smtp_error.log------------------ \n\n" . file_get_contents('cache/logs/smtp_error.log');
       
      $msg = " Here are the current error logs from the ".$base_dir."/cache/logs/ directory. \n\n You can disable receiving log emails in the Admin Config \"Communications\" section. \n \n =========================================================================== \n \n"  . ( $emailed_logs != '' ? $emailed_logs : 'No error logs currently.' );
      
        // Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
        $send_params = array(
                            'email' => array(
                                            'subject' => 'Open Crypto Tracker - Error Logs Report',
                                            'message' => $msg
                                            )
                            );
                
      // Send notifications
      @$this->queue_notify($send_params);
                
      $this->save_file($base_dir . '/cache/events/email-error-logs.dat', date('Y-m-d H:i:s')); // Track this emailing event, to determine next time to email logs again.
      
      }
      
      
      // Log errors...Purge old logs before storing new logs, if it's time to...otherwise just append.
	  // 1439 minutes instead (minus 1 minute), to try keeping daily recurrences at same exact runtime (instead of moving up the runtime daily)
      if ( $this->update_cache('cache/events/purge-error-logs.dat', ( $ct_conf['power']['logs_purge'] * 1439 ) ) == true ) {
      
      unlink($base_dir . '/cache/logs/smtp_error.log');
      unlink($base_dir . '/cache/logs/error.log');
      
      $this->save_file('cache/events/purge-error-logs.dat', date('Y-m-d H:i:s'));
      
      sleep(1);
      
      }
      
      
      if ( $error_log != null ) {
        
      $store_file_contents = $this->save_file($base_dir . '/cache/logs/error.log', $error_log, "append");
        
          if ( $store_file_contents != true ) {
          return 'Error logs write error for "' . $base_dir . '/cache/logs/error.log" (MAKE SURE YOUR DISK ISN\'T FULL), data_size_bytes: ' . strlen($error_log) . ' bytes';
          }
          // DEBUGGING ONLY (rules out issues other than full disk)
          elseif ( $ct_conf['dev']['debug'] == 'all' || $ct_conf['dev']['debug'] == 'all_telemetry' ) {
          return 'Error logs write success for "' . $base_dir . '/cache/logs/error.log", data_size_bytes: ' . strlen($error_log) . ' bytes';
          }
      
      }
   
   
  return true;
  
  }
  
  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function save_file($file, $data, $mode=false, $lock=true) {
  
  global $ct_conf, $ct_var, $ct_gen, $current_runtime_user, $possible_http_users, $http_runtime_user;
  
  
    // If no data was passed on to write to file, log it and return false early for runtime speed sake
    if ( strlen($data) == 0 ) {
     
    $ct_gen->log(
    			'system_error',
    			'No bytes of data received to write to file "' . $ct_gen->obfusc_path_data($file) . '" (aborting useless file write)'
    			);
    
     // API timeouts are a confirmed cause for write errors of 0 bytes, so we want to alert end users that they may need to adjust their API timeout settings to get associated API data
     if ( preg_match("/cache\/secured\/apis/i", $file) ) {
       
     $ct_gen->log(
     			'ext_data_error',
     								
     			'POSSIBLE api timeout' . ( $ct_conf['dev']['remote_api_strict_ssl'] == 'on' ? ' or strict_ssl' : '' ) . ' issue for cache file "' . $ct_gen->obfusc_path_data($file) . '" (IF ISSUE PERSISTS, TRY INCREASING "remote_api_timeout" IN Admin Config POWER USER SECTION' . ( $ct_conf['dev']['remote_api_strict_ssl'] == 'on' ? ', OR SETTING "remote_api_strict_ssl" to "off" IN Admin Config DEVELOPER SECTION' : '' ) . ')',
     								
     			'remote_api_timeout: '.$ct_conf['power']['remote_api_timeout'].' seconds; remote_api_strict_ssl: ' . $ct_conf['dev']['remote_api_strict_ssl'] . ';'
     			);
     
     }
    
    return false;
    
    }
  
  
  $path_parts = pathinfo($file);
  
    if ( function_exists('posix_getpwuid') ) {
    $file_owner_info = posix_getpwuid(fileowner($file));
    }
    else {
    $file_owner_info = get_current_user();
    }
  
  
    // Does the current runtime user own this file (or will they own it after creating a non-existent file)?
    if ( file_exists($file) == false || isset($current_runtime_user) && $current_runtime_user == $file_owner_info['name'] ) {
    $is_file_owner = 1;
    }
   
   
    // We ALWAYS set .htaccess files to a more secure $ct_conf['dev']['chmod_index_sec'] permission AFTER EDITING, 
    // so we TEMPORARILY set .htaccess to $ct_conf['dev']['chmod_cache_file'] for NEW EDITING...
    if ( strstr($file, '.htaccess') != false || strstr($file, '.user.ini') != false || strstr($file, 'index.php') != false ) {
     
    $chmod_setting = octdec($ct_conf['dev']['chmod_cache_file']);
    
    
     // Run chmod compatibility on certain PHP setups (if we can because we are running as the file owner)
     // In this case only if the file exists, as we are chmod BEFORE editing it (.htaccess files)
     if ( file_exists($file) == true && $is_file_owner == 1 && !$http_runtime_user 
     || file_exists($file) == true && $is_file_owner == 1 && isset($http_runtime_user) && in_array($http_runtime_user, $possible_http_users) ) {
      
     $oldmask = umask(0);
     
     $did_chmod = chmod($file, $chmod_setting);
     
      if ( !$did_chmod ) {
      	
      $ct_gen->log(
      			'system_error',
      							
      			'Chmod failed for file "' . $ct_gen->obfusc_path_data($file) . '" (check permissions for the path "' . $ct_gen->obfusc_path_data($path_parts['dirname']) . '", and the file "' . $ct_var->obfusc_str($path_parts['basename'], 5) . '")',
      							
      			'chmod_setting: ' . $chmod_setting . '; current_runtime_user: ' . $current_runtime_user . '; file_owner: ' . $file_owner_info['name'] . ';'
      			);
      
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
    	
    $ct_gen->log(
    				'system_error',
    				'File write failed storing '.strlen($data).' bytes of data to file "' . $ct_gen->obfusc_path_data($file) . '" (MAKE SURE YOUR DISK ISN\'T FULL. Check permissions for the path "' . $ct_gen->obfusc_path_data($path_parts['dirname']) . '", and the file "' . $ct_var->obfusc_str($path_parts['basename'], 5) . '")'
    				);
    
    }
    
    
    // For security, NEVER make an .htaccess file writable by any user not in the group
    if ( strstr($file, '.htaccess') != false || strstr($file, '.user.ini') != false || strstr($file, 'index.php') != false ) {
    $chmod_setting = octdec($ct_conf['dev']['chmod_index_sec']);
    }
    // All other files
    else {
    $chmod_setting = octdec($ct_conf['dev']['chmod_cache_file']);
    }
   
   
    // Run chmod compatibility on certain PHP setups (if we can because we are running as the file owner)
    if ( $is_file_owner == 1 && !$http_runtime_user || $is_file_owner == 1 && isset($http_runtime_user) && in_array($http_runtime_user, $possible_http_users) ) {
     
    $oldmask = umask(0);
    
    $did_chmod = chmod($file, $chmod_setting);
     
     if ( !$did_chmod ) {
     	
     $ct_gen->log(
     			'system_error',
     								
     			'Chmod failed for file "' . $ct_gen->obfusc_path_data($file) . '" (check permissions for the path "' . $ct_gen->obfusc_path_data($path_parts['dirname']) . '", and the file "' . $ct_var->obfusc_str($path_parts['basename'], 5) . '")',
     								
     			'chmod_setting: ' . $chmod_setting . '; current_runtime_user: ' . $current_runtime_user . '; file_owner: ' . $file_owner_info['name'] . ';'
     			);
     
     }
     
    umask($oldmask);
    
    }
   
   
  return $result;
  
  }
  
  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function update_lite_chart($archive_path, $newest_arch_data=false, $days_span=1) {
  
  global $ct_conf, $base_dir, $ct_var, $ct_gen;
  
  $arch_data = array();
  $queued_arch_lines = array();
  $new_lite_data = null;
  // Lite chart file path
  $lite_path = preg_replace("/archival/i", 'lite/' . $days_span . '_days', $archive_path);
  
  
    // Hash of lite path, AND random X hours update threshold, to spread out and event-track 'all' chart rebuilding
    if ( $days_span == 'all' ) {
    $lite_path_hash = md5($lite_path);
    $thres_range = explode(',', $ct_conf['dev']['all_chart_rebuild_min_max']);
    $all_chart_rebuild_thres = rand($thres_range[0], $thres_range[1]); // Randomly within the min/max range, to spead the load across multiple runtimes
    }
   
   
    // Get LAST line of lite chart data (determines newest lite timestamp)
    if ( file_exists($lite_path) ) {
    $last_lite_line = $this->tail_custom($lite_path);
    $last_lite_array = explode("||", $last_lite_line);
    $newest_lite_timestamp = ( isset($last_lite_array[0]) ? $ct_var->num_to_str($last_lite_array[0]) : false );
    }
    else {
    $newest_lite_timestamp = false;
    }
  
  
  // Get LAST line of archival chart data (we save SIGNIFICANTLY on runtime / resource usage, if this var is passed into this function already)
  // (determines newest archival timestamp)
  $last_arch_line = ( $newest_arch_data != false ? $newest_arch_data : $this->tail_custom($archive_path) );
  $last_arch_array = explode("||", $last_arch_line);
  $newest_arch_timestamp = $ct_var->num_to_str($last_arch_array[0]);
     
     
  // Get FIRST line of archival chart data (determines oldest archival timestamp)
  $fopen_archive = fopen($archive_path, 'r');
  
    if ($fopen_archive) {
    $first_arch_line = fgets($fopen_archive);
    fclose($fopen_archive);
    gc_collect_cycles(); // Clean memory cache
    }
   
  $first_arch_array = explode("||", $first_arch_line);
  $oldest_arch_timestamp = $ct_var->num_to_str($first_arch_array[0]);
   
     
    // Oldest base timestamp we can use (only applies for x days charts, not 'all')
    if ( $days_span != 'all' ) {
    $base_min_timestamp = $ct_var->num_to_str( strtotime('-'.$days_span.' day', $newest_arch_timestamp) );
    }
    
    // If it's the 'all' lite chart, OR the oldest archival timestamp is newer than oldest base timestamp we can use
    if ( $days_span == 'all' || $days_span != 'all' && $oldest_arch_timestamp > $base_min_timestamp ) {
    $oldest_allowed_timestamp = $oldest_arch_timestamp;
    }
    // If it's an X days lite chart (not 'all'), and we have archival timestamps that are older than oldest base timestamp we can use
    elseif ( $days_span != 'all' ) {
    $oldest_allowed_timestamp = $base_min_timestamp;  
    }
   
   
    // Minimum time interval between data points in lite chart
    if ( $days_span == 'all' ) {
    $min_data_interval = round( ($newest_arch_timestamp - $oldest_arch_timestamp) / $ct_conf['power']['lite_chart_data_points_max'] ); // Dynamic
    }
    else {
    $min_data_interval = round( ($days_span * 86400) / $ct_conf['power']['lite_chart_data_points_max'] ); // Fixed X days (86400 seconds per day)
    }
  
  
    // #INITIALLY# (if no lite data exists yet) we randomly spread the load across multiple cron jobs
    // THEN IT #REMAINS RANDOMLY SPREAD# ACROSS CRON JOBS #WITHOUT DOING ANYTHING AFTER# THE INITIAL RANDOMNESS
    if ( $newest_lite_timestamp == false ) {
    $lite_data_update_thres = rand( (time() - 3333) , (time() + 6666) ); // 1/3 of all lite charts REBUILDS update on average, per runtime
    }
    // Update threshold calculated from pre-existing lite data
    else {
    $lite_data_update_thres = $newest_lite_timestamp + $min_data_interval;
    }
  
  
  // Large number support (NOT scientific format), since we manipulated these
  $min_data_interval = $ct_var->num_to_str($min_data_interval); 
  $lite_data_update_thres = $ct_var->num_to_str($lite_data_update_thres); 
  
  
     // If we are queued to update an existing lite chart, get the data points we want to add 
     // (may be multiple data points, if the last update had network errors / system reboot / etc)
     if ( isset($newest_lite_timestamp) && $lite_data_update_thres <= $newest_arch_timestamp ) {
     
        // If we are only adding the newest archival data point (passed into this function), 
        // #we save BIGTIME on resource usage# (used EVERYTIME, other than very rare FALLBACKS)
        // CHECKS IF UPDATE THRESHOLD IS GREATER THAN NEWEST ARCHIVAL DATA POINT TIMESTAMP, 
        // #WHEN ADDING AN EXTRA# $min_data_interval (so we know to only add one data point)
        if ( $ct_var->num_to_str($lite_data_update_thres + $min_data_interval) > $newest_arch_timestamp ) {
        $queued_arch_lines[] = $last_arch_line;
        }
       // If multiple lite chart data points missing (from any very rare FALLBACK instances, like network / load / disk / runtime issues, etc)
        else {
        
       $tail_arch_lines = $this->tail_custom($archive_path, 20); // Grab last 20 lines, to be safe
       $tail_arch_lines_array = explode("\n", $tail_arch_lines);
       // Remove all null / false / empty strings, and reindex
       $tail_arch_lines_array = array_values( array_filter( $tail_arch_lines_array, 'strlen' ) ); 
         
          foreach( $tail_arch_lines_array as $arch_line ) {
          $arch_line_array = explode('||', $arch_line);
          $arch_line_array[0] = $ct_var->num_to_str($arch_line_array[0]);
           
             if ( !$added_arch_timestamp && $lite_data_update_thres <= $arch_line_array[0]
             || isset($added_arch_timestamp) && $ct_var->num_to_str($added_arch_timestamp + $min_data_interval) <= $arch_line_array[0] ) {
             $queued_arch_lines[] = $arch_line;
             $added_arch_timestamp = $arch_line_array[0];
             }
          
          }
         
        }
       
       
        // DEBUGGING data
        if ( is_array($queued_arch_lines) ) {
        $added_arch_mode = sizeof($queued_arch_lines) . '_ADDED';
        }
        else {
        $added_arch_mode = '0_ADDED';
        }
        
     
     }
  
  
  
    ////////////////////////////////////////////////////////////////////////////////////////////////
    // Not time to update / rebuild this lite chart yet
    ////////////////////////////////////////////////////////////////////////////////////////////////
    if ( $lite_data_update_thres > $newest_arch_timestamp ) {
    gc_collect_cycles(); // Clean memory cache
    return false;
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////
    // If no lite chart exists yet, OR it's time to RESET intervals in the 'all' chart, rebuild from scratch
    // (we STILL check $queued_arch_lines for new data, to see if we should SKIP an 'all' charts full rebuild now)
    ////////////////////////////////////////////////////////////////////////////////////////////////
    elseif ( !$newest_lite_timestamp 
    || $days_span == 'all' && is_array($queued_arch_lines) && sizeof($queued_arch_lines) > 0 && $this->update_cache($base_dir . '/cache/events/lite_chart_rebuilds/all_days_chart_'.$lite_path_hash.'.dat', (60 * $all_chart_rebuild_thres) ) == true ) {
   
    $archive_file_data = file($archive_path);
    $archive_file_data = array_reverse($archive_file_data); // Save time, only loop / read last lines needed
    
    
      foreach($archive_file_data as $line) {
      
      $line_array = explode("||", $line);
      $line_array[0] = $ct_var->num_to_str($line_array[0]);
     
        if ( $line_array[0] >= $oldest_allowed_timestamp ) {
        $arch_data[] = $line;
        }
      
      }
    
     
      // We are looping IN REVERSE ODER, to ALWAYS include the latest data
      $loop = 0;
      $data_points = 0;
      // $data_points <= is INTENTIONAL, as we can have max data points slightly under without it
      while ( isset($arch_data[$loop]) && $data_points <= $ct_conf['power']['lite_chart_data_points_max'] ) {
       
      $data_point_array = explode("||", $arch_data[$loop]);
      $data_point_array[0] = $ct_var->num_to_str($data_point_array[0]);
        
        if ( !$next_timestamp || isset($next_timestamp) && $data_point_array[0] <= $next_timestamp ) {
        $new_lite_data = $arch_data[$loop] . $new_lite_data;// WITHOUT newline, since file() maintains those by default
        $next_timestamp = $data_point_array[0] - $min_data_interval;
        $data_points = $data_points + 1;
        }
     
      $loop = $loop + 1;
      }
     
    
    // Store the lite chart data (rebuild)
    $result = $this->save_file($lite_path, $new_lite_data);  // WITHOUT newline, since file() maintains those by default (file write)
    $lite_mode_logging = 'REBUILD';
    
      // Update the 'all' lite chart rebuild event tracking, IF THE LITE CHART UPDATED SUCESSFULLY
      if ( $days_span == 'all' && $result == true ) {
      $this->save_file($base_dir . '/cache/events/lite_chart_rebuilds/all_days_chart_'.$lite_path_hash.'.dat', $ct_gen->time_date_format(false, 'pretty_date_time') );
      }
     
   
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////
    // If the lite chart has existing data, then $queued_arch_lines should be populated (IF we have new data to append to it).
    // We also trim out X first lines of stale data (earlier then the X days time range)
    ////////////////////////////////////////////////////////////////////////////////////////////////
    elseif ( is_array($queued_arch_lines) && sizeof($queued_arch_lines) > 0 ) {
     
    $queued_arch_data = implode("\n", $queued_arch_lines);
    
    // Current lite chart lines, plus new archival lines queued to be added
    $check_lite_data_lines = $ct_gen->get_lines($lite_path) + sizeof($queued_arch_lines);
     
    // Get FIRST line of lite chart data (determines oldest lite timestamp)
    $fopen_lite = fopen($lite_path, 'r');
    
      if ($fopen_lite) {
      $first_lite_line = fgets($fopen_lite);
      fclose($fopen_lite);
      usleep(20000); // Wait 0.02 seconds, since we'll be writing data to this file momentarily
      gc_collect_cycles(); // Clean memory cache
      }
       
    $first_lite_array = explode("||", $first_lite_line);
    $oldest_lite_timestamp = $ct_var->num_to_str($first_lite_array[0]);
     
      // If our oldest lite timestamp is older than allowed, remove the stale data points
      if ( $oldest_lite_timestamp < $oldest_allowed_timestamp ) {
      $lite_data_removed_outdated_lines = $ct_gen->prune_first_lines($lite_path, 0, $oldest_allowed_timestamp);
      
      // ONLY APPEND A LINE BREAK TO THE NEW ARCHIVAL DATA, since $ct_gen->prune_first_lines() maintains the existing line breaks
      $result = $this->save_file($lite_path, $lite_data_removed_outdated_lines['data'] . $queued_arch_data . "\n");  // WITH newline for NEW data (file write)
      $lite_mode_logging = 'OVERWRITE_' . $lite_data_removed_outdated_lines['lines_removed'] . '_OUTDATED_PRUNED_' . $added_arch_mode;
      }
      // If we're clear to just append the latest data
      else {
      $result = $this->save_file($lite_path, $queued_arch_data . "\n", "append");  // WITH newline (file write)
      $lite_mode_logging = 'APPEND_' . $added_arch_mode;
      }
     
   
    }
    // No lite data to update
    else {
    $result = false;
    }
   
  
  
    // Logging results
    if ( $result == true ) {
     
    $_SESSION['lite_charts_updated'] = $_SESSION['lite_charts_updated'] + 1;
      
      if ( 
      $ct_conf['dev']['debug'] == 'all'
      || $ct_conf['dev']['debug'] == 'all_telemetry'
      || $ct_conf['dev']['debug'] == 'lite_chart_telemetry' 
      ) {
      	
      $ct_gen->log(
      			'cache_debug',
      			'Lite chart ' . $lite_mode_logging . ' COMPLETED ('.$_SESSION['lite_charts_updated'].') for ' . $lite_path
      			);
      
      }
       
      if ( 
      $ct_conf['dev']['debug'] == 'all'
      || $ct_conf['dev']['debug'] == 'all_telemetry'
      || $ct_conf['dev']['debug'] == 'memory_usage_telemetry' 
      ) {
      	
      $ct_gen->log(
      			'system_debug',
      			$_SESSION['lite_charts_updated'] . ' lite charts updated, CURRENT script memory usage is ' . $ct_gen->conv_bytes(memory_get_usage(), 1) . ', PEAK script memory usage is ' . $ct_gen->conv_bytes(memory_get_peak_usage(), 1) . ', php_sapi_name is "' . php_sapi_name() . '"'
      			);
     
      }
      
    }
    else {
        
        if ( file_exists($archive_path) ) {
        $ct_gen->log( 'cache_error', 'Lite chart ' . $lite_mode_logging . ' FAILED, data from archive file ' . $archive_path . ' could not be read. Check cache directory permissions.');
        }
        else {
        $ct_gen->log( 'cache_error', 'Lite chart ' . $lite_mode_logging . ' FAILED for ' . $lite_path . ', archival data not created yet (for new installs please wait a few hours, then check cache directory permissions if this error continues beyond then).');
        }
    
    }
  
   
  gc_collect_cycles(); // Clean memory cache
  
  return $result;
  
  }
  
  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function send_notifications() {
  
  global $base_dir, $ct_conf, $ct_var, $ct_gen, $processed_msgs, $possible_http_users, $http_runtime_user, $current_runtime_user, $telegram_user_data, $telegram_activated;
  
  
  // Array of currently queued messages in the cache
  $msgs_queue = $ct_gen->sort_files($base_dir . '/cache/secured/messages', 'queue', 'asc');
   
  //var_dump($msgs_queue); // DEBUGGING ONLY
  //return false; // DEBUGGING ONLY
  
  
    // If queued messages exist, proceed
    if ( is_array($msgs_queue) && sizeof($msgs_queue) > 0 ) {
    
    
      if ( !isset($processed_msgs['notifications_count']) ) {
      $processed_msgs['notifications_count'] = 0;
      }
      
      
      // If it's been well over 5 minutes since a notifyme alert was sent 
      // (we use 6 minutes, safely over the 5 minute limit for the maximum 5 requests), 
      // and no session count is set, set session count to zero
      // Don't update the file-cached count here, that will happen automatically from resetting the session count to zero 
      // (if there are notifyme messages queued to send)
      if ( !isset($processed_msgs['notifyme_count']) && $this->update_cache($base_dir . '/cache/events/throttling/notifyme-alerts-sent.dat', 6) == true ) {
      $processed_msgs['notifyme_count'] = 0;
      }
      // If it hasn't been well over 5 minutes since the last notifyme send
      // (we use 6 minutes, safely over the 5 minute limit for the maximum 5 requests), and there is no session count, 
      // use the file-cached count for the session count starting point
      elseif ( !isset($processed_msgs['notifyme_count']) && $this->update_cache($base_dir . '/cache/events/throttling/notifyme-alerts-sent.dat', 6) == false ) {
      $processed_msgs['notifyme_count'] = trim( file_get_contents($base_dir . '/cache/events/throttling/notifyme-alerts-sent.dat') );
      }
      
      
      if ( !isset($processed_msgs['text_count']) ) {
      $processed_msgs['text_count'] = 0;
      }
      
      
      if ( !isset($processed_msgs['telegram_count']) ) {
      $processed_msgs['telegram_count'] = 0;
      }
      
      
      if ( !isset($processed_msgs['email_count']) ) {
      $processed_msgs['email_count'] = 0;
      }
     
    
    // ONLY process queued messages IF they are NOT already being processed by another runtime instance
    // Use file locking with flock() to do this
     
    $queued_msgs_processing_lock_file = $base_dir . '/cache/events/notifications-queue-processing.dat';
     
    $fp = fopen($queued_msgs_processing_lock_file, "w+");
    
     
      if ( flock($fp, LOCK_EX) ) {  // If we are allowed a file lock, we can proceed
      
      ////////////START//////////////////////
      
      
        // Sleep for 2 seconds before starting ANY consecutive message send, to help avoid being blocked / throttled by external server
        if ( $processed_msgs['notifications_count'] > 0 ) {
        sleep(2);
        }
      
      
      $notifyme_params = array(
              'notification' => null, // Setting this right before sending
              'accessCode' => $ct_conf['comms']['notifyme_accesscode']
                );
          
          
      $textbelt_params = array(
              'message' => null, // Setting this right before sending
              'phone' => $ct_gen->mob_number($ct_conf['comms']['to_mobile_text']),
              'key' => $ct_conf['comms']['textbelt_apikey']
             );
          
          
      $textlocal_params = array(
               'message' => null, // Setting this right before sending
               'username' => $ct_var->str_to_array($ct_conf['comms']['textlocal_account'])[0],
               'hash' => $ct_var->str_to_array($ct_conf['comms']['textlocal_account'])[1],
               'numbers' => $ct_gen->mob_number($ct_conf['comms']['to_mobile_text'])
                );
      
      
       
        // Send messages
        foreach ( $msgs_queue as $queued_cache_file ) {
        
        
        $msg_data = trim( file_get_contents($base_dir . '/cache/secured/messages/' . $queued_cache_file) );
        
         
          // If 0 bytes from system / network issues, just delete it to keep the directory contents clean
          if ( filesize($base_dir . '/cache/secured/messages/' . $queued_cache_file) == 0 ) {
          unlink($base_dir . '/cache/secured/messages/' . $queued_cache_file);
          }
          // Notifyme
          elseif ( $msg_data != '' && trim($ct_conf['comms']['notifyme_accesscode']) != '' && preg_match("/notifyme/i", $queued_cache_file) ) {
            
            $notifyme_params['notification'] = $msg_data;
            
          // Sleep for 1 second EXTRA on EACH consecutive notifyme message, to throttle MANY outgoing messages, to help avoid being blocked / throttled by external server
          $notifyme_sleep = 1 * $processed_msgs['notifyme_count'];
          sleep($notifyme_sleep);
          
           
              // Only 5 notifyme messages allowed per minute
              if ( $processed_msgs['notifyme_count'] < 5 ) {
              
              $notifyme_response = @$this->ext_data('params', $notifyme_params, 0, 'https://api.notifymyecho.com/v1/NotifyMe');
             
              $processed_msgs['notifyme_count'] = $processed_msgs['notifyme_count'] + 1;
              
              $msg_sent = 1;
              
              $this->save_file($base_dir . '/cache/events/throttling/notifyme-alerts-sent.dat', $processed_msgs['notifyme_count']); 
              
                if ( $ct_conf['dev']['debug'] == 'all' || $ct_conf['dev']['debug'] == 'all_telemetry' || $ct_conf['dev']['debug'] == 'api_comms_telemetry' ) {
                $this->save_file($base_dir . '/cache/logs/debug/external_data/last-response-notifyme.log', $notifyme_response);
                }
              
              unlink($base_dir . '/cache/secured/messages/' . $queued_cache_file);
              
              }
          
          
          }
          
          
          
          // Textbelt
          // To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
          // Only run if textlocal API isn't being used to avoid double texts
          if ( $msg_data != '' && trim($ct_conf['comms']['textbelt_apikey']) != '' && $ct_conf['comms']['textlocal_account'] == '' && preg_match("/textbelt/i", $queued_cache_file) ) {
            
          $textbelt_params['message'] = $msg_data;
            
          // Sleep for 1 second EXTRA on EACH consecutive text message, to throttle MANY outgoing messages, to help avoid being blocked / throttled by external server
          $text_sleep = 1 * $processed_msgs['text_count'];
          sleep($text_sleep);
            
          $textbelt_response = @$this->ext_data('params', $textbelt_params, 0, 'https://textbelt.com/text', 2);
            
          $processed_msgs['text_count'] = $processed_msgs['text_count'] + 1;
          
          $msg_sent = 1;
            
            if ( $ct_conf['dev']['debug'] == 'all' || $ct_conf['dev']['debug'] == 'all_telemetry' || $ct_conf['dev']['debug'] == 'api_comms_telemetry' ) {
            $this->save_file($base_dir . '/cache/logs/debug/external_data/last-response-textbelt.log', $textbelt_response);
            }
          
          unlink($base_dir . '/cache/secured/messages/' . $queued_cache_file);
          
          }
          
          
          
          // Textlocal
          // To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
          // Only run if textbelt API isn't being used to avoid double texts
          if ( $msg_data != '' && $ct_conf['comms']['textlocal_account'] != '' && trim($ct_conf['comms']['textbelt_apikey']) == '' && preg_match("/textlocal/i", $queued_cache_file) ) {  
            
          $textlocal_params['message'] = $msg_data;
            
          // Sleep for 1 second EXTRA on EACH consecutive text message, to throttle MANY outgoing messages, to help avoid being blocked / throttled by external server
          $text_sleep = 1 * $processed_msgs['text_count'];
          sleep($text_sleep);
            
          $textlocal_response = @$this->ext_data('params', $textlocal_params, 0, 'https://api.txtlocal.com/send/', 1);
            
          $processed_msgs['text_count'] = $processed_msgs['text_count'] + 1;
          
          $msg_sent = 1;
            
            if ( $ct_conf['dev']['debug'] == 'all' || $ct_conf['dev']['debug'] == 'all_telemetry' || $ct_conf['dev']['debug'] == 'api_comms_telemetry' ) {
            $this->save_file($base_dir . '/cache/logs/debug/external_data/last-response-textlocal.log', $textlocal_response);
            }
          
          unlink($base_dir . '/cache/secured/messages/' . $queued_cache_file);
          
          }
          
          
          
          // Telegram
          if ( $telegram_activated == 1 && preg_match("/telegram/i", $queued_cache_file) ) {
            
          // Sleep for 1 second EXTRA on EACH consecutive telegram message, to throttle MANY outgoing messages, to help avoid being blocked / throttled by external server
          $telegram_sleep = 1 * $processed_msgs['telegram_count'];
          sleep($telegram_sleep);
            
          $telegram_response = $ct_gen->telegram_msg($msg_data, $telegram_user_data['message']['chat']['id']);
          
             if ( $telegram_response != false ) {
              
             $processed_msgs['telegram_count'] = $processed_msgs['telegram_count'] + 1;
             
            $msg_sent = 1;
           
            unlink($base_dir . '/cache/secured/messages/' . $queued_cache_file);
              
             }
             else {
             $ct_gen->log( 'system_error', 'Telegram sending failed', $telegram_response);
             }
              
            
             if ( $ct_conf['dev']['debug'] == 'all' || $ct_conf['dev']['debug'] == 'all_telemetry' || $ct_conf['dev']['debug'] == 'api_comms_telemetry' ) {
             $this->save_file($base_dir . '/cache/logs/debug/external_data/last-response-telegram.log', $telegram_response);
             }
          
          }
           
             
           // SEND EMAILS LAST, IN CASE OF SMTP METHOD FAILURE AND RUNTIME EXIT
          
          
          // Text email
          // To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
          // Only use text-to-email if other text services aren't configured
          if ( $ct_gen->valid_email( $ct_gen->text_email($ct_conf['comms']['to_mobile_text']) ) == 'valid' && trim($ct_conf['comms']['textbelt_apikey']) == '' && $ct_conf['comms']['textlocal_account'] == '' && preg_match("/textemail/i", $queued_cache_file) ) {
            
          $textemail_array = json_decode($msg_data, true);
            
          $restore_text_charset = $textemail_array['charset'];
            
              // json_encode() only accepts UTF-8, SO CONVERT BACK TO ORIGINAL CHARSET
              if ( strtolower($restore_text_charset) != 'utf-8' ) {
              
                foreach( $textemail_array as $textemail_key => $textemail_val ) {
                // Leave charset / content_type vars UTF-8
                $textemail_array[$textemail_key] = ( $textemail_key == 'charset' || $textemail_key == 'content_type' ? $textemail_val : mb_convert_encoding($textemail_val, $restore_text_charset, 'UTF-8') );
                }
          
              }
         
            
              if ( $textemail_array['subject'] != '' && $textemail_array['message'] != '' ) {
               
              // Sleep for 1 second EXTRA on EACH consecutive text message, to throttle MANY outgoing messages, to help avoid being blocked / throttled by external server
              $text_sleep = 1 * $processed_msgs['text_count'];
              sleep($text_sleep);
               
              $result = @$ct_gen->safe_mail( $ct_gen->text_email($ct_conf['comms']['to_mobile_text']) , $textemail_array['subject'], $textemail_array['message'], $textemail_array['content_type'], $textemail_array['charset']);
               
                 if ( $result == true ) {
                 
                 $processed_msgs['text_count'] = $processed_msgs['text_count'] + 1;
                
                 $msg_sent = 1;
              
                 unlink($base_dir . '/cache/secured/messages/' . $queued_cache_file);
                 
                 }
                 else {
                 	
                 $ct_gen->log(
                 			'system_error',
                 			'Email-to-mobile-text sending failed',
                 			'to_text_email: ' . $ct_gen->text_email($ct_conf['comms']['to_mobile_text']) . '; from: ' . $ct_conf['comms']['from_email'] . '; subject: ' . $textemail_array['subject'] . '; function_response: ' . $result . ';'
                 			);
                 
                 }
              
              
              }
          
          
          }
            
            
            
          // Normal email
          if ( $ct_gen->valid_email($ct_conf['comms']['to_email']) == 'valid' && preg_match("/normalemail/i", $queued_cache_file) ) {
            
          $email_array = json_decode($msg_data, true);
            
          $restore_email_charset = $email_array['charset'];
            
              // json_encode() only accepts UTF-8, SO CONVERT BACK TO ORIGINAL CHARSET
              if ( strtolower($restore_email_charset) != 'utf-8' ) {
              
                foreach( $email_array as $email_key => $email_val ) {
                // Leave charset / content_type vars UTF-8
                $email_array[$email_key] = ( $email_key == 'charset' || $email_key == 'content_type' ? $email_val : mb_convert_encoding($email_val, $restore_email_charset, 'UTF-8') );
                }
          
              }
            
            
              if ( $email_array['subject'] != '' && $email_array['message'] != '' ) {
               
              // Sleep for 1 second EXTRA on EACH consecutive email message, to throttle MANY outgoing messages, to help avoid being blocked / throttled by external server
              $email_sleep = 1 * $processed_msgs['email_count'];
              sleep($email_sleep);
               
              $result = @$ct_gen->safe_mail($ct_conf['comms']['to_email'], $email_array['subject'], $email_array['message'], $email_array['content_type'], $email_array['charset']);
               
                 if ( $result == true ) {
                 
                 $processed_msgs['email_count'] = $processed_msgs['email_count'] + 1;
                
                 $msg_sent = 1;
              
                 unlink($base_dir . '/cache/secured/messages/' . $queued_cache_file);
                 
                 }
                 else {
                 	
                 $ct_gen->log(
                 			'system_error',
                 			'Email sending failed',
                 			'to_email: ' . $ct_conf['comms']['to_email'] . '; from: ' . $ct_conf['comms']['from_email'] . '; subject: ' . $email_array['subject'] . '; function_response: ' . $result . ';'
                 			);
                 
                 }
                 
              
              }
          
          
          }
           
        
        
        }
       
       
       
        if ( $msg_sent == 1 ) {
        $processed_msgs['notifications_count'] = $processed_msgs['notifications_count'] + 1;
        }
      
      
      
      ////////////END//////////////////////
      
      
      
      // We are done processing the queue, so we can release the lock
      fwrite($fp, $ct_gen->time_date_format(false, 'pretty_date_time'). " UTC (with file lock)\n");
      fflush($fp);            // flush output before releasing the lock
      flock($fp, LOCK_UN);    // release the lock
      $result = true;
      } 
      else {
      fwrite($fp, $ct_gen->time_date_format(false, 'pretty_date_time'). " UTC (no file lock)\n");
      $result = false; // Another runtime instance was already processing the queue, so skip processing and return false
      }
     
    fclose($fp);
   
    gc_collect_cycles(); // Clean memory cache
    
    
    // MAKE SURE we have good chmod file permissions for less-sophisticated server setups
    $path_parts = pathinfo($queued_msgs_processing_lock_file);
    $file_owner_info = posix_getpwuid(fileowner($queued_msgs_processing_lock_file));
       
      // Does the current runtime user own this file?
      if ( isset($current_runtime_user) && $current_runtime_user == $file_owner_info['name'] ) {
      
      $chmod_setting = octdec($ct_conf['dev']['chmod_cache_file']);
      
        // Run chmod compatibility on certain PHP setups
        if ( !$http_runtime_user || isset($http_runtime_user) && in_array($http_runtime_user, $possible_http_users) ) {
         
        $oldmask = umask(0);
        
        $did_chmod = chmod($queued_msgs_processing_lock_file, $chmod_setting);
       
          if ( !$did_chmod ) {
          	
          $ct_gen->log(
          			'system_error',
          							
          			'Chmod failed for file "' . $queued_msgs_processing_lock_file . '" (check permissions for the path "' . $path_parts['dirname'] . '", and the file "' . $path_parts['basename'] . '")',
          							
          			'chmod_setting: ' . $chmod_setting . '; current_runtime_user: ' . $current_runtime_user . '; file_owner: ' . $file_owner_info['name'] . ';'
          			);
          
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
  
  
  function ext_data($mode, $request_params, $ttl, $api_server=null, $post_encoding=3, $test_proxy=null, $headers=null) { // Default to JSON encoding post requests (most used)
  
  // $ct_conf['gen']['btc_prim_currency_pairing'] / $ct_conf['gen']['btc_prim_exchange'] / $sel_opt['sel_btc_prim_currency_val'] USED FOR TRACE DEBUGGING (TRACING)
  
  global $base_dir, $base_url, $ct_conf, $ct_var, $ct_gen, $sel_opt, $proxy_checkup, $log_array, $limited_api_calls, $api_runtime_cache, $user_agent, $api_connections, $defipulse_api_limit, $htaccess_username, $htaccess_password;
  
  $cookie_jar = tempnam('/tmp','cookie');
   
  // To cache duplicate requests based on a data hash, during runtime update session (AND persist cache to flat files)
  $hash_check = ( $mode == 'params' ? md5(serialize($request_params)) : md5($request_params) );
  
  $api_endpoint = ( $mode == 'params' ? $api_server : $request_params );
     
  $endpoint_tld_or_ip = $ct_gen->get_tld_or_ip($api_endpoint);
  
  $tld_session_prefix = preg_replace("/\./i", "_", $endpoint_tld_or_ip);
    
   
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
    unlink($base_dir . '/cache/secured/external_data/'.$hash_check.'.dat');
    }
    //////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////
    // FIRST, see if we have data in the RUNTIME cache (the MEMORY cache, NOT the FILE cache), for the quickest data retrieval time
    // Only use runtime cache if $ttl greater than zero (set as 0 NEVER wants cached data, -1 is flag for deleting cache data)
    //////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////
    elseif ( isset($api_runtime_cache[$hash_check]) && $ttl > 0 ) {
    
    $data = $api_runtime_cache[$hash_check];
    
    // Size of data, for checks in error log UX logic
    $data_bytes = strlen($data);
    $data_bytes_ux = $ct_gen->conv_bytes($data_bytes, 2);
    
     
      if ( $data == 'none' ) {
      
      
        if ( !$log_array['error_duplicates'][$hash_check] ) {
        $log_array['error_duplicates'][$hash_check] = 1; 
        }
        else {
        $log_array['error_duplicates'][$hash_check] = $log_array['error_duplicates'][$hash_check] + 1;
        }
       
       
      // Don't log this error again during THIS runtime, as it would be a duplicate...just overwrite same error message, BUT update the error count in it
      
      $ct_gen->log(
      			'cache_error',
      							
      			'no RUNTIME CACHE data from failure with ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . $ct_gen->obfusc_url_data($api_endpoint),
      							
      			'requested_from: cache ('.$log_array['error_duplicates'][$hash_check].' runtime instances); mode: ' . $mode . '; received: ' . $data_bytes_ux . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; hash_check: ' . $ct_var->obfusc_str($hash_check, 4) . ';',
      							
      			$hash_check
      			);
       
      }
      elseif ( $ct_conf['dev']['debug'] == 'all' || $ct_conf['dev']['debug'] == 'all_telemetry' || $ct_conf['dev']['debug'] == 'ext_data_cache_telemetry' ) {
      
      
        if ( !$log_array['debug_duplicates'][$hash_check] ) {
        $log_array['debug_duplicates'][$hash_check] = 1; 
        }
        else {
        $log_array['debug_duplicates'][$hash_check] = $log_array['debug_duplicates'][$hash_check] + 1;
        }
       
       
      // Don't log this debugging again during THIS runtime, as it would be a duplicate...just overwrite same debugging message, BUT update the debugging count in it
      
      $ct_gen->log(
      			'cache_debug',
      							
      			'RUNTIME CACHE request for ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . $ct_gen->obfusc_url_data($api_endpoint),
      							
      			'requested_from: cache ('.$log_array['debug_duplicates'][$hash_check].' runtime instances); mode: ' . $mode . '; received: ' . $data_bytes_ux . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; hash_check: ' . $ct_var->obfusc_str($hash_check, 4) . ';',
      							
      			$hash_check
      			);
      
      }
    
    
    }
    //////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////
    // Live data retrieval (if no runtime cache exists yet)
    //////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////
    elseif ( !isset($api_runtime_cache[$hash_check]) && $this->update_cache($base_dir . '/cache/secured/external_data/'.$hash_check.'.dat', $ttl) == true || $ttl == 0 ) {
    
    // Time the request
    $api_time = microtime();
    $api_time = explode(' ', $api_time);
    $api_time = $api_time[1] + $api_time[0];
    $api_start_time = $api_time;
     
     
    // Servers with STRICT reconnect limits
    $strict_reconnect_servers = array(
              						'defipulse.com',
              						);
              
              
      if ( in_array($endpoint_tld_or_ip, $strict_reconnect_servers) ) {
        
      $api_connections[$tld_session_prefix] = $api_connections[$tld_session_prefix] + 1;
      
        if ( $api_connections[$tld_session_prefix] > 1 ) {
        usleep(110000); // Throttle 0.11 seconds
        }
       
      }
    
    
      // Throttled endpoints in $ct_conf['dev']['limited_apis']
      // If this is an API service that requires multiple calls (for each market), 
      // and a request to it has been made consecutively, we throttle it to avoid being blocked / throttled by external server
      if ( in_array($endpoint_tld_or_ip, $ct_conf['dev']['limited_apis']) ) {
      
        if ( !$limited_api_calls[$tld_session_prefix . '_calls'] ) {
        $limited_api_calls[$tld_session_prefix . '_calls'] = 1;
        }
        elseif ( $limited_api_calls[$tld_session_prefix . '_calls'] == 1 ) {
        usleep(150000); // Throttle 0.15 seconds
        }
    
      }
     
    
    // Initiate the curl external data request
    $ch = curl_init( ( $mode == 'params' ? $api_server : '' ) );
     
     
      // If header data is being passed in
      if ( $headers != null ) {
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      }
      
      
      // If proxies are configured
      if ( is_array($ct_conf['proxy']['proxy_list']) && sizeof($ct_conf['proxy']['proxy_list']) > 0 ) {
       
      $current_proxy = ( $mode == 'proxy-check' && $test_proxy != null ? $test_proxy : $ct_var->random_array_var($ct_conf['proxy']['proxy_list']) );
      
      // Check for valid proxy config
      $ip_port = explode(':', $current_proxy);
    
      $ip = $ip_port[0];
      $port = $ip_port[1];
    
        // If no ip/port detected in data string, cancel and continue runtime
        if ( !$ip || !$port ) {
        $ct_gen->log('ext_data_error', 'proxy '.$current_proxy.' is not a valid format');
        return false;
        }
    
      
      curl_setopt($ch, CURLOPT_PROXY, trim($current_proxy) );     
      curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, true);  
      
        // To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
        if ( $ct_conf['proxy']['proxy_login'] != ''  ) {
       
        $user_pass = explode('||', $ct_conf['proxy']['proxy_login']);
         
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
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $ct_conf['power']['remote_api_timeout']);
    curl_setopt($ch, CURLOPT_TIMEOUT, $ct_conf['power']['remote_api_timeout']);
    
     
     // Medium / Reddit (and maybe whatbitcoindid) are a bit funky with allowed user agents, so we need to let them know this is a real feed parser (not just a spammy bot)
     $strict_feed_servers = array(
              'medium.com',
              'reddit.com',
              'whatbitcoindid.com',
              'simplecast.com',
              );
              
     
      if ( in_array($endpoint_tld_or_ip, $strict_feed_servers) ) {
      curl_setopt($ch, CURLOPT_USERAGENT, 'Custom_Feed_Parser/1.0 (compatible; Open_Crypto_Tracker/' . $app_version . '; +https://github.com/taoteh1221/Open_Crypto_Tracker)');
      }
      else {
      curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
      }
     
     
      // If this is an SSL connection, add SSL parameters
      if ( preg_match("/https:\/\//i", $api_endpoint) ) {
      
      
      // We don't want strict SSL checks if this is our app calling itself (as we may be running our own self-signed certificate)
      // (app running an external check on its htaccess, etc)
      $regex_base_url = $ct_gen->regex_compat_url($base_url);
       
      // Secure random hash to nullify any preg_match() below, as we are submitting out htaccess user/pass if setup
      $scan_base_url = ( $regex_base_url != '' ? $regex_base_url : $ct_gen->rand_hash(8) );
      
       
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
        $remote_api_strict_ssl = $ct_conf['dev']['remote_api_strict_ssl'];
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
     if ( $endpoint_tld_or_ip == 'blablabla.com' ) {
     $debug_problem_endpoint_data = 1;
     curl_setopt($ch, CURLOPT_VERBOSE, 1);
     curl_setopt($ch, CURLOPT_HEADER, 1);
     }
     */
     
    
    // Get response data
    $data = curl_exec($ch);
    
    // Size of data, for checks in error log UX logic
    $data_bytes = strlen($data);
    $data_bytes_ux = $ct_gen->conv_bytes($data_bytes, 2);
    
    
      // IF DEBUGGING FOR PROBLEM ENDPOINT IS ENABLED
      if ( $debug_problem_endpoint_data ) {
      
      // Response data
      $debug_header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
      $debug_header = substr($data, 0, $debug_header_size);
      $debug_body = substr($data, $debug_header_size);
      
      // Debugging output
      $debug_data = "\n\n\n" . 'header_size: ' . $debug_header_size . ' bytes' . "\n\n\n" . 'header: ' . "\n\n\n" . $debug_header . "\n\n\n" . 'body: ' . "\n\n\n" . $debug_body . "\n\n\n";
      
      $debug_response_log = $base_dir . '/cache/logs/debug/external_data/problem-endpoint-'.preg_replace("/\./", "_", $endpoint_tld_or_ip).'-hash-'.$hash_check.'-timestamp-'.time().'.log';
      
      // Store to file
      $this->save_file($debug_response_log, $debug_data);
      
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
      if ( $data == '' && $is_self_security_test != 1 ) {
       
      // FALLBACK TO FILE CACHE DATA, IF AVAILABLE (WE STILL LOG THE FAILURE, SO THIS OS OK)
      // (NO LOGIC NEEDED TO CHECK RUNTIME CACHE, AS WE ONLY ARE HERE IF THERE IS NONE)
       
      $data = trim( file_get_contents($base_dir . '/cache/secured/external_data/'.$hash_check.'.dat') );
        
        
        // IF CACHE DATA EXISTS, flag cache fallback as succeeded, and IMMEADIATELY add data set to runtime cache / update the file cache timestamp
        // (so all following requests DURING THIS RUNTIME are run from cache ASAP, since we had a live request failure)
        if ( $data != '' && $data != 'none' ) {
        $fallback_cache_data = true;
        // IMMEADIATELY RUN THIS LOGIC NOW, EVEN THOUGH IT RUNS AT END OF STATEMENT TOO, SINCE WE HAD A LIVE REQUEST FAILURE
        $api_runtime_cache[$hash_check] = $data;
        touch($base_dir . '/cache/secured/external_data/'.$hash_check.'.dat'); // Update cache file time
        }
        
        
        if ( isset($fallback_cache_data) ) {
        $log_append = ' (cache fallback SUCCEEDED)';
        }
        else {
        $log_append = ' (cache fallback FAILED)';
        }
     
      
      // LOG-SAFE VERSION (no post data with API keys etc)
      $ct_gen->log(
      			'ext_data_error',
      							
      			'connection failed ('.$data_bytes_ux.' received) for ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . $ct_gen->obfusc_url_data($api_endpoint) . $log_append,
      							
      			'requested_from: server (' . $ct_conf['power']['remote_api_timeout'] . ' second timeout); live_request_time: ' . $api_total_time . ' seconds; mode: ' . $mode . '; received: ' . $data_bytes_ux . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; hash_check: ' . $ct_var->obfusc_str($hash_check, 4) . ';'
      			);
      
      
        if ( is_array($ct_conf['proxy']['proxy_list']) && sizeof($ct_conf['proxy']['proxy_list']) > 0 && $current_proxy != '' && $mode != 'proxy-check' ) { // Avoid infinite loops doing proxy checks
     
        $proxy_checkup[] = array(
                    			'endpoint' => ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . $ct_gen->obfusc_url_data($api_endpoint),
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
        if ( 
        preg_match("/xml version/i", $data) // RSS feeds (that are likely intact)
        || preg_match("/invalid vs_currency/i", $data) // Coingecko (we fallback to USD in this case anyways, and error would repeat every cache refresh cluttering logs)
        || preg_match("/\"error\":\[\],/i", $data) // kraken.com / generic
        || preg_match("/\"error_code\":0/i", $data) 
        ) { // coinmarketcap.com / generic
        $false_positive = 1;
        }
       
       
        // DON'T FLAG as a possible error if detected as a false positive already
        // (THIS LOGIC IS FOR STORING THE POSSIBLE ERROR IN /cache/logs/error/external_data FOR REVIEW)
        if ( $false_positive != 1 ) {
         
            // MUST RUN BEFORE FALLBACK ATTEMPT TO CACHED DATA
            // If response seems to contain an error message ('error' only found once, no words containing 'error')
            // DON'T ADD TOO MANY CHECKS HERE, OR RUNTIME WILL SLOW SIGNIFICANTLY!!
            if ( $ct_var->substri_count($data, 'error') == 1 && !preg_match("/terror/i", $data) ) {
             
            // Log full results to file, WITH UNIQUE TIMESTAMP IN FILENAME TO AVOID OVERWRITES (FOR ADEQUATE DEBUGGING REVIEW)
            $error_response_log = '/cache/logs/error/external_data/error-response-'.preg_replace("/\./", "_", $endpoint_tld_or_ip).'-hash-'.$hash_check.'-timestamp-'.time().'.log';
            
            // LOG-SAFE VERSION (no post data with API keys etc)
             $ct_gen->log(
             			'ext_data_error',
             							
             			'POSSIBLE error for ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . $ct_gen->obfusc_url_data($api_endpoint),
             							
             			'requested_from: server (' . $ct_conf['power']['remote_api_timeout'] . ' second timeout); live_request_time: ' . $api_total_time . ' seconds; mode: ' . $mode . '; received: ' . $data_bytes_ux . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; debug_file: ' . $error_response_log . '; btc_prim_currency_pairing: ' . $ct_conf['gen']['btc_prim_currency_pairing'] . '; btc_prim_exchange: ' . $ct_conf['gen']['btc_prim_exchange'] . '; sel_btc_prim_currency_val: ' . $ct_var->num_to_str($sel_opt['sel_btc_prim_currency_val']) . '; hash_check: ' . $ct_var->obfusc_str($hash_check, 4) . ';'
             			);
            
            // Log this error response from this data request
            $this->save_file($base_dir . $error_response_log, $data);
             
            }
        
        
        }
        ////////////////////////////////////////////////////////////////
       
        ////////////////////////////////////////////////////////////////
        // FALLBACK ATTEMPT TO CACHED DATA, IF AVAILABLE (WE STILL LOG THE FAILURE, SO THIS OS OK)
        // WE DON'T WANT TO SLOW DOWN THE RUNTIME TOO MUCH, BUT WE WANT AS MUCH FALLBACK AS IS REASONABLE
        // If response is seen to NOT contain USUAL data, use cache if available
       
        // Check that we didn't detect as a false positive already
        if ( $false_positive != 1 ) {
       
        
            // !!!!!DON'T ADD TOO MANY CHECKS HERE, OR RUNTIME WILL SLOW SIGNIFICANTLY!!!!!
            if ( 
            // Errors / unavailable / null / throttled / maintenance
            preg_match("/cf-error/i", $data) // Cloudflare (DDOS protection service)
            || preg_match("/cf-browser/i", $data) // Cloudflare (DDOS protection service)
            || preg_match("/scheduled maintenance/i", $data) // Bittrex.com / generic
            || preg_match("/Service Unavailable/i", $data) // Bittrex.com / generic
            || preg_match("/temporarily unavailable/i", $data) // Bitfinex.com / generic
            || preg_match("/Server Error/i", $data) // Kucoin.com / generic
            || preg_match("/site is down/i", $data) // Blockchain.info / generic
            || preg_match("/something went wrong/i", $data) // Bitbns.com / generic
            || preg_match("/An error has occurred/i", $data) // Bitflyer.com / generic
            || preg_match("/too many requests/i", $data) // reddit.com / generic
            || preg_match("/Request failed/i", $data) // Defipulse.com / generic
            || preg_match("/EService:Unavailable/i", $data) // Kraken.com / generic
            || preg_match("/EService:Busy/i", $data) // Kraken.com / generic
            || preg_match("/\"result\":{}/i", $data) // Kraken.com / generic
            || preg_match("/\"result\":null/i", $data) // Bittrex.com / generic
            || preg_match("/\"result\":\[\],/i", $data) // Generic
            || preg_match("/\"results\":\[\],/i", $data) // defipulse.com / generic
            || preg_match("/\"data\":null/i", $data) // Bitflyer.com / generic
            || preg_match("/\"success\":false/i", $data) // BTCturk.com / Bittrex.com / generic
            || preg_match("/\"error\":\"timeout/i", $data) // Defipulse.com / generic
            || preg_match("/\"reason\":\"Maintenance\"/i", $data) // Gemini.com / generic
            // APIs famous for returning no data frequently
            || $endpoint_tld_or_ip == 'localbitcoins.com' && !preg_match("/volume_btc/i", $data)
            || $endpoint_tld_or_ip == 'coinmarketcap.com' && !preg_match("/last_updated/i", $data) 
            ) {
            
              
            $data = trim( file_get_contents($base_dir . '/cache/secured/external_data/'.$hash_check.'.dat') );
             
                if ( $data != '' && $data != 'none' ) {
                $fallback_cache_data = true;
                }
               
               
                if ( isset($fallback_cache_data) ) {
                $log_append = ' (cache fallback SUCCEEDED)';
                }
                else {
                $log_append = ' (cache fallback FAILED)';
                }
             
             
            // LOG-SAFE VERSION (no post data with API keys etc)
            $ct_gen->log(
            			'ext_data_error',
            							
            			'CONFIRMED error for ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . $ct_gen->obfusc_url_data($api_endpoint) . $log_append,
            							
            			'requested_from: server (' . $ct_conf['power']['remote_api_timeout'] . ' second timeout); live_request_time: ' . $api_total_time . ' seconds; mode: ' . $mode . '; received: ' . $data_bytes_ux . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; btc_prim_currency_pairing: ' . $ct_conf['gen']['btc_prim_currency_pairing'] . '; btc_prim_exchange: ' . $ct_conf['gen']['btc_prim_exchange'] . '; sel_btc_prim_currency_val: ' . $ct_var->num_to_str($sel_opt['sel_btc_prim_currency_val']) . '; hash_check: ' . $ct_var->obfusc_str($hash_check, 4) . ';'
            			);
             
           
            }
    
       
        }
       
        ////////////////////////////////////////////////////////////////
       
      
      
        // Data debugging telemetry
        if ( $ct_conf['dev']['debug'] == 'all' || $ct_conf['dev']['debug'] == 'all_telemetry' || $ct_conf['dev']['debug'] == 'ext_data_live_telemetry' ) {
         
        // LOG-SAFE VERSION (no post data with API keys etc)
        $ct_gen->log(
        			'ext_data_debug',
        								
        			'LIVE request for ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . $ct_gen->obfusc_url_data($api_endpoint),
        								
        			'requested_from: server (' . $ct_conf['power']['remote_api_timeout'] . ' second timeout); live_request_time: ' . $api_total_time . ' seconds; mode: ' . $mode . '; received: ' . $data_bytes_ux . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; hash_check: ' . $ct_var->obfusc_str($hash_check, 4) . ';'
        			);
        
        // Log this as the latest response from this data request
        $this->save_file($base_dir . '/cache/logs/debug/external_data/last-response-'.preg_replace("/\./", "_", $endpoint_tld_or_ip).'-'.$hash_check.'.log', $data);
        
        }
       
       
      }
    
     
     
     
      // Cache data to the file cache, EVEN IF WE HAVE NO DATA, TO AVOID CONSECUTIVE TIMEOUT HANGS (during page reloads etc) FROM A NON-RESPONSIVE API ENDPOINT
      // Cache API data for this runtime session AFTER PERSISTENT FILE CACHE UPDATE, file cache doesn't reliably update until runtime session is ending because of file locking
      // WE RE-CACHE DATA EVEN IF THIS WAS A FALLBACK TO CACHED DATA, AS WE WANT TO RESET THE TTL UNTIL NEXT LIVE API CHECK
      if ( $ttl > 0 && $mode != 'proxy-check' ) {
      
      // DON'T USE isset(), use != '' to store as 'none' reliably (so we don't keep hitting a server that may be throttling us, UNTIL cache TTL runs out)
      $api_runtime_cache[$hash_check] = ( $data != '' ? $data : 'none' ); 
      
        if ( isset($fallback_cache_data) ) {
        $store_file_contents = touch($base_dir . '/cache/secured/external_data/'.$hash_check.'.dat');
        }
        else {
        $store_file_contents = $this->save_file($base_dir . '/cache/secured/external_data/'.$hash_check.'.dat', $api_runtime_cache[$hash_check]);
        }
        
       
        if ( $store_file_contents == false && isset($fallback_cache_data) ) {
        	
        $ct_gen->log(
        			'ext_data_error',
        			'Cache file touch() error for ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . $ct_gen->obfusc_url_data($api_endpoint),
        			'data_size_bytes: ' . strlen($api_runtime_cache[$hash_check]) . ' bytes'
        			);
        
        }
        elseif ( $store_file_contents == false && !isset($fallback_cache_data) ) {
        	
        $ct_gen->log(
        			'ext_data_error',
        			'Cache file write error for ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . $ct_gen->obfusc_url_data($api_endpoint),
        			'data_size_bytes: ' . strlen($api_runtime_cache[$hash_check]) . ' bytes'
        			);
        
        }
      
      }
      // NEVER cache proxy checking data, OR TTL == 0
      elseif ( $mode == 'proxy-check' || $ttl == 0 ) {
      $api_runtime_cache[$hash_check] = null; 
      }
     
   
      // API timeout limit near / exceeded warning (ONLY IF THIS ISN'T A DATA FAILURE)
      if ( $data_bytes > 0 && $ct_var->num_to_str($ct_conf['power']['remote_api_timeout'] - 1) <= $ct_var->num_to_str($api_total_time) ) {
      	
      $ct_gen->log(
      			'notify_error',
      							
      			'Remote API timeout near OR exceeded for ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . $ct_gen->obfusc_url_data($api_endpoint) . ' (' . $api_total_time . ' seconds / received ' . $data_bytes_ux . '), consider setting "remote_api_timeout" higher in POWER USER config if this persists OFTEN',
      							
      			'remote_api_timeout: ' . $ct_conf['power']['remote_api_timeout'] . ' seconds; live_request_time: ' . $api_total_time . ' seconds; mode: ' . $mode . '; received: ' . $data_bytes_ux . ';',
      							
      			$hash_check
      			);
      
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
      $fallback_cache_data = true;
      }
      else {
        
      $data = trim( file_get_contents($base_dir . '/cache/secured/external_data/'.$hash_check.'.dat') );
      
        if ( $data != '' && $data != 'none' ) {
        $api_runtime_cache[$hash_check] = $data; // Create a runtime cache from the file cache, for any additional requests during runtime for this data set
        $fallback_cache_data = true;
        }
       
      }
    
    // Size of data, for checks in error log UX logic
    $data_bytes = strlen($data);
    $data_bytes_ux = $ct_gen->conv_bytes($data_bytes, 2);
    
     
      if ( $data == 'none' || !isset($fallback_cache_data) ) {
      
        if ( !$log_array['error_duplicates'][$hash_check] ) {
        $log_array['error_duplicates'][$hash_check] = 1; 
        }
        else {
        $log_array['error_duplicates'][$hash_check] = $log_array['error_duplicates'][$hash_check] + 1;
        }
       
      // Don't log this error again during THIS runtime, as it would be a duplicate...just overwrite same error message, BUT update the error count in it
      
      $ct_gen->log(
      			'cache_error',
      							
      			'no FILE CACHE data from failure with ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . $ct_gen->obfusc_url_data($api_endpoint),
      							
      			'requested_from: cache ('.$log_array['error_duplicates'][$hash_check].' runtime instances); mode: ' . $mode . '; received: ' . $data_bytes_ux . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; hash_check: ' . $ct_var->obfusc_str($hash_check, 4) . ';',
      							
      			$hash_check
      			);
       
      }
      elseif ( $ct_conf['dev']['debug'] == 'all' || $ct_conf['dev']['debug'] == 'all_telemetry' || $ct_conf['dev']['debug'] == 'ext_data_cache_telemetry' ) {
      
        if ( !$log_array['debug_duplicates'][$hash_check] ) {
        $log_array['debug_duplicates'][$hash_check] = 1; 
        }
        else {
        $log_array['debug_duplicates'][$hash_check] = $log_array['debug_duplicates'][$hash_check] + 1;
        }
       
      // Don't log this debugging again during THIS runtime, as it would be a duplicate...just overwrite same debugging message, BUT update the debugging count in it
      
      $ct_gen->log(
      			'cache_debug',
      							
      			'FILE CACHE request for ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . $ct_gen->obfusc_url_data($api_endpoint),
      							
      			'requested_from: cache ('.$log_array['debug_duplicates'][$hash_check].' runtime instances); mode: ' . $mode . '; received: ' . $data_bytes_ux . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; hash_check: ' . $ct_var->obfusc_str($hash_check, 4) . ';',
      							
      			$hash_check
      			);
      
      }
    
    
    }
   
  
   
    // Defipulse API limit exceeded detection (FAILSAFE AT END OF FUNCTION before returning, whether live OR cache)
    if ( $endpoint_tld_or_ip == 'defipulse.com' && trim($ct_conf['gen']['defipulse_key']) != null && preg_match("/API limit exceeded/i", $data) ) {
    $ct_gen->log('notify_error', 'DeFiPulse.com monthly API limit exceeded (check your account there)', false, 'defipulsecom_api_limit');
    $defipulse_api_limit = true;
    }
  
  
  gc_collect_cycles(); // Clean memory cache
  return $data;
  
  
  }
  
  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////

      
   
}





?>
