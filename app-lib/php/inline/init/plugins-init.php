<?php
/*
 * Copyright 2014-2026 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


//////////////////////////////////////////////////////////////////
// PLUGINS INIT
//////////////////////////////////////////////////////////////////


// Dynamically revise available plugins config, by scanning the plugins directory
// (also triggers a config update if any changes / not a config reset happening)
$ct['gen']->refresh_plugins_list();


// Configs for any plugins activated in ct_conf
foreach ( $ct['conf']['plugins']['plugin_status'] as $key => $val ) {
			
$this_plug = trim($key);


     // If we are mid-flight on activating / deactivating a plugin in the admin interface, then use that value instead
     if ( isset($ct['verified_update_request']['plugin_status'][$this_plug]) ) {
     $val = $ct['verified_update_request']['plugin_status'][$this_plug];
     $plugin_status_is_updating = true;
     }
	
	
	if ( $val == 'on' ) {
	
	// IF any plugin upgrades get triggered, we need to run any setting resets for each plugin
	// (FOR RUNTIME OPTIMIZATION, WE ONLY RUN THIS MUCH FURTHER BELOW, *ONLY IF* UPGRADE CHECKS WERE TRIGGERED)
	$ct['plugin_setting_resets'][] = $this_plug;
	
	$plug_conf_file = $ct['base_dir'] . '/plugins/' . $this_plug . '/plug-conf.php'; // Loaded NOW to have ready for any cached app config resets (for ANY runtime)

     // SET SIMPLIFIED / MINIMIZED PLUG_CONF ONLY FOR USE *INSIDE* PLUGIN LOGIC / PLUGIN INIT LOOPS
	$plug['conf'][$this_plug] = array();
		
	require_once($plug_conf_file); // Populate $plug['conf'][$this_plug] with the defaults
	
	
	     // REQUIRE A VALID RUNTIME MODE / PLUGIN VERSION NUMBER / INIT FILE, TO ACTIVATE PLUGINS!!!!!!!!!
	     if (
	     isset($plug['conf'][$this_plug]['runtime_mode'])
	     && in_array($plug['conf'][$this_plug]['runtime_mode'], $ct['plugin_runtime_mode_check'])
	     && isset($ct['plug_version'][$this_plug])
	     && preg_match('#^(\d+\.)?(\d+\.)?(\d+)(-[a-z0-9]+)?$#i', $ct['plug_version'][$this_plug])
	     && file_exists($ct['base_dir'] . '/plugins/' . $this_plug . '/plug-lib/plug-init.php')
	     ) {
     
          $ct['default_conf']['plug_conf'][$this_plug] = $plug['conf'][$this_plug]; // Add each plugin's HARD-CODED config into the DEFAULT app config
                    
     		
               // If this plugin config is not set in the ACTIVELY-USED ct_conf yet, add it now (from HARD-CODED config)
          	if ( !isset($ct['conf']['plug_conf'][$this_plug]) ) {
               
               // Add this plugin's config into the GLOBAL app config
          	$ct['conf']['plug_conf'][$this_plug] = $ct['default_conf']['plug_conf'][$this_plug]; 
     		   
     		   
          	    // If were're not high security mode / resetting / updating plugin status, flag a cached config update to occur
          	    if ( $ct['admin_area_sec_level'] != 'high' && !$ct['reset_config'] && !$plugin_status_is_updating ) {
                   $ct['update_config'] = true;
                   }
                                 
     		   
          	}
          	// WE *MUST* RESET $plug['conf'][$this_plug] TO USE *CACHED* CONFIG DATA HERE,
          	// AS IN THIS CASE WE ALREADY HAVE IT ACTIVATED IN THE *CACHED* CONFIG!
          	else {
          	$plug['conf'][$this_plug] = $ct['conf']['plug_conf'][$this_plug];
          	}
     		 
     		
          //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
     	// AT THIS POINT $ct['conf']['plug_conf'][$this_plug] AND $plug['conf'][$this_plug] ARE THE SAME
     	// (ONE IS GLOBAL CT_CONF, ONE IS SIMPLIFIED / MINIMIZED PLUG_CONF ONLY FOR USE *INSIDE* PLUGIN LOGIC / PLUGIN INIT LOOPS)
          //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
               
               
          // Trim stanardized plugin config values (basic error auto-correcting)
          $ct['conf']['plug_conf'][$this_plug]['runtime_mode'] = trim($ct['conf']['plug_conf'][$this_plug]['runtime_mode']);
          $plug['conf'][$this_plug]['runtime_mode'] = trim($plug['conf'][$this_plug]['runtime_mode']);
               
          $ct['conf']['plug_conf'][$this_plug]['ui_location'] = trim($ct['conf']['plug_conf'][$this_plug]['ui_location']);
          $plug['conf'][$this_plug]['ui_location'] = trim($plug['conf'][$this_plug]['ui_location']);
               
          $ct['conf']['plug_conf'][$this_plug]['ui_name'] = trim($ct['conf']['plug_conf'][$this_plug]['ui_name']);
          $plug['conf'][$this_plug]['ui_name'] = trim($plug['conf'][$this_plug]['ui_name']);
     		    
     		    
     		// Set to DEFAULT 'ui_location' IF not set 
     		// (UPDATE *BOTH* GLOBAL AND PLUGIN CONFIGS FOR CLEAN / RELIABLE CODE)
     		if ( !isset($plug['conf'][$this_plug]['ui_location']) || isset($plug['conf'][$this_plug]['ui_location']) && $plug['conf'][$this_plug]['ui_location'] == '' ) {
     		$ct['conf']['plug_conf'][$this_plug]['ui_location'] = 'none';
     		$plug['conf'][$this_plug]['ui_location'] = 'none';
     		}
     		    
     		    
     		// Set to DEFAULT 'ui_name' IF not set
     		// (UPDATE *BOTH* GLOBAL AND PLUGIN CONFIGS FOR CLEAN / RELIABLE CODE)
     		if ( !isset($plug['conf'][$this_plug]['ui_name']) || isset($plug['conf'][$this_plug]['ui_name']) && $plug['conf'][$this_plug]['ui_name'] == '' ) {
     		$ct['conf']['plug_conf'][$this_plug]['ui_name'] = $this_plug;
     		$plug['conf'][$this_plug]['ui_name'] = $this_plug;
     		}
     		
     		
               // Each plugin is allowed to run in more than one runtime, if configured for that (some plugins may run in the UI and cron runtimes, etc)
     		
     		// Add to activated cron plugins 
     		if ( $plug['conf'][$this_plug]['runtime_mode'] == 'cron' || $plug['conf'][$this_plug]['runtime_mode'] == 'all' ) {
     		
     		$plug['activated']['cron'][$this_plug] = $ct['base_dir'] . '/plugins/' . $this_plug . '/plug-lib/plug-init.php'; // Loaded LATER at bottom of cron.php (if cron runtime)
     		
     		ksort($plug['activated']['cron']); // Alphabetical order (for admin UI)
     		
     		$plugin_activated = true;
     		
     		}
     		
     		
     		// Add to activated UI plugins
     		if ( $plug['conf'][$this_plug]['runtime_mode'] == 'ui' || $plug['conf'][$this_plug]['runtime_mode'] == 'all' ) {
     		
     		$plug['activated']['ui'][$this_plug] = $ct['base_dir'] . '/plugins/' . $this_plug . '/plug-lib/plug-init.php';
     		
     		ksort($plug['activated']['ui']); // Alphabetical order (for admin UI)
     
     		$plugin_activated = true;
     
     		}
     		
     		
     		// Add to activated webhook plugins
     		if ( $plug['conf'][$this_plug]['runtime_mode'] == 'webhook' || $plug['conf'][$this_plug]['runtime_mode'] == 'all' ) {
     		
     		$plug['activated']['webhook'][$this_plug] = $ct['base_dir'] . '/plugins/' . $this_plug . '/plug-lib/plug-init.php';
          
     		ksort($plug['activated']['webhook']); // Alphabetical order (for admin UI)
          
                  	
                       	// If NOT A FAST RUNTIME, and we don't have webhook keys set yet for this webhook plugin,
                       	// OR a webhook secret key reset from authenticated admin is verified (STRICT 2FA MODE ONLY)
                         if (
                         !$ct['fast_runtime'] && !isset($ct['int_webhooks'][$this_plug])
                         || $_POST['reset_' . $this_plug . '_webhook_key'] == 1 && $ct['sec']->pass_sec_check($_POST['admin_nonce'], 'reset_' . $this_plug . '_webhook_key') && $ct['sec']->valid_2fa('strict')
                         ) {
                         	
                         $secure_128bit_hash = $ct['sec']->rand_hash(16); // 128-bit (16-byte) hash converted to hexadecimal, used for suffix
                         $secure_256bit_hash = $ct['sec']->rand_hash(32); // 256-bit (32-byte) hash converted to hexadecimal, used for var
                              	
                              	
                              // Halt the process if an issue is detected safely creating a random hash
                              if ( $secure_128bit_hash == false || $secure_256bit_hash == false ) {
                         		
                              $ct['gen']->log(
                         			'security_error',
                         			'Cryptographically secure pseudo-random bytes could not be generated for webhook key (in secured cache storage), webhook key creation aborted to preserve security'
                         			);
                              	
                              }
                              // WE AUTOMATICALLY DELETE OUTDATED CACHE FILES SORTING BY DATE WHEN WE LOAD IT, SO NO NEED TO DELETE THE OLD ONE
                              else {
                              $ct['cache']->save_file($ct['base_dir'] . '/cache/secured/'.$this_plug.'_webhook_key_'.$secure_128bit_hash.'.dat', $secure_256bit_hash);
                              $ct['int_webhooks'][$this_plug] = $secure_256bit_hash;
                              }
                              	
                         $admin_reset_success = 'The "' . $this_plug . '" webhook key was reset successfully.'; 
                              
                         }
                              
          
     		$plugin_activated = true;
     		
     		}
     		
             	
        		// Add this plugin's default class (only if activated / the file exists)
        		if ( $plugin_activated == true && file_exists($ct['base_dir'] . '/plugins/'.$this_plug.'/plug-lib/plug-class.php') ) {
                    include($ct['base_dir'] . '/plugins/'.$this_plug.'/plug-lib/plug-class.php');
        		}
             	
          	    
          $plugin_activated = false; // RESET

	     }
	     // Otherwise RESET the initial activation we did (BEFORE failing the plugin VERSION validity check)
	     else {

               
     	     // Alerts for any issues with MANDATORY 'runtime_mode' plugin config setting		
     	     if (
     	     !isset($plug['conf'][$this_plug]['runtime_mode'])
     	     || isset($plug['conf'][$this_plug]['runtime_mode'])
     	     && !in_array($plug['conf'][$this_plug]['runtime_mode'], $ct['plugin_runtime_mode_check'])
     	     ) {

               $ct['gen']->log(
                                  'conf_error',
                                  'plugin "'.$this_plug.'" has an INVALID "runtime_mode" configuration setting (' . ( isset($plug['conf'][$this_plug]['runtime_mode']) ? $plug['conf'][$this_plug]['runtime_mode'] : 'NOT SET' ) . '), activation was SKIPPED, until fixed'
                                  );
                                  
               }
               
               
               if ( !file_exists($ct['base_dir'] . '/plugins/' . $this_plug . '/plug-lib/plug-init.php') ) {
               
               $ct['gen']->log(
                                  'conf_error',
                                  'plugin "'.$this_plug.'" is MISSING it\'s plug-init.php file, activation was SKIPPED, until fixed'
                                  );
                                 
               }
	     
	     
	          // Remove any associated plugin resets
     	     if ( ( $temp_key = array_search($this_plug, $ct['plugin_setting_resets']) ) !== false ) {
               unset($ct['plugin_setting_resets'][$temp_key]);
               }

	     
	     // Just unset EVERYTHING to be safe,
	     // as unset() will NOT throw an error if the var does not exist
	     
	     unset($ct['plug_version'][$this_plug]);
	     
	     unset($plug['conf'][$this_plug]); 

	     unset($ct['conf']['plug_conf'][$this_plug]);
         
          unset($ct['conf']['plugins']['plugin_status'][$this_plug]);
	    
	     unset($ct['conf']['version_states']['plug_version'][$this_plug]);

          unset($ct['default_conf']['plug_conf'][$this_plug]);
	    
          unset($ct['default_conf']['plugins']['plugin_status'][$this_plug]);
	    
	     unset($ct['default_conf']['version_states']['plug_version'][$this_plug]);
                   
	     }
     		
	
	// Reset
	
	unset($plug_conf_file); 
	
	}
	// If we recently de-activated this plugin, we STILL need to remove it's config from 'plug_conf'
	else if ( isset($ct['conf']['plug_conf'][$this_plug]) ) {
	     
     unset($ct['conf']['plug_conf'][$this_plug]);
     
     	    // If were're not high security mode / resetting, flag a cached config update to occurr
     	    if ( $ct['admin_area_sec_level'] != 'high' && !$ct['reset_config'] ) {
              $ct['update_config'] = true;
              }
              
	}


unset($this_plug);  // Reset

}
          

// Flag ACTIVE plugins as registered
$ct['active_plugins_registered'] = true;


//////////////////////////////////////////////////////////////////
// END PLUGINS INIT
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>