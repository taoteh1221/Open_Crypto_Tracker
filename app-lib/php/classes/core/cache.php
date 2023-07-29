<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
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
  
  }
  
  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function php_timeout_defaults($dir_var) {
   
  global $ct_conf, $ct_var;
  
  $ui_exec_time = $ct_conf['power']['ui_max_exec_time']; // Don't overwrite globals
  
    // If the UI timeout var wasn't set properly / is not a whole number 3600 or less
    if ( !$ct_var->whole_int($ui_exec_time) || $ui_exec_time > 3600 ) {
    $ui_exec_time = 250; // Default
    }
  
  return preg_replace("/\[PHP_TIMEOUT\]/i", $ui_exec_time, file_get_contents($dir_var) );
  
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
              
              if ( time() - filemtime($file) >= (60 * 60 * 24 * $days) ) {
               
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
   
   
   function subarray_cached_ct_conf_upgrade($conf, $cat_key, $conf_key, $skip_upgrading) {
   
   global $default_ct_conf, $ct_gen;
   
      // Check for new variables, and add them
      foreach ( $default_ct_conf[$cat_key][$conf_key] as $setting_key => $setting_val ) {
      
         // Skip this array depth if it's yet another subarray, UNLESS this is the plugin configs
         if ( is_array($setting_val) && $cat_key != 'plug_conf' ) {
         $ct_gen->log('conf_error', 'ct_conf[' .$cat_key . ']['. $conf_key . '][' . $setting_key . '] config upgrade not needed (skipping)');
         }
         elseif ( !in_array($setting_key, $skip_upgrading) && !isset($conf[$cat_key][$conf_key][$setting_key]) ) {
         	
         $conf[$cat_key][$conf_key][$setting_key] = $default_ct_conf[$cat_key][$conf_key][$setting_key];
         
         $ct_gen->log(
         			'conf_error',
         			'Outdated app config, upgraded parameter ct_conf[' . $cat_key . '][' . $conf_key . '][' . $setting_key . '] imported (default value: ' . $default_ct_conf[$cat_key][$conf_key][$setting_key] . ')'
         			);
         
         $conf_upgraded = 1;
         
         }
            
      }
      
      // Check for depreciated variables, and remove them
      foreach ( $conf[$cat_key][$conf_key] as $setting_key => $setting_val ) {
      
         // Skip this array depth if it's yet another subarray, UNLESS this is the plugin configs
         if ( is_array($setting_val) && $cat_key != 'plug_conf' ) {
         $ct_gen->log('conf_error', 'ct_conf[' .$cat_key . ']['. $conf_key . '][' . $setting_key . '] config upgrade not needed (skipping)');
         }
         elseif ( !in_array($setting_key, $skip_upgrading) && !isset($default_ct_conf[$cat_key][$conf_key][$setting_key]) ) {
         	
         unset($conf[$cat_key][$conf_key][$setting_key]);
         
         $ct_gen->log(
         			'conf_error',
         			'Depreciated app config, parameter ct_conf[' . $cat_key . '][' . $conf_key . '][' . $setting_key . '] removed'
         			);
         
         $conf_upgraded = 1;
         
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
       
       	$htaccess_contents = $this->php_timeout_defaults($base_dir . '/templates/back-end/root-app-directory-htaccess.template') . 
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
  
  
  function api_throttling($tld_or_ip) {
  
  global $ct_conf, $ct_gen, $base_dir, $api_throttle_count, $api_throttle_flag;
  
  // We wait until we are in this function, to grab any cached data at the last minute,
  // to assure we get anything written recently by other runtimes
  $api_throttle_count_check = json_decode( trim( file_get_contents($base_dir . '/cache/events/throttling/' . $tld_or_ip . '.dat') ) , TRUE);
  
  
     // If we haven't initiated yet this runtime, AND there is ALREADY valid data cached, import it as the $api_throttle_count array
     if ( !isset($api_throttle_flag['init']) && $api_throttle_count_check != false && $api_throttle_count_check != null && $api_throttle_count_check != "null" ) {
     $api_throttle_count = $api_throttle_count_check;
     }
     
     
     $api_throttle_flag['init'] = true; // Flag as initiated this runtime (AFTER above logic)

     
     // Set OR reset MINUTE start time / counts, if needed
     if (
     !isset($api_throttle_count[$tld_or_ip]['minute_count']['start'])
     || isset($api_throttle_count[$tld_or_ip]['minute_count']['start']) && $api_throttle_count[$tld_or_ip]['minute_count']['start'] <= ( time() - 60 )
     ) {
     $api_throttle_count[$tld_or_ip]['minute_count']['start'] = time();
     $api_throttle_count[$tld_or_ip]['minute_count']['count'] = 0;
     }
     
     
     // Set OR reset HOUR start time / counts, if needed
     if (
     !isset($api_throttle_count[$tld_or_ip]['hour_count']['start'])
     || isset($api_throttle_count[$tld_or_ip]['hour_count']['start']) && $api_throttle_count[$tld_or_ip]['hour_count']['start'] <= ( time() - 3600 )
     ) {
     $api_throttle_count[$tld_or_ip]['hour_count']['start'] = time();
     $api_throttle_count[$tld_or_ip]['hour_count']['count'] = 0;
     }
     
     
     // Thresholds for API servers (we throttle-limit, to have reliable LIVE data EVERY HOUR OF THE DAY)
     if (
     $tld_or_ip == 'alphavantage.co' && $api_throttle_count[$tld_or_ip]['minute_count']['count'] >= $ct_conf['other_api']['alphavantage_per_minute_limit']
     || $tld_or_ip == 'alphavantage.co' && $api_throttle_count[$tld_or_ip]['hour_count']['count'] >= floor($ct_conf['other_api']['alphavantage_per_day_limit'] / 24)
     ) {
         
     $api_throttle_flag[$tld_or_ip] = true;
          
     $ct_gen->log(
          		  'notify_error',
          		  'throttling threshold met for API server "' . $tld_or_ip . '" (minute_requests='.$api_throttle_count[$tld_or_ip]['minute_count']['count'].',hour_requests='.$api_throttle_count[$tld_or_ip]['hour_count']['count'].')',
          		  false,
          		  md5($tld_or_ip) . '_throttle_flagged' // unique key with no symbols
          		  );
                  
     $store_api_throttle_count = json_encode($api_throttle_count, JSON_PRETTY_PRINT);
     $store_file_contents = $this->save_file($base_dir . '/cache/events/throttling/' . $tld_or_ip . '.dat', $store_api_throttle_count);
     
     return true;
     
     }
     else {
         
     unset($api_throttle_flag[$tld_or_ip]);
         
     $api_throttle_count[$tld_or_ip]['minute_count']['count'] = $api_throttle_count[$tld_or_ip]['minute_count']['count'] + 1;
     $api_throttle_count[$tld_or_ip]['hour_count']['count'] = $api_throttle_count[$tld_or_ip]['hour_count']['count'] + 1;
                  
     $store_api_throttle_count = json_encode($api_throttle_count, JSON_PRETTY_PRINT);
     $store_file_contents = $this->save_file($base_dir . '/cache/events/throttling/' . $tld_or_ip . '.dat', $store_api_throttle_count);
     
     return false;
     
     }
  
  
  }
  
  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function backup_archive($backup_prefix, $backup_target, $interval, $password='no') {
  
  global $ct_conf, $ct_gen, $ext_zip, $base_dir, $base_url;
  
  
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
          $backup_results = $ext_zip->zip_recursively($backup_target, $backup_dest, $password, ZipArchive::CREATE);
           
           
              if ( $backup_results == 'done' ) {
               
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
  
  global $base_dir, $ct_conf, $ct_gen, $valid_to_email, $telegram_activated, $notifyme_activated, $sms_service;
  
     
     // Abort queueing comms for sending out notifications, if allowing comms is disabled
     if ( $ct_conf['comms']['allow_comms'] != 'on' ) {
     return;
     }
  
  
   // Queue messages
   
   // RANDOM HASH SHOULD BE CALLED PER-STATEMENT, OTHERWISE FOR SOME REASON SEEMS TO REUSE SAME HASH FOR THE WHOLE RUNTIME INSTANCE (if set as a variable beforehand)

   
     // Notifyme
     if ( isset($send_params['notifyme']) && $send_params['notifyme'] != '' && $notifyme_activated ) {
   	 $this->save_file($base_dir . '/cache/secured/messages/notifyme-' . $ct_gen->rand_hash(8) . '.queue', $send_params['notifyme']);
     }

   
     // Telegram
     if ( isset($send_params['telegram']) && $send_params['telegram'] != '' && $telegram_activated ) {
     $this->save_file($base_dir . '/cache/secured/messages/telegram-' . $ct_gen->rand_hash(8) . '.queue', $send_params['telegram']);
     }
    
    
     // SMS service
     if ( isset($send_params['text']['message']) && $send_params['text']['message'] != '' && $sms_service == 'twilio' ) { 
     $this->save_file($base_dir . '/cache/secured/messages/twilio-' . $ct_gen->rand_hash(8) . '.queue', $send_params['text']['message']);
     }
     elseif ( isset($send_params['text']['message']) && $send_params['text']['message'] != '' && $sms_service == 'textbelt' ) { 
     $this->save_file($base_dir . '/cache/secured/messages/textbelt-' . $ct_gen->rand_hash(8) . '.queue', $send_params['text']['message']);
     }
     elseif ( isset($send_params['text']['message']) && $send_params['text']['message'] != '' && $sms_service == 'textlocal' ) { 
     $this->save_file($base_dir . '/cache/secured/messages/textlocal-' . $ct_gen->rand_hash(8) . '.queue', $send_params['text']['message']);
     }
     elseif ( isset($send_params['text']['message']) && $send_params['text']['message'] != '' && $sms_service == 'email_gateway' ) { 
     
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
     if ( isset($send_params['email']['message']) && $send_params['email']['message'] != '' && $valid_to_email ) {
     
     $email_array = array('subject' => $send_params['email']['subject'], 'message' => $send_params['email']['message'], 'content_type' => ( $send_params['email']['content_type'] ? $send_params['email']['content_type'] : 'text/plain' ), 'charset' => ( $send_params['email']['charset'] ? $send_params['email']['charset'] : $ct_conf['power']['charset_default'] ) );
     
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
   
   
   // Check to see if we need to upgrade the app config (add new primary vars / remove depreciated primary vars)
   function upgrade_cached_ct_conf($conf) {
   
   global $check_default_ct_conf, $default_ct_conf, $ct_gen;
   
   
   // WE LEAVE THE SUB-ARRAYS FOR PROXIES / CHARTS / TEXT GATEWAYS / PORTFOLIO ASSETS / ETC / ETC ALONE
   // (ANY SUB-ARRAY WHERE A USER ADDS / DELETES VARIABLES THEY WANTED DIFFERENT FROM DEFAULT VARS)
   $skip_upgrading = array(
                           'proxy',
                           'tracked_mrkts',
                           'crypto_pair',
                           'crypto_pair_pref_mrkts',
                           'btc_currency_mrkts',
                           'btc_pref_currency_mrkts',
                           'eth_erc20_icos',
                           'mob_net_txt_gateways',
                           'assets',
                           'news_feed',
                           );
   
   
      // If no cached app config or it's corrupt, just use full default app config
      if ( $conf != true ) {
      return $default_ct_conf;
      }
      // If the default app config has changed since last check (from upgrades / end user editing)
      elseif ( $check_default_ct_conf != md5(serialize($default_ct_conf)) ) {
         
         
         // Check for new variables, and add them
         foreach ( $default_ct_conf as $cat_key => $cat_val ) {
            
            foreach ( $cat_val as $conf_key => $conf_val ) {
         
               if ( !in_array($cat_key, $skip_upgrading) && !in_array($conf_key, $skip_upgrading) ) {
                  
                  if ( is_array($conf_val) ) {
                  $this->subarray_cached_ct_conf_upgrade($conf, $cat_key, $conf_key, $skip_upgrading);
                  }
                  elseif ( !isset($conf[$cat_key][$conf_key]) ) {
                  	
                  $conf[$cat_key][$conf_key] = $default_ct_conf[$cat_key][$conf_key];
                  
                  $ct_gen->log(
                  			'conf_error',
                  			'Outdated app config, upgraded parameter ct_conf[' . $cat_key . '][' . $conf_key . '] imported (default value: ' . $default_ct_conf[$cat_key][$conf_key] . ')'
                  			);
                  						
                  $conf_upgraded = 1;
                  
                  }
            
               }
               else {
               $ct_gen->log('conf_error', 'ct_conf[' .$cat_key . ']['. $conf_key . '] config upgrade not needed (skipping)');
               }
            
            }
         
         }
         
         
         // Check for depreciated variables, and remove them
         foreach ( $conf as $cached_cat_key => $cached_cat_val ) {
            
            foreach ( $cached_cat_val as $cached_conf_key => $cached_conf_val ) {
         
               if ( !in_array($cached_cat_key, $skip_upgrading) && !in_array($cached_conf_key, $skip_upgrading) ) {
               
                  if ( is_array($cached_conf_val) ) {
                  $this->subarray_cached_ct_conf_upgrade($conf, $cached_cat_key, $cached_conf_key, $skip_upgrading);
                  }
                  elseif ( !isset($default_ct_conf[$cached_cat_key][$cached_conf_key]) ) {
                  	
                  unset($conf[$cached_cat_key][$cached_conf_key]);
                  
                  $ct_gen->log(
                  			'conf_error',
                  			'Depreciated app config parameter ct_conf[' . $cached_cat_key . '][' . $cached_conf_key . '] removed'
                  			);
                  
                  $conf_upgraded = 1;
                  
                  }
                  
               }
               else {
               $ct_gen->log('conf_error', 'ct_conf[' .$cat_key . ']['. $conf_key . '] config upgrade not needed (skipping)');
               }
               
            }
            
         }
         
      
      return $conf;
      
      }
   
   
   }


   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function load_cached_config() {
   
   global $ct_conf, $ct_gen, $ct_cache, $base_dir, $restore_conf_path, $telegram_user_data;
   
   // Secured cache files
   $files = $ct_gen->sort_files($base_dir . '/cache/secured', 'dat', 'desc');
        
        
        foreach( $files as $secured_file ) {
        
        
        	// Restore config
        	if ( preg_match("/restore_conf_/i", $secured_file) ) {
		
		
        		// If we already loaded the newest modified file, delete any stale ones
        		if ( $newest_cached_restore_conf == 1 ) {
        		unlink($base_dir . '/cache/secured/' . $secured_file);
        		$ct_gen->log('conf_error', 'OLD CACHED restore_conf found, deleting');
        		}
        		else {
        		$newest_cached_restore_conf = 1;
	            $restore_conf_path = $base_dir . '/cache/secured/' . $secured_file;
        		}
		
	
        	}
        	// Telegram user data
        	elseif ( preg_match("/telegram_user_data_/i", $secured_file) ) {
        		
        		// If we already loaded the newest modified telegram SECURED CACHE config file
        		// DON'T WORRY ABOUT REFRESHING TELEGRAM DATA WHEN APP CONFIG IS REFRESHING, AS WE CAN'T DO THAT RELIABLY IN THIS LOOP
        		// AND IT'S DONE AFTER THE LOOP ANYWAY (WE JUST CLEANUP ANY STALE TELEGRAM CONFIGS IN THIS LOOP)
        		if ( $newest_cached_telegram_user_data == 1 ) {
        		unlink($base_dir . '/cache/secured/' . $secured_file);
        		}
        		else {
        		
        		$newest_cached_telegram_user_data = 1;
        		
        		$cached_telegram_user_data = json_decode( trim( file_get_contents($base_dir . '/cache/secured/' . $secured_file) ) , TRUE);
        			
        			
        			// "null" in quotes as the actual value is returned sometimes
        			if ( $cached_telegram_user_data != false && $cached_telegram_user_data != null && $cached_telegram_user_data != "null" ) {
        			$telegram_user_data = $cached_telegram_user_data;
        			}
        			else {
        			$ct_gen->log('conf_error', 'Cached telegram_user_data non-existant or corrupted (refresh will happen automatically)');
        			unlink($base_dir . '/cache/secured/' . $secured_file);
        			}
        		
        		
        		}
        	
        	
        	}
        	// App config
        	elseif ( preg_match("/ct_conf_/i", $secured_file) ) {
		
		
        		// If we already loaded the newest modified file, delete any stale ones
        		if ( $newest_cached_ct_conf == 1 ) {
        		unlink($base_dir . '/cache/secured/' . $secured_file);
        		$ct_gen->log('conf_error', 'OLD CACHED ct_conf found, deleting');
        		}
        		else {
        		
        		$newest_cached_ct_conf = 1;
        			
        		$cached_ct_conf = json_decode( trim( file_get_contents($base_dir . '/cache/secured/' . $secured_file) ) , TRUE);
        			
        		    // "null" in quotes as the actual value is returned sometimes
        			if ( $ct_gen->admin_security_level_check() == true && $cached_ct_conf != false && $cached_ct_conf != null && $cached_ct_conf != "null" ) {
        			$ct_conf = $cached_ct_conf; // Use cached ct_conf if it exists, seems intact, and DEFAULT Admin Config (in config.php) hasn't been revised since last check
        			// $ct_gen->log('conf_error', 'CACHED ct_conf OK'); // DEBUGGING ONLY
        			$config_ok = true;
        			}
        			elseif ( $cached_ct_conf != true ) {
        			unlink($base_dir . '/cache/secured/' . $secured_file);
        			$ct_gen->log('conf_error', 'CACHED ct_conf appears corrupt, refreshing from DEFAULT or RESTORE ct_conf');
        			$refresh_config = true;
        			}
        			elseif ( $ct_gen->admin_security_level_check() == false ) {
        			unlink($base_dir . '/cache/secured/' . $secured_file);
        			$ct_gen->log('conf_error', 'CACHED ct_conf outdated (DEFAULT ct_conf updated), refreshing from DEFAULT ct_conf');
        			$refresh_config = true;
        			}
        			
        			
        		}
		
	
        	}
        	
        	
        }
        	
        	
        if ( !isset($newest_cached_ct_conf) ) {
        $ct_gen->log('conf_error', 'CACHED ct_conf not found, refreshing from DEFAULT or RESTORE ct_conf');
        $refresh_config = true;
        }			 


        // We use the $refresh_config flag, so we can wait for the GLOBAL $restore_conf_path which may be set above (if a restore file exists)
        // (allowing config restoration from last known working config)
        if ( $refresh_config == true ) {
        $ct_conf = $this->refresh_cached_ct_conf(false);
        }
        
        
   gc_collect_cycles(); // Clean memory cache

   }


   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function refresh_cached_ct_conf($passed_config, $upgrade_mode=false, $user_reset=false) {
   
   global $ct_conf, $ct_gen, $ct_cache, $base_dir, $default_ct_conf, $restore_conf_path, $admin_area_sec_level, $telegram_activated, $telegram_user_data, $htaccess_username, $htaccess_password;


   // If no valid cached_ct_conf, or if DEFAULT Admin Config (in config.php) variables have been changed...
   
   
        // If no ct_conf (IN PHP ARRAY FORM) was passed into this function, to use for this refresh
        // (VALUE false WAS EXPLICITLY PASSED TO TRIGGER A RESET)
        if ( !$passed_config ) {
        
        
	        // If no reset ct_conf flag, try loading last working config (if it exists, before falling back on default ct_conf)
	        if ( !$user_reset && file_exists($restore_conf_path) ) {
             $passed_config = json_decode( trim( file_get_contents($restore_conf_path) ) , TRUE);
	        }
				
             
             // If NO valid last working config, IS high security mode, IS a user-initiated reset to ct_conf defaults,
             // WE USE THE DEFAULT CT_CONF (FROM THE PHP CONFIGURATION FILES)
             if ( !$passed_config || $admin_area_sec_level == 'high' || $user_reset ) {
             $passed_config = $default_ct_conf;
    		   $ct_gen->log('conf_error', 'CACHED ct_conf RESET, it will be refreshed using the DEFAULT ct_conf');
    		   }
             // All other conditions
             else {
    		   $ct_gen->log('conf_error', 'CACHED ct_conf RESET, it will be restored using the LAST-KNOWN WORKING ct_conf');
             }
             
        
        }
   
    	
   $secure_128bit_hash = $ct_gen->rand_hash(16); // 128-bit (16-byte) hash converted to hexadecimal, used for suffix
    	
    	
    	// Halt the process if an issue is detected safely creating a random hash
    	if ( $secure_128bit_hash == false ) {
    		
    	$ct_gen->log(
    				'security_error', 
    				'Cryptographically secure pseudo-random bytes could not be generated for cached ct_conf array (secured cache storage) suffix, cached ct_conf array creation aborted to preserve security'
    				);
    	
    	}
    	else {
    	
    	
        	// Check to see if we need to upgrade the CACHED app config (NEW / DEPRECIATED CORE VARIABLES ONLY, NOT OVERWRITING EXISTING CORE VARIABLES)
    	    if ( $admin_area_sec_level != 'high' && $upgrade_mode == true ) {
    	    $upgrade_cache_ct_conf = $this->upgrade_cached_ct_conf($passed_config);
    	    }
            // CACHED WITH NO UPGRADE FLAG
    	    elseif ( $admin_area_sec_level != 'high' ) {
    	    $upgrade_cache_ct_conf = $passed_config;
    	    }
        	// (REFRESHES CACHED APP CONFIG TO EXACTLY MIRROR THE HARD-CODED VARIABLES IN CONFIG.PHP, IF CONFIG.PHP IS CHANGED IN EVEN THE SLIGHTEST WAY)
    	    else {
    	    $upgrade_cache_ct_conf = $ct_conf;
    	    }
    	
    	
    	// Check that the app config is valid / not corrupt
    	$store_cached_ct_conf = json_encode($upgrade_cache_ct_conf, JSON_PRETTY_PRINT);
    	
    	
    		// If there was an issue updating the cached app config
    		// Need to check a few different possible results for no data found ("null" in quotes as the actual value is returned sometimes)
    		if ( $store_cached_ct_conf == false || $store_cached_ct_conf == null || $store_cached_ct_conf == "null" ) {
    		    
    		$ct_gen->log('conf_error', 'updated ct_conf data could not be saved (to secured cache storage) in json format');
    	
              // Attempt to restore last-known good config (if it exists)	
              if ( file_exists($restore_conf_path) ) {
    		    $cached_restore_conf = json_decode( trim( file_get_contents($restore_conf_path) ) , TRUE);
    		    }
    		
    		
    		    if ( $cached_restore_conf != false && $cached_restore_conf != null && $cached_restore_conf != "null" ) {
    	
    	
                	// Check to see if we need to upgrade the CACHED app config (NEW / DEPRECIATED CORE VARIABLES ONLY, NOT OVERWRITING EXISTING CORE VARIABLES)
            	    if ( $admin_area_sec_level != 'high' && $upgrade_mode == true ) {
            	    $upgrade_cache_ct_conf = $this->upgrade_cached_ct_conf($cached_restore_conf);
            	    }
            	    // CACHED WITH NO UPGRADE FLAG
            	    elseif ( $admin_area_sec_level != 'high' ) {
            	    $upgrade_cache_ct_conf = $cached_restore_conf;
            	    }
                	// (REFRESHES CACHED APP CONFIG TO EXACTLY MIRROR THE HARD-CODED VARIABLES IN CONFIG.PHP, IF CONFIG.PHP IS CHANGED IN EVEN THE SLIGHTEST WAY)
            	    else {
            	    $upgrade_cache_ct_conf = $cached_restore_conf;
            	    }
            	     
            	
            	// Check that the app config is valid / not corrupt
            	$store_cached_ct_conf = json_encode($upgrade_cache_ct_conf, JSON_PRETTY_PRINT);
            	
            	
            		// If there was an issue updating the cached app config
            		// Need to check a few different possible results for no data found ("null" in quotes as the actual value is returned sometimes)
            		if ( $store_cached_ct_conf == false || $store_cached_ct_conf == null || $store_cached_ct_conf == "null" ) {
            		$ct_gen->log('conf_error', 'ct_conf data could not be restored from last-known working config');
            		}
            		// If restoring last-known working config was successfull
            		else {
            		    
            		$ct_gen->log('conf_error', 'ct_conf CACHE restore from last-known working config triggered, refreshed successfully'); 
            		$ct_conf = $upgrade_cache_ct_conf;
            		$ct_cache->save_file($base_dir . '/cache/secured/ct_conf_'.$secure_128bit_hash.'.dat', $store_cached_ct_conf);
            		
            		
                		// For checking later, if DEFAULT Admin Config (in config.php) values are updated we save to json again
            		    if ( $admin_area_sec_level == 'high' ) {
                		$ct_cache->save_file($base_dir . '/cache/vars/default_ct_conf_md5.dat', md5(serialize($default_ct_conf))); 
            		    }
            		
            		
            		// Refresh any custom .htaccess / php.ini settings (deleting will trigger a restore)
            		unlink($base_dir . '/.htaccess');
            		unlink($base_dir . '/.user.ini');
            		unlink($base_dir . '/cache/secured/.app_htpasswd');
            		
            		}
            		
    		   
    		    }
    		    
    		
    		}
    		// If cached app config updated successfully
    		else {
    		    
    		$ct_gen->log('conf_error', 'ct_conf CACHE update triggered, refreshed successfully');
    		$ct_conf = $upgrade_cache_ct_conf;
    		$ct_cache->save_file($base_dir . '/cache/secured/ct_conf_'.$secure_128bit_hash.'.dat', $store_cached_ct_conf);
    		$ct_cache->save_file($base_dir . '/cache/secured/restore_conf_'.$secure_128bit_hash.'.dat', $store_cached_ct_conf);
    		
    		
                 // For checking later, if DEFAULT Admin Config (in config.php) values are updated we save to json again
            	 if ( $admin_area_sec_level == 'high' ) {
                 $ct_cache->save_file($base_dir . '/cache/vars/default_ct_conf_md5.dat', md5(serialize($default_ct_conf))); 
    		     }
    		    
    		
            // Refresh any custom .htaccess / php.ini settings (deleting will trigger a restore)
    		unlink($base_dir . '/.htaccess');
    		unlink($base_dir . '/.user.ini');
    		unlink($base_dir . '/cache/secured/.app_htpasswd');
    		
    		}
    		
    	
    	}
    	
   
   // Return $ct_conf, EVEN THOUGH IT'S A GLOBAL, AS WE ARE SOMETIMES UPDATING IT MORE THAN ONCE IN load_cached_config()
   return $ct_conf;
   
   }
  
  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function debug_log() {
  
  global $runtime_mode, $base_dir, $ct_conf, $ct_gen, $log_debugging, $alerts_gui_debugging;
  
  
      if ( $ct_conf['power']['debug_mode'] == 'off' ) {
      return false;
      }
    
    
      foreach ( $log_debugging['notify_debug'] as $debugging ) {
      $debug_log .= strip_tags($debugging); // Remove any HTML formatting used in UI alerts
      }
  
  
  // Combine all debugging logged
  $debug_log .= strip_tags($log_debugging['security_debug']); // Remove any HTML formatting used in UI alerts
  
  $debug_log .= strip_tags($log_debugging['system_debug']); // Remove any HTML formatting used in UI alerts
  
  $debug_log .= strip_tags($log_debugging['conf_debug']); // Remove any HTML formatting used in UI alerts
  
  $debug_log .= strip_tags($log_debugging['ext_data_debug']); // Remove any HTML formatting used in UI alerts
  
  $debug_log .= strip_tags($log_debugging['int_api_debug']); // Remove any HTML formatting used in UI alerts
  
  $debug_log .= strip_tags($log_debugging['market_debug']); // Remove any HTML formatting used in UI alerts
  
  $debug_log .= strip_tags($log_debugging['other_debug']); // Remove any HTML formatting used in UI alerts
  
  
      foreach ( $log_debugging['cache_debug'] as $debugging ) {
      $debug_log .= strip_tags($debugging); // Remove any HTML formatting used in UI alerts
      }
      
  
  // Sort logs by timestamp
  $debug_log = $ct_gen->sort_log($debug_log);
      
      
      // Save a copy for interface alerts
      if ( $runtime_mode == 'ui') {
      $alerts_gui_debugging = nl2br($debug_log);
      }
  
  
      // If it's time to email debugging logs...
	  // 1439 minutes instead (minus 1 minute), to try keeping daily recurrences at same exact runtime (instead of moving up the runtime daily)
      if ( $ct_conf['comms']['logs_email'] > 0 && $this->update_cache('cache/events/email-debugging-logs.dat', ( $ct_conf['comms']['logs_email'] * 1439 ) ) == true ) {
       
      $emailed_logs = "\n\n ------------------debug.log------------------ \n\n" . file_get_contents('cache/logs/debug.log') . "\n\n ------------------smtp_debug.log------------------ \n\n" . file_get_contents('cache/logs/smtp_debug.log');
       
      $msg = " Here are the current debugging logs from the " . $base_dir . "/cache/logs/ directory. \n\n You can disable / change receiving log emails (every " . $ct_conf['comms']['logs_email'] . " days) in the Admin Config \"Communications\" section. \n =========================================================================== \n \n"  . ( isset($emailed_logs) && $emailed_logs != '' ? $emailed_logs : 'No debugging logs currently.' );
      
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
      $log_debugging = array(); // RESET DEBUG LOGS ARRAY (clears logs from memory, that we just wrote to disk)
        
          if ( $store_file_contents != true ) {
          return 'Debugging logs write error for "' . $base_dir . '/cache/logs/debug.log" (MAKE SURE YOUR DISK ISN\'T FULL), data_size_bytes: ' . strlen($debug_log) . ' bytes';
          }
          // DEBUGGING ONLY (rules out issues other than full disk)
          elseif ( $ct_conf['power']['debug_mode'] == 'all' || $ct_conf['power']['debug_mode'] == 'all_telemetry' ) {
          return 'Debugging logs write success for "' . $base_dir . '/cache/logs/debug.log", data_size_bytes: ' . strlen($debug_log) . ' bytes';
          }
        
      }
   
   
  return true;
  
  }
  
  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function error_log() {
  
  global $runtime_mode, $base_dir, $ct_conf, $ct_gen, $log_errors, $alerts_gui_errors;

  
      foreach ( $log_errors['notify_error'] as $error ) {
      $error_log .= strip_tags($error); // Remove any HTML formatting used in UI alerts
      }
      
  
  // Combine all errors logged
  $error_log .= strip_tags($log_errors['security_error']); // Remove any HTML formatting used in UI alerts
  
  $error_log .= strip_tags($log_errors['system_warning']); // Remove any HTML formatting used in UI alerts
  
  $error_log .= strip_tags($log_errors['system_error']); // Remove any HTML formatting used in UI alerts
  
  $error_log .= strip_tags($log_errors['conf_error']); // Remove any HTML formatting used in UI alerts
  
  $error_log .= strip_tags($log_errors['ext_data_error']); // Remove any HTML formatting used in UI alerts
  
  $error_log .= strip_tags($log_errors['int_api_error']); // Remove any HTML formatting used in UI alerts
  
  $error_log .= strip_tags($log_errors['market_error']); // Remove any HTML formatting used in UI alerts
  
  $error_log .= strip_tags($log_errors['other_error']); // Remove any HTML formatting used in UI alerts
  
     
      foreach ( $log_errors['cache_error'] as $error ) {
      $error_log .= strip_tags($error); // Remove any HTML formatting used in UI alerts
      }
      
  
  // Sort logs by timestamp
  $error_log = $ct_gen->sort_log($error_log);
      
      
      // Save a copy for interface alerts
      if ( $runtime_mode == 'ui') {
      $alerts_gui_errors = nl2br($error_log);
      }
    
    
      // If it's time to email error logs...
	  // 1439 minutes instead (minus 1 minute), to try keeping daily recurrences at same exact runtime (instead of moving up the runtime daily)
      if ( $ct_conf['comms']['logs_email'] > 0 && $this->update_cache('cache/events/email-error-logs.dat', ( $ct_conf['comms']['logs_email'] * 1439 ) ) == true ) {
       
      $emailed_logs = "\n\n ------------------error.log------------------ \n\n" . file_get_contents('cache/logs/error.log') . "\n\n ------------------smtp_error.log------------------ \n\n" . file_get_contents('cache/logs/smtp_error.log');
       
      $msg = " Here are the current error logs from the ".$base_dir."/cache/logs/ directory. \n\n You can disable / change receiving log emails (every " . $ct_conf['comms']['logs_email'] . " days) in the Admin Config \"Communications\" section. \n \n =========================================================================== \n \n"  . ( isset($emailed_logs) && $emailed_logs != '' ? $emailed_logs : 'No error logs currently.' );
      
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
      $log_errors = array(); // RESET ERROR LOGS ARRAY (clears logs from memory, that we just wrote to disk)
        
          if ( $store_file_contents != true ) {
          return 'Error logs write error for "' . $base_dir . '/cache/logs/error.log" (MAKE SURE YOUR DISK ISN\'T FULL), data_size_bytes: ' . strlen($error_log) . ' bytes';
          }
          // DEBUGGING ONLY (rules out issues other than full disk)
          elseif ( $ct_conf['power']['debug_mode'] == 'all' || $ct_conf['power']['debug_mode'] == 'all_telemetry' ) {
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
     								
     			'POSSIBLE api timeout' . ( $ct_conf['sec']['remote_api_strict_ssl'] == 'on' ? ' or strict_ssl' : '' ) . ' issue for cache file "' . $ct_gen->obfusc_path_data($file) . '" (IF ISSUE PERSISTS, TRY INCREASING "remote_api_timeout" IN Admin Config POWER USER SECTION' . ( $ct_conf['sec']['remote_api_strict_ssl'] == 'on' ? ', OR SETTING "remote_api_strict_ssl" to "off" IN Admin Config POWER USER SECTION' : '' ) . ')',
     								
     			'remote_api_timeout: '.$ct_conf['power']['remote_api_timeout'].' seconds; remote_api_strict_ssl: ' . $ct_conf['sec']['remote_api_strict_ssl'] . ';'
     			);
     
     }
    
    return false;
    
    }
   
   
    // We ALWAYS set .htaccess files to a more secure $ct_conf['sec']['chmod_index_sec'] permission AFTER EDITING, 
    // so we TEMPORARILY set .htaccess to $ct_conf['sec']['chmod_cache_file'] for NEW EDITING...
    // (anything else stays weaker write security permissions, for UX)
    if ( strstr($file, '.dat') != false || strstr($file, '.htaccess') != false || strstr($file, '.user.ini') != false || strstr($file, 'index.php') != false ) {
     
    $chmod_setting = octdec($ct_conf['sec']['chmod_cache_file']);
    
         // Run chmod compatibility on certain PHP setups (if we can because we are running as the file owner)
         // In this case only if the file exists, as we are chmod BEFORE editing it (.htaccess files)
         if ( file_exists($file) == true ) {
         $ct_gen->ct_chmod($file, $chmod_setting);
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
    $chmod_setting = octdec($ct_conf['sec']['chmod_index_sec']);
    }
    // All other files
    else {
    $chmod_setting = octdec($ct_conf['sec']['chmod_cache_file']);
    }
   
    $ct_gen->ct_chmod($file, $chmod_setting);
   
  return $result;
  
  }
  
  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function update_light_chart($archive_path, $newest_arch_data=false, $days_span=1) {
  
  global $ct_conf, $ct_var, $ct_gen, $base_dir, $system_info, $light_chart_first_build_count;
  
  $arch_data = array();
  $queued_arch_lines = array();
  $new_light_data = null;
  
  // Light chart file path
  $light_path = preg_replace("/archival/i", 'light/' . $days_span . '_days', $archive_path);
  
  
    // Hash of light path, AND random X hours update threshold, to spread out and event-track 'all' chart rebuilding
    if ( $days_span == 'all' ) {
    $light_path_hash = md5($light_path);
    $thres_range = explode(',', $ct_conf['power']['all_chart_rebuild_min_max']);
    $all_chart_rebuild_thres = rand($thres_range[0], $thres_range[1]); // Randomly within the min/max range, to spead the load across multiple runtimes
    }
   
   
    // Get FIRST AND LAST line of light chart data (determines oldest / newest light timestamp)
    if ( file_exists($light_path) ) {
    
    $oldest_light_array = explode("||", file($light_path)[0]);
    $oldest_light_timestamp = $ct_var->num_to_str( $oldest_light_array[0] );
        
    $last_light_line = $this->tail_custom($light_path);
    $last_light_array = explode("||", $last_light_line);
    $newest_light_timestamp = ( isset($last_light_array[0]) ? $ct_var->num_to_str($last_light_array[0]) : false );
    
    gc_collect_cycles(); // Clean memory cache
    
    }
    else {
    
        if ( $ct_gen->dir_struct( dirname($light_path) ) != true ) {
        $ct_gen->log('system_error', 'Unable to create light chart directory structure ('.dirname($light_path).')');
        return false;
        }
        
    $newest_light_timestamp = false;
    
    usleep(150000); // Wait 0.15 seconds, since we just re-created the light chart path (likely after a mid-flight reset)
    
    }
  
  
  // WE PRESUME ARCHIVAL CHART FILES EXIST, BECAUSE IT IS WRITTEN TO #RIGHT BEFORE# THIS LIGHT CHARTS FUNCTION IS CALLED
  // Get LAST line of archival chart data (we save SIGNIFICANTLY on runtime / resource usage, if this var is passed into this function already)
  // (determines newest archival timestamp)
  $last_arch_line = ( $newest_arch_data != false ? $newest_arch_data : $this->tail_custom($archive_path) );
  $last_arch_array = explode("||", $last_arch_line);
  $newest_arch_timestamp = $ct_var->num_to_str($last_arch_array[0]);
  
  
    // Get FIRST line of archival chart data (determines oldest archival timestamp)
    if ( file_exists($archive_path) ) {
    $oldest_arch_array = explode("||", file($archive_path)[0]);
    $oldest_arch_timestamp = $ct_var->num_to_str( $oldest_arch_array[0] );
    gc_collect_cycles(); // Clean memory cache
    }
    
    
    // If we don't have any valid archival data, return false
    if ( !$oldest_arch_timestamp ) {
    $ct_gen->log('cache_error', 'Archival chart data not found ('.$archive_path.')');
    return false;
    }
    // If we recently restored to OLDER / LARGER archival data sets, RESET ALL LIGHT CHARTS
    // (EVERY LIGHT CHART, JUST TO BE SAFE)
    elseif (
    $days_span == 'all'
    && isset($oldest_arch_timestamp)
    && isset($oldest_light_timestamp)
    && trim($oldest_arch_timestamp) != ''
    && $oldest_arch_timestamp != $oldest_light_timestamp
    ) {
        
    $ct_gen->log('cache_error', 'Archival chart data appears recently restored, resetting ALL light charts');
    
    // Delete ALL light charts (this will automatically trigger a re-build)
    $this->remove_dir($base_dir . '/cache/charts/spot_price_24hr_volume/light');
    $this->remove_dir($base_dir . '/cache/charts/system/light');
    
    return 'reset';
    
    }
   
     
    // Oldest base timestamp we can use (only applies for x days charts, not 'all')
    if ( $days_span != 'all' ) {
    $base_min_timestamp = $ct_var->num_to_str( strtotime('-'.$days_span.' day', $newest_arch_timestamp) );
    }
    
    // If it's the 'all' light chart, OR the oldest archival timestamp is newer than oldest base timestamp we can use
    if ( $days_span == 'all' || $days_span != 'all' && $oldest_arch_timestamp > $base_min_timestamp ) {
    $oldest_allowed_timestamp = $oldest_arch_timestamp;
    }
    // If it's an X days light chart (not 'all'), and we have archival timestamps that are older than oldest base timestamp we can use
    elseif ( $days_span != 'all' ) {
    $oldest_allowed_timestamp = $base_min_timestamp;  
    }
   
   
    // Minimum time interval between data points in light chart
    if ( $days_span == 'all' ) {
    $min_data_interval = round( ($newest_arch_timestamp - $oldest_arch_timestamp) / $ct_conf['power']['light_chart_data_points_max'] ); // Dynamic
    }
    else {
    $min_data_interval = round( ($days_span * 86400) / $ct_conf['power']['light_chart_data_points_max'] ); // Fixed X days (86400 seconds per day)
    }
  
  
    // #INITIALLY# (if no light data exists yet) we randomly spread the load across multiple cron jobs
    // THEN IT #REMAINS RANDOMLY SPREAD# ACROSS CRON JOBS #WITHOUT DOING ANYTHING AFTER# THE INITIAL RANDOMNESS
    if ( $newest_light_timestamp == false ) {
    $light_data_update_thres = rand( (time() - 3333) , (time() + 6666) ); // 1/3 of all light charts REBUILDS update on average, per runtime
    }
    // Update threshold calculated from pre-existing light data
    else {
    $light_data_update_thres = $newest_light_timestamp + $min_data_interval;
    }
  
  
  // Large number support (NOT scientific format), since we manipulated these
  $min_data_interval = $ct_var->num_to_str($min_data_interval); 
  $light_data_update_thres = $ct_var->num_to_str($light_data_update_thres); 
  
  
     // If we are queued to update an existing light chart, get the data points we want to add 
     // (may be multiple data points, if the last update had network errors / system reboot / etc)
     if ( isset($newest_light_timestamp) && $light_data_update_thres <= $newest_arch_timestamp ) {
     
        // If we are only adding the newest archival data point (passed into this function), 
        // #we save BIGTIME on resource usage# (used EVERYTIME, other than very rare FALLBACKS)
        // CHECKS IF UPDATE THRESHOLD IS GREATER THAN NEWEST ARCHIVAL DATA POINT TIMESTAMP, 
        // #WHEN ADDING AN EXTRA# $min_data_interval (so we know to only add one data point)
        if ( $ct_var->num_to_str($light_data_update_thres + $min_data_interval) > $newest_arch_timestamp ) {
        $queued_arch_lines[] = $last_arch_line;
        }
       // If multiple light chart data points missing (from any very rare FALLBACK instances, like network / load / disk / runtime issues, etc)
        else {
        
       $tail_arch_lines = $this->tail_custom($archive_path, 20); // Grab last 20 lines, to be safe
       $tail_arch_lines_array = explode("\n", $tail_arch_lines);
       // Remove all null / false / empty strings, and reindex
       $tail_arch_lines_array = array_values( array_filter( $tail_arch_lines_array, 'strlen' ) ); 
         
          foreach( $tail_arch_lines_array as $arch_line ) {
          $arch_line_array = explode('||', $arch_line);
          $arch_line_array[0] = $ct_var->num_to_str($arch_line_array[0]);
           
             if ( !$added_arch_timestamp && $light_data_update_thres <= $arch_line_array[0]
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
    // Not time to update / rebuild this light chart yet
    ////////////////////////////////////////////////////////////////////////////////////////////////
    if ( $light_data_update_thres > $newest_arch_timestamp ) {
    gc_collect_cycles(); // Clean memory cache
    return false;
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////
    // If no light chart exists yet, OR it's time to rebuild the 'all' chart from scratch
    // (we STILL check $queued_arch_lines for new data, to see if we should SKIP an 'all' charts full rebuild now)
    ////////////////////////////////////////////////////////////////////////////////////////////////
    elseif (
    !$newest_light_timestamp
    || $days_span == 'all' && is_array($queued_arch_lines) && sizeof($queued_arch_lines) > 0 && $this->update_cache($base_dir . '/cache/events/light_chart_rebuilds/all_days_chart_'.$light_path_hash.'.dat', (60 * $all_chart_rebuild_thres) ) == true
    ) {
    
      
      // Avoid overloading low power devices with the SCALED first build hard limit
      // (multiplies the first build limit by the number of available CPU threads)
      // [less cores == lower hard limit == NOT OVERLOADING SLOW DEVICES]
      // [more cores == higher hard limit == FASTER ON FAST DEVICES]
      if ( isset($system_info['cpu_threads']) && $system_info['cpu_threads'] > 1 ) {
      $scaled_first_build_hard_limit = ($ct_conf['power']['light_chart_first_build_hard_limit'] * $system_info['cpu_threads']);
      }
      // Doubles as failsafe (if number of threads not detected on this system, eg: windows devices)
      else {
      $scaled_first_build_hard_limit = $ct_conf['power']['light_chart_first_build_hard_limit'];
      }
      
      
      if ( !$newest_light_timestamp && $light_chart_first_build_count >= $scaled_first_build_hard_limit ) {
      return false;
      }
      // Count first builds, to enforce first build hard limit
      elseif ( !$newest_light_timestamp ) {
      $light_chart_first_build_count = $light_chart_first_build_count + 1;
      }
      
   
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
      while ( isset($arch_data[$loop]) && $data_points <= $ct_conf['power']['light_chart_data_points_max'] ) {
       
      $data_point_array = explode("||", $arch_data[$loop]);
      $data_point_array[0] = $ct_var->num_to_str($data_point_array[0]);
        
         if ( !$next_timestamp || isset($next_timestamp) && $data_point_array[0] <= $next_timestamp ) {
            
         $new_light_data = $arch_data[$loop] . $new_light_data; // WITHOUT newline, since file() maintains those by default
         $next_timestamp = $data_point_array[0] - $min_data_interval;
         $data_points = $data_points + 1;
        
            if ( $loop == ( sizeof($arch_data) - 1 ) ) {
            $lastline_added = true;
            }
            else {
            $lastline_added = false;
            }
        
         }
     
      $loop = $loop + 1;
      }
        
        
        
      // If last array value hasn't been added yet, AND it's the 'all' light chart,
      // we ALWAYS want to ALSO include THIS VERY FIRST ARCHIVAL data point TOO (which is the last value in this reversed array),
      // so we can detect if a user ever restores archival charts WITH OLDER / LARGER data sets
      // (so we know if we need to reset light charts, by comparing the first data point on the 'all' light charts to archival charts)
      if ( $days_span == 'all' && !$lastline_added ) {
      $new_light_data = $arch_data[ ( sizeof($arch_data) - 1 ) ] . $new_light_data; // WITHOUT newline, since file() maintains those by default
      }
     
    
    // Store the light chart data (rebuild)
    $result = $this->save_file($light_path, $new_light_data);  // WITHOUT newline, since file() maintains those by default (file write)
    $light_mode_logging = 'REBUILD';
    
    
      // Update the 'all' light chart rebuild event tracking, IF THE LIGHT CHART UPDATED SUCESSFULLY
      if ( $days_span == 'all' && $result == true ) {
      $this->save_file($base_dir . '/cache/events/light_chart_rebuilds/all_days_chart_'.$light_path_hash.'.dat', $ct_gen->time_date_format(false, 'pretty_date_time') );
      }
    
   
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////
    // If the light chart has existing data, then $queued_arch_lines should be populated (IF we have new data to append to it).
    // We also trim out X first lines of stale data (earlier then the X days time range)
    ////////////////////////////////////////////////////////////////////////////////////////////////
    elseif ( is_array($queued_arch_lines) && sizeof($queued_arch_lines) > 0 ) {
     
    $queued_arch_data = implode("\n", $queued_arch_lines);
    
    // Current light chart lines, plus new archival lines queued to be added
    $check_light_data_lines = $ct_gen->get_lines($light_path) + sizeof($queued_arch_lines);
     
    // Get FIRST line of light chart data (determines oldest light timestamp)
    $fopen_light = fopen($light_path, 'r');
    
      if ($fopen_light) {
      $first_light_line = fgets($fopen_light);
      fclose($fopen_light);
      usleep(20000); // Wait 0.02 seconds, since we'll be writing data to this file momentarily
      gc_collect_cycles(); // Clean memory cache
      }
       
    $first_light_array = explode("||", $first_light_line);
    $oldest_light_timestamp = $ct_var->num_to_str($first_light_array[0]);
     
      // If our oldest light timestamp is older than allowed, remove the stale data points
      if ( $oldest_light_timestamp < $oldest_allowed_timestamp ) {
      $light_data_removed_outdated_lines = $ct_gen->prune_first_lines($light_path, 0, $oldest_allowed_timestamp);
      
      // ONLY APPEND A LINE BREAK TO THE NEW ARCHIVAL DATA, since $ct_gen->prune_first_lines() maintains the existing line breaks
      $result = $this->save_file($light_path, $light_data_removed_outdated_lines['data'] . $queued_arch_data . "\n");  // WITH newline for NEW data (file write)
      $light_mode_logging = 'OVERWRITE_' . $light_data_removed_outdated_lines['lines_removed'] . '_OUTDATED_PRUNED_' . $added_arch_mode;
      }
      // If we're clear to just append the latest data
      else {
      $result = $this->save_file($light_path, $queued_arch_data . "\n", "append");  // WITH newline (file write)
      $light_mode_logging = 'APPEND_' . $added_arch_mode;
      }
     
   
    }
    // No light data to update
    else {
    $result = 'no_update';
    }
   
  
  
    // Logging results
    if ( $result == true ) {
     
    $_SESSION['light_charts_updated'] = $_SESSION['light_charts_updated'] + 1;
      
      if ( 
      $ct_conf['power']['debug_mode'] == 'all'
      || $ct_conf['power']['debug_mode'] == 'all_telemetry'
      || $ct_conf['power']['debug_mode'] == 'light_chart_telemetry' 
      ) {
      	
      $ct_gen->log(
      			'cache_debug',
      			'Light chart ' . $light_mode_logging . ' COMPLETED ('.$_SESSION['light_charts_updated'].') for ' . $light_path
      			);
      
      }
       
      if ( 
      $ct_conf['power']['debug_mode'] == 'all'
      || $ct_conf['power']['debug_mode'] == 'all_telemetry'
      || $ct_conf['power']['debug_mode'] == 'memory_usage_telemetry' 
      ) {
      	
      $ct_gen->log(
      			'system_debug',
      			$_SESSION['light_charts_updated'] . ' light charts updated, CURRENT script memory usage is ' . $ct_gen->conv_bytes(memory_get_usage(), 1) . ', PEAK script memory usage is ' . $ct_gen->conv_bytes(memory_get_peak_usage(), 1) . ', php_sapi_name is "' . php_sapi_name() . '"'
      			);
     
      }
      
    }
    elseif ( $result == false ) {
        
        if ( !is_readable($archive_path) ) {
        $ct_gen->log( 'cache_error', 'Light chart ' . $light_mode_logging . ' FAILED, data from archive file ' . $archive_path . ' could not be read. Check file AND cache directory permissions');
        }
        elseif ( !file_exists($archive_path) ) {
        $ct_gen->log( 'cache_error', 'Light chart ' . $light_mode_logging . ' FAILED for ' . $light_path . ', archival data not created yet (for new installs please wait a few hours, then check cache directory permissions if this error continues beyond then)');
        }
    
    }
    elseif ( $result == 'no_update' ) {
    // Do nothing
    }
  
   
  gc_collect_cycles(); // Clean memory cache
  
  return $result;
  
  }
  
  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function send_notifications() {
  
  global $base_dir, $ct_conf, $ct_var, $ct_gen, $processed_msgs, $possible_http_users, $http_runtime_user, $current_runtime_user, $telegram_user_data, $valid_to_email, $telegram_activated, $notifyme_activated, $sms_service;
  
  
  // Array of currently queued messages in the cache
  $msgs_queue = $ct_gen->sort_files($base_dir . '/cache/secured/messages', 'queue', 'asc');
   
  //var_dump($msgs_queue); // DEBUGGING ONLY
  //return false; // DEBUGGING ONLY
  
  
    // If queued messages exist, proceed
    if ( is_array($msgs_queue) && sizeof($msgs_queue) > 0 ) {
    
    
      if ( !isset($processed_msgs['notifications_count']) ) {
      $processed_msgs['notifications_count'] = 0;
      }
      
      
      // If it's been over 5 minutes since a notifyme alert was sent 
      // (we use 6 minutes, safely over the 5 minute limit for the maximum 5 requests), 
      // and no session count is set, set session count to zero
      // Don't update the file-cached count here, that will happen automatically from resetting the session count to zero 
      // (if there are notifyme messages queued to send)
      if ( !isset($processed_msgs['notifyme_count']) && $this->update_cache($base_dir . '/cache/events/throttling/notifyme-alerts-sent.dat', 5) == true ) {
      $processed_msgs['notifyme_count'] = 0;
      }
      // If it hasn't been over 5 minutes since the last notifyme send, and there is no session count, 
      // use the file-cached count for the session count starting point
      elseif ( !isset($processed_msgs['notifyme_count']) && $this->update_cache($base_dir . '/cache/events/throttling/notifyme-alerts-sent.dat', 5) == false ) {
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
    $queued_msgs_processing_lock_file = $base_dir . '/cache/events/notifications-queue-processing-lock.dat';
    
    
      // If we find no file lock (OR if there is a VERY stale file lock [OVER 9 MINUTES OLD]), we can proceed
      if ( $this->update_cache($queued_msgs_processing_lock_file, 9) == true ) {  
      
      // Re-save new file lock
      $this->save_file($queued_msgs_processing_lock_file, $ct_gen->time_date_format(false, 'pretty_date_time') );
      
      /////////////////////////////////////////////////
      ////////////FILE-LOCKED START////////////////////
      /////////////////////////////////////////////////
      
      
        // Sleep for 2 seconds before starting ANY consecutive message send, to help avoid being blocked / throttled by external server
        if ( $processed_msgs['notifications_count'] > 0 ) {
        sleep(2);
        }
      
      
        // Notifyme params
        if ( $notifyme_activated ) {
        
        $notifyme_params = array(
                                'notification' => null, // Setting this right before sending
                                'accessCode' => $ct_conf['comms']['notifyme_accesscode']
                                );
                
        }
      
        
        // SMS service params 
        if ( $sms_service == 'twilio' ) {
        
        $twilio_params = array(
                              'Body' => null, // Setting this right before sending
                              'To' => '+' . $ct_gen->mob_number($ct_conf['comms']['to_mobile_text']),
                              'From' => '+' . $ct_conf['comms']['twilio_number']
                               );
                            
        }
        elseif ( $sms_service == 'textbelt' ) {
            
        $textbelt_params = array(
                                  'message' => null, // Setting this right before sending
                                  'phone' => $ct_gen->mob_number($ct_conf['comms']['to_mobile_text']),
                                  'key' => $ct_conf['comms']['textbelt_apikey']
                                 );
                            
        }
        elseif ( $sms_service == 'textlocal' ) {
            
        $textlocal_params = array(
                                   'message' => null, // Setting this right before sending
                                   'sender' => $ct_conf['comms']['textlocal_sender'],
                                   'apikey' => $ct_conf['comms']['textlocal_apikey'],
                                   'numbers' => $ct_gen->mob_number($ct_conf['comms']['to_mobile_text'])
                                    );
                            
        }
      
      
       
        // Send messages
        foreach ( $msgs_queue as $queued_cache_file ) {
        
        
        $msg_data = trim( file_get_contents($base_dir . '/cache/secured/messages/' . $queued_cache_file) );
        
        
          if ( isset($msg_data) && $msg_data != '' ) {
             
              
               // If 0 bytes from system / network issues, just delete it to keep the directory contents clean
               if ( filesize($base_dir . '/cache/secured/messages/' . $queued_cache_file) == 0 ) {
               unlink($base_dir . '/cache/secured/messages/' . $queued_cache_file);
               }
               
               
               // Notifyme
               elseif ( $notifyme_activated && preg_match("/notifyme/i", $queued_cache_file) ) {
                 
                 $notifyme_params['notification'] = $msg_data;
                 
               // Sleep for 1 second EXTRA on EACH consecutive notifyme message, to throttle MANY outgoing messages, to help avoid being blocked / throttled by external server
               $notifyme_sleep = 1 * ( $processed_msgs['notifyme_count'] > 0 ? $processed_msgs['notifyme_count'] : 1 );
               sleep($notifyme_sleep);
               
                
                   // Only 5 notifyme messages allowed per minute
                   if ( $processed_msgs['notifyme_count'] < 5 ) {
                   
                   $notifyme_response = @$this->ext_data('params', $notifyme_params, 0, 'https://api.notifymyecho.com/v1/NotifyMe');
                  
                   $processed_msgs['notifyme_count'] = $processed_msgs['notifyme_count'] + 1;
                   
                   $msg_sent = 1;
                   
                   $this->save_file($base_dir . '/cache/events/throttling/notifyme-alerts-sent.dat', $processed_msgs['notifyme_count']); 
                   
                     if ( $ct_conf['power']['debug_mode'] == 'all' || $ct_conf['power']['debug_mode'] == 'all_telemetry' || $ct_conf['power']['debug_mode'] == 'api_comms_telemetry' ) {
                     $this->save_file($base_dir . '/cache/logs/debug/external_data/last-response-notifyme.log', $notifyme_response);
                     }
                   
                   unlink($base_dir . '/cache/secured/messages/' . $queued_cache_file);
                   
                   }
               
               
               }
               
               
               
               // Telegram
               elseif ( $telegram_activated && preg_match("/telegram/i", $queued_cache_file) ) {
                 
               // Sleep for 1 second EXTRA on EACH consecutive telegram message, to throttle MANY outgoing messages, to help avoid being blocked / throttled by external server
               $telegram_sleep = 1 * ( $processed_msgs['telegram_count'] > 0 ? $processed_msgs['telegram_count'] : 1 );
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
                   
                 
                  if ( $ct_conf['power']['debug_mode'] == 'all' || $ct_conf['power']['debug_mode'] == 'all_telemetry' || $ct_conf['power']['debug_mode'] == 'api_comms_telemetry' ) {
                  $this->save_file($base_dir . '/cache/logs/debug/external_data/last-response-telegram.log', $telegram_response);
                  }
               
               }
               
               
               
               // Twilio
               elseif ( $sms_service == 'twilio' && preg_match("/twilio/i", $queued_cache_file) ) {
                 
               $twilio_params['Body'] = $msg_data;
                 
               // Sleep for 1 second EXTRA on EACH consecutive text message, to throttle MANY outgoing messages, to help avoid being blocked / throttled by external server
               $text_sleep = 1 * ( $processed_msgs['text_count'] > 0 ? $processed_msgs['text_count'] : 1 );
               sleep($text_sleep);
               usleep(500000); // Wait 0.5 seconds EXTRA, as standard twilio pay-as-you-go plans are 1 text per second (so play it safe)
                 
               $twilio_response = @$this->ext_data('params', $twilio_params, 0, 'https://api.twilio.com/2010-04-01/Accounts/' . $ct_conf['comms']['twilio_sid'] . '/Messages.json', 2);
                 
               $processed_msgs['text_count'] = $processed_msgs['text_count'] + 1;
               
               $msg_sent = 1;
                 
                 if ( $ct_conf['power']['debug_mode'] == 'all' || $ct_conf['power']['debug_mode'] == 'all_telemetry' || $ct_conf['power']['debug_mode'] == 'api_comms_telemetry' ) {
                 $this->save_file($base_dir . '/cache/logs/debug/external_data/last-response-twilio.log', $twilio_response);
                 }
               
               unlink($base_dir . '/cache/secured/messages/' . $queued_cache_file);
               
               }
               
               
               
               // Textbelt
               elseif ( $sms_service == 'textbelt' && preg_match("/textbelt/i", $queued_cache_file) ) {
                 
               $textbelt_params['message'] = $msg_data;
                 
               // Sleep for 1 second EXTRA on EACH consecutive text message, to throttle MANY outgoing messages, to help avoid being blocked / throttled by external server
               $text_sleep = 1 * ( $processed_msgs['text_count'] > 0 ? $processed_msgs['text_count'] : 1 );
               sleep($text_sleep);
                 
               $textbelt_response = @$this->ext_data('params', $textbelt_params, 0, 'https://textbelt.com/text', 2);
                 
               $processed_msgs['text_count'] = $processed_msgs['text_count'] + 1;
               
               $msg_sent = 1;
                 
                 if ( $ct_conf['power']['debug_mode'] == 'all' || $ct_conf['power']['debug_mode'] == 'all_telemetry' || $ct_conf['power']['debug_mode'] == 'api_comms_telemetry' ) {
                 $this->save_file($base_dir . '/cache/logs/debug/external_data/last-response-textbelt.log', $textbelt_response);
                 }
               
               unlink($base_dir . '/cache/secured/messages/' . $queued_cache_file);
               
               }
               
               
               
               // Textlocal
               elseif ( $sms_service == 'textlocal' && preg_match("/textlocal/i", $queued_cache_file) ) {  
                 
               $textlocal_params['message'] = $msg_data;
                 
               // Sleep for 1 second EXTRA on EACH consecutive text message, to throttle MANY outgoing messages, to help avoid being blocked / throttled by external server
               $text_sleep = 1 * ( $processed_msgs['text_count'] > 0 ? $processed_msgs['text_count'] : 1 );
               sleep($text_sleep);
                 
               $textlocal_response = @$this->ext_data('params', $textlocal_params, 0, 'https://api.txtlocal.com/send/', 1);
                 
               $processed_msgs['text_count'] = $processed_msgs['text_count'] + 1;
               
               $msg_sent = 1;
                 
                 if ( $ct_conf['power']['debug_mode'] == 'all' || $ct_conf['power']['debug_mode'] == 'all_telemetry' || $ct_conf['power']['debug_mode'] == 'api_comms_telemetry' ) {
                 $this->save_file($base_dir . '/cache/logs/debug/external_data/last-response-textlocal.log', $textlocal_response);
                 }
               
               unlink($base_dir . '/cache/secured/messages/' . $queued_cache_file);
               
               }
                
                  
                // SEND EMAILS LAST, IN CASE OF SMTP METHOD FAILURE AND RUNTIME EXIT
               
               
               // Text email
               // To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
               // Only use text-to-email if other text services aren't configured
               elseif ( $sms_service == 'email_gateway' && preg_match("/textemail/i", $queued_cache_file) ) {
                 
               $textemail_array = json_decode($msg_data, true);
                 
               $restore_text_charset = $textemail_array['charset'];
                 
                   // json_encode() only accepts UTF-8, SO CONVERT BACK TO ORIGINAL CHARSET
                   if ( strtolower($restore_text_charset) != 'utf-8' ) {
                   
                     foreach( $textemail_array as $textemail_key => $textemail_val ) {
                     // Leave charset / content_type vars UTF-8
                     $textemail_array[$textemail_key] = ( $textemail_key == 'charset' || $textemail_key == 'content_type' ? $textemail_val : mb_convert_encoding($textemail_val, $restore_text_charset, 'UTF-8') );
                     }
               
                   }
              
                 
                   if ( isset($textemail_array['subject']) && isset($textemail_array['message']) && $textemail_array['subject'] != '' && $textemail_array['message'] != '' ) {
                    
                   // Sleep for 1 second EXTRA on EACH consecutive text message, to throttle MANY outgoing messages, to help avoid being blocked / throttled by external server
                   $text_sleep = 1 * ( $processed_msgs['text_count'] > 0 ? $processed_msgs['text_count'] : 1 );
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
               elseif ( $valid_to_email && preg_match("/normalemail/i", $queued_cache_file) ) {
                 
               $email_array = json_decode($msg_data, true);
                 
               $restore_email_charset = $email_array['charset'];
                 
                   // json_encode() only accepts UTF-8, SO CONVERT BACK TO ORIGINAL CHARSET
                   if ( strtolower($restore_email_charset) != 'utf-8' ) {
                   
                     foreach( $email_array as $email_key => $email_val ) {
                     // Leave charset / content_type vars UTF-8
                     $email_array[$email_key] = ( $email_key == 'charset' || $email_key == 'content_type' ? $email_val : mb_convert_encoding($email_val, $restore_email_charset, 'UTF-8') );
                     }
               
                   }
                 
                 
                   if ( isset($email_array['subject']) && isset($email_array['message']) && $email_array['subject'] != '' && $email_array['message'] != '' ) {
                    
                   // Sleep for 1 second EXTRA on EACH consecutive email message, to throttle MANY outgoing messages, to help avoid being blocked / throttled by external server
                   $email_sleep = 1 * ( $processed_msgs['email_count'] > 0 ? $processed_msgs['email_count'] : 1 );
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
          // No data in message queue file
          else {
          unlink($base_dir . '/cache/secured/messages/' . $queued_cache_file);
          }

        
        }
       
       
       
        if ( $msg_sent == 1 ) {
        $processed_msgs['notifications_count'] = $processed_msgs['notifications_count'] + 1;
        }
      
      
      /////////////////////////////////////////////////
      ////////////FILE-LOCKED END//////////////////////
      /////////////////////////////////////////////////
      
      $result = true;
      
      // We are done running cron, so we can release the lock
      unlink($queued_msgs_processing_lock_file);
      
      }
     
     
    gc_collect_cycles(); // Clean memory cache
       
    return $result;
    
    }
    else {
    return false; // No messages are queued to send, so skip and return false
    }
  
  
  }
  
  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function ext_data($mode, $request_params, $ttl, $api_server=null, $post_encoding=3, $test_proxy=null, $headers=null) { // Default to JSON encoding post requests (most used)
  
  // $ct_conf['gen']['btc_prim_currency_pair'] / $ct_conf['gen']['btc_prim_exchange'] / $sel_opt['sel_btc_prim_currency_val'] USED FOR TRACE DEBUGGING (TRACING)
  
  global $app_version, $base_dir, $base_url, $ct_conf, $ct_var, $ct_gen, $sel_opt, $proxy_checkup, $log_errors, $log_debugging, $limited_api_calls, $api_runtime_cache, $curl_user_agent, $api_connections, $htaccess_username, $htaccess_password;
  
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
    
      $data_bytes_ux = 'data flagged as none'; // OVERWRITE 
      
      
        if ( !$log_errors['error_duplicates'][$hash_check] ) {
        $log_errors['error_duplicates'][$hash_check] = 1; 
        }
        else {
        $log_errors['error_duplicates'][$hash_check] = $log_errors['error_duplicates'][$hash_check] + 1;
        }
       
       
      // Don't log this error again during THIS runtime, as it would be a duplicate...just overwrite same error message, BUT update the error count in it
      
      $ct_gen->log(
      			'cache_error',
      							
      			'no RUNTIME CACHE data from failure with ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . $ct_gen->obfusc_url_data($api_endpoint),
      							
      			'requested_from: cache ('.$log_errors['error_duplicates'][$hash_check].' runtime instances); mode: ' . $mode . '; received: ' . $data_bytes_ux . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; hash_check: ' . $ct_var->obfusc_str($hash_check, 4) . ';',
      							
      			$hash_check
      			);
       
      }
      elseif ( $ct_conf['power']['debug_mode'] == 'all' || $ct_conf['power']['debug_mode'] == 'all_telemetry' || $ct_conf['power']['debug_mode'] == 'ext_data_cache_telemetry' ) {
      
      
        if ( !$log_debugging['debug_duplicates'][$hash_check] ) {
        $log_debugging['debug_duplicates'][$hash_check] = 1; 
        }
        else {
        $log_debugging['debug_duplicates'][$hash_check] = $log_debugging['debug_duplicates'][$hash_check] + 1;
        }
       
       
      // Don't log this debugging again during THIS runtime, as it would be a duplicate...just overwrite same debugging message, BUT update the debugging count in it
      
      $ct_gen->log(
      			'cache_debug',
      							
      			'RUNTIME CACHE request for ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . $ct_gen->obfusc_url_data($api_endpoint),
      							
      			'requested_from: cache ('.$log_debugging['debug_duplicates'][$hash_check].' runtime instances); mode: ' . $mode . '; received: ' . $data_bytes_ux . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; hash_check: ' . $ct_var->obfusc_str($hash_check, 4) . ';',
      							
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
              
      
      // Servers requiring TRACKED THROTTLE-LIMITING, due to limited-allowed minute / hour / daily requests
      // (are processed by this->api_throttling(), to avoid using up daily request limits)
      if ( in_array($endpoint_tld_or_ip, $ct_conf['power']['tracked_throttle_limited_servers']) && $this->api_throttling($endpoint_tld_or_ip) == true ) {
              
            
      // Set $data var with any cached value (null / false result is OK), as we don't want to cache any PROBABLE error response
      // (will be set / reset as 'none' further down in the logic and cached / recached for a TTL cycle, if no cached data exists to fallback on)
      $data = trim( file_get_contents($base_dir . '/cache/secured/external_data/'.$hash_check.'.dat') );
      
      // DON'T USE isset(), use != '' to store as 'none' reliably (so we don't keep hitting a server that may be throttling us, UNTIL cache TTL runs out)
      $api_runtime_cache[$hash_check] = ( isset($data) && $data != '' ? $data : 'none' ); 
             
                
          // Flag if cache fallback succeeded
          if ( isset($data) && $data != '' && $data != 'none' ) {
          $fallback_cache_data = true;
          }
          
      
          // Fallback just needs 'modified time' updated with touch()
          if ( isset($fallback_cache_data) ) {
          $store_file_contents = touch($base_dir . '/cache/secured/external_data/'.$hash_check.'.dat');
          $ct_gen->log('ext_data_error', 'cache fallback SUCCEEDED during throttling of API for: ' . $endpoint_tld_or_ip);
          }
          else {
          $store_file_contents = $this->save_file($base_dir . '/cache/secured/external_data/'.$hash_check.'.dat', $api_runtime_cache[$hash_check]);
          $ct_gen->log('ext_data_error', 'cache fallback FAILED during throttling of API for: ' . $endpoint_tld_or_ip);
          }
                
                
      gc_collect_cycles(); // Clean memory cache
      return $data;
                
                
      }
              
      
      // Servers with STRICT CONSECUTIVE CONNECT limits (we add 0.11 seconds to the wait between consecutive connections)
      if ( in_array($endpoint_tld_or_ip, $ct_conf['power']['strict_consecutive_connect_servers']) ) {
        
      $api_connections[$tld_session_prefix] = $api_connections[$tld_session_prefix] + 1;
      
        if ( $api_connections[$tld_session_prefix] > 1 ) {
        usleep(110000); // Throttle 0.11 seconds
        }
       
      }
    
    
      // Throttled endpoints in $ct_conf['power']['limited_apis']
      // If this is an API service that requires multiple calls (for each market), 
      // and a request to it has been made consecutively, we throttle it to avoid being blocked / throttled by external server
      if ( in_array($endpoint_tld_or_ip, $ct_conf['power']['limited_apis']) ) {
      
        if ( !$limited_api_calls[$tld_session_prefix . '_calls'] ) {
        $limited_api_calls[$tld_session_prefix . '_calls'] = 1;
        }
        elseif ( $limited_api_calls[$tld_session_prefix . '_calls'] == 1 ) {
        usleep(150000); // Throttle 0.15 seconds
        }
    
      }
     
    
    // Initiate the curl external data request
    $ch = curl_init( ( $mode == 'params' ? $api_server : '' ) );
     
     
      // If this is a windows desktop edition
      if ( file_exists($base_dir . '/cache/other/win_curl_cacert.pem') ) {
      curl_setopt($ch, CURLOPT_CAINFO, $base_dir . '/cache/other/win_curl_cacert.pem');
      }
     
     
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
              
     
      // RSS feed services that are a bit funky with allowed user agents, so we need to let them know this is a real feed parser (not just a spammy bot)
      if ( in_array($endpoint_tld_or_ip, $ct_conf['power']['strict_news_feed_servers']) ) {
      curl_setopt($ch, CURLOPT_USERAGENT, 'Custom_Feed_Parser/1.0 (compatible; Open_Crypto_Tracker/' . $app_version . '; +https://github.com/taoteh1221/Open_Crypto_Tracker)');
      }
      else {
      curl_setopt($ch, CURLOPT_USERAGENT, $curl_user_agent);
      }
     
     
      // If this is an SSL connection, add SSL parameters
      if ( preg_match("/https:\/\//i", $api_endpoint) ) {
      
      $remote_api_strict_ssl = $ct_conf['sec']['remote_api_strict_ssl']; // Default
      
      $regex_base_url = $ct_gen->regex_compat_path($base_url);
       
      // Secure random hash to nullify any preg_match() below (if $regex_base_url FAILS to set a value above),
      // as we may be submitting out htaccess user/pass (if setup)
      $scan_base_url = ( isset($regex_base_url) && $regex_base_url != '' ? $regex_base_url : $ct_gen->rand_hash(8) );
      
      
        // If we are making a request to our own base URL (self-security-checks / calls to internal API endpoints / etc)
        if ( isset($scan_base_url) && $scan_base_url != '' && preg_match("/".$scan_base_url."/i", $api_endpoint) ) {
        
          
          // Flag if this is an htaccess security check
          if ( preg_match("/htaccess_security_check/i", $api_endpoint) ) {
          $is_self_security_test = 1;
          }
          
         
          // If we have password protection on in the app
          if ( isset($htaccess_username) && isset($htaccess_password) && $htaccess_username != '' && $htaccess_password != '' ) {
          curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
          // DO NOT ENCAPSULATE PHP USER/PASS VARS IN QUOTES, IT BREAKS THE FEATURE
          curl_setopt($ch, CURLOPT_USERPWD, $htaccess_username . ':' . $htaccess_password); 
          }
         
         
        // We don't want strict SSL checks since this is our app calling itself
        // (as we may be running our own self-signed certificate)
        $remote_api_strict_ssl = 'off'; // (Overwrite / Reset)
        
        }
        // If this is a twilio endpoint, we need to authenticate
        elseif ( $endpoint_tld_or_ip == 'twilio.com' ) {
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        // DO NOT ENCAPSULATE PHP USER/PASS VARS IN QUOTES, IT BREAKS THE FEATURE
        curl_setopt($ch, CURLOPT_USERPWD, $ct_conf['comms']['twilio_sid'] . ':' . $ct_conf['comms']['twilio_token']); 
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
        if ( isset($data) && $data != '' && $data != 'none' ) {
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
      			
      			
        // Servers which are known to block API access by location / jurasdiction
        // (we alert end-users in error logs, when a corrisponding API server connection fails [one-time notice per-runtime])
        if ( in_array($endpoint_tld_or_ip, $ct_conf['power']['location_blocked_servers']) ) {

             
             if ( is_array($ct_conf['proxy']['proxy_list']) && sizeof($ct_conf['proxy']['proxy_list']) > 0 ) {
             $ip_description = 'PROXY';
             }
             else {
             $ip_description = 'SERVER';
             }

            
        $ct_gen->log(
          		'notify_error',
          		'your ' . $ip_description . '\'S IP ADDRESS location / jurasdiction *MAY* be blocked from accessing the "'.$endpoint_tld_or_ip.'" API, *IF* THIS ERROR REPEATS *VERY OFTEN*',
          		false,
          		md5($endpoint_tld_or_ip) . '_possibly_blocked'
          		);
          		    
        }
      
      
        if ( is_array($ct_conf['proxy']['proxy_list']) && sizeof($ct_conf['proxy']['proxy_list']) > 0 && isset($current_proxy) && $current_proxy != '' && $mode != 'proxy-check' ) { // Avoid infinite loops doing proxy checks
     
        $proxy_checkup[] = array(
                    			'endpoint' => ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . $ct_gen->obfusc_url_data($api_endpoint),
                    			'proxy' => $current_proxy
                    			);
                    
        }
      
      
      }
      // Log this latest live data response, 
      // ONLY IF WE DETECT AN $endpoint_tld_or_ip, AND TTL IS !NOT! ZERO (TTL==0 usually means too many unique requests that would bloat the cache)
      elseif ( isset($data) && isset($endpoint_tld_or_ip) && $endpoint_tld_or_ip != '' && $ttl != 0 ) {
      
      
        ////////////////////////////////////////////////////////////////	
        // Checks for error false positives, BEFORE CHECKING FOR A POSSIBLE ERROR
        // https://www.php.net/manual/en/regexp.reference.meta.php
        // DON'T ADD TOO MANY CHECKS HERE, OR RUNTIME WILL SLOW SIGNIFICANTLY!!
        if ( 
        preg_match("/xml version/i", $data) // RSS feeds (that are likely intact)
        || preg_match("/invalid vs_currency/i", $data) // Coingecko (we fallback to USD in this case anyways, and error would repeat every cache refresh cluttering logs)
        || preg_match("/\"error\":\[\],/i", $data) // kraken.com / generic
        || preg_match("/warning-icon/i", $data)  // Medium.com RSS feeds
        || preg_match("/\"error_code\":0/i", $data) // Generic
        ) { // coinmarketcap.com / generic
        $false_positive = 1;
        }
       
       
        // DON'T FLAG as a possible error if detected as a false positive already
        // (THIS LOGIC IS FOR STORING THE POSSIBLE ERROR IN /cache/logs/error/external_data FOR REVIEW)
        if ( $false_positive != 1 ) {
         
            // MUST RUN BEFORE FALLBACK ATTEMPT TO CACHED DATA
            // If response seems to contain an error message ('error' only found once, no words containing 'error')
            // DON'T ADD TOO MANY CHECKS HERE, OR RUNTIME WILL SLOW SIGNIFICANTLY!!
            if ( $ct_var->substri_count($data, 'error') > 0 && !preg_match("/terror/i", $data) ) {
             
            // Log full results to file, WITH UNIQUE TIMESTAMP IN FILENAME TO AVOID OVERWRITES (FOR ADEQUATE DEBUGGING REVIEW)
            $error_response_log = '/cache/logs/error/external_data/error-response-'.preg_replace("/\./", "_", $endpoint_tld_or_ip).'-hash-'.$hash_check.'-timestamp-'.time().'.log';
            
            // LOG-SAFE VERSION (no post data with API keys etc)
             $ct_gen->log(
             			'ext_data_error',
             							
             			'POSSIBLE error for ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . $ct_gen->obfusc_url_data($api_endpoint),
             							
             			'requested_from: server (' . $ct_conf['power']['remote_api_timeout'] . ' second timeout); live_request_time: ' . $api_total_time . ' seconds; mode: ' . $mode . '; received: ' . $data_bytes_ux . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; debug_file: ' . $error_response_log . '; btc_prim_currency_pair: ' . $ct_conf['gen']['btc_prim_currency_pair'] . '; btc_prim_exchange: ' . $ct_conf['gen']['btc_prim_exchange'] . '; sel_btc_prim_currency_val: ' . $ct_var->num_to_str($sel_opt['sel_btc_prim_currency_val']) . '; hash_check: ' . $ct_var->obfusc_str($hash_check, 4) . ';'
             			);
            
            // Log this error response from this data request
            $this->save_file($base_dir . $error_response_log, $data);
             
            }
        
        
        ////////////////////////////////////////////////////////////////
       
        ////////////////////////////////////////////////////////////////
        // FALLBACK ATTEMPT TO CACHED DATA, IF AVAILABLE (WE STILL LOG THE FAILURE, SO THIS OS OK)
        // WE DON'T WANT TO SLOW DOWN THE RUNTIME TOO MUCH, BUT WE WANT AS MUCH FALLBACK AS IS REASONABLE
        // If response is seen to NOT contain USUAL data, use cache if available
       
        // Check that we didn't detect as a false positive already
       
        
            // !!!!!DON'T ADD TOO MANY CHECKS HERE, OR RUNTIME WILL SLOW SIGNIFICANTLY!!!!!
            if ( 
            // Errors / unavailable / null / throttled / maintenance
            // Generic
            preg_match("/cf-error/i", $data) // Cloudflare (DDOS protection service)
            || preg_match("/cf-browser/i", $data) // Cloudflare (DDOS protection service)
            || preg_match("/{\"status\":{\"error_code\":/i", $data) // Bittrex.com / generic
            || preg_match("/scheduled maintenance/i", $data) // Bittrex.com / generic
            || preg_match("/Service Unavailable/i", $data) // Bittrex.com / generic
            || preg_match("/temporarily unavailable/i", $data) // Bitfinex.com / generic
            || preg_match("/Server Error/i", $data) // Kucoin.com / generic
            || preg_match("/site is down/i", $data) // Blockchain.info / generic
            || preg_match("/something went wrong/i", $data) // Bitbns.com / generic
            || preg_match("/An error has occurred/i", $data) // Bitflyer.com / generic
            || preg_match("/too many requests/i", $data) // reddit.com / generic
            || preg_match("/Request failed/i", $data) // generic
            || preg_match("/EService:Unavailable/i", $data) // Kraken.com / generic
            || preg_match("/EService:Busy/i", $data) // Kraken.com / generic
            || preg_match("/\"result\":{}/i", $data) // Kraken.com / generic
            || preg_match("/\"result\":null/i", $data) // Bittrex.com / generic
            || preg_match("/\"result\":\[\],/i", $data) // Generic
            || preg_match("/\"results\":\[\],/i", $data) // generic
            || preg_match("/\"data\":null/i", $data) // Bitflyer.com / generic
            || preg_match("/\"success\":false/i", $data) // BTCturk.com / Bittrex.com / generic
            || preg_match("/\"error\":\"timeout/i", $data) // generic
            || preg_match("/\"reason\":\"Maintenance\"/i", $data) // Gemini.com / generic
            // API-specific
            || $endpoint_tld_or_ip == 'coingecko.com' && preg_match("/error code: /i", $data)
            || $endpoint_tld_or_ip == 'localbitcoins.com' && !preg_match("/volume_btc/i", $data)
            || $endpoint_tld_or_ip == 'coinmarketcap.com' && !preg_match("/last_updated/i", $data) 
            ) {
              
            
            // Reset $data var with any cached value (null / false result is OK), as we don't want to cache a KNOWN error response
            // (will be set / reset as 'none' further down in the logic and cached / recached for a TTL cycle, if no cached data exists to fallback on)
            $data = trim( file_get_contents($base_dir . '/cache/secured/external_data/'.$hash_check.'.dat') );
             
                
                // Flag if cache fallback succeeded
                if ( isset($data) && $data != '' && $data != 'none' ) {
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
            							
            			'requested_from: server (' . $ct_conf['power']['remote_api_timeout'] . ' second timeout); live_request_time: ' . $api_total_time . ' seconds; mode: ' . $mode . '; received: ' . $data_bytes_ux . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; btc_prim_currency_pair: ' . $ct_conf['gen']['btc_prim_currency_pair'] . '; btc_prim_exchange: ' . $ct_conf['gen']['btc_prim_exchange'] . '; sel_btc_prim_currency_val: ' . $ct_var->num_to_str($sel_opt['sel_btc_prim_currency_val']) . '; hash_check: ' . $ct_var->obfusc_str($hash_check, 4) . ';'
            			);
             
           
            }
    
       
        }
       
       
       
        ////////////////////////////////////////////////////////////////
       
      
      
        // Data debugging telemetry
        if ( $ct_conf['power']['debug_mode'] == 'all' || $ct_conf['power']['debug_mode'] == 'all_telemetry' || $ct_conf['power']['debug_mode'] == 'ext_data_live_telemetry' ) {
         
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
      $api_runtime_cache[$hash_check] = ( isset($data) && $data != '' ? $data : 'none' ); 
      
        // Fallback just needs 'modified time' updated with touch()
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
      							
      			'Remote API timeout near OR exceeded for ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . $ct_gen->obfusc_url_data($api_endpoint) . ' (' . $api_total_time . ' seconds / received ' . $data_bytes_ux . '), consider setting "remote_api_timeout" higher in POWER USER config *IF* this persists OFTEN',
      							
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
      if ( isset($api_runtime_cache[$hash_check]) && $api_runtime_cache[$hash_check] != '' && $api_runtime_cache[$hash_check] != 'none' ) {
      $data = $api_runtime_cache[$hash_check];
      $fallback_cache_data = true;
      }
      else {
        
      $data = trim( file_get_contents($base_dir . '/cache/secured/external_data/'.$hash_check.'.dat') );
      
        if ( isset($data) && $data != '' && $data != 'none' ) {
        $api_runtime_cache[$hash_check] = $data; // Create a runtime cache from the file cache, for any additional requests during runtime for this data set
        $fallback_cache_data = true;
        }
       
      }
    
    
      // Size of data, for checks in error log UX logic
      if ( $data == 'none' ) {
      $data_bytes_ux = 'data flagged as none';
      }
      else {
      $data_bytes = strlen($data);
      $data_bytes_ux = $ct_gen->conv_bytes($data_bytes, 2);
      }

     
      // Only do FILE CACHE error logging if we HAVE NOT YET set this file cache data as 'none', 
      // for logging UX (avoid exessive log entries EVERY RUNTIME that is using cached data)
      if ( $data == '' ) {
      
        if ( !$log_errors['error_duplicates'][$hash_check] ) {
        $log_errors['error_duplicates'][$hash_check] = 1; 
        }
        else {
        $log_errors['error_duplicates'][$hash_check] = $log_errors['error_duplicates'][$hash_check] + 1;
        }
       
      // Don't log this error again during THIS runtime, as it would be a duplicate...just overwrite same error message, BUT update the error count in it
      
      $ct_gen->log(
      			'cache_error',
      							
      			'no FILE CACHE data from recent failure with ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . $ct_gen->obfusc_url_data($api_endpoint),
      							
      			'requested_from: cache ('.$log_errors['error_duplicates'][$hash_check].' runtime instances); mode: ' . $mode . '; received: ' . $data_bytes_ux . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; hash_check: ' . $ct_var->obfusc_str($hash_check, 4) . ';',
      							
      			$hash_check
      			);
       
      }
      elseif ( $ct_conf['power']['debug_mode'] == 'all' || $ct_conf['power']['debug_mode'] == 'all_telemetry' || $ct_conf['power']['debug_mode'] == 'ext_data_cache_telemetry' ) {
      
        if ( !$log_debugging['debug_duplicates'][$hash_check] ) {
        $log_debugging['debug_duplicates'][$hash_check] = 1; 
        }
        else {
        $log_debugging['debug_duplicates'][$hash_check] = $log_debugging['debug_duplicates'][$hash_check] + 1;
        }
        
        
        if ( $data == 'none' ) {
        $log_append = ' (FLAGGED AS ERROR / NO DATA FROM LIVE REQUEST)';
        }
        
        
      // Don't log this debugging again during THIS runtime, as it would be a duplicate...just overwrite same debugging message, BUT update the debugging count in it
      
      $ct_gen->log(
      			'cache_debug',
      							
      			'FILE CACHE request for ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . $ct_gen->obfusc_url_data($api_endpoint) . $log_append,
      							
      			'requested_from: cache ('.$log_debugging['debug_duplicates'][$hash_check].' runtime instances); mode: ' . $mode . '; received: ' . $data_bytes_ux . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; hash_check: ' . $ct_var->obfusc_str($hash_check, 4) . ';',
      							
      			$hash_check
      			);
      
      }
    
    
    }
  
  
  gc_collect_cycles(); // Clean memory cache
  return $data;
  
  
  }
  
  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////

      
   
}


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>