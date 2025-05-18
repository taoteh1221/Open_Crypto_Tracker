<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


//////////////////////////////////////////////////////////////////
// PLUGINS CONFIG
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
	
	$plug_conf_file = $ct['base_dir'] . '/plugins/' . $this_plug . '/plug-conf.php'; // Loaded NOW to have ready for any cached app config resets (for ANY runtime)

     // SET SIMPLIFIED / MINIMIZED PLUG_CONF ONLY FOR USE *INSIDE* PLUGIN LOGIC / PLUGIN INIT LOOPS
	$plug['conf'][$this_plug] = array();
		
	require_once($plug_conf_file); // Populate $plug['conf'][$this_plug] with the defaults
     		
     $default_ct_conf['plug_conf'][$this_plug] = $plug['conf'][$this_plug]; // Add each plugin's HARD-CODED config into the DEFAULT app config

     // Minimize calls
     $cached_plug_version_file = $ct['plug']->state_cache('plug_version.dat');


          // If CACHED plugin version set, set the runtime var, AND FLAG ANY UPGRADE FOR
          // NON-HIGH SECURITY MODE'S CACHED CONFIG (IF IT DOESN'T MATCH THE CURRENT PLUGIN VERSION NUMBER)
          if ( file_exists($cached_plug_version_file) ) {
               
          $ct['cached_plug_version'][$this_plug] = trim( file_get_contents($cached_plug_version_file) );
          
               // Check version number against cached value
               if ( $ct['cached_plug_version'][$this_plug] != $ct['plug_version'][$this_plug] ) {

               $plug_version_compare = $ct['gen']->version_compare($ct['plug_version'][$this_plug], $ct['cached_plug_version'][$this_plug]);
                    
                    
                    // IF we are DOWNGRADING, warn user WE MUST RESET THE PLUGIN CONFIG FOR COMPATIBILITY!
                    if ( $plug_version_compare['base_diff'] < 0 ) {
                    
                    // Triggers resetting (by forcing re-activation) this plugin's config to default,
                    // AND writing the new config to disk (see plugin activation further below)
                    unset($ct['conf']['plug_conf'][$this_plug]);
                    
                    $ct['gen']->log(
                         			'notify_error',
                         			'"' . $this_plug . '" plugin DOWNGRADE detected, RESETTING this ENTIRE plugin TO ASSURE COMPATIBILITY'
                            			);
                    
                    // RESETS don't auto-update CACHED version, so save it now
                    $ct['cache']->save_file($cached_plug_version_file, $ct['plug_version'][$this_plug]);
               
                    }
                    // Otherwise, flag upgrading
                    else {
                    $ct['upgraded_plugin'] = true;
                    }


               }
               
          }
          // Otherwise save cached plugin version for NEW installs,
          // OR flag any DB upgrading (ONLY for POST-PLUGIN-VERSIONING compatibility)
          else {

               // POST-PLUGIN-VERSIONING compatibility, ONLY ON EXISTING (NOT NEW) INSTALLATIONS!
               if ( isset($ct['cached_app_version']) ) {
               $ct['upgraded_plugin'] = true; // Flag plugin upgrade check
               }
               // Save the cached plugin version (for NEW installations)
               else {
               // Do NOT set $ct['cached_plug_version'][$this_plug] here,
               // as we have FIRST RUN / POST-PLUGIN-VERSIONING logic seeing if the CACHED version is set!
               $ct['cache']->save_file($cached_plug_version_file, $ct['plug_version'][$this_plug]);
               }
               
          }


          // IF plugin config was flagged to upgrade
          if ( $ct['upgraded_plugin'] ) {
               
          $ct['plugin_upgrade_check'] = true; // Flag plugin upgrade check
                             
          // User updates halted message (avoid any conflicts, as we are busy finishing the upgrade above)
          $ct['user_update_config_halt'] = 'The plugins are busy UPGRADING their cached config, please wait a minute and try again.';    
          
          // Process any developer-added DB RESETS (for RELIABLE DB upgrading)
          // PLUGINS INCLUDE THEIR RESET DATA IN THEIR CONFIG FILE (FOR DEV UX), SO WE JUST NEED TO PROCESS IT
          require($ct['base_dir'] . '/app-lib/php/inline/config/plugin-setting-reset-config.php');
               
          // Update the CACHED plugin version (AFTER processing any config setting RESETS above)
          $ct['cache']->save_file($cached_plug_version_file, $ct['plug_version'][$this_plug]);
          
          }
          
     		
          // If this plugin config is not set in the ACTIVELY-USED ct_conf yet, add it now (from HARD-CODED config)
     	if ( !isset($ct['conf']['plug_conf'][$this_plug]) ) {
     		        
     	$ct['conf']['plug_conf'][$this_plug] = $default_ct_conf['plug_conf'][$this_plug]; // Add this plugin's config into the GLOBAL app config
     		   
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
		         
		
	     // Check MANDATORY 'runtime_mode' plugin config setting		
		if ( !isset($plug['conf'][$this_plug]['runtime_mode']) || isset($plug['conf'][$this_plug]['runtime_mode']) && !in_array($plug['conf'][$this_plug]['runtime_mode'], $ct['plugin_runtime_mode_check']) ) {
     	    
     	unset($plug['conf'][$this_plug]);

     	unset($ct['conf']['plug_conf'][$this_plug]);

     	unset($default_ct_conf['plug_conf'][$this_plug]);

         	$ct['gen']->log('conf_error', 'plugin "'.$this_plug.'" has an INVALID "runtime_mode" configuration setting (' . ( isset($plug['conf'][$this_plug]['runtime_mode']) ? $plug['conf'][$this_plug]['runtime_mode'] : 'NOT SET' ) . '), skipping activation until fixed');

		}
		// Cleared for takeoff
		else {
		         
		         
		     // Set to DEFAULT 'ui_location' IF not set 
		     // (UPDATE *BOTH* GLOBAL AND PLUGIN CONFIGS FOR CLEAN / RELIABLE CODE)
		     if ( !isset($plug['conf'][$this_plug]['ui_location']) || isset($plug['conf'][$this_plug]['ui_location']) && $plug['conf'][$this_plug]['ui_location'] == '' ) {
		     $ct['conf']['plug_conf'][$this_plug]['ui_location'] = 'tools';
		     $plug['conf'][$this_plug]['ui_location'] = 'tools';
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
                    !$is_fast_runtime && !isset($ct['int_webhooks'][$this_plug])
                    || $_POST['reset_' . $this_plug . '_webhook_key'] == 1 && $ct['gen']->pass_sec_check($_POST['admin_nonce'], 'reset_' . $this_plug . '_webhook_key') && $ct['gen']->valid_2fa('strict')
                    ) {
                    	
                    $secure_128bit_hash = $ct['gen']->rand_hash(16); // 128-bit (16-byte) hash converted to hexadecimal, used for suffix
                    $secure_256bit_hash = $ct['gen']->rand_hash(32); // 256-bit (32-byte) hash converted to hexadecimal, used for var
                         	
                         	
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
		    
	
	// Reset
	
	unset($cached_plug_version_file);
	
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
// END PLUGINS CONFIG
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>