<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */
 


class ct_cache {
	
// Class variables / arrays
var $ct_var1;
var $ct_var2;
var $ct_var3;

var $ct_array = array();
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function prune_access_stats() {
   
   global $ct;
   
   $prune_threshold = time() - $ct['var']->num_to_str($ct['conf']['power']['access_stats_delete_old'] * 86400);
   
   //var_dump($prune_threshold);
  
   $access_stats_files = $ct['gen']->sort_files($ct['base_dir'] . '/cache/secured/access_stats', 'dat', 'desc');
  
       
       // Prune ALL the stats
       foreach( $access_stats_files as $ip_access_file ) {
   
       $queued_newer_lines = array();
       
       $path = $ct['base_dir'] . '/cache/secured/access_stats/' . $ip_access_file;
       
       // Access stats file array
       $file_lines = file($path);
       
       
          foreach ( $file_lines as $line ) {
          
          $data_array = explode("||", $line);
          
          //var_dump($data_array);
          
              if ( $ct['var']->num_to_str($data_array[0]) >= $prune_threshold ) {
              $queued_newer_lines[] = $line;
              //var_dump($line);
              }
          
          }
       
          
       $pruned_data = implode("", $queued_newer_lines);
       
       $result = $this->save_file($path, $pruned_data); 
   
       gc_collect_cycles(); // Clean memory cache
       
       }
       
   
   // Give the file write / lock time to release
   // (as we'll be updating access stats at the end of this runtime)
   sleep(1); 
   
   }

  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function list_directories($path) {
       
  $results = array();
  
  $scanned_parent = scandir($path);
  
  //var_dump($scanned_parent);
  
     foreach ( $scanned_parent as $dir_check ) {
     
          if ( is_dir($path . '/' . $dir_check) && $dir_check != '.' && $dir_check != '..' ) {
          $results[] = $dir_check;
          }
     
     }
     
  return $results;
  
  }

  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function price_chart_cleanup($path=false) {
       
  global $ct;
  
     if ( !$path ) {
     $path = $ct['base_dir'] . '/cache/charts/spot_price_24hr_volume/archival/';
     }
  
  $subdirectory_list = $this->list_directories($path);
  
     foreach ( $subdirectory_list as $asset_ticker ) {
     
          if ( !isset($ct['conf']['assets'][$asset_ticker]) ) {
               
          //var_dump($path . $asset_ticker);
               
          $this->remove_dir($path . $asset_ticker);
		
     		// Light charts
     		foreach( $ct['light_chart_day_intervals'] as $light_chart_days ) {
     		$this->remove_dir($ct['base_dir'] . '/cache/charts/spot_price_24hr_volume/light/'.$light_chart_days.'_days/' . $asset_ticker);
     		}
		
          }
     
     }
  
  }

  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function update_cache($cache_file, $minutes) {
    
    // We ROUND (60 * $minutes), to also support SECONDS if needed
    // ($minutes is allowed to be DECIMALS, including BEING LESS THAN 1.00)
    if (  file_exists($cache_file) && filemtime($cache_file) > ( time() - round(60 * $minutes) )  ) {
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
  
  global $ct;
      
  $ct['gen']->log(
    		'other_error',
    		'CHECK ('.$loc.') for plugin error logs @ ' . time()
    		);
    			
      
  sleep(1); 
  
  $this->app_log();
  
  }
  
  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function php_timeout_defaults($dir_var) {
   
  global $ct;
  
  $ui_exec_time = $ct['dev']['ui_max_exec_time']; // Don't overwrite globals
  
    // If the UI timeout var wasn't set properly / is not a whole number 3600 or less
    if ( !$ct['var']->whole_int($ui_exec_time) || $ui_exec_time > 3600 ) {
    $ui_exec_time = 300; // Default
    }
  
  return preg_replace("/\[PHP_TIMEOUT\]/i", $ui_exec_time, file_get_contents($dir_var) );
  
  }
  
  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function remove_dir($src) {
       
    if ( !is_dir($src) ) {
    return false;
    }
  	
    $dir = opendir($src);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            $full = $src . '/' . $file;
            if ( is_dir($full) ) {
                $this->remove_dir($full);
            }
            else {
                unlink($full);
            }
        }
    }
    closedir($dir);
    rmdir($src);
  
  }


   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////

   
   function other_cached_data($mode, $file_path, $data_set=false, $json_storage=true, $file_save_mode=false, $file_lock=true) {

   global $ct;                                   
                         
                                      
      if ( $mode == 'save' && $data_set ) {
          
      // Check that the json encoding or other data format seems valid / not corrupt
      $checked_data = ( $json_storage ? json_encode($data_set, JSON_PRETTY_PRINT) : $data_set );
     
     
          if ( $checked_data != false || $checked_data != null || $checked_data != "null" ) {
          $ct['cache']->save_file($file_path, $checked_data, $file_save_mode, $file_lock);
          }
          
          
      }  
      elseif ( $mode == 'load' ) {

      $data = trim( file_get_contents($file_path) );
     		
      $cached_data = ( $json_storage ? json_decode($data, TRUE) : $data );
             			
           // "null" in quotes as the actual value is returned sometimes
           if ( $cached_data != false && $cached_data != null && $cached_data != "null" ) {
           return $cached_data;
           }
           else {
           return false;
           }
      
      }
      

   }
  
  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function log_access_stats() {
  
  global $ct;
  
  // We wait until we are in this function, to grab any cached data at the last minute,
  // to assure we get anything written recently by other runtimes
  
  $safe_name = $ct['gen']->compat_file_name($ct['remote_ip']);
  
  $file_save_path = $ct['base_dir'] . '/cache/secured/access_stats/ip_' . $safe_name . '.dat';
  
  
        if ( isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '' ) {
        $access_data_set .= '||' . $_SERVER['REQUEST_URI'];
        }
        elseif ( isset($_SERVER['SCRIPT_NAME']) && $_SERVER['SCRIPT_NAME'] != '' ) {
        $access_data_set .= '||' . $_SERVER['SCRIPT_NAME'];
        }
        else {
        $access_data_set .= '||' . basename(__FILE__);
        }
        
  
  $access_data_set .= '||' . $ct['remote_ip'];
  
  $access_data_set .= '||' . $ct['user_agent'];
  
  
        if ( isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '' ) {
        $access_data_set .= '||' . $_SERVER['HTTP_REFERER'];
        }
        else {
        $access_data_set .= '||NO_DATA';
        }
        	
        
        // In case a rare error occurred from power outage / corrupt memory / etc, we'll check the timestamp (in a non-resource-intensive way)
        // (#SEEMED# TO BE A REAL ISSUE ON A RASPI ZERO AFTER MULTIPLE POWER OUTAGES [ONE TIMESTAMP HAD PREPENDED CORRUPT DATA])
        $now = time();
        
        
        // (WE DON'T WANT TO STORE DATA WITH A CORRUPT TIMESTAMP)
        if ( $now > 0 ) {
        
        // Store system data to archival / light charts
        $access_stats_data = time() . $access_data_set;
        
        $ct['cache']->save_file($file_save_path, $access_stats_data . "\n", "append", false); // WITH newline (UNLOCKED file write)
        
        }
        
        
  }
  
  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function delete_old_files($dir_arr, $days, $ext=false) {
  
  global $ct;
   
      // Support for string OR array in the calls, for directory data
      if ( !is_array($dir_arr) ) {
      $dir_arr = array($dir_arr);
      }
     
     
      // Process each directory
      foreach ( $dir_arr as $dir ) {
      
          if ( !$ext ) {
          $files = glob($dir."/*");
          }
          else {
          $files = glob($dir."/*.".$ext);
          }
     
          foreach ($files as $file) {
           
               if ( is_file($file) ) {
                   
                    if ( time() - filemtime($file) >= (60 * 60 * 24 * $days) ) {
                    
                    $result = unlink($file);
                   
                    	if ( $result == false ) {
                    		
                    	$ct['gen']->log(
                    				'system_error',
                    				'File deletion failed for file "' . $file . '" (check permissions for "' . basename($file) . '")'
                    				);
                    	
                    	}
                   
                    }
                   
               }
            
          }
     
      }
  
  
  }
       
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function subarray_cached_ct_conf_upgrade($conf, $cat_key, $conf_key, $mode) {
   
   global $ct, $plug, $default_ct_conf;
        
        
        if ( is_array($conf[$cat_key][$conf_key]) ) {
        $orig_array_size = sizeof($conf[$cat_key][$conf_key]);
        }
        else {
        $orig_array_size = 0;
        }
   
   
        // New additions
        if ( $mode == 'new' ) {
             
             
           // Check for new variables, and add them
           foreach ( $default_ct_conf[$cat_key][$conf_key] as $setting_key => $setting_val ) {
        
              
              // Check $ct['conf']['plug_conf'][$this_plug] (activated plugins)...Uses === for PHPv7.4 support
              if ( $ct['plugin_upgrade_check'] && $cat_key === 'plugins' && $conf_key === 'plugin_status' && $conf[$cat_key][$conf_key][$setting_key] == 'on' ) {
                   
              $this_plug = $setting_key;
                           
                   
                   foreach ( $default_ct_conf['plug_conf'][$this_plug] as $plug_setting_key => $plug_setting_val ) {
                   
                      
                      // If setting doesn't exist yet, OR RESET FLAGGED
                      // (OR IT IS ***SPECIFICALLY*** SET TO NULL [WHICH PHP CONSIDERS NOT SET, BUT WE CONSIDER CORRUPT IN THE CACHED CONFIG SPEC])
                      if (
                      !isset($conf['plug_conf'][$this_plug][$plug_setting_key])
                      || is_array($ct['dev']['plugin_allow_resets'][$this_plug]) && array_key_exists($plug_setting_key, $ct['dev']['plugin_allow_resets'][$this_plug])
                      ) {
                           
                           
                           if ( !isset($conf['plug_conf'][$this_plug][$plug_setting_key]) ) {
                           $desc = 'NEW';
                           }
                           else {
                           $desc = 'RESET';
                           }

                      
                      $conf['plug_conf'][$this_plug][$plug_setting_key] = $default_ct_conf['plug_conf'][$this_plug][$plug_setting_key];
                       			
                      // Use DEFAULT config for ordering the PARENT array IN THE ORIGINAL ORDER
                      $conf['plug_conf'][$this_plug] = $ct['var']->assoc_array_order( $conf['plug_conf'][$this_plug], $ct['var']->assoc_array_order_map($default_ct_conf['plug_conf'][$this_plug]) );
                        
                      $ct['conf_upgraded'] = true;
                              
                      // Uses === / !== for PHPv7.4 support
                      $log_val_descr = ( $default_ct_conf['plug_conf'][$this_plug][$plug_setting_key] !== null || $default_ct_conf['plug_conf'][$this_plug][$plug_setting_key] !== false || $default_ct_conf['plug_conf'][$this_plug][$plug_setting_key] === 0 ? $default_ct_conf['plug_conf'][$this_plug][$plug_setting_key] : '[null / false / zero]' );
                           
                      // If we're resetting a subarray setting
                      $log_val_descr = ( is_array($default_ct_conf['plug_conf'][$this_plug][$plug_setting_key]) ? 'default array size: ' . sizeof($default_ct_conf['plug_conf'][$this_plug][$plug_setting_key]) : 'default value: ' . $ct['sec']->obfusc_str($log_val_descr, 4) );
                        
                      $ct['gen']->log(
                                  			'notify_error',
                                  			$desc . ' plugin config, SUBARRAY PARAMETER ct[conf][plug_conf][' . $this_plug . '][' . $plug_setting_key . '] imported (' . $log_val_descr . ')'
                                  			);
                           
                      }
                      
                   
                   }
                     
              
              }
              // Check everything else (IF IT'S THE FIRT RUN BEFORE ACTIVE PLUGINS UPGRADE CHECK)...
              ////
              // If DEFAULT $conf_key ARRAY KEYS ARE ***INTEGER-BASED OR AUTO-INDEXING***, AND ACTIVE / DEFAULT ARRAYS DON'T MATCH,
              // then import and check for duplicates after (for efficiency)
              else if (
              !$ct['plugin_upgrade_check']
              && !$ct['var']->has_string_keys($default_ct_conf[$cat_key][$conf_key])
              && md5(serialize($conf[$cat_key][$conf_key])) != md5(serialize($default_ct_conf[$cat_key][$conf_key]))
              ) {
                   
              $conf[$cat_key][$conf_key][] = $default_ct_conf[$cat_key][$conf_key][$setting_key];
                  
              // REMOVE DUPLICATES (MORE EFFICIENT THEN SEARCHING FOR THEM WHILE ADDING ITEMS...SO WE MAY HAVE DUPLICATED AN ENTRY WE SHOULDN'T HAVE)
              $conf[$cat_key][$conf_key] = array_intersect_key( $conf[$cat_key][$conf_key] , array_unique( array_map('serialize' , $conf[$cat_key][$conf_key] ) ) );
                  
              // WE DON'T NEED ORDERING HERE, AS IT'S ARRAY KEYS ARE ***INTEGER-BASED OR AUTO-INDEXING***
              // (we don't care about ordering here "under the hood", only in the UI [maybe])
                        
              $ct['conf_upgraded'] = true;
                  
              $no_string_keys = true;
                   
                  
              }
              // If ACTIVE (NOT DEFAULT) setting doesn't exist yet, ***ONLY IF*** DEFAULT $conf_key ARRAY KEYS ARE ***STRING-BASED***
              // (WE ALREADY CHECK IF BOTH ACTIVE AND DEFAULT $conf_key ARE STRING-BASED IN upgrade_cached_ct_conf() BEFOREHAND)
              // (DEFAULT SETTING CAN BE ANOTHER SUBARRAY WITHIN THE PARENT SUBARRAY)
              // (IF THE VALUE IS ***SPECIFICALLY*** SET TO NULL [WHICH PHP CONSIDERS NOT SET], WE CONSIDER IT CORRUPT [FOR UPGRADE COMPATIBILITY], AND WE UPGRADE IT)
              else if (
              !$ct['plugin_upgrade_check']
              && $ct['var']->has_string_keys($default_ct_conf[$cat_key][$conf_key])
              && !isset($conf[$cat_key][$conf_key][$setting_key])
              ) {
              			
              $conf[$cat_key][$conf_key][$setting_key] = $default_ct_conf[$cat_key][$conf_key][$setting_key];
                  			
              // Use DEFAULT config for ordering the PARENT array IN THE ORIGINAL ORDER
              $conf[$cat_key][$conf_key] = $ct['var']->assoc_array_order( $conf[$cat_key][$conf_key], $ct['var']->assoc_array_order_map($default_ct_conf[$cat_key][$conf_key]) );
                   
              $ct['conf_upgraded'] = true;
                         
              // Uses === / !== for PHPv7.4 support
              $log_val_descr = ( $default_ct_conf[$cat_key][$conf_key][$setting_key] !== null || $default_ct_conf[$cat_key][$conf_key][$setting_key] !== false || $default_ct_conf[$cat_key][$conf_key][$setting_key] === 0 ? $default_ct_conf[$cat_key][$conf_key][$setting_key] : '[null / false / zero]' );
                   
              $ct['gen']->log(
                        		'notify_error',
                        		'NEW app config, *STRING INDEXED* SUBARRAY PARAMETER ct[conf][' . $cat_key . '][' . $conf_key . '][' . $setting_key . '] imported (default value: ' . $ct['sec']->obfusc_str($log_val_descr, 4) . ')'
                        		);
              
              }
              
                 
           }
           
           
        }
        // Depreciated
        else if ( $mode == 'depreciated' ) {
        
           
           // Check for depreciated variables, and remove them
           foreach ( $conf[$cat_key][$conf_key] as $setting_key => $setting_val ) {
        
              
              // Check $ct['conf']['plug_conf'][$this_plug] (activated plugins)
              if ( $ct['plugin_upgrade_check'] && $cat_key === 'plugins' && $conf_key === 'plugin_status' && $conf[$cat_key][$conf_key][$setting_key] == 'on' ) {
                   
              $this_plug = $setting_key;
                   
                   
                   foreach ( $conf['plug_conf'][$this_plug] as $plug_setting_key => $plug_setting_val ) {
                   
                      if ( !isset($default_ct_conf['plug_conf'][$this_plug][$plug_setting_key]) ) {
                      
                      unset($conf['plug_conf'][$this_plug][$plug_setting_key]);
                   
                      $ct['conf_upgraded'] = true;
                   
                      $ct['gen']->log(
                             			'notify_error',
                             			'NON-EXISTANT plugin config, SUBARRAY PARAMETER ct[conf][plug_conf][' . $this_plug . '][' . $plug_setting_key . '] removed'
                             			);
                   
                      }
                   
                   }
              
              
              }
              // Check everything else (IF IT'S THE FIRT RUN BEFORE ACTIVE PLUGINS UPGRADE CHECK)...
              // (ONLY ALLOW REMOVAL OF STRING-BASED ARRAY KEYS [WE'RE BLIND FOR NOW ON NUMERIC / AUTO-INDEXING KEYS, UNLESS LOGIC IS BUILT TO SAFELY CHECK THAT])
              else if (
              !$ct['plugin_upgrade_check']
              && $ct['var']->has_string_keys($default_ct_conf[$cat_key][$conf_key])
              && !isset($default_ct_conf[$cat_key][$conf_key][$setting_key])
              ) {
              			
              unset($conf[$cat_key][$conf_key][$setting_key]);
                   
              $ct['conf_upgraded'] = true;
                   
              $ct['gen']->log(
                        	     'notify_error',
                        		'NON-EXISTANT app config, SUBARRAY PARAMETER ct[conf][' . $cat_key . '][' . $conf_key . '][' . $setting_key . '] removed'
                        		);
              
              }
                 
                 
           }
           
        
        }
   
        
        if ( is_array($conf[$cat_key][$conf_key]) ) {
        $new_array_size = sizeof($conf[$cat_key][$conf_key]);
        }
        else {
        $new_array_size = 0;
        }
   
   
   $array_size_change = $new_array_size - $orig_array_size;
   
   
        // Logs for upgrades to integer-based / auto-indexed subarrays
        if ( $no_string_keys && $array_size_change > 0 ) {
             
        $ct['gen']->log(
                             		'notify_error',
                             		'NEW app config, *AUTO/INTEGER INDEXED* SUBARRAY PARAMETERS for ct[conf][' . $cat_key . '][' . $conf_key . '] imported (new array size: ' . $new_array_size . ' [+'.$array_size_change.'])'
                             		);
                             		
        }
        elseif ( $no_string_keys && $array_size_change < 0 ) {
             
        $ct['gen']->log(
                             		'notify_error',
                             		'NON-EXISTANT app config, *AUTO/INTEGER INDEXED* SUBARRAY PARAMETERS for ct[conf][' . $cat_key . '][' . $conf_key . '] removed (new array size: ' . $new_array_size . ' ['.$array_size_change.'])'
                             		);
        }
        
      
   return $conf;
      
   }
   
  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function htaccess_dir_protection() {
  
  global $ct, $htaccess_username, $htaccess_password;
  
  $valid_username = $ct['gen']->valid_username($htaccess_username);
  
  // Password must be exactly 8 characters long for good htaccess security (htaccess only checks the first 8 characters for a match)
  $password_strength = $ct['sec']->pass_strength($htaccess_password, 8, 8); 
  
  
      if ( $htaccess_username == '' || $htaccess_password == '' ) {
      return false;
      }
      elseif ( $valid_username != 'valid' ) {
      	
      $ct['gen']->log(
      			'security_error',
      			'"interface_login" USERNAME does not meet minimum requirements: ' . $valid_username
      			);
      
      return false;
      
      }
      elseif ( $password_strength != 'valid' ) {
      	
      $ct['gen']->log(
      			'security_error',
      			'"interface_login" PASSWORD does not meet minimum requirements: ' . $password_strength
      			);
      
      return false;
      
      }
      else {
      
      // WORKS IN LINUX ***AND WINDOWS TOO***
      // (temp var name, OR WE OVERWRITE THE GLOBAL VAR!!)
      $htaccess_password_temp = password_hash($htaccess_password, PASSWORD_DEFAULT);
      
      $password_set = $this->save_file($ct['base_dir'] . '/cache/secured/.app_htpasswd', $htaccess_username . ':' . $htaccess_password_temp);
      
       	if ( $password_set == true ) {
       
       	$htaccess_contents = $this->php_timeout_defaults($ct['base_dir'] . '/templates/back-end/root-app-directory-htaccess.template') . 
        preg_replace("/\[BASE_DIR\]/i", $ct['base_dir'], file_get_contents($ct['base_dir'] . '/templates/back-end/enable-password-htaccess.template') );
      
       	$htaccess_set = $this->save_file($ct['base_dir'] . '/.htaccess', $htaccess_contents);
      
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
    Usage: $last_line = $this->tail_custom($file_path);
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
  
  global $ct;
  
     
     // If there is no throttling profile, skip / return false
     if ( !isset($ct['dev']['throttle_limited_servers'][$tld_or_ip]) ) {
     return false;
     }
     
  
  // We wait until we are in this function, to grab any cached data at the last minute,
  // to assure we get anything written recently by other runtimes
  
  // SAFE filename
  $file_save_path = $ct['base_dir'] . '/cache/events/throttling/' . $ct['gen']->compat_file_name($tld_or_ip) . '.dat';
  
  $api_throttle_count_check = json_decode( trim( file_get_contents($file_save_path) ) , true);
  
  
     // If we haven't initiated yet this runtime, AND there is ALREADY valid data cached, import it as the $ct['api_throttle_count'] array
     if ( !isset($ct['api_throttle_flag']['init'][$tld_or_ip]) && $api_throttle_count_check != false && $api_throttle_count_check != null && $api_throttle_count_check != "null" ) {
     $ct['api_throttle_count'][$tld_or_ip] = $api_throttle_count_check;
     }
     
     
     $ct['api_throttle_flag']['init'][$tld_or_ip] = true; // Flag as initiated this runtime (AFTER above logic)

     
     // Set OR reset DAY start time / counts, if needed
     // (SECONDS ARE NOT NEEDED, AS WE CAN JUST SLEEP 1 SECOND DURING THIS RUNTIME TO THROTTLE)
     if (
     isset($ct['throttled_api_per_day_limit'][$tld_or_ip]) && !isset($ct['api_throttle_count'][$tld_or_ip]['day_count']['start'])
     || isset($ct['api_throttle_count'][$tld_or_ip]['day_count']['start']) && $ct['api_throttle_count'][$tld_or_ip]['day_count']['start'] <= ( time() - 86400 )
     ) {
     $ct['api_throttle_count'][$tld_or_ip]['day_count']['start'] = time();
     $ct['api_throttle_count'][$tld_or_ip]['day_count']['count'] = 0;
     }

     
     // Set OR reset MINUTE start time / counts, if needed
     // (SECONDS ARE NOT NEEDED, AS WE CAN JUST SLEEP 1 SECOND DURING THIS RUNTIME TO THROTTLE)
     if (
     isset($ct['throttled_api_per_minute_limit'][$tld_or_ip]) && !isset($ct['api_throttle_count'][$tld_or_ip]['minute_count']['start'])
     || isset($ct['api_throttle_count'][$tld_or_ip]['minute_count']['start']) && $ct['api_throttle_count'][$tld_or_ip]['minute_count']['start'] <= ( time() - 60 )
     ) {
     $ct['api_throttle_count'][$tld_or_ip]['minute_count']['start'] = time();
     $ct['api_throttle_count'][$tld_or_ip]['minute_count']['count'] = 0;
     }
     
     
     // Thresholds for API servers (we throttle-limit, to have reliable LIVE data EVERY HOUR OF THE DAY) 
     // (ALL WE DO HERE BESIDES CACHING JSON RESULTS, IS RETURN TRUE / FALSE FOR $ct['api_throttle_flag'][$tld_or_ip] *AND* THE FUNCTION CALL)
     
     
     // Limits met, return TRUE
     if (
     isset($ct['throttled_api_per_minute_limit'][$tld_or_ip]) && $ct['api_throttle_count'][$tld_or_ip]['minute_count']['count'] >= $ct['throttled_api_per_minute_limit'][$tld_or_ip]
     || isset($ct['throttled_api_per_day_limit'][$tld_or_ip]) && $ct['api_throttle_count'][$tld_or_ip]['day_count']['count'] >= $ct['throttled_api_per_day_limit'][$tld_or_ip]
     ) {
     return true;
     }
     // Limits NOT met, up counts, return FALSE
     elseif (
     isset($ct['throttled_api_per_second_limit'][$tld_or_ip])
     || isset($ct['throttled_api_per_minute_limit'][$tld_or_ip])
     || isset($ct['throttled_api_per_day_limit'][$tld_or_ip])
     ) {
         
         
         // PER-DAY count
         if ( isset($ct['throttled_api_per_day_limit'][$tld_or_ip]) ) {
         $ct['api_throttle_count'][$tld_or_ip]['day_count']['count'] = $ct['api_throttle_count'][$tld_or_ip]['day_count']['count'] + 1;
         $save_limit_counts = true;         
         }
         
         
         // PER-MINUTE count
         if ( isset($ct['throttled_api_per_minute_limit'][$tld_or_ip]) ) {
         $ct['api_throttle_count'][$tld_or_ip]['minute_count']['count'] = $ct['api_throttle_count'][$tld_or_ip]['minute_count']['count'] + 1;
         $save_limit_counts = true; 
         }
 
         
         // We only store to cached file, if there is a limit count updated
         if ( $save_limit_counts ) {
         $store_api_throttle_count = json_encode($ct['api_throttle_count'][$tld_or_ip], JSON_PRETTY_PRINT);
         $store_file_contents = $this->save_file($file_save_path, $store_api_throttle_count);
         }
         
         
         // FOR VALID PER-SECOND LIMITS, WE CAN JUST USE USLEEP DURING THIS RUNTIME,
         // TO PREFORM THE REQUIRED THROTTLING FOR THIS SERVER (NO NEED TO TALLY A REQUEST COUNTER)
         if (
         isset($ct['throttled_api_per_second_limit'][$tld_or_ip])
         && $ct['throttled_api_per_second_limit'][$tld_or_ip] >= 1
         ) {
              
              // Cap per-second throttle to 1 million, to support our usleep auto-calculation logic
              if ( $ct['throttled_api_per_second_limit'][$tld_or_ip] > 1000000 ) {
              $ct['throttled_api_per_second_limit'][$tld_or_ip] = 1000000;
              }
         
         $sleep_microseconds = (1 / $ct['throttled_api_per_second_limit'][$tld_or_ip]) * 1000000;
         
         // Assure any large number is NON-scientific format (and NO DECIMALS!)
         $sleep_microseconds = $ct['var']->num_to_str( round($sleep_microseconds, 0) );
         
         // https://www.php.net/manual/en/function.usleep.php
         usleep($sleep_microseconds);

         }
         // We ALWAYS want at least a small amount of sleep, IF WE UPDATED THE DAY / MINUTE COUNT CACHE FILE
         // (since we may still have consecutive counts for this server, during this runtime)
         elseif ( $save_limit_counts ) {
         usleep(100000); // Wait 0.1 seconds, since we just re-saved the count cache file
         }

     
     return false;
     
     }
  
  
  }
  
  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function backup_archive($backup_prefix, $backup_target, $interval, $password='no') {
  
  global $ct;
  
  
	  // With offset, to try keeping daily recurrences at same exact runtime (instead of moving up the runtime daily)
      if ( $this->update_cache($ct['base_dir'] . '/cache/events/backup-'.$backup_prefix.'.dat', ( $interval * 1440 ) + $ct['dev']['tasks_time_offset'] ) == true ) {
     
      $secure_128bit_hash = $ct['sec']->rand_hash(16); // 128-bit (16-byte) hash converted to hexadecimal, used for suffix
      
      
          // We only want to store backup files with suffixes that can't be guessed, 
          // otherwise halt the application if an issue is detected safely creating a random hash
          if ( $secure_128bit_hash == false ) {
          	
          $ct['gen']->log(
          			'security_error',
          			'Cryptographically secure pseudo-random bytes could not be generated for ' . $backup_prefix . ' backup archive filename suffix, backup aborted to preserve backups directory privacy'
          			);
          
          }
          else {
           
          $backup_file = $backup_prefix . '_' . $ct['year_month_day'] . '_' . $secure_128bit_hash.'.zip';
          $backup_dest = $ct['base_dir'] . '/cache/secured/backups/' . $backup_file;
           
           
              // Zip archive
              if ( is_dir($backup_target) || is_file($backup_target) ) {
              $backup_results = $ct['ext_zip']->zip_recursively($backup_target, $backup_dest, $password, ZipArchive::CREATE);
              }
              else {
              
              $ct['gen']->log(
          			'other_error',
          			'zip file backup target "'.$backup_target.'" does NOT exist'
          			);
          
              }
           
           
              if ( $backup_results == 'done' ) {
               
              $this->save_file($ct['base_dir'] . '/cache/events/backup-'.$backup_prefix.'.dat', $ct['gen']->time_date_format(false, 'pretty_date_time') );
               
              $backup_url = $ct['base_url'] . 'download.php?backup=' . $backup_file;
              
              $msg = "A backup archive has been created for: ".$backup_prefix."\n\nHere is a link to download the backup to your computer:\n\n" . $backup_url . "\n\n(backup archives are purged after " . $ct['conf']['power']['backup_archive_delete_old'] . " days)";
              
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
              $ct['gen']->log('system_error', 'Backup zip archive creation failed with ' . $backup_results);
              }
            
          
          }
      
     
      }
  
  
  }
  
  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function queue_notify($send_params) {
  
  global $ct;
  
     
     // Abort queueing comms for sending out notifications, if allowing comms is disabled
     if ( $ct['conf']['comms']['allow_comms'] != 'on' ) {
     return;
     }
  
  
   // Queue messages
   
   // RANDOM HASH SHOULD BE CALLED PER-STATEMENT, OTHERWISE FOR SOME REASON SEEMS TO REUSE SAME HASH FOR THE WHOLE RUNTIME INSTANCE (if set as a variable beforehand)

   
     // Notifyme
     if ( isset($send_params['notifyme']) && $send_params['notifyme'] != '' && $ct['notifyme_activated'] ) {
   	 $this->save_file($ct['base_dir'] . '/cache/secured/messages/notifyme-' . $ct['sec']->rand_hash(8) . '.queue', $send_params['notifyme']);
     }

   
     // Telegram
     if ( isset($send_params['telegram']) && $send_params['telegram'] != '' && $ct['telegram_activated'] ) {
     $this->save_file($ct['base_dir'] . '/cache/secured/messages/telegram-' . $ct['sec']->rand_hash(8) . '.queue', $send_params['telegram']);
     }
    
    
     // SMS service
     if ( isset($send_params['text']['message']) && $send_params['text']['message'] != '' && $ct['sms_service'] == 'twilio' ) { 
     $this->save_file($ct['base_dir'] . '/cache/secured/messages/twilio-' . $ct['sec']->rand_hash(8) . '.queue', $send_params['text']['message']);
     }
     elseif ( isset($send_params['text']['message']) && $send_params['text']['message'] != '' && $ct['sms_service'] == 'textbelt' ) { 
     $this->save_file($ct['base_dir'] . '/cache/secured/messages/textbelt-' . $ct['sec']->rand_hash(8) . '.queue', $send_params['text']['message']);
     }
     elseif ( isset($send_params['text']['message']) && $send_params['text']['message'] != '' && $ct['sms_service'] == 'textlocal' ) { 
     $this->save_file($ct['base_dir'] . '/cache/secured/messages/textlocal-' . $ct['sec']->rand_hash(8) . '.queue', $send_params['text']['message']);
     }
     elseif ( isset($send_params['text']['message']) && $send_params['text']['message'] != '' && $ct['sms_service'] == 'email_gateway' && $ct['email_activated'] ) { 
     
     // $send_params['text_charset'] SHOULD ALWAYS BE SET FROM THE CALL TO HERE (for emojis, or other unicode characters to send via text message properly)
     // SUBJECT !!MUST BE SET!! OR SOME TEXT SERVICES WILL NOT ACCEPT THE MESSAGE!
     $textemail_array = array('subject' => 'Text Notify', 'message' => $send_params['text']['message'], 'content_type' => 'text/plain', 'charset' => $send_params['text']['charset'] );
     
      	// json_encode() only accepts UTF-8, SO TEMPORARILY CONVERT TO THAT FOR MESSAGE QUEUE STORAGE
      	if ( strtolower($send_params['text']['charset']) != 'utf-8' ) {
      	 
      	 	foreach( $textemail_array as $textemail_key => $textemail_val ) {
      	 	$textemail_array[$textemail_key] = mb_convert_encoding($textemail_val, 'UTF-8', mb_detect_encoding($textemail_val, "auto") );
      	 	}
      
      	}
     
     $this->save_file($ct['base_dir'] . '/cache/secured/messages/textemail-' . $ct['sec']->rand_hash(8) . '.queue', json_encode($textemail_array) );
   
     }
     
            
     // Normal email
     if ( isset($send_params['email']['message']) && $send_params['email']['message'] != '' && $ct['email_activated'] ) {
     
     $email_array = array('subject' => $send_params['email']['subject'], 'message' => $send_params['email']['message'], 'content_type' => ( $send_params['email']['content_type'] ? $send_params['email']['content_type'] : 'text/plain' ), 'charset' => ( $send_params['email']['charset'] ? $send_params['email']['charset'] : $ct['dev']['charset_default'] ) );
     
      	// json_encode() only accepts UTF-8, SO TEMPORARILY CONVERT TO THAT FOR MESSAGE QUEUE STORAGE
      	if ( strtolower($send_params['email']['charset']) != 'utf-8' ) {
      	 
      	 	foreach( $email_array as $email_key => $email_val ) {
      	 	$email_array[$email_key] = mb_convert_encoding($email_val, 'UTF-8', mb_detect_encoding($email_val, "auto") );
      	 	}
      
      	}
     
   	 $this->save_file($ct['base_dir'] . '/cache/secured/messages/normalemail-' . $ct['sec']->rand_hash(8) . '.queue', json_encode($email_array) );
   
     }
    
  
  }
  
  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function show_access_stats() {
  
  global $ct;
  
      if ( $ct['sec']->admin_logged_in() == false ) {
      return false;
      }
      
  
  $access_stats_files = $ct['gen']->sort_files($ct['base_dir'] . '/cache/secured/access_stats', 'dat', 'desc');
  
       
       // Bundle / organize ALL the stats into an array (for rendering AFTER)
       foreach( $access_stats_files as $ip_access_file ) {
     
       // Counting for url / user agent visits (PER-IP)
       $url_visit_count = array();
       $user_agent_visit_count = array();    
        
       $fn = fopen($ct['base_dir'] . '/cache/secured/access_stats/' . $ip_access_file, "r");
          
          
           while( !feof($fn) )  {
           
           $result = explode("||", fgets($fn) );
           
           $result = array_map('trim', $result); // Trim whitespace out of all array values
           
           
               if ( !isset($result[4]) ) {
               continue; // Skip this loop
               }
           
           
           $time = $result[0];
           
           $url = ( $result[1] == 'NO_DATA' ? 'None' : $ct['gen']->pretty_app_uri(false, true, $result[1]) );
           
           $ip = $result[2];
           
           $agent = $result[3];
           
           $referrer = ( $result[4] == 'NO_DATA' ? 'None' : $ct['gen']->pretty_app_uri(false, true, $result[4]) );
           
           // PER-IP OR BUNDLED RESULTS
           $results_array_keyed_by = ( $_GET['mode'] == 'bundled' ? 'all' : $ip );
               
           $safe_name = $ct['gen']->compat_file_name($results_array_keyed_by);
               
           $ct['show_access_stats'][$safe_name]['ip'] = $results_array_keyed_by;

           
               if ( !isset($ct['show_access_stats'][$safe_name]['total_visits_count']) ) {
               $ct['show_access_stats'][$safe_name]['total_visits_count'] = 1;
               }
               else {
               $ct['show_access_stats'][$safe_name]['total_visits_count'] = $ct['show_access_stats'][$safe_name]['total_visits_count'] + 1;
               }
                     
               
               if ( $_GET['mode'] == 'bundled' ) {
               $ct['show_access_stats'][$safe_name]['visited_pages'][ md5($url) ]['last_ip'] = $ip;
               }
               
                     
           $ct['show_access_stats'][$safe_name]['visited_pages'][ md5($url) ]['last_visit'] = $time;
                         
           $ct['show_access_stats'][$safe_name]['visited_pages'][ md5($url) ]['last_referrer'] = $referrer;
                         
           $ct['show_access_stats'][$safe_name]['visited_pages'][ md5($url) ]['url'] = $url;
         
                         
               // For UX, we only want one table row per-page (no duplicates same page)
               // (an we just include page / user agent counts in this row)
               // $time is the visited timestamp, we only want the MOST RECENT
               if ( !isset($url_visit_count[ md5($url) ]) ) {
               $url_visit_count[ md5($url) ] = 1;
               }
               // URL visits
               else {
               $url_visit_count[ md5($url) ] = $url_visit_count[ md5($url) ] + 1;
               }
                         
                    
           // Add to $ct['show_access_stats']
           $ct['show_access_stats'][$safe_name]['ip_url_visits'][ md5($url) ] = $url_visit_count[ md5($url) ];
                         
                         
               // Add the user agent name, IF not added yet
               if ( !isset($user_agent_visit_count[ md5($url) ][ md5($agent) ]) ) {
                              
               $ct['show_access_stats'][$safe_name]['user_agents'][ md5($url) ][ md5($agent) ] = $agent;
                         
               $user_agent_visit_count[ md5($url) ][ md5($agent) ] = 1;
                         
               }
               // User agent visits
               else {
               $user_agent_visit_count[ md5($url) ][ md5($agent) ] = $user_agent_visit_count[ md5($url) ][ md5($agent) ] + 1;
               }
                         
                    
           // Add to $ct['show_access_stats']
           $ct['show_access_stats'][$safe_name]['ip_user_agent_visits'][ md5($url) ][ md5($agent) ] = $user_agent_visit_count[ md5($url) ][ md5($agent) ];
           
           }
   
   
       fclose($fn);
       
       gc_collect_cycles(); // Clean memory cache
                    
       }
      
      
      // Alphabetically sort PER-IP access stats by 'total_visits_count'
      // We need to use uasort, instead of usort, to maintain the associative array structure
      if ( $_GET['mode'] == 'ip' ) {
      $ct['sort_by_nested'] = 'root=>total_visits_count';
      uasort($ct['show_access_stats'], array($ct['var'], 'usort_desc') );
      $ct['sort_by_nested'] = false; // RESET
      }

      
      // Render the stats
      foreach ( $ct['show_access_stats'] as $key => $val ) {
           
      // PER-IP OR BUNDLED RESULTS
      $results_array_keyed_by = ( $_GET['mode'] == 'bundled' ? 'all' : $val['ip'] );
      
      $safe_name = $ct['gen']->compat_file_name($results_array_keyed_by);
           
      ?>

          <fieldset class='subsection_fieldset'>
               
               <legend class='subsection_legend'> 
               
               <?php
               if ( $_GET['mode'] == 'bundled' ) {
               ?>
               ALL (<?=$val['total_visits_count']?> visits) 
               <?php
               }
               else {
               ?>
               IP Address: <?=$val['ip']?> (<?=$val['total_visits_count']?> visits<?=( $val['ip'] == $ct['remote_ip'] ? ' <span class="bitcoin">[YOUR current address]</span>' : '' )?>) 
               <?php
               }
               ?>
               
               </legend>
               
               <?=$ct['gen']->table_pager_nav($safe_name . '_ip', 'access_stats')?>
               
               <table id='<?=$safe_name?>_ip' border='0' cellpadding='10' cellspacing='0' class="access_stats data_table align_center" style='width: 100% !important;'>
                <thead>
                   <tr>
                    <th class="filter-match" data-placeholder="Filter Results">Last Visit</th>
                    <th class="filter-match" data-placeholder="Filter Results">Total Visits</th>
                    <?php
                    if ( $_GET['mode'] == 'bundled' ) {
                    ?>
                    <th class="filter-match" data-placeholder="Filter Results">Last IP Address</th>
                    <?php
                    }
                    ?>
                    <th class="filter-match" data-placeholder="Filter Results">URL</th>
                    <th class="filter-match" data-placeholder="Filter Results">Last Referrer <span class='bitcoin'>(CAN BE SPOOFED!)</span></th>
                    <th class="filter-match" data-placeholder="Filter Results">User Agents <span class='bitcoin'>(CAN BE SPOOFED!)</span></th>
                   </tr>
                 </thead>
                 
                <tbody>
                   
                   <?php
                   foreach ( $ct['show_access_stats'][$key]['visited_pages'] as $visited_pages ) {
                   ?>
                   
                   <tr>
                   
                     <td><?=date("Y-m-d H:i:s", $visited_pages['last_visit'])?></td>
                     <td> <?=$ct['show_access_stats'][$key]['ip_url_visits'][ md5($visited_pages['url']) ]?> </td>
                    <?php
                    if ( $_GET['mode'] == 'bundled' ) {
                    ?>
                     <td> <?=$visited_pages['last_ip']?> </td>
                    <?php
                    }
                    ?>
                     <td style='word-break: break-all;'> <?=$visited_pages['url']?> </td>
                     <td style='word-break: break-all;'> <?=$visited_pages['last_referrer']?> </td>
                     <td> 
                     
                     <?php
                     foreach ( $ct['show_access_stats'][$key]['user_agents'][ md5($visited_pages['url']) ] as $user_agent_key => $user_agent_val ) {
                     
                     
                         // Known user agents (for description in the interface)
                         if ( stristr($user_agent_val, 'googlebot') ) {
                         $user_agent_desc = 'GoogleBot';
                         }
                         elseif ( stristr($user_agent_val, 'bingbot') ) {
                         $user_agent_desc = 'BingBot';
                         }
                         elseif ( stristr($user_agent_val, 'slurp') ) {
                         $user_agent_desc = 'YahooSlurpBot';
                         }
                         elseif ( stristr($user_agent_val, 'firefox/') ) {
                         $user_agent_desc = 'FireFox';
                         }
                         elseif ( stristr($user_agent_val, 'edge/') ) {
                         $user_agent_desc = 'Edge';
                         }
                         elseif ( stristr($user_agent_val, 'epiphany/') ) {
                         $user_agent_desc = 'Epiphany';
                         }
                         elseif ( stristr($user_agent_val, 'brave/') ) {
                         $user_agent_desc = 'Brave';
                         }
                         elseif ( stristr($user_agent_val, 'opera/') ) {
                         $user_agent_desc = 'Opera';
                         }
                         elseif ( stristr($user_agent_val, 'chrome/') ) {
                         $user_agent_desc = 'Chrome';
                         }
                         elseif ( stristr($user_agent_val, 'safari/') ) {
                         $user_agent_desc = 'WebKit/Safari';
                         }
                         elseif ( stristr($user_agent_val, 'curl/') ) {
                         $user_agent_desc = 'Curl';
                         }
                         else {
                         $user_agent_desc = 'Other';
                         }
                     
                     
                         // Known operating systems (for description in the interface)
                         if ( stristr($user_agent_val, 'android') ) {
                         $os_desc = 'Android';
                         }
                         elseif ( stristr($user_agent_val, 'iphone') ) {
                         $os_desc = 'iPhone';
                         }
                         elseif ( stristr($user_agent_val, 'linux') ) {
                         $os_desc = 'Linux';
                         }
                         elseif ( stristr($user_agent_val, 'macintosh') ) {
                         $os_desc = 'Macintosh';
                         }
                         elseif ( stristr($user_agent_val, 'windows') ) {
                         $os_desc = 'Windows';
                         }
                         else {
                         $os_desc = 'Other';
                         }
                         
                         
                     ?>
                     
                     <p style='border: 0.05em solid #808080; padding: 0.25em; border-radius: 0.4em; margin-bottom: 0px !important; margin: 0.25em !important;'>
                     
                     <?=$ct['show_access_stats'][$key]['ip_user_agent_visits'][ md5($visited_pages['url']) ][ md5($user_agent_val) ]?> visit(s) from <a style='cursor: pointer;' class='<?=( $user_agent_desc != 'Other' ? 'green' : 'bitcoin' )?>' title='<?=htmlspecialchars($ct['show_access_stats'][$key]['user_agents'][ md5($visited_pages['url']) ][ md5($user_agent_val) ], ENT_QUOTES)?>'><?=$user_agent_desc?></a> (<?=$os_desc?> OS)
                     
                     </p>
                     
                     <?php
                     }
                     ?>
                     
                     </td>
                   
                   </tr>
                   
                   <?php
                   }
                   ?>

                </tbody>
                </table>
               
           
          </fieldset>

      <?php
      }

  
  }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   // Check to see if we need to upgrade the app config (add new primary vars / remove depreciated primary vars)
   function upgrade_cached_ct_conf($conf=false) {
   
   global $ct, $check_default_ct_conf, $default_ct_conf, $admin_general_success;
   
   // Check that the config is valid / not corrupt FOR FUTURE JSON FILE STORAGE
   $test_conf = json_encode($conf, JSON_PRETTY_PRINT);
    	
    	
      // If there was an issue testing it converted to json format
    	 // Need to check a few different possible results for no data found ("null" in quotes as the actual value is returned sometimes)
      if ( $test_conf == '' || $test_conf == false || $test_conf == null || $test_conf == "null" ) {
      
      $ct['gen']->log(
                   	  'conf_error',
                   	  'no valid config passed to upgrade_cached_ct_conf(config)'
                   	 );
                   			
      return false;
      
      }
      else {
      $ct['gen']->log('notify_error', ( $ct['plugin_upgrade_check'] ? 'ACTIVE PLUGINS UPDATE check flagged, checking ALL plugins now' : 'MAIN CONFIG ' . $ct['db_upgrade_desc']['app'] . ' check flagged, checking now' ) );
      }
                   	 
         
      // Check for new variables, and add them
      foreach ( $default_ct_conf as $cat_key => $cat_val ) {
           
                
           // We don't process anything in 'plug_conf' 
           if ( $cat_key === 'plug_conf' ) { // Uses === for PHPv7.4 support
           continue;
           }   
           // If category not set yet, or reset on this category is flagged (and it's not the SECOND upgrade check for active registered plugins)
           else if ( !isset($conf[$cat_key]) || array_key_exists($cat_key, $ct['dev']['config_allow_resets']) && !$ct['plugin_upgrade_check'] ) {
                    
                if ( !isset($conf[$cat_key]) ) {
                $desc = 'NEW';
                }
                else {
                $desc = 'RESET';
                }
                    
           $conf[$cat_key] = $default_ct_conf[$cat_key];
                  			
           // Use DEFAULT config for ordering the PARENT array IN THE ORIGINAL ORDER
           $conf = $ct['var']->assoc_array_order( $conf, $ct['var']->assoc_array_order_map($default_ct_conf) );
                  						
           $ct['conf_upgraded'] = true;
                  
           $ct['gen']->log(
                  	       'notify_error',
                  		  $desc . ' app config CATEGORY ct[conf][' . $cat_key . '] imported (default array size: ' . sizeof($default_ct_conf[$cat_key]) . ')'
                  		 );
           
           // Since we just overwrote the ENTIRE category's settings, we can safely skip per-setting checks
           continue;
           
           }
                  	
           
           // Setting keys
           foreach ( $cat_val as $conf_key => $conf_val ) {
         
               
               // If subarray setting (NOT queued to be RESET), AND ARRAY KEY TYPE MATCHES
               if (
               is_array($conf[$cat_key][$conf_key]) && !array_key_exists($conf_key, $ct['dev']['config_allow_resets'])
               && $ct['var']->has_string_keys($default_ct_conf[$cat_key][$conf_key]) && $ct['var']->has_string_keys($conf[$cat_key][$conf_key])
               || is_array($conf[$cat_key][$conf_key]) && !array_key_exists($conf_key, $ct['dev']['config_allow_resets'])
               && !$ct['var']->has_string_keys($default_ct_conf[$cat_key][$conf_key]) && !$ct['var']->has_string_keys($conf[$cat_key][$conf_key])
               ) {
           
                    if (
                    // If not in 'config_deny_additions'
                    !in_array($cat_key, $ct['dev']['config_deny_additions']) && !in_array($conf_key, $ct['dev']['config_deny_additions'])
                    // If plugin status (we handle whitelisting for this in subarray_cached_ct_conf_upgrade())
                    // (WE CHECK $ct['plugin_upgrade_check'] IN subarray_cached_ct_conf_upgrade() FOR CODE READABILITY)
                    || $cat_key === 'plugins' && $conf_key === 'plugin_status' // Uses === for PHPv7.4 support
                    ) {
                    $conf = $this->subarray_cached_ct_conf_upgrade($conf, $cat_key, $conf_key, 'new');
                    }
                    
               }
               // If regular setting, RESET on a subarray setting, OR array key type does NOT match
               else if (
               // If we are allowed to add settings in this category, and the setting doesn't exist
               // (OR IT IS ***SPECIFICALLY*** SET TO NULL [WHICH PHP CONSIDERS NOT SET, BUT WE CONSIDER CORRUPT IN THE CACHED CONFIG SPEC])
               !in_array($cat_key, $ct['dev']['config_deny_additions']) && !isset($conf[$cat_key][$conf_key])
               // If reset on a subarray is flagged (and it's not the SECOND upgrade check for active registered plugins)
               || !$ct['plugin_upgrade_check'] && is_array($conf[$cat_key][$conf_key]) && array_key_exists($conf_key, $ct['dev']['config_allow_resets'])
               // If we UPGRADED to using integer-based / auto-index array keys (for better admin interface compatibility...and it's not the SECOND upgrade check for active registered plugins)
               || !$ct['plugin_upgrade_check'] && is_array($conf[$cat_key][$conf_key]) && !$ct['var']->has_string_keys($default_ct_conf[$cat_key][$conf_key]) && $ct['var']->has_string_keys($conf[$cat_key][$conf_key])
               // If we DOWNGRADED from using integer-based / auto-index array keys (downgrading to an OLDER version of the app etc...and it's not the SECOND upgrade check for active registered plugins)
               || !$ct['plugin_upgrade_check'] && is_array($conf[$cat_key][$conf_key]) && $ct['var']->has_string_keys($default_ct_conf[$cat_key][$conf_key]) && !$ct['var']->has_string_keys($conf[$cat_key][$conf_key])
               ) {
                    
                    if ( !isset($conf[$cat_key][$conf_key]) ) {
                    $desc = 'NEW';
                    }
                    elseif ( !$ct['var']->has_string_keys($default_ct_conf[$cat_key][$conf_key]) && $ct['var']->has_string_keys($conf[$cat_key][$conf_key]) ) {
                    $desc = 'CONVERTED (UPGRADE)';
                    }
                    elseif ( $ct['var']->has_string_keys($default_ct_conf[$cat_key][$conf_key]) && !$ct['var']->has_string_keys($conf[$cat_key][$conf_key]) ) {
                    $desc = 'CONVERTED (DOWNGRADE)';
                    }
                    else {
                    $desc = 'RESET';
                    }
                  	
               $conf[$cat_key][$conf_key] = $default_ct_conf[$cat_key][$conf_key];
                  			
               // Use DEFAULT config for ordering the PARENT array IN THE ORIGINAL ORDER
               $conf[$cat_key] = $ct['var']->assoc_array_order( $conf[$cat_key], $ct['var']->assoc_array_order_map($default_ct_conf[$cat_key]) );
                  						
               $ct['conf_upgraded'] = true;
               
               // Uses === / !== for PHPv7.4 support
               $log_val_descr = ( $default_ct_conf[$cat_key][$conf_key] !== null && $default_ct_conf[$cat_key][$conf_key] !== false && $default_ct_conf[$cat_key][$conf_key] !== 0 ? $ct['sec']->obfusc_str($default_ct_conf[$cat_key][$conf_key]) : '[null / false / zero]' );
               
               // If we're resetting a subarray setting
               $log_val_descr = ( is_array($default_ct_conf[$cat_key][$conf_key]) ? 'default array size: ' . sizeof($default_ct_conf[$cat_key][$conf_key]) : 'default value: ' . $ct['sec']->obfusc_str($log_val_descr, 4) );
                  
               $ct['gen']->log(
                  			'notify_error',
                  			$desc . ' app config PARAMETER ct[conf][' . $cat_key . '][' . $conf_key . '] imported (' . $log_val_descr . ')'
                  			);
                  
               }
               
            
           }
            
         
      }
         
         
      // Check for depreciated variables, and remove them
      foreach ( $conf as $cached_cat_key => $cached_cat_val ) {
           
                
           // We don't process anything in 'plug_conf' 
           if ( $cached_cat_key === 'plug_conf' ) { // Uses === for PHPv7.4 support
           continue;
           }
           // If category is depreciated
           else if ( !isset($default_ct_conf[$cached_cat_key]) ) {
                  	
           unset($conf[$cached_cat_key]);
                  
           $ct['conf_upgraded'] = true;
                  
           $ct['gen']->log(
               		  'notify_error',
               		  'NON-EXISTANT app config CATEGORY ct[conf][' . $cached_cat_key . '] removed'
                  		 );
           
           // Since we just deleted the ENTIRE category's settings, we can safely skip per-setting checks
           continue;
           
           }
                  	
           
           // Setting keys
           foreach ( $cached_cat_val as $cached_conf_key => $cached_conf_val ) {
         
               
               // If subarray setting
               if ( is_array($default_ct_conf[$cached_cat_key][$cached_conf_key]) ) {
                    
                    if (
                    !in_array($cached_cat_key, $ct['dev']['config_deny_removals']) && !in_array($cached_conf_key, $ct['dev']['config_deny_removals'])
                    // Uses === for PHPv7.4 support
                    // (WE CHECK $ct['plugin_upgrade_check'] IN subarray_cached_ct_conf_upgrade() FOR CODE READABILITY)
                    || $cached_cat_key === 'plugins' && $cached_conf_key === 'plugin_status'
                    ) {
                    $conf = $this->subarray_cached_ct_conf_upgrade($conf, $cached_cat_key, $cached_conf_key, 'depreciated');
                    }
                    
               }
               // If regular setting
               else if ( !in_array($cached_cat_key, $ct['dev']['config_deny_removals']) && !isset($default_ct_conf[$cached_cat_key][$cached_conf_key]) ) {
                  	
               unset($conf[$cached_cat_key][$cached_conf_key]);
                  
               $ct['conf_upgraded'] = true;
                  
               $ct['gen']->log(
               			'notify_error',
               			'NON-EXISTANT app config PARAMETER ct[conf][' . $cached_cat_key . '][' . $cached_conf_key . '] removed'
                  			);
                  
               }
               
               
           }
           
            
      }
         
   
   //$this->app_log(); // DEBUGGING
      
           
      if ( $ct['conf_upgraded'] ) {
      $ct['conf_upgraded'] = false; // Reset, because we run main config / active plugins upgrades SEPERATELY
      return $conf;
      }
      else {
    	 $ct['gen']->log('notify_error', 'no ' . ( $ct['plugin_upgrade_check'] ? 'ACTIVE PLUGINS UPDATES' : 'MAIN CONFIG ' . $ct['db_upgrade_desc']['app'] . 'S' ) . ' needed');
    	 return false;
      }
      
   
   }


   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function load_cached_config() {
   
   global $ct;
   
   // Secured cache files
   $files = $ct['gen']->sort_files($ct['base_dir'] . '/cache/secured', 'dat', 'desc');
        
        
        foreach( $files as $secured_file ) {
        
        
        	// Restore config
        	if ( preg_match("/restore_conf_/i", $secured_file) ) {
		
		
        		// If we already loaded the newest modified file, delete any stale ones
        		if ( $newest_cached_restore_conf == 1 ) {
        		     
        		unlink($ct['base_dir'] . '/cache/secured/' . $secured_file);
    		
         		     if ( $ct['conf']['power']['debug_mode'] == 'conf_telemetry' ) {
        		     $ct['gen']->log('conf_debug', 'OLD CACHED restore_conf found, deleting');
         		     }
    		     
        		}
        		else {
        		$newest_cached_restore_conf = 1;
	          $ct['restore_conf_path'] = $ct['base_dir'] . '/cache/secured/' . $secured_file;
        		}
		
	
        	}
        	// App config
        	elseif ( preg_match("/ct_conf_/i", $secured_file) ) {
		
		
        		// If we already loaded the newest modified file, delete any stale ones
        		if ( $newest_cached_ct_conf == 1 ) {
        		     
        		unlink($ct['base_dir'] . '/cache/secured/' . $secured_file);
    		
         		     if ( $ct['conf']['power']['debug_mode'] == 'conf_telemetry' ) {
        		     $ct['gen']->log('conf_debug', 'OLD CACHED ct_conf found, deleting');
         		     }
    		     
        		}
        		else {
        		
        		$newest_cached_ct_conf = 1;
        		
	          $ct['cached_conf_path'] = $ct['base_dir'] . '/cache/secured/' . $secured_file;
        			
        		$cached_ct_conf = json_decode( trim( file_get_contents($ct['base_dir'] . '/cache/secured/' . $secured_file) ) , true);
        			
        			
        		    // "null" in quotes as the actual value is returned sometimes
        			if ( $ct['gen']->config_state_synced() && $cached_ct_conf != false && $cached_ct_conf != null && $cached_ct_conf != "null" ) {

        			// Use cached ct_conf if it exists, seems intact, BUT RUN A CHECK ON IT JUST IN CASE
        			// (which triggers running it through the cached config upgrade mechanism, IF it seems wonky)
        			$ct['conf'] = $cached_ct_conf; 


        			    // Avoid running during any AJAX runtimes etc
        			    if ( $ct['runtime_mode'] == 'ui' || $ct['runtime_mode'] == 'cron' ) {
        			         
             			         
                             // RUN UPGRADE CHECK MODES IF FLAGGED
                             
                             // (RUNNING APP CHECK EARLY HELPS FIX ANY DATA CORRUPTION IN THE CACHED CONFIG,
                             // THAT MIGHT CRASH THE RUNTIME AT A LATER POINT!)
        			         if ( $ct['app_upgrade_check'] ) {
        			         
        			         $ct['conf'] = $this->update_cached_config($ct['conf'], true);
                             
                             $ct['app_upgrade_check'] = false; // RESET, as we've now upgraded the app config 
        			         
        			         }
        			         // PLUGIN UPGRADE CHECK
             			    elseif ( $ct['plugin_upgrade_check'] ) {
             			    
             			    $ct['conf'] = $this->update_cached_config($ct['conf'], true);
     						     
                             $ct['plugin_upgrade_check'] = false; // RESET, as we've now upgraded the plugin configs
             			    
             			    }

        			    
        			    }    

        			
        			}
        			elseif ( !$ct['gen']->config_state_synced() ) {
        			
        			unlink($ct['base_dir'] . '/cache/secured/' . $secured_file);

        			$ct['gen']->log('conf_error', 'CACHED ct_conf outdated (DEFAULT ct_conf updated), RESETTING from DEFAULT ct_conf');

        			$ct['reset_config'] = true;

                    $ct['update_config_halt'] = 'The app was busy RESETTING it\'s cached config, please wait a minute and try again.';

        			}
        			elseif ( $cached_ct_conf != true ) {
        			     
        			unlink($ct['base_dir'] . '/cache/secured/' . $secured_file);
        			
        			$ct['gen']->log('conf_error', 'CACHED ct_conf appears corrupt, resetting from DEFAULT ct_conf');

        			$ct['reset_config'] = true;
                    
                    $ct['update_config_halt'] = 'The app was busy RESETTING it\'s cached config, please wait a minute and try again.';

        			}
        			
        			
        		}
		
	
        	}
        	
        	
        }
        	
        	
        if ( !isset($newest_cached_ct_conf) ) {
        $ct['gen']->log('conf_error', 'CACHED ct_conf not found, resetting from DEFAULT ct_conf');
        $ct['reset_config'] = true;
        $ct['update_config_halt'] = 'The app was busy RESETTING it\'s cached config, please wait a minute and try again.';
        }
        
        
        // We need to reset the cached config here, FOR TWO REASONS:
        // 1) load_cached_config() LOADS AT END OF load-config-by-security-level.php IN HIGH SECURITY MODE
        // 2) A corrupt / non-existent CACHED config should ALWAYS be REPLACED IMMEDIATELY (so runtime won't hang / freeze)
        if ( $ct['reset_config'] ) {
             
        $ct['conf'] = $this->update_cached_config(false, false, true); // Reset config

        $ct['reset_config'] = false; // Reset the reset flag (lol) IMMEDIATELY, as it's a global var

        }
        
        
   //$this->app_log(); // DEBUGGING
   
   gc_collect_cycles(); // Clean memory cache

   }


   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function update_cached_config($passed_config, $upgrade_mode=false, $reset_flagged=false) {
   
   global $ct, $default_ct_conf, $htaccess_username, $htaccess_password;
        

   // If no valid cached_ct_conf, or if DEFAULT Admin Config (in config.php) variables have been changed...
   
   
     // If no ct_conf (IN PHP ARRAY FORM) was passed into this function, to use for this refresh
     // (VALUE false WAS EXPLICITLY PASSED TO TRIGGER A RESET)
     if ( !$passed_config ) {
        
        
	     // If no reset ct_conf flag, try loading last working config (if it exists, before falling back on default ct_conf)
	     if ( !$reset_flagged && file_exists($ct['restore_conf_path']) ) {
          $passed_config = json_decode( trim( file_get_contents($ct['restore_conf_path']) ) , true);
	     }
				
             
          // If NO valid last working config / IS high security mode / IS a user-initiated reset to ct_conf defaults,
          // WE USE THE DEFAULT CT_CONF (FROM THE PHP CONFIGURATION FILES)
          if ( !$passed_config || $ct['admin_area_sec_level'] == 'high' || $reset_flagged ) {
                  
          $passed_config = $default_ct_conf;
    		
    		     if ( $ct['conf']['power']['debug_mode'] == 'conf_telemetry' ) {
    		     $ct['gen']->log('conf_debug', 'ct_conf CACHE RESET, it will be RESET using the DEFAULT ct_conf');
    		     }
             
    		}
          // All other conditions
          elseif ( $ct['conf']['power']['debug_mode'] == 'conf_telemetry' ) {
    		$ct['gen']->log('conf_debug', 'ct_conf CACHE RESET, it will be RESTORED using the LAST-KNOWN WORKING ct_conf');
          }
             
        
     }
   
    	
   $secure_128bit_hash = $ct['sec']->rand_hash(16); // 128-bit (16-byte) hash converted to hexadecimal, used for suffix
    	
    	
    	// Halt the process if an issue is detected safely creating a random hash
    	if ( $secure_128bit_hash == false ) {
    		
    	$ct['gen']->log(
    				'security_error', 
    				'Cryptographically secure pseudo-random bytes could not be generated for cached ct_conf array (secured cache storage) suffix, cached ct_conf array creation aborted to preserve security'
    				);
    	
    	}
    	else {
    	
    	
        	// Check to see if we need to upgrade the CACHED app config (NEW / DEPRECIATED CORE VARIABLES ONLY, NOT OVERWRITING EXISTING CORE VARIABLES)
    	    if ( $ct['admin_area_sec_level'] != 'high' && $upgrade_mode ) {
    	         
    	    $updated_cache_ct_conf = $this->upgrade_cached_ct_conf($passed_config);
    	    
    	        // If no upgrades were needed in the cached config, OR IT FAILED JSON CONVERSION CHECKS,
    	        // we can just return the config that was passed in this function
    	        if ( !$updated_cache_ct_conf ) {
    	        return $passed_config;
    	        }
    	    
    	    }
         // CACHED WITH NO UPGRADE FLAG
    	    elseif ( $ct['admin_area_sec_level'] != 'high' ) {
    	    $updated_cache_ct_conf = $passed_config;
    	    }
         // (REFRESHES CACHED APP CONFIG TO EXACTLY MIRROR THE HARD-CODED VARIABLES IN CONFIG.PHP, IF CONFIG.PHP IS CHANGED IN EVEN THE SLIGHTEST WAY)
    	    else {
    	    $updated_cache_ct_conf = $ct['conf'];
    	    }
    	
    	
    	// Check that the app config is valid / not corrupt
    	$store_cached_ct_conf = json_encode($updated_cache_ct_conf, JSON_PRETTY_PRINT);
    	
    	
    		// If there was an issue updating the cached app config
    		// Need to check a few different possible results for no data found ("null" in quotes as the actual value is returned sometimes)
    		if ( $store_cached_ct_conf == false || $store_cached_ct_conf == null || $store_cached_ct_conf == "null" ) {
    		    
    		$ct['gen']->log('conf_error', 'updated ct_conf data could not be saved (to secured cache storage) in json format');
    	
              // Attempt to restore last-known good config (if it exists)	
              if ( file_exists($ct['restore_conf_path']) ) {
    		    $cached_restore_conf = json_decode( trim( file_get_contents($ct['restore_conf_path']) ) , true);
    		    }
    		
    		
    		    if ( $cached_restore_conf != false && $cached_restore_conf != null && $cached_restore_conf != "null" ) {
    	
    	
                   if ( $ct['admin_area_sec_level'] != 'high' ) {
            	    $updated_cache_ct_conf = $cached_restore_conf;
            	    }
                	// (REFRESHES CACHED APP CONFIG TO EXACTLY MIRROR THE HARD-CODED VARIABLES IN CONFIG.PHP, IF CONFIG.PHP IS CHANGED IN EVEN THE SLIGHTEST WAY)
            	    else {
            	    $updated_cache_ct_conf = $cached_restore_conf;
            	    }
            	     
            	
            	// Check that the app config is valid / not corrupt
            	$store_cached_ct_conf = json_encode($updated_cache_ct_conf, JSON_PRETTY_PRINT);
            	
            	
            		// If there was an issue updating the cached app config
            		// Need to check a few different possible results for no data found ("null" in quotes as the actual value is returned sometimes)
            		if ( $store_cached_ct_conf == false || $store_cached_ct_conf == null || $store_cached_ct_conf == "null" ) {
            		$ct['gen']->log('conf_error', 'ct_conf data could not be restored from last-known working config');
            		}
            		// If restoring last-known working config was successful
            		else {
            		    
            		$ct['gen']->log('conf_error', 'ct_conf CACHE restore from last-known working config triggered, updated successfully'); 
            		$ct['conf'] = $updated_cache_ct_conf;
            		$this->save_file($ct['base_dir'] . '/cache/secured/ct_conf_'.$secure_128bit_hash.'.dat', $store_cached_ct_conf);
            		
            		
                		// For checking later, if DEFAULT Admin Config (in config.php) values are updated we save to json again
            		    if ( $ct['admin_area_sec_level'] == 'high' || $reset_flagged ) {
                		$this->save_file($ct['base_dir'] . '/cache/vars/state-tracking/default_ct_conf_md5.dat', md5( serialize($default_ct_conf) ) ); 
            		    }
            		
            		
            		// Refresh any custom .htaccess / php.ini settings (deleting will trigger a restore)
            		unlink($ct['base_dir'] . '/.htaccess');
            		unlink($ct['base_dir'] . '/.user.ini');
            		unlink($ct['base_dir'] . '/cache/secured/.app_htpasswd');
            		
            		}
            		
    		   
    		    }
    		    
    		
    		}
    		// If cached app config updated successfully
    		else {
    		
    		
    		$ct['conf'] = $updated_cache_ct_conf;
    		
    		$this->save_file($ct['base_dir'] . '/cache/secured/ct_conf_'.$secure_128bit_hash.'.dat', $store_cached_ct_conf);
    		
    		$this->save_file($ct['base_dir'] . '/cache/secured/restore_conf_'.$secure_128bit_hash.'.dat', $store_cached_ct_conf);
    		
    		
               // For checking later, if DEFAULT Admin Config (in config.php) values are updated (in high security mode),
               // or we reset (any security mode) / upgrade (normal / medium security mode) the cached config, we save the digest check to json again
            	if ( $ct['admin_area_sec_level'] == 'high' || $reset_flagged || $upgrade_mode ) {
               $this->save_file($ct['base_dir'] . '/cache/vars/state-tracking/default_ct_conf_md5.dat', md5( serialize($default_ct_conf) ) ); 
    		     }
    		
    		
    		     if ( $ct['conf']['power']['debug_mode'] == 'conf_telemetry' ) {
    		          
    		          if ( $reset_flagged ) {
    		          $update_desc = 'RESET';
    		          }
    		          elseif ( $upgrade_mode ) {
    		          $update_desc = 'UPGRADE';
    		          }
    		          elseif ( $ct['update_config'] ) {
    		          $update_desc = 'UPDATE';
    		          }
    		          else {
    		          $update_desc = '(MODE UNKNOWN)';
    		          }
    		     
    		     $ct['gen']->log('conf_debug', 'ct_conf CACHE ' . $update_desc . ' triggered, updated successfully');
    		     
    		     }
    		    
    		
          // Refresh any custom .htaccess / php.ini settings (deleting will trigger a restore)
    		unlink($ct['base_dir'] . '/.htaccess');
    		unlink($ct['base_dir'] . '/.user.ini');
    		unlink($ct['base_dir'] . '/cache/secured/.app_htpasswd');
    		
    		}
    		
    	
    	}
    	
   
   sleep(1); // Chill for a second, since we just refreshed the conf on disk

             
     // Since we are resetting OR updating the cached config, telegram chatroom data should be refreshed too
     // (ONLY IF TELEGRAM SETTINGS HAVE CHANGED)
     if ( $ct['update_config'] && $ct['telegram_user_data_path'] != null || $reset_flagged && $ct['telegram_user_data_path'] != null ) {
        
     $check_telegram_conf_md5 = trim( file_get_contents($ct['base_dir'] . '/cache/vars/state-tracking/telegram_conf_md5.dat') );

     $telegram_conf_md5 = md5($ct['conf']['ext_apis']['telegram_your_username'] . $ct['conf']['ext_apis']['telegram_bot_username'] . $ct['conf']['ext_apis']['telegram_bot_name'] . $ct['conf']['ext_apis']['telegram_bot_token']);       
        
          // Completely reset ALL telegram config data IF IT'S BEEN REVISED
          if ( $check_telegram_conf_md5 != $telegram_conf_md5 )  {
          $ct['telegram_user_data'] = array();
          unlink($ct['telegram_user_data_path']); 
          }
             
     }
     
     
   //$this->app_log(); // DEBUGGING

   // Return $ct['conf']
   return $ct['conf'];
   
   }
  
  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function app_log() {
  
  global $ct;

  // ERRORS 
  
      foreach ( $ct['log_errors']['notify_error'] as $error ) {
      $error_log .= strip_tags($error); // Remove any HTML formatting used in UI alerts
      }
      
  
  // Combine all errors logged
  $error_log .= strip_tags($ct['log_errors']['security_error']); // Remove any HTML formatting used in UI alerts
  
  $error_log .= strip_tags($ct['log_errors']['system_warning']); // Remove any HTML formatting used in UI alerts
  
  $error_log .= strip_tags($ct['log_errors']['system_error']); // Remove any HTML formatting used in UI alerts
  
  $error_log .= strip_tags($ct['log_errors']['conf_error']); // Remove any HTML formatting used in UI alerts
  
  $error_log .= strip_tags($ct['log_errors']['ext_data_error']); // Remove any HTML formatting used in UI alerts
  
  $error_log .= strip_tags($ct['log_errors']['int_api_error']); // Remove any HTML formatting used in UI alerts
  
  $error_log .= strip_tags($ct['log_errors']['int_webhook_error']); // Remove any HTML formatting used in UI alerts
  
  $error_log .= strip_tags($ct['log_errors']['market_error']); // Remove any HTML formatting used in UI alerts
  
  $error_log .= strip_tags($ct['log_errors']['other_error']); // Remove any HTML formatting used in UI alerts
  
     
      foreach ( $ct['log_errors']['cache_error'] as $error ) {
      $error_log .= strip_tags($error); // Remove any HTML formatting used in UI alerts
      }
    
  // DEBUGGING
  
      foreach ( $ct['log_debugging']['notify_debug'] as $debugging ) {
      $debug_log .= strip_tags($debugging); // Remove any HTML formatting used in UI alerts
      }
  
  
  // Combine all debugging logged
  $debug_log .= strip_tags($ct['log_debugging']['security_debug']); // Remove any HTML formatting used in UI alerts
  
  $debug_log .= strip_tags($ct['log_debugging']['system_debug']); // Remove any HTML formatting used in UI alerts
  
  $debug_log .= strip_tags($ct['log_debugging']['conf_debug']); // Remove any HTML formatting used in UI alerts
  
  $debug_log .= strip_tags($ct['log_debugging']['ext_data_debug']); // Remove any HTML formatting used in UI alerts
  
  $debug_log .= strip_tags($ct['log_debugging']['int_api_debug']); // Remove any HTML formatting used in UI alerts
  
  $debug_log .= strip_tags($ct['log_debugging']['market_debug']); // Remove any HTML formatting used in UI alerts
  
  $debug_log .= strip_tags($ct['log_debugging']['other_debug']); // Remove any HTML formatting used in UI alerts
  
  
      foreach ( $ct['log_debugging']['cache_debug'] as $debugging ) {
      $debug_log .= strip_tags($debugging); // Remove any HTML formatting used in UI alerts
      }
      
  
  // Sort error / debug logs (combined) by timestamp
  $app_log = $ct['gen']->sort_log($error_log . $debug_log);
      
  // Format / save to global var, for interface alerts
  $ct['alerts_gui_logs'] = nl2br($app_log);
    
    
      // If it's time to email error logs...
	  // With offset, to try keeping daily recurrences at same exact runtime (instead of moving up the runtime daily)
      if ( $ct['conf']['comms']['logs_email'] > 0 && $this->update_cache('cache/events/logging/email-app-logs.dat', ( $ct['conf']['comms']['logs_email'] * 1440 ) + $ct['dev']['tasks_time_offset'] ) == true ) {
       
      $emailed_logs = "\n\n ------------------error.log------------------ \n\n" . file_get_contents('cache/logs/app_log.log') . "\n\n ------------------smtp_error.log------------------ \n\n" . file_get_contents('cache/logs/smtp_error.log');
       
      $msg = " Here are the current error logs from the ".$ct['base_dir']."/cache/logs/ directory. \n\n You can disable / change receiving log emails (every " . $ct['conf']['comms']['logs_email'] . " days) in the Admin Config \"Communications\" section. \n \n =========================================================================== \n \n"  . ( isset($emailed_logs) && $emailed_logs != '' ? $emailed_logs : 'No error logs currently.' );
      
        // Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
        $send_params = array(
                            'email' => array(
                                            'subject' => 'Open Crypto Tracker - Error Logs Report',
                                            'message' => $msg
                                            )
                            );
                
      // Send notifications
      @$this->queue_notify($send_params);
                
      $this->save_file($ct['base_dir'] . '/cache/events/logging/email-app-logs.dat', date('Y-m-d H:i:s')); // Track this emailing event, to determine next time to email logs again.
      
      }
      
      
      // Log errors...Purge old logs before storing new logs, if it's time to...otherwise just append.
	  // With offset, to try keeping daily recurrences at same exact runtime (instead of moving up the runtime daily)
      if ( $this->update_cache('cache/events/logging/purge-app-logs.dat', ( $ct['conf']['power']['logs_purge'] * 1440 ) + $ct['dev']['tasks_time_offset'] ) == true ) {
      
      unlink($ct['base_dir'] . '/cache/logs/smtp_error.log');
      unlink($ct['base_dir'] . '/cache/logs/app_log.log');
      
      $this->save_file('cache/events/logging/purge-app-logs.dat', date('Y-m-d H:i:s'));
      
      sleep(1);
      
      }
      
      
      if ( $app_log != null ) {
        
      $store_file_contents = $this->save_file($ct['base_dir'] . '/cache/logs/app_log.log', $app_log, "append");
      $ct['log_errors'] = array(); // RESET ERROR LOGS ARRAY (clears logs from memory, that we just wrote to disk)
      $ct['log_debugging'] = array(); // RESET DEBUG LOGS ARRAY (clears logs from memory, that we just wrote to disk)
        
          if ( $store_file_contents != true ) {
          return 'Error logs write error for "' . $ct['base_dir'] . '/cache/logs/app_log.log" (MAKE SURE YOUR DISK ISN\'T FULL), data_size_bytes: ' . strlen($app_log) . ' bytes';
          }
      
      }
   
   
  return true;
  
  }
  
  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function save_file($file_path, $data, $mode=false, $lock=true) {
  
  global $ct;
  
  
    // If no data was passed on to write to file, log it and return false early for runtime speed sake
    if ( strlen($data) == 0 ) {
     
    $ct['gen']->log(
    			'system_error',
    			'No bytes of data received to write to file "' . $ct['sec']->obfusc_path_data($file_path) . '" (aborting useless file write)'
    			);
    
     // API timeouts are a confirmed cause for write errors of 0 bytes, so we want to alert end users that they may need to adjust their API timeout settings to get associated API data
     if ( preg_match("/cache\/secured\/apis/i", $file_path) ) {
       
     $ct['gen']->log(
     			'ext_data_error',
     								
     			'POSSIBLE api timeout' . ( $ct['conf']['sec']['remote_api_strict_ssl'] == 'on' ? ' or strict_ssl' : '' ) . ' issue for cache file "' . $ct['sec']->obfusc_path_data($file_path) . '" (IF ISSUE PERSISTS, TRY INCREASING "remote_api_timeout" IN Admin Config EXTERNAL APIS SECTION' . ( $ct['conf']['sec']['remote_api_strict_ssl'] == 'on' ? ', OR SETTING "remote_api_strict_ssl" to "off" IN Admin Config SECURITY SECTION' : '' ) . ')',
     								
     			'remote_api_timeout: '.$ct['conf']['ext_apis']['remote_api_timeout'].' seconds; remote_api_strict_ssl: ' . $ct['conf']['sec']['remote_api_strict_ssl'] . ';'
     			);
     
     }
    
    return false;
    
    }
    
    
    // If we are over the 260 character path limit on windows
    // https://learn.microsoft.com/en-us/windows/win32/fileio/maximum-file-path-limitation
    if ( preg_match("/windows/i", PHP_OS_FAMILY) && strlen($file_path) >= 260 ) {
    
    $ct['gen']->log(
    			'notify_error',
    			'Windows Operating System MAXIMUM PATH LENGTH of 260 characters MET / EXCEEDED. PLEASE MOVE THIS APP TO A SHORTER FILE PATH, OR YOU LIKELY WILL ENCOUNTER SIGNIFICANT ISSUES ('.strlen($file_path).' characters in path: ' . $ct['sec']->obfusc_path_data($file_path) . ')',
          	false,
               'windows_max_path_alert'
    			);
    			
    }
   
   
    // We ALWAYS set .htaccess files to a more secure $ct['dev']['chmod_index_sec'] permission AFTER EDITING, 
    // so we TEMPORARILY set .htaccess to $ct['dev']['chmod_cache_file'] for NEW EDITING...
    // (anything else stays weaker write security permissions, for UX)
    if (
    strstr($file_path, '.dat') != false
    || strstr($file_path, '.htaccess') != false
    || strstr($file_path, '.user.ini') != false
    || strstr($file_path, 'index.php') != false
    ) {
     
    $chmod_setting = octdec($ct['dev']['chmod_cache_file']);
    
         // Run chmod compatibility on certain PHP setups (if we can because we are running as the file owner)
         // In this case only if the file exists, as we are chmod BEFORE editing it (.htaccess files)
         if ( file_exists($file_path) == true ) {
         $ct['sec']->ct_chmod($file_path, $chmod_setting);
         }
    
    }
   
  
  
    // Write to the file
    if ( $mode == 'append' && $lock ) {
    $result = file_put_contents($file_path, $data, FILE_APPEND | LOCK_EX);
    }
    elseif ( $mode == 'append' && !$lock ) {
    $result = file_put_contents($file_path, $data, FILE_APPEND);
    }
    elseif ( !$mode && $lock ) {
    $result = file_put_contents($file_path, $data, LOCK_EX);
    }
    else {
    $result = file_put_contents($file_path, $data);
    }
   
   
    // Log any write error
    if ( $result == false ) {
    
    $path_parts = pathinfo($file_path);
    	
    $ct['gen']->log(
    				'system_error',
    				'File write failed storing '.strlen($data).' bytes of data to file "' . $ct['sec']->obfusc_path_data($file_path) . '" (MAKE SURE YOUR DISK ISN\'T FULL. Check permissions for the path "' . $ct['sec']->obfusc_path_data($path_parts['dirname']) . '", and the file "' . $ct['sec']->obfusc_str($path_parts['basename'], 5) . '")'
    				);
    
    }
    
    
    // For security, NEVER make an .htaccess file writable by any user not in the group
    if (
    strstr($file_path, '.htaccess') != false
    || strstr($file_path, '.user.ini') != false
    || strstr($file_path, 'index.php') != false
    ) {
    $chmod_setting = octdec($ct['dev']['chmod_index_sec']);
    }
    // All other files
    else {
    $chmod_setting = octdec($ct['dev']['chmod_cache_file']);
    }
   
    $ct['sec']->ct_chmod($file_path, $chmod_setting);
   
  return $result;
  
  }
  
  
  ////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////
  
  
  function update_light_chart($archive_path, $newest_arch_data=false, $days_span=1) {
  
  global $ct;
  
  $arch_data = array();
  $queued_arch_lines = array();
  $new_light_data = null;
  
  // Light chart file path
  $light_path = preg_replace("/archival/i", 'light/' . $days_span . '_days', $archive_path);
  
  
    // Hash of light path, AND random X hours update threshold, to spread out and event-track 'all' chart rebuilding
    if ( $days_span == 'all' ) {
    $light_path_hash = md5($light_path);
    $thres_range = array_map( "trim", explode(',', $ct['conf']['power']['light_chart_all_rebuild_min_max']) );
    $all_chart_rebuild_thres = rand($thres_range[0], $thres_range[1]); // Randomly within the min/max range, to spead the load across multiple runtimes
    }
   
   
    // Get FIRST AND LAST line of light chart data (determines oldest / newest light timestamp)
    if ( file_exists($light_path) ) {
    
    $oldest_light_array = explode("||", file($light_path)[0]);
    $oldest_light_timestamp = $ct['var']->num_to_str( $oldest_light_array[0] );
        
    $last_light_line = $this->tail_custom($light_path);
    $last_light_array = explode("||", $last_light_line);
    $newest_light_timestamp = ( isset($last_light_array[0]) ? $ct['var']->num_to_str($last_light_array[0]) : false );
    
    gc_collect_cycles(); // Clean memory cache
    
    }
    else {
    
        if ( $ct['gen']->dir_struct( dirname($light_path) ) != true ) {
        $ct['gen']->log('system_error', 'Unable to create light chart directory structure ('.dirname($light_path).')');
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
  $newest_arch_timestamp = $ct['var']->num_to_str($last_arch_array[0]);
  
  
    // Get FIRST line of archival chart data (determines oldest archival timestamp)
    if ( file_exists($archive_path) ) {
    $oldest_arch_array = explode("||", file($archive_path)[0]);
    $oldest_arch_timestamp = $ct['var']->num_to_str( $oldest_arch_array[0] );
    gc_collect_cycles(); // Clean memory cache
    }
    
    
    // If we don't have any valid archival data, return false
    if ( !$oldest_arch_timestamp ) {
    $ct['gen']->log('cache_error', 'Archival chart data not found ('.$archive_path.')');
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
        
    $ct['gen']->log('cache_error', 'Archival chart data appears recently restored, resetting ALL light charts');
    
    // Delete ALL light charts (this will automatically trigger a re-build)
    $this->remove_dir($ct['base_dir'] . '/cache/charts/spot_price_24hr_volume/light');
    $this->remove_dir($ct['base_dir'] . '/cache/charts/system/light');
    
    return 'reset';
    
    }
   
     
    // Oldest base timestamp we can use (only applies for x days charts, not 'all')
    if ( $days_span != 'all' ) {
    $base_min_timestamp = $ct['var']->num_to_str( strtotime('-'.$days_span.' day', $newest_arch_timestamp) );
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
    $min_data_interval = round( ($newest_arch_timestamp - $oldest_arch_timestamp) / $ct['conf']['power']['light_chart_data_points_maximum'] ); // Dynamic
    }
    else {
    $min_data_interval = round( ($days_span * 86400) / $ct['conf']['power']['light_chart_data_points_maximum'] ); // Fixed X days (86400 seconds per day)
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
  $min_data_interval = $ct['var']->num_to_str($min_data_interval); 
  $light_data_update_thres = $ct['var']->num_to_str($light_data_update_thres); 
  
  
     // If we are queued to update an existing light chart, get the data points we want to add 
     // (may be multiple data points, if the last update had network errors / system reboot / etc)
     if ( isset($newest_light_timestamp) && $light_data_update_thres <= $newest_arch_timestamp ) {
     
        // If we are only adding the newest archival data point (passed into this function), 
        // #we save BIGTIME on resource usage# (used EVERYTIME, other than very rare FALLBACKS)
        // CHECKS IF UPDATE THRESHOLD IS GREATER THAN NEWEST ARCHIVAL DATA POINT TIMESTAMP, 
        // #WHEN ADDING AN EXTRA# $min_data_interval (so we know to only add one data point)
        if ( $ct['var']->num_to_str($light_data_update_thres + $min_data_interval) > $newest_arch_timestamp ) {
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
          $arch_line_array[0] = $ct['var']->num_to_str($arch_line_array[0]);
           
             if ( !$added_arch_timestamp && $light_data_update_thres <= $arch_line_array[0]
             || isset($added_arch_timestamp) && $ct['var']->num_to_str($added_arch_timestamp + $min_data_interval) <= $arch_line_array[0] ) {
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
        
        
     gc_collect_cycles(); // Clean memory cache
     
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
    || $days_span == 'all' && is_array($queued_arch_lines) && sizeof($queued_arch_lines) > 0 && $this->update_cache($ct['base_dir'] . '/cache/events/light_chart_rebuilds/all_days_chart_'.$light_path_hash.'.dat', (60 * $all_chart_rebuild_thres) ) == true
    ) {
    
      
      // Avoid overloading low power devices with the SCALED first build hard limit
      // (multiplies the first build limit by the number of available CPU threads)
      // [less cores == lower hard limit == NOT OVERLOADING SLOW DEVICES]
      // [more cores == higher hard limit == FASTER ON FAST DEVICES]
      if ( isset($ct['system_info']['cpu_threads']) && $ct['system_info']['cpu_threads'] > 1 ) {
      $scaled_first_build_hard_limit = ($ct['conf']['power']['light_chart_first_build_hard_limit'] * $ct['system_info']['cpu_threads']);
      }
      // Doubles as failsafe (if number of threads not detected on this system, eg: windows devices)
      else {
      $scaled_first_build_hard_limit = $ct['conf']['power']['light_chart_first_build_hard_limit'];
      }
      
      
      if ( !$newest_light_timestamp && $ct['light_chart_first_build_count'] >= $scaled_first_build_hard_limit ) {
      return false;
      }
      // Count first builds, to enforce first build hard limit
      elseif ( !$newest_light_timestamp ) {
      $ct['light_chart_first_build_count'] = $ct['light_chart_first_build_count'] + 1;
      }
      
   
    $archive_file_data = file($archive_path);
    $archive_file_data = array_reverse($archive_file_data); // Save time, only loop / read last lines needed
    
    
      foreach($archive_file_data as $line) {
      
      $line_array = explode("||", $line);
      $line_array[0] = $ct['var']->num_to_str($line_array[0]);
     
        if ( $line_array[0] >= $oldest_allowed_timestamp ) {
        $arch_data[] = $line;
        }
      
      }
    
     
      // We are looping IN REVERSE ODER, to ALWAYS include the latest data
      $loop = 0;
      $data_points = 0;
      // $data_points <= is INTENTIONAL, as we can have max data points slightly under without it
      while ( isset($arch_data[$loop]) && $data_points <= $ct['conf']['power']['light_chart_data_points_maximum'] ) {
       
      $data_point_array = explode("||", $arch_data[$loop]);
      $data_point_array[0] = $ct['var']->num_to_str($data_point_array[0]);
        
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
      $this->save_file($ct['base_dir'] . '/cache/events/light_chart_rebuilds/all_days_chart_'.$light_path_hash.'.dat', $ct['gen']->time_date_format(false, 'pretty_date_time') );
      }
    
   
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////
    // If the light chart has existing data, then $queued_arch_lines should be populated (IF we have new data to append to it).
    // We also trim out X first lines of stale data (earlier then the X days time range)
    ////////////////////////////////////////////////////////////////////////////////////////////////
    elseif ( is_array($queued_arch_lines) && sizeof($queued_arch_lines) > 0 ) {
     
    $queued_arch_data = implode("\n", $queued_arch_lines);
    
    // Current light chart lines, plus new archival lines queued to be added
    $check_light_data_lines = $ct['gen']->get_lines($light_path) + sizeof($queued_arch_lines);
     
    // Get FIRST line of light chart data (determines oldest light timestamp)
    $fopen_light = fopen($light_path, 'r');
    
      if ($fopen_light) {
      $first_light_line = fgets($fopen_light);
      fclose($fopen_light);
      usleep(20000); // Wait 0.02 seconds, since we'll be writing data to this file momentarily
      gc_collect_cycles(); // Clean memory cache
      }
       
    $first_light_array = explode("||", $first_light_line);
    $oldest_light_timestamp = $ct['var']->num_to_str($first_light_array[0]);
     
      // If our oldest light timestamp is older than allowed, remove the stale data points
      if ( $oldest_light_timestamp < $oldest_allowed_timestamp ) {
      $light_data_removed_outdated_lines = $ct['gen']->prune_first_lines($light_path, 0, $oldest_allowed_timestamp);
      
      // ONLY APPEND A LINE BREAK TO THE NEW ARCHIVAL DATA, since $ct['gen']->prune_first_lines() maintains the existing line breaks
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
      
      if ( $ct['conf']['power']['debug_mode'] == 'light_chart_telemetry' ) {
      	
      $ct['gen']->log(
      			'cache_debug',
      			'Light chart ' . $light_mode_logging . ' COMPLETED ('.$_SESSION['light_charts_updated'].') for ' . $light_path
      			);
      
      }
       
      if ( $ct['conf']['power']['debug_mode'] == 'memory_usage_telemetry' ) {
      	
      $ct['gen']->log(
      			'system_debug',
      			$_SESSION['light_charts_updated'] . ' light charts updated, CURRENT script memory usage is ' . $ct['gen']->conv_bytes(memory_get_usage(), 1) . ', PEAK script memory usage is ' . $ct['gen']->conv_bytes(memory_get_peak_usage(), 1) . ', php_sapi_name is "' . php_sapi_name() . '"'
      			);
     
      }
      
    }
    elseif ( $result == false ) {
        
        if ( !is_readable($archive_path) ) {
        $ct['gen']->log( 'cache_error', 'Light chart ' . $light_mode_logging . ' FAILED, data from archive file ' . $archive_path . ' could not be read. Check file AND cache directory permissions');
        }
        elseif ( !file_exists($archive_path) ) {
        $ct['gen']->log( 'cache_error', 'Light chart ' . $light_mode_logging . ' FAILED for ' . $light_path . ', archival data not created yet (for new installs please wait a few hours, then check cache directory permissions if this error continues beyond then)');
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
  
  global $ct;
  
  
  // Array of currently queued messages in the cache
  $msgs_queue = $ct['gen']->sort_files($ct['base_dir'] . '/cache/secured/messages', 'queue', 'asc');
   
  //var_dump($msgs_queue); // DEBUGGING ONLY
  //return false; // DEBUGGING ONLY
  
  
    // If queued messages exist, proceed
    if ( is_array($msgs_queue) && sizeof($msgs_queue) > 0 ) {
    
    
      if ( !isset($ct['processed_msgs']['notifications_count']) ) {
      $ct['processed_msgs']['notifications_count'] = 0;
      }
      
      
      // If it's been over 5 minutes since a notifyme alert was sent 
      // (we use 6 minutes, safely over the 5 minute limit for the maximum 5 requests), 
      // and no session count is set, set session count to zero
      // Don't update the file-cached count here, that will happen automatically from resetting the session count to zero 
      // (if there are notifyme messages queued to send)
      if ( !isset($ct['processed_msgs']['notifyme_count']) && $this->update_cache($ct['base_dir'] . '/cache/events/throttling/notifyme-alerts-sent.dat', 5) == true ) {
      $ct['processed_msgs']['notifyme_count'] = 0;
      }
      // If it hasn't been over 5 minutes since the last notifyme send, and there is no session count, 
      // use the file-cached count for the session count starting point
      elseif ( !isset($ct['processed_msgs']['notifyme_count']) && $this->update_cache($ct['base_dir'] . '/cache/events/throttling/notifyme-alerts-sent.dat', 5) == false ) {
      $ct['processed_msgs']['notifyme_count'] = trim( file_get_contents($ct['base_dir'] . '/cache/events/throttling/notifyme-alerts-sent.dat') );
      }
      
      
      if ( !isset($ct['processed_msgs']['text_count']) ) {
      $ct['processed_msgs']['text_count'] = 0;
      }
      
      
      if ( !isset($ct['processed_msgs']['telegram_count']) ) {
      $ct['processed_msgs']['telegram_count'] = 0;
      }
      
      
      if ( !isset($ct['processed_msgs']['email_count']) ) {
      $ct['processed_msgs']['email_count'] = 0;
      }
     
    
    // ONLY process queued messages IF they are NOT already being processed by another runtime instance
    $queued_msgs_processing_lock_file = $ct['base_dir'] . '/cache/events/notifications-queue-processing-lock.dat';
    
    
      // If we find no file lock (OR if there is a VERY stale file lock [OVER 9 MINUTES OLD]), we can proceed
      if ( $this->update_cache($queued_msgs_processing_lock_file, 9) == true ) {  
      
      // Re-save new file lock
      $this->save_file($queued_msgs_processing_lock_file, $ct['gen']->time_date_format(false, 'pretty_date_time') );
      
      /////////////////////////////////////////////////
      ////////////FILE-LOCKED START////////////////////
      /////////////////////////////////////////////////
      
      
        // Sleep for 2 seconds before starting ANY consecutive message send, to help avoid being blocked / throttled by external server
        if ( $ct['processed_msgs']['notifications_count'] > 0 ) {
        sleep(2);
        }
      
      
        // Notifyme params
        if ( $ct['notifyme_activated'] ) {
        
        $notifyme_params = array(
                                'notification' => null, // Setting this right before sending
                                'accessCode' => $ct['conf']['ext_apis']['notifyme_access_code']
                                );
                
        }
      
        
        // SMS service params 
        if ( $ct['sms_service'] == 'twilio' ) {
        
        $twilio_params = array(
                              'Body' => null, // Setting this right before sending
                              'To' => '+' . $ct['gen']->mob_number($ct['conf']['comms']['to_mobile_text']),
                              'From' => '+' . $ct['conf']['ext_apis']['twilio_number']
                               );
                            
        }
        elseif ( $ct['sms_service'] == 'textbelt' ) {
            
        $textbelt_params = array(
                                  'message' => null, // Setting this right before sending
                                  'phone' => $ct['gen']->mob_number($ct['conf']['comms']['to_mobile_text']),
                                  'key' => $ct['conf']['ext_apis']['textbelt_api_key']
                                 );
                            
        }
        elseif ( $ct['sms_service'] == 'textlocal' ) {
            
        $textlocal_params = array(
                                   'message' => null, // Setting this right before sending
                                   'sender' => $ct['conf']['ext_apis']['textlocal_sender'],
                                   'apikey' => $ct['conf']['ext_apis']['textlocal_api_key'],
                                   'numbers' => $ct['gen']->mob_number($ct['conf']['comms']['to_mobile_text'])
                                    );
                            
        }
      
      
       
        // Send messages
        foreach ( $msgs_queue as $queued_cache_file ) {
        
        
        $msg_data = trim( file_get_contents($ct['base_dir'] . '/cache/secured/messages/' . $queued_cache_file) );
        
        
          if ( isset($msg_data) && $msg_data != '' ) {
             
              
               // If 0 bytes from system / network issues, just delete it to keep the directory contents clean
               if ( filesize($ct['base_dir'] . '/cache/secured/messages/' . $queued_cache_file) == 0 ) {
               unlink($ct['base_dir'] . '/cache/secured/messages/' . $queued_cache_file);
               }
               
               
               // Notifyme
               elseif ( $ct['notifyme_activated'] && preg_match("/notifyme/i", $queued_cache_file) ) {
                 
                 $notifyme_params['notification'] = $msg_data;
                 
               // Sleep for 1 second EXTRA on EACH consecutive notifyme message, to throttle MANY outgoing messages, to help avoid being blocked / throttled by external server
               $notifyme_sleep = 1 * ( $ct['processed_msgs']['notifyme_count'] > 0 ? $ct['processed_msgs']['notifyme_count'] : 1 );
               sleep($notifyme_sleep);
               
                
                   // Only 5 notifyme messages allowed per minute
                   if ( $ct['processed_msgs']['notifyme_count'] < 5 ) {
                   
                   $notifyme_response = @$this->ext_data('params', $notifyme_params, 0, 'https://api.notifymyecho.com/v1/NotifyMe');
                  
                   $ct['processed_msgs']['notifyme_count'] = $ct['processed_msgs']['notifyme_count'] + 1;
                   
                   $msg_sent = 1;
                   
                   $this->save_file($ct['base_dir'] . '/cache/events/throttling/notifyme-alerts-sent.dat', $ct['processed_msgs']['notifyme_count']); 
                   
                     if ( $ct['conf']['power']['debug_mode'] == 'api_comms_telemetry' ) {
                     $this->save_file($ct['base_dir'] . '/cache/logs/debug/external_data/last-response-notifyme.log', $notifyme_response);
                     }
                   
                   unlink($ct['base_dir'] . '/cache/secured/messages/' . $queued_cache_file);
                   
                   }
               
               
               }
               
               
               
               // Telegram
               elseif ( $ct['telegram_activated'] && preg_match("/telegram/i", $queued_cache_file) ) {
                  
               // Sleep for 1 second EXTRA on EACH consecutive telegram message, to throttle MANY outgoing messages, to help avoid being blocked / throttled by external server
               $telegram_sleep = 1 * ( $ct['processed_msgs']['telegram_count'] > 0 ? $ct['processed_msgs']['telegram_count'] : 1 );
               sleep($telegram_sleep);
                 
               $telegram_response = $ct['gen']->telegram_msg($msg_data);
               
               
                  if ( $telegram_response != false ) {
                   
                  $ct['processed_msgs']['telegram_count'] = $ct['processed_msgs']['telegram_count'] + 1;
                  
                  $msg_sent = 1;
                
                  unlink($ct['base_dir'] . '/cache/secured/messages/' . $queued_cache_file);
                   
                  }
                  else {
                  $ct['gen']->log( 'system_error', 'Telegram sending failed', $telegram_response);
                  }
                   
                 
                  if ( $ct['conf']['power']['debug_mode'] == 'api_comms_telemetry' ) {
                  $this->save_file($ct['base_dir'] . '/cache/logs/debug/external_data/last-response-telegram.log', $telegram_response);
                  }
               
               
               }
               
               
               
               // Twilio
               elseif ( $ct['sms_service'] == 'twilio' && preg_match("/twilio/i", $queued_cache_file) ) {
                 
               $twilio_params['Body'] = $msg_data;
                 
               // Sleep for 1 second EXTRA on EACH consecutive text message, to throttle MANY outgoing messages, to help avoid being blocked / throttled by external server
               $text_sleep = 1 * ( $ct['processed_msgs']['text_count'] > 0 ? $ct['processed_msgs']['text_count'] : 1 );
               sleep($text_sleep);
                 
               $twilio_response = @$this->ext_data('params', $twilio_params, 0, 'https://api.twilio.com/2010-04-01/Accounts/' . $ct['conf']['ext_apis']['twilio_sid'] . '/Messages.json', 2);
                 
               $ct['processed_msgs']['text_count'] = $ct['processed_msgs']['text_count'] + 1;
               
               $msg_sent = 1;
                 
                 if ( $ct['conf']['power']['debug_mode'] == 'api_comms_telemetry' ) {
                 $this->save_file($ct['base_dir'] . '/cache/logs/debug/external_data/last-response-twilio.log', $twilio_response);
                 }
               
               unlink($ct['base_dir'] . '/cache/secured/messages/' . $queued_cache_file);
               
               }
               
               
               
               // Textbelt
               elseif ( $ct['sms_service'] == 'textbelt' && preg_match("/textbelt/i", $queued_cache_file) ) {
                 
               $textbelt_params['message'] = $msg_data;
                 
               // Sleep for 1 second EXTRA on EACH consecutive text message, to throttle MANY outgoing messages, to help avoid being blocked / throttled by external server
               $text_sleep = 1 * ( $ct['processed_msgs']['text_count'] > 0 ? $ct['processed_msgs']['text_count'] : 1 );
               sleep($text_sleep);
                 
               $textbelt_response = @$this->ext_data('params', $textbelt_params, 0, 'https://textbelt.com/text', 2);
                 
               $ct['processed_msgs']['text_count'] = $ct['processed_msgs']['text_count'] + 1;
               
               $msg_sent = 1;
                 
                 if ( $ct['conf']['power']['debug_mode'] == 'api_comms_telemetry' ) {
                 $this->save_file($ct['base_dir'] . '/cache/logs/debug/external_data/last-response-textbelt.log', $textbelt_response);
                 }
               
               unlink($ct['base_dir'] . '/cache/secured/messages/' . $queued_cache_file);
               
               }
               
               
               
               // Textlocal
               elseif ( $ct['sms_service'] == 'textlocal' && preg_match("/textlocal/i", $queued_cache_file) ) {  
                 
               $textlocal_params['message'] = $msg_data;
                 
               // Sleep for 1 second EXTRA on EACH consecutive text message, to throttle MANY outgoing messages, to help avoid being blocked / throttled by external server
               $text_sleep = 1 * ( $ct['processed_msgs']['text_count'] > 0 ? $ct['processed_msgs']['text_count'] : 1 );
               sleep($text_sleep);
                 
               $textlocal_response = @$this->ext_data('params', $textlocal_params, 0, 'https://api.txtlocal.com/send/', 1);
                 
               $ct['processed_msgs']['text_count'] = $ct['processed_msgs']['text_count'] + 1;
               
               $msg_sent = 1;
                 
                 if ( $ct['conf']['power']['debug_mode'] == 'api_comms_telemetry' ) {
                 $this->save_file($ct['base_dir'] . '/cache/logs/debug/external_data/last-response-textlocal.log', $textlocal_response);
                 }
               
               unlink($ct['base_dir'] . '/cache/secured/messages/' . $queued_cache_file);
               
               }
                
                  
                // SEND EMAILS LAST, IN CASE OF SMTP METHOD FAILURE AND RUNTIME EXIT
               
               
               // Text email
               // To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
               // Only use text-to-email if other text services aren't configured
               elseif ( $ct['email_activated'] && $ct['sms_service'] == 'email_gateway' && preg_match("/textemail/i", $queued_cache_file) ) {
                 
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
                   $text_sleep = 1 * ( $ct['processed_msgs']['text_count'] > 0 ? $ct['processed_msgs']['text_count'] : 1 );
                   sleep($text_sleep);
                    
                   $result = @$ct['gen']->safe_mail( $ct['gen']->text_email($ct['conf']['comms']['to_mobile_text']) , $textemail_array['subject'], $textemail_array['message'], $textemail_array['content_type'], $textemail_array['charset']);
                    
                      if ( $result == true ) {
                      
                      $ct['processed_msgs']['text_count'] = $ct['processed_msgs']['text_count'] + 1;
                     
                      $msg_sent = 1;
                   
                      unlink($ct['base_dir'] . '/cache/secured/messages/' . $queued_cache_file);
                      
                      }
                      else {
                      	
                      $ct['gen']->log(
                      			'system_error',
                      			'Email-to-mobile-text sending failed',
                      			'to_text_email: ' . $ct['gen']->text_email($ct['conf']['comms']['to_mobile_text']) . '; from: ' . $ct['conf']['comms']['from_email'] . '; subject: ' . $textemail_array['subject'] . '; function_response: ' . $result . ';'
                      			);
                      
                      }
                   
                   
                   }
               
               
               }
                 
                 
                 
               // Normal email
               elseif ( $ct['email_activated'] && preg_match("/normalemail/i", $queued_cache_file) ) {
                 
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
                   $email_sleep = 1 * ( $ct['processed_msgs']['email_count'] > 0 ? $ct['processed_msgs']['email_count'] : 1 );
                   sleep($email_sleep);
                    
                   $result = @$ct['gen']->safe_mail($ct['conf']['comms']['to_email'], $email_array['subject'], $email_array['message'], $email_array['content_type'], $email_array['charset']);
                    
                      if ( $result == true ) {
                      
                      $ct['processed_msgs']['email_count'] = $ct['processed_msgs']['email_count'] + 1;
                     
                      $msg_sent = 1;
                   
                      unlink($ct['base_dir'] . '/cache/secured/messages/' . $queued_cache_file);
                      
                      }
                      else {
                      	
                      $ct['gen']->log(
                      			'system_error',
                      			'Email sending failed',
                      			'to_email: ' . $ct['conf']['comms']['to_email'] . '; from: ' . $ct['conf']['comms']['from_email'] . '; subject: ' . $email_array['subject'] . '; function_response: ' . $result . ';'
                      			);
                      
                      }
                      
                   
                   }
               
               
               }
                
                
          }
          // No data in message queue file
          else {
          unlink($ct['base_dir'] . '/cache/secured/messages/' . $queued_cache_file);
          }

        
        }
       
       
       
        if ( $msg_sent == 1 ) {
        $ct['processed_msgs']['notifications_count'] = $ct['processed_msgs']['notifications_count'] + 1;
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
  
  global $ct, $htaccess_username, $htaccess_password;
   
  // To cache duplicate requests based on a data hash, during runtime update session (AND persist cache to flat files)
  $hash_check = ( $mode == 'params' ? md5( serialize($request_params) ) : md5($request_params) );
  
  // Cache path, for checks etc
  $cached_path = $ct['base_dir'] . '/cache/secured/external_data/'.$hash_check.'.dat';
       
  
    // We should be able to make sure there is no whitespace safely on API Server
    if ( $api_server != null ) {
    $api_server = trim($api_server);
    }
  
  $api_endpoint = ( $mode == 'params' ? $api_server : $request_params );
     
  $endpoint_tld_or_ip = $ct['gen']->get_tld_or_ip($api_endpoint);
  
  
    if ( $endpoint_tld_or_ip == 'alphavantage.co' && $ct['conf']['ext_apis']['alphavantage_api_key'] == '' ) {
    
    $ct['gen']->log(
          		    'notify_error',
          		    '"alphavantage_api_key" (free API key) is not configured in Admin Config EXTERNAL APIS section',
          		    false,
          		    'alphavantage_api_key'
          		    );
    
    return false;
    
    }
    elseif ( $endpoint_tld_or_ip == 'etherscan.io' && $ct['conf']['ext_apis']['etherscan_api_key'] == '' ) {
    
    $ct['gen']->log(
          		    'notify_error',
          		    '"etherscan_api_key" (free API key) is not configured in Admin Config EXTERNAL APIS section',
          		    false,
          		    'etherscan_api_key'
          		    );
    
    return false;
    
    }

  
  // IPV6 friendly filename (no illegal filename characters)
  $safe_name = $ct['gen']->compat_file_name($endpoint_tld_or_ip);
  
  $tld_session_prefix = preg_replace("/\./i", "_", $endpoint_tld_or_ip);
  
  $cookie_file = $ct['base_dir'] . '/cache/secured/external_data/cookies/ext_dat_cookie_' . $ct['app_id'] . '_' . $endpoint_tld_or_ip . '.dat';
      
  // FAILSAFE (< V6.00.29 UPGRADES), IF UPGRADE MECHANISM FAILS FOR WHATEVER REASON
  $temp_array = array();
  $anti_proxy_servers = ( is_array($ct['conf']['proxy']['anti_proxy_servers']) ? $ct['conf']['proxy']['anti_proxy_servers'] : $temp_array );

             
    if ( $ct['activate_proxies'] == 'on' && is_array($ct['conf']['proxy']['proxy_list']) && sizeof($ct['conf']['proxy']['proxy_list']) > 0 && !in_array($endpoint_tld_or_ip, $anti_proxy_servers) ) {
    $ip_description = 'PROXY';
    }
    else {
    $ip_description = 'SERVER';
    }
    
   
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
    unlink($cached_path);
    }
    //////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////
    // FIRST, see if we have data in the RUNTIME cache (the MEMORY cache, NOT the FILE cache), for the quickest data retrieval time
    // Only use runtime cache if $ttl greater than zero (set as 0 NEVER wants cached data, -1 is flag for deleting cache data)
    //////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////
    elseif ( isset($ct['api_runtime_cache'][$hash_check]) && $ttl > 0 ) {
    
    $data = $ct['api_runtime_cache'][$hash_check];
    
    // Size of data, for checks in error log UX logic
    $data_bytes = strlen($data);
    $data_bytes_ux = $ct['gen']->conv_bytes($data_bytes, 2);
    
     
      if ( $data == 'none' ) {
    
      $data_bytes_ux = 'data flagged as none'; // OVERWRITE 
      
      
        if ( !$ct['log_errors']['error_duplicates'][$hash_check] ) {
        $ct['log_errors']['error_duplicates'][$hash_check] = 1; 
        }
        else {
        $ct['log_errors']['error_duplicates'][$hash_check] = $ct['log_errors']['error_duplicates'][$hash_check] + 1;
        }
       
       
      // Don't log this error again during THIS runtime, as it would be a duplicate...just overwrite same error message, BUT update the error count in it
      
      $ct['gen']->log(
      			'cache_error',
      							
      			'no RUNTIME CACHE data from failure with ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . $api_endpoint,
      							
      			'requested_from: cache ('.$ct['log_errors']['error_duplicates'][$hash_check].' runtime instances); mode: ' . $mode . '; received: ' . $data_bytes_ux . '; hash_check: ' . $ct['sec']->obfusc_str($hash_check, 4) . ';',
      							
      			$hash_check
      			);
       
      }
      
      
      if ( $ct['conf']['power']['debug_mode'] == 'ext_data_cache_telemetry' ) {
      
      
        if ( !$ct['log_debugging']['debug_duplicates'][$hash_check] ) {
        $ct['log_debugging']['debug_duplicates'][$hash_check] = 1; 
        }
        else {
        $ct['log_debugging']['debug_duplicates'][$hash_check] = $ct['log_debugging']['debug_duplicates'][$hash_check] + 1;
        }
       
       
      // Don't log this debugging again during THIS runtime, as it would be a duplicate...just overwrite same debugging message, BUT update the debugging count in it
      
      $ct['gen']->log(
      			'cache_debug',
      							
      			'RUNTIME CACHE request for ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . $api_endpoint,
      							
      			'requested_from: cache ('.$ct['log_debugging']['debug_duplicates'][$hash_check].' runtime instances); mode: ' . $mode . '; received: ' . $data_bytes_ux . '; hash_check: ' . $ct['sec']->obfusc_str($hash_check, 4) . ';',
      							
      			$hash_check
      			);
      
      }
    
    
    }
    //////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////
    // Live data retrieval (if no RUNTIME cache exists yet, OR ttl set to zero [explicit request to NOT use cached data])
    //////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////
    elseif ( !isset($ct['api_runtime_cache'][$hash_check]) && $this->update_cache($cached_path, $ttl) == true || $ttl == 0 ) {
    
    // Track the request response time
    $api_time = microtime();
    $api_time = explode(' ', $api_time);
    $api_time = $api_time[1] + $api_time[0];
    $api_start_time = $api_time;
              
      
      // Servers requiring TRACKED THROTTLE-LIMITING ******BASED OFF API REQUEST COUNT******, due to limited-allowed minute / hour / daily requests
      // (are processed by api_throttling(), to avoid using up request limits getting LIVE DATA)
      if ( $this->api_throttling($endpoint_tld_or_ip) == true ) {
            
      // Set $data var with any cached value (null / false result is OK), as we don't want to cache any PROBABLE error response
      // (will be set / reset as 'none' further down in the logic and cached / recached for a TTL cycle, if no cached data exists to fallback on)
      $data = trim( file_get_contents($cached_path) );
      
      // DON'T USE isset(), use != '' to store as 'none' reliably (so we don't keep hitting a server that may be throttling us, UNTIL cache TTL runs out)
      $ct['api_runtime_cache'][$hash_check] = ( isset($data) && $data != '' ? $data : 'none' ); 
             
                
          // Flag if cache fallback succeeded
          if ( isset($data) && $data != '' && $data != 'none' ) {
          $fallback_cache_data = true;
          }
          
      
          // (we're deleting any pre-existing cache data here, AND RETURNING FALSE TO AVOID RE-SAVING ANY CACHE DATA, *ONLY IF* IT FAILS TO
          // FALLBACK ON VALID API DATA, SO IT CAN "GET TO THE FRONT OF THE THROTTLED LINE" THE NEXT TIME IT'S REQUESTED)
          if ( !isset($fallback_cache_data) ) {
               
          $ct['gen']->log('ext_data_error', 'cache fallback FAILED during (LIVE) throttling of API for: ' . $endpoint_tld_or_ip);
          
          unset($ct['api_runtime_cache'][$hash_check]);
          
          unlink($cached_path);
          
          return false;
          
          }
                
                
      gc_collect_cycles(); // Clean memory cache
      
      return $data;
                
                
      }
    
    
      // LIMITED endpoints we should throttle (APIs with poor multiple-data-sets support) in $ct['dev']['limited_apis']
      // If this is an API service that requires multiple calls (for each market), 
      // and a request to it has been made consecutively, we throttle it to avoid being blocked / throttled by external server
      if ( in_array($endpoint_tld_or_ip, $ct['dev']['limited_apis']) ) {
      
        if ( !$ct['limited_api_calls'][$tld_session_prefix . '_calls'] ) {
        $ct['limited_api_calls'][$tld_session_prefix . '_calls'] = 1;
        }
        elseif ( $ct['limited_api_calls'][$tld_session_prefix . '_calls'] == 1 ) {
        usleep(550000); // Throttle 0.55 seconds
        }
    
      }
     
    
    // Initiate the curl external data request
    $ch = curl_init( ( $mode == 'params' ? $api_server : '' ) );
     
     
      // Use our own cached CURL CACERT data (to avoid invalid CA cert errors)
      if ( $ct['curl_cacert_path'] ) {
      curl_setopt($ch, CURLOPT_CAINFO, $ct['curl_cacert_path']);
      }
     
     
      // If header data is being passed in
      if ( $headers != null ) {
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      }
      
      
      // If proxies are configured FOR PRIVACY
      if ( $ct['activate_proxies'] == 'on' && is_array($ct['conf']['proxy']['proxy_list']) && sizeof($ct['conf']['proxy']['proxy_list']) > 0 && !in_array($endpoint_tld_or_ip, $anti_proxy_servers) ) {
       
      $current_proxy = ( $mode == 'proxy-check' && $test_proxy != null ? $test_proxy : $ct['var']->random_array_var($ct['conf']['proxy']['proxy_list']) );
      
      // Check for BASIC valid proxy config params
      $ip_port = explode(':', $current_proxy);
    
      $ip = $ip_port[0];
      $port = $ip_port[1];
    
    
        // If no ip/port detected in data string, cancel and continue runtime
        if ( !$ip || !$port ) {
        $ct['gen']->log('ext_data_error', 'proxy '.$current_proxy.' is not a valid format');
        return false;
        }
    
      
      curl_setopt($ch, CURLOPT_PROXY, trim($current_proxy) );     
      curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, true);  
          
        
        // API servers that don't like the user-setup proxy servers
        // To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
        if ( $ct['conf']['proxy']['proxy_login'] != '' ) {
       
        $user_pass = explode('||', $ct['conf']['proxy']['proxy_login']);
         
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, $user_pass[0] . ':' . $user_pass[1]); // DO NOT ENCAPSULATE PHP USER/PASS VARS IN QUOTES, IT BREAKS THE FEATURE
        
        }
        
      
      }
      // Otherwise, allow using cookies
      else {
      curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
      curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
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
    
    
    // Compatibility settings
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    
    // Timeout settings
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $ct['conf']['ext_apis']['remote_api_timeout']);
    curl_setopt($ch, CURLOPT_TIMEOUT, $ct['conf']['ext_apis']['remote_api_timeout']);
              
              
    // FAILSAFE (< V6.00.29 UPGRADES), IF UPGRADE MECHANISM FAILS FOR WHATEVER REASON
    $temp_array = array();
    $strict_news_feed_servers = ( is_array($ct['conf']['news']['strict_news_feed_servers']) ? $ct['conf']['news']['strict_news_feed_servers'] : $temp_array );
          
     
      // RSS feed services that are a bit funky with allowed user agents, so we need to let them know this is a real feed parser (not just a spammy bot)
      if ( in_array($endpoint_tld_or_ip, $strict_news_feed_servers) ) {
      curl_setopt($ch, CURLOPT_USERAGENT, 'RSS_Feed_Parser/1.1 (compatible; Open_Crypto_Tracker/' . $ct['app_version'] . '; +https://github.com/taoteh1221/Open_Crypto_Tracker)');
      }
      // Strict user agent
      elseif ( in_array($endpoint_tld_or_ip, $anti_proxy_servers) ) {
      curl_setopt($ch, CURLOPT_USERAGENT, $ct['strict_curl_user_agent']);
      }
      // Regular user agent
      elseif ( isset($ct['curl_user_agent']) ) {
      curl_setopt($ch, CURLOPT_USERAGENT, $ct['curl_user_agent']);
      }
      // FAILSAFE / DEFAULT TO STRICT USER AGENT (FOR ADMIN INPUT VALIDATION ETC ETC)
      else {
      curl_setopt($ch, CURLOPT_USERAGENT, $ct['strict_curl_user_agent']);
      }
      
    
    // Are we calling an endpoint that needs login authorization?
    
    // In case we are calling an endpoint on this local machine, we need to make a valid regex to check for that
    $regex_base_url = $ct['gen']->regex_compat_path($ct['base_url']);
       
    // Secure random hash to nullify any preg_match() below, if $regex_base_url FAILS to set a value above,
    // as we may be submitting out htaccess user/pass (if setup)
    $scan_base_url = ( isset($regex_base_url) && $regex_base_url != '' ? $regex_base_url : $ct['sec']->rand_hash(8) );
      
      
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
      // (as we may be running our own self-signed certificate),
      // so flag it as such for the SSL config later
      $remote_api_is_self = true;
        
      }
      // If this is a twilio endpoint, we need to authenticate
      elseif ( $endpoint_tld_or_ip == 'twilio.com' ) {
      curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
      // DO NOT ENCAPSULATE PHP USER/PASS VARS IN QUOTES, IT BREAKS THE FEATURE
      curl_setopt($ch, CURLOPT_USERPWD, $ct['conf']['ext_apis']['twilio_sid'] . ':' . $ct['conf']['ext_apis']['twilio_token']); 
      }
     
     
      // If this is an SSL connection, add SSL parameters
      if ( preg_match("/https:\/\//i", $api_endpoint) ) {
      
      // Skip any strict SSL setting, IF we are calling the same machine we are running this app on
      $remote_api_strict_ssl = ( $remote_api_is_self ? 'off' : $ct['conf']['sec']['remote_api_strict_ssl'] );
      
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
    $data_bytes_ux = $ct['gen']->conv_bytes($data_bytes, 2);
    
    
      // IF DEBUGGING FOR PROBLEM ENDPOINT IS ENABLED
      if ( $debug_problem_endpoint_data ) {
      
      // Response data
      $debug_header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
      $debug_header = substr($data, 0, $debug_header_size);
      $debug_body = substr($data, $debug_header_size);
      
      // Debugging output
      $debug_data = "\n\n\n" . 'header_size: ' . $debug_header_size . ' bytes' . "\n\n\n" . 'header: ' . "\n\n\n" . $debug_header . "\n\n\n" . 'body: ' . "\n\n\n" . $debug_body . "\n\n\n";
      
      $debug_response_log = $ct['base_dir'] . '/cache/logs/debug/external_data/problem-endpoint-' . $safe_name . '-hash-'.$hash_check.'-timestamp-'.time().'.log';
      
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
    
      
      // LIVE data debugging telemetry
      if ( $ct['conf']['power']['debug_mode'] == 'ext_data_live_telemetry' ) {
         
      // LOG-SAFE VERSION (no post data with API keys etc)
      $ct['gen']->log(
        			'ext_data_debug',
        								
        			'LIVE request for ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . $api_endpoint,
        								
        			'requested_from: server (' . $ct['conf']['ext_apis']['remote_api_timeout'] . ' second timeout); live_request_time: ' . $api_total_time . ' seconds; mode: ' . $mode . '; received: ' . $data_bytes_ux . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; hash_check: ' . $ct['sec']->obfusc_str($hash_check, 4) . ';'
        			);
        
      // Log this as the latest response from this data request
      $this->save_file($ct['base_dir'] . '/cache/logs/debug/external_data/last-response-' . $safe_name . '-'.$hash_check.'.log', $data);
        
      }
     
     
      // No data error logging, ONLY IF THIS IS #NOT# A SELF SECURITY TEST NEW INSTALLS WILL RUN
      // !!!!!!!!!!!!!!!!!NEVER RUN $data THROUGH trim() FOR CHECKS ETC, AS trim() CAN FLIP OUT AND RETURN NULL IF OBSCURE SYMBOLS ARE PRESENT!!!!!!!!!!!!!!!!!
      if ( $data == '' && $is_self_security_test != 1 ) {
       
      // FALLBACK TO FILE CACHE DATA, IF AVAILABLE (WE STILL LOG THE FAILURE, SO THIS OS OK)
      // (NO LOGIC NEEDED TO CHECK RUNTIME CACHE, AS WE ONLY ARE HERE IF THERE IS NONE)
       
      $data = trim( file_get_contents($cached_path) );
        
        
        // IF CACHE DATA EXISTS, flag cache fallback as succeeded, and IMMEADIATELY add data set to runtime cache / update the file cache timestamp
        // (so all following requests DURING THIS RUNTIME are run from cache ASAP, since we had a live request failure)
        if ( isset($data) && $data != '' && $data != 'none' ) {
        $fallback_cache_data = true;
        // IMMEADIATELY RUN THIS LOGIC NOW, EVEN THOUGH IT RUNS AT END OF STATEMENT TOO, SINCE WE HAD A LIVE REQUEST FAILURE
        $ct['api_runtime_cache'][$hash_check] = $data;
        touch($cached_path); // Update cache file time
        }
        
        
        if ( isset($fallback_cache_data) ) {
        $log_append = ' (cache fallback SUCCEEDED)';
        }
        else {
        $log_append = ' (cache fallback FAILED)';
        }
     
      
      // LOG-SAFE VERSION (no post data with API keys etc)
      $ct['gen']->log(
      
      			'ext_data_error',
      							
      			$ip_description . ' connection failed ('.$data_bytes_ux.' received) for ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . $api_endpoint . $log_append,
      							
      			'requested_from: server (' . $ct['conf']['ext_apis']['remote_api_timeout'] . ' second timeout); live_request_time: ' . $api_total_time . ' seconds; mode: ' . $mode . '; received: ' . $data_bytes_ux . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; hash_check: ' . $ct['sec']->obfusc_str($hash_check, 4) . ';'
      			
      			);
      			
      			
        // Servers which are known to block API access by location / jurisdiction
        // (we alert end-users in error logs, when a corresponding API server connection fails [one-time notice per-runtime])
        if ( in_array($endpoint_tld_or_ip, $ct['dev']['location_blocked_servers']) ) {

            
        $ct['gen']->log(
        
          		'notify_error',

          		'your ' . $ip_description . '\'S IP ADDRESS location / jurisdiction *MAY* be blocked from accessing the "'.$endpoint_tld_or_ip.'" API, *IF* THIS ERROR REPEATS *VERY OFTEN*',

          		false,

          		md5($endpoint_tld_or_ip) . '_possibly_blocked'

          		);
          		    
        }
      
      
        if ( $ct['activate_proxies'] == 'on' && is_array($ct['conf']['proxy']['proxy_list']) && sizeof($ct['conf']['proxy']['proxy_list']) > 0 && isset($current_proxy) && $current_proxy != '' && $mode != 'proxy-check' ) { // Avoid infinite loops doing proxy checks
     
        $ct['proxy_checkup'][] = array(
                    			'endpoint' => ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . $api_endpoint,
                    			'proxy' => $current_proxy
                    			);
                    
        }
      
      
      }
      // Scan this latest live data response for POSSIBLE errors, 
      // ONLY IF WE DETECT AN $endpoint_tld_or_ip, AND TTL IS !NOT! ZERO (TTL==0 usually means too many unique requests that would bloat the cache)
      elseif ( isset($data) && isset($endpoint_tld_or_ip) && $endpoint_tld_or_ip != '' && $ttl != 0 ) {
      
      
        ////////////////////////////////////////////////////////////////	
        // Checks for error false positives, BEFORE CHECKING FOR A POSSIBLE ERROR
        // https://www.php.net/manual/en/regexp.reference.meta.php
        // DON'T ADD TOO MANY CHECKS HERE, OR RUNTIME WILL SLOW SIGNIFICANTLY!!
        if ( 
        preg_match("/xml version/i", $data) // RSS feeds (that are likely intact)
        || preg_match("/\"error\":\[\],/i", $data) // kraken.com / generic
        || preg_match("/warning-icon/i", $data)  // Medium.com RSS feeds
        || preg_match("/\"error_code\":0/i", $data) // Generic
        ) {
        $false_positive = true;
        }
       
       
        // DON'T FLAG as a possible error if detected as a false positive already
        // (THIS LOGIC IS FOR STORING THE POSSIBLE ERROR IN /cache/logs/error/external_data FOR REVIEW)
        if ( !$false_positive ) {
         
            // MUST RUN BEFORE FALLBACK ATTEMPT TO CACHED DATA
            // If response seems to contain an error message ('error' STRICTLY found once [no sentences containing ' error '])
            // DON'T ADD TOO MANY CHECKS HERE, OR RUNTIME WILL SLOW SIGNIFICANTLY!!
            // ALSO, SKIP THIS 'POSSIBLE ERROR' IF WE ARE JUST DOING AN ASSET SEARCH!!
            if (
            !$ct['ticker_markets_search']
            && $ct['var']->substri_count($data, 'error') == 1
            && $ct['var']->substri_count($data, ' error ') < 1
            && $ct['var']->substri_count($data, 'terror') < 1
            ) {
             
            // Log full results to file, WITH UNIQUE TIMESTAMP IN FILENAME TO AVOID OVERWRITES (FOR ADEQUATE DEBUGGING REVIEW)
            $error_response_log = '/cache/logs/error/external_data/error-response-' . $safe_name . '-hash-'.$hash_check.'-timestamp-'.time().'.log';
            
            // LOG-SAFE VERSION (no post data with API keys etc)
             $ct['gen']->log(
             
             			'ext_data_error',
             							
             			'POSSIBLE error for ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . $api_endpoint,
             							
             			'requested_from: server (' . $ct['conf']['ext_apis']['remote_api_timeout'] . ' second timeout); live_request_time: ' . $api_total_time . ' seconds; mode: ' . $mode . '; received: ' . $data_bytes_ux . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; debug_file: ' . $error_response_log . '; bitcoin_primary_currency_pair: ' . $ct['conf']['currency']['bitcoin_primary_currency_pair'] . '; bitcoin_primary_currency_exchange: ' . $ct['conf']['currency']['bitcoin_primary_currency_exchange'] . '; sel_btc_prim_currency_val: ' . $ct['var']->num_to_str($ct['sel_opt']['sel_btc_prim_currency_val']) . '; hash_check: ' . $ct['sec']->obfusc_str($hash_check, 4) . ';'
             			
             			);
            
            // Log this error response from this data request
            $this->save_file($ct['base_dir'] . $error_response_log, $data);
             
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
            preg_match("/cf-error/i", $data) // Cloudflare (DDOS protection service)
            || preg_match("/cf-browser/i", $data) // Cloudflare (DDOS protection service)
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
            || preg_match("/\"result\":\[\],/i", $data) // Generic
            || preg_match("/\"results\":\[\],/i", $data) // generic
            || preg_match("/\"data\":null/i", $data) // Bitflyer.com / generic
            || preg_match("/\"success\":false/i", $data) // BTCturk.com / generic
            || preg_match("/\"error\":\"timeout/i", $data) // generic
            || preg_match("/\"reason\":\"Maintenance\"/i", $data) // Gemini.com / generic
            || preg_match("/not found/i", $data)  // Generic
            || preg_match("/missing a valid API key/i", $data) // Google / generic
            || preg_match("/if you would like to target a higher API call/i", $data)  // Alphavantage
            || preg_match("/block access from your country/i", $data)  // ByBit (via Amazon CloudFront)
            // API-specific (confirmed no price data in response)
            || $endpoint_tld_or_ip == 'coinmarketcap.com' && !preg_match("/last_updated/i", $data) 
            || $endpoint_tld_or_ip == 'jup.ag' && !preg_match("/price/i", $data) && !preg_match("/symbol/i", $data)
            || $endpoint_tld_or_ip == 'alphavantage.co' && !preg_match("/price/i", $data) 
            // API-specific (confirmed error in response)
            || $endpoint_tld_or_ip == 'coingecko.com' && preg_match("/supported_vs_currencies/i", $request_params) && !preg_match("/btc/i", $data)
            || $endpoint_tld_or_ip == 'coingecko.com' && preg_match("/simple\/price/i", $request_params) && !preg_match("/24h_vol/i", $data) 
            || $endpoint_tld_or_ip == 'coingecko.com' && preg_match("/search/i", $request_params) && !preg_match("/api_symbol/i", $data)  
            || $endpoint_tld_or_ip == 'coingecko.com' && preg_match("/coins/i", $request_params) && !preg_match("/name/i", $data) 
            ) {
                 
                 
                 // IMMEADIATELY adjust API throttling for coingecko, as under loads they decrease API limits up to 66%!
                 // https://support.coingecko.com/hc/en-us/articles/4538771776153-What-is-the-rate-limit-for-CoinGecko-API-public-plan
                 if ( $endpoint_tld_or_ip == 'coingecko.com' && $ct['throttled_api_per_minute_limit']['coingecko.com'] > 5 ) {
                      
                      // INCREMENTALLY, DOWN TO 5 CALLS PER MINUTE
                      if ( $ct['throttled_api_per_minute_limit']['coingecko.com'] >= 15 ) {
                      $ct['throttled_api_per_minute_limit']['coingecko.com'] = 10;
                      }
                      elseif ( $ct['throttled_api_per_minute_limit']['coingecko.com'] >= 10 ) {
                      $ct['throttled_api_per_minute_limit']['coingecko.com'] = 5;
                      }
                     
                 }
                 
            
            // Reset $data var with any cached value (null / false result is OK), as we don't want to cache a KNOWN error response
            // (will be set / reset as 'none' further down in the logic and cached / recached for a TTL cycle, if no cached data exists to fallback on)
            $data = trim( file_get_contents($cached_path) );
             
                
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
                     
                 
                // FREE alphavantage API tier limit hit ERROR LOGGING
                if ( 
                $endpoint_tld_or_ip == 'alphavantage.co' && $ct['conf']['ext_apis']['alphavantage_per_minute_limit'] <= 5 && preg_match("/api rate limit/i", $data)
                || $endpoint_tld_or_ip == 'alphavantage.co' && $ct['conf']['ext_apis']['alphavantage_per_minute_limit'] <= 5 && $data == ''
                ) {
                     
                          
                      if ( preg_match("/api rate limit/i", $data) ) {
                      $desc = 'HAS';
                      }
                      else {
                      $desc = 'MAY HAVE';
                      }
                     
                     
                $ct['gen']->log(
                   		    'notify_error',
                   		    'your FREE tier API key for "' . $endpoint_tld_or_ip . '" ' . $desc . ' hit it\'s DAILY LIMIT for LIVE data requests (this is USUALLY auto-throttled [to stay within limits], BUT if you recently installed OR updated "' . $endpoint_tld_or_ip . '" markets, YOU MAY NEED TO WAIT ~24 HOURS for this issue to start auto-fixing itself, OR upgrade to the premium tier at: alphavantage.co/premium [AND raise the auto-throttle limits in "Admin => APIs => External APIs"])',
                   		    false,
                   		    'no_market_data_' . $endpoint_tld_or_ip
                   		    );
                   		    
                }
                // Everything else ERROR LOGGING
                else {
             
                // LOG-SAFE VERSION (no post data with API keys etc)
                $ct['gen']->log(
            
            			'ext_data_error',
            							
            			'CONFIRMED error for ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . $api_endpoint . $log_append,
            							
            			'requested_from: server (' . $ct['conf']['ext_apis']['remote_api_timeout'] . ' second timeout); live_request_time: ' . $api_total_time . ' seconds; mode: ' . $mode . '; received: ' . $data_bytes_ux . '; proxy: ' .( $current_proxy ? $current_proxy : 'none' ) . '; bitcoin_primary_currency_pair: ' . $ct['conf']['currency']['bitcoin_primary_currency_pair'] . '; bitcoin_primary_currency_exchange: ' . $ct['conf']['currency']['bitcoin_primary_currency_exchange'] . '; sel_btc_prim_currency_val: ' . $ct['var']->num_to_str($ct['sel_opt']['sel_btc_prim_currency_val']) . '; hash_check: ' . $ct['sec']->obfusc_str($hash_check, 4) . ';'
            			
            			);
             
      			
                     // Servers which are known to block API access by location / jurisdiction
                     // (we alert end-users in error logs, when a corresponding API server connection fails [one-time notice per-runtime])
                     if ( in_array($endpoint_tld_or_ip, $ct['dev']['location_blocked_servers']) ) {
               
                     $ct['gen']->log(
                       
                         		'notify_error',
     
                         		'your ' . $ip_description . '\'S IP ADDRESS location / jurisdiction *MAY* be blocked from accessing the "'.$endpoint_tld_or_ip.'" API, *IF* THIS ERROR REPEATS *VERY OFTEN*',
     
                         		false,
     
                         		md5($endpoint_tld_or_ip) . '_possibly_blocked'
     
                         		);
                         		    
                     }
                       
                   
                }
                
           
            }
    
       
        }
       
       
      }
    
     
     
     
      // Cache data to the file cache, EVEN IF WE HAVE NO DATA, TO AVOID CONSECUTIVE TIMEOUT HANGS (during page reloads etc) FROM A NON-RESPONSIVE API ENDPOINT
      // Cache API data for this runtime session AFTER PERSISTENT FILE CACHE UPDATE, file cache doesn't reliably update until runtime session is ending because of file locking
      // WE RE-CACHE DATA EVEN IF THIS WAS A FALLBACK TO CACHED DATA, AS WE WANT TO RESET THE TTL UNTIL NEXT LIVE API CHECK
      if ( $ttl > 0 && $mode != 'proxy-check' ) {
      
      // DON'T USE isset(), use != '' to store as 'none' reliably (so we don't keep hitting a server that may be throttling us, UNTIL cache TTL runs out)
      $ct['api_runtime_cache'][$hash_check] = ( isset($data) && $data != '' ? $data : 'none' ); 
      
        // Fallback just needs 'modified time' updated with touch()
        if ( isset($fallback_cache_data) ) {
        $store_file_contents = touch($cached_path);
        }
        else {
        $store_file_contents = $this->save_file($cached_path, $ct['api_runtime_cache'][$hash_check]);
        }
        
       
        if ( $store_file_contents == false && isset($fallback_cache_data) ) {
        	
        $ct['gen']->log(
        			'ext_data_error',
        			'Cache file touch() error for ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . $api_endpoint,
        			'data_size_bytes: ' . strlen($ct['api_runtime_cache'][$hash_check]) . ' bytes'
        			);
        
        }
        elseif ( $store_file_contents == false && !isset($fallback_cache_data) ) {
        	
        $ct['gen']->log(
        			'ext_data_error',
        			'Cache file write error for ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . $api_endpoint,
        			'data_size_bytes: ' . strlen($ct['api_runtime_cache'][$hash_check]) . ' bytes'
        			);
        
        }
      
      
      }
      // NEVER cache proxy checking data, OR TTL == 0
      elseif ( $mode == 'proxy-check' || $ttl == 0 ) {
      unset($ct['api_runtime_cache'][$hash_check]); 
      }
     
   
      // API timeout limit near / exceeded warning (ONLY IF THIS ISN'T A DATA FAILURE)
      if ( $data_bytes > 0 && $ct['var']->num_to_str($ct['conf']['ext_apis']['remote_api_timeout'] - 1) <= $ct['var']->num_to_str($api_total_time) ) {
      	
      $ct['gen']->log(
      
      			'notify_error',
      							
      			'Remote API timeout near OR exceeded for ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . $api_endpoint . ' (' . $api_total_time . ' seconds / received ' . $data_bytes_ux . '), consider setting "remote_api_timeout" higher in EXTERNAL APIS config *IF* this persists OFTEN',
      							
      			'remote_api_timeout: ' . $ct['conf']['ext_apis']['remote_api_timeout'] . ' seconds; live_request_time: ' . $api_total_time . ' seconds; mode: ' . $mode . '; received: ' . $data_bytes_ux . ';',
      							
      			$hash_check
      			
      			);
      
      }
    
    
    }
    //////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////
    // IF --FILE-- CACHE DATA WITHIN IT'S TTL EXISTS (so we use CACHED data)
    //////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////
    else {
      
    
      // Use runtime cache if it exists. Remember file cache doesn't update until session is nearly over because of file locking, so only reliable for persisting a cache long term
      // If no API data was received, add error notices to UI / error logs (we don't try fetching the data again until cache TTL expiration, so as to NOT hang the app)
      // Run from runtime cache if requested again (for runtime speed improvements)
      if ( isset($ct['api_runtime_cache'][$hash_check]) && $ct['api_runtime_cache'][$hash_check] != '' && $ct['api_runtime_cache'][$hash_check] != 'none' ) {
      $data = $ct['api_runtime_cache'][$hash_check];
      $fallback_cache_data = true;
      }
      else {
        
      $data = trim( file_get_contents($cached_path) );
      
        if ( isset($data) && $data != '' && $data != 'none' ) {
        $ct['api_runtime_cache'][$hash_check] = $data; // Create a runtime cache from the file cache, for any additional requests during runtime for this data set
        $fallback_cache_data = true;
        }
       
      }
    
    
      // Servers requiring 'throttled_api_min_cache_time' THROTTLE-LIMITING ******BASED OFF API CACHED TIME******,
      // due to limited-allowed daily requests
      if (
      isset($ct['throttled_api_min_cache_time'][$endpoint_tld_or_ip])
      && $this->update_cache($cached_path, $ct['throttled_api_min_cache_time'][$endpoint_tld_or_ip]) == false
      ) {
          
          
          // (we're deleting any pre-existing cache data here, AND RETURNING FALSE TO AVOID RE-SAVING ANY CACHE DATA, *ONLY IF* IT FAILS TO
          //  FALLBACK ON VALID API DATA, SO IT CAN "GET TO THE FRONT OF THE THROTTLED LINE" THE NEXT TIME IT'S REQUESTED)
          if ( !isset($fallback_cache_data) ) {
               
          $ct['gen']->log('ext_data_error', 'cached fallback FAILED during "throttled_api_min_cache_time" throttling of API for: ' . $endpoint_tld_or_ip);
          
          unset($ct['api_runtime_cache'][$hash_check]);
          
          unlink($cached_path);
          
          return false;
          
          }
          
                
      }
    
    
      // Size of data, for checks in error log UX logic
      if ( $data == 'none' ) {
      $data_bytes_ux = 'data flagged as none';
      }
      else {
      $data_bytes = strlen($data);
      $data_bytes_ux = $ct['gen']->conv_bytes($data_bytes, 2);
      }

     
      // Only do FILE CACHE error logging if we HAVE NOT YET set this file cache data as 'none', 
      // for logging UX (avoid exessive log entries EVERY RUNTIME that is using cached data)
      if ( $data == '' ) {
      
        if ( !$ct['log_errors']['error_duplicates'][$hash_check] ) {
        $ct['log_errors']['error_duplicates'][$hash_check] = 1; 
        }
        else {
        $ct['log_errors']['error_duplicates'][$hash_check] = $ct['log_errors']['error_duplicates'][$hash_check] + 1;
        }
       
      // Don't log this error again during THIS runtime, as it would be a duplicate...just overwrite same error message, BUT update the error count in it
      
      $ct['gen']->log(
      
      			'cache_error',
      							
      			'no FILE CACHE data from recent failure with ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . $api_endpoint,
      							
      			'requested_from: cache ('.$ct['log_errors']['error_duplicates'][$hash_check].' runtime instances); mode: ' . $mode . '; received: ' . $data_bytes_ux . '; hash_check: ' . $ct['sec']->obfusc_str($hash_check, 4) . ';',
      							
      			$hash_check
      			
      			);
       
      }
      
      
      if ( $ct['conf']['power']['debug_mode'] == 'ext_data_cache_telemetry' ) {
      
        if ( !$ct['log_debugging']['debug_duplicates'][$hash_check] ) {
        $ct['log_debugging']['debug_duplicates'][$hash_check] = 1; 
        }
        else {
        $ct['log_debugging']['debug_duplicates'][$hash_check] = $ct['log_debugging']['debug_duplicates'][$hash_check] + 1;
        }
        
        
        if ( $data == 'none' ) {
        $log_append = ' (FLAGGED AS ERROR / NO DATA FROM LIVE REQUEST)';
        }
        
        
      // Don't log this debugging again during THIS runtime, as it would be a duplicate...just overwrite same debugging message, BUT update the debugging count in it
      
      $ct['gen']->log(
      
      			'cache_debug',
      							
      			'FILE CACHE request for ' . ( $mode == 'params' ? 'server at ' : 'endpoint at ' ) . $api_endpoint . $log_append,
      							
      			'requested_from: cache ('.$ct['log_debugging']['debug_duplicates'][$hash_check].' runtime instances); mode: ' . $mode . '; received: ' . $data_bytes_ux . '; hash_check: ' . $ct['sec']->obfusc_str($hash_check, 4) . ';',
      							
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
