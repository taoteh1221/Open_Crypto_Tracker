<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// LOAD CONFIG
//////////////////////////////////////////////////////////////////


// Default config, used for upgrade checks
// (#MUST# BE SET BEFORE load-config-by-security-level.php)
// WE MODIFY / RUN THIS AND UPGRADE LOGIC, WITHIN load-config-by-security-level.php
$default_ct_conf = $ct_conf; 


// Used for quickening runtimes on app config upgrading checks
// (#MUST# BE SET BEFORE load-config-by-security-level.php)
if ( file_exists($base_dir . '/cache/vars/default_ct_conf_md5.dat') ) {
$check_default_ct_conf = trim( file_get_contents($base_dir . '/cache/vars/default_ct_conf_md5.dat') );
}
else {
$check_default_ct_conf = null;
}


// load_cached_config() LOADS *BEFORE* PLUGIN CONFIGS IN *ENHANCED / NORMAL* ADMIN SECURITY MODES
// (UNLESS IT'S A CT_CONF USER-INITIATED RESET)
if ( $admin_area_sec_level != 'high' && !$reset_ct_conf ) {
$ct_cache->load_cached_config();
}


// Configs for any plugins activated in ct_conf
foreach ( $ct_conf['power']['activate_plugins'] as $key => $val ) {
	
	if ( $val == 'on' ) {
		
	$key = trim($key);
	
	$plug_conf_file = $base_dir . '/plugins/' . $key . '/plug-conf.php'; // Loaded NOW to have ready for any cached app config resets (for ANY runtime)


		if ( file_exists($plug_conf_file) ) {
			
		$this_plug = $key;
		
		$plug_conf[$this_plug] = array();
		
		require_once($plug_conf_file); // Populate $plug_conf[$this_plug] with the defaults
		
		$default_ct_conf['plug_conf'][$this_plug] = $plug_conf[$this_plug]; // Add each plugin's config into the DEFAULT app config
		
		
		    // If this plugin has not been added to the ACTIVELY-USED ct_conf yet, add it now
		    if ( !isset($ct_conf['plug_conf'][$this_plug]) ) {
		        
		    $ct_conf['plug_conf'][$this_plug] = $plug_conf[$this_plug]; // Add each plugin's config into the GLOBAL app config
		    
		        if ( $admin_area_sec_level != 'high' && !$reset_ct_conf ) {
    		    $ct_gen->log('conf_error', 'plugin "'.$this_plug.'" ADDED, refreshing CACHED ct_conf');
                $refresh_config = true;
		        }
		        
		    }
		
		
			// Each plugin is allowed to run in more than one runtime, if configured for that (some plugins may run in the UI and cron runtimes, etc)
		
			// Add to activated cron plugins 
			if ( $plug_conf[$this_plug]['runtime_mode'] == 'cron' || $plug_conf[$this_plug]['runtime_mode'] == 'all' ) {
			$activated_plugins['cron'][$this_plug] = $base_dir . '/plugins/' . $this_plug . '/plug-lib/plug-init.php'; // Loaded LATER at bottom of cron.php (if cron runtime)
			ksort($activated_plugins['cron']); // Alphabetical order (for admin UI)
			}
			
			// Add to activated UI plugins
			if ( $plug_conf[$this_plug]['runtime_mode'] == 'ui' || $plug_conf[$this_plug]['runtime_mode'] == 'all' ) {
			$activated_plugins['ui'][$this_plug] = $base_dir . '/plugins/' . $this_plug . '/plug-lib/plug-init.php';
			ksort($activated_plugins['ui']); // Alphabetical order (for admin UI)
			}
		
		$this_plug = null; // Reset
		
		}
		// If plugin has been removed AND we are running the NORMAL SECURITY admin pages, then remove any ct_conf entry
		// (THIS AUTOMATICALLY #CANNOT# HAPPEN IF WE ARE #NOT# IN NORMAL SECURITY ADMIN MODE)
        // (if NO USER-INITIATED CT_CONF RESET)
		elseif ( $admin_area_sec_level != 'high' && !$reset_ct_conf ) {
		unset($ct_conf['plug_conf'][$this_plug]);
    	$ct_gen->log('conf_error', 'plugin "'.$this_plug.'" REMOVED, refreshing CACHED ct_conf');
        $refresh_config = true;
		}
	
	
	$plug_conf_file = null; // Reset
	
	}

}


// IF ADMIN-USER-INITIATED ct_conf CACHE RESET (ALSO LOADS CT_CONF [WITH ACTIVATED PLUGIN CONFIGS])
if ( $reset_ct_conf ) {
$ct_conf = $ct_cache->refresh_cached_ct_conf(false, false, true); // Admin-user-initiated reset flag
sleep(2); // Give recache file save a couple seconds breather, BEFORE load_cached_config() READS FROM IT
}
// We use the $refresh_config flag, to avoid multiple calls in the loop
elseif ( $refresh_config == true ) {
$ct_conf = $ct_cache->refresh_cached_ct_conf($ct_conf);
unset($refresh_config); // Unset, since this is an inline global var
}
// Otherwise we are clear to check for and run any upgrades instead, on the CACHED ct_conf
elseif ( $admin_area_sec_level != 'high' ) {
//$ct_conf = $ct_cache->refresh_cached_ct_conf($ct_conf, true); // NOT NEEDED YET / THROWS ERROR...SEE TODO.txt
}


// load_cached_config() LOADS *AFTER* PLUGIN CONFIGS IN *HIGH* ADMIN SECURITY MODE
// (AND IF THERE IS A USER-INITIATED CT_CONF RESET)
if ( $admin_area_sec_level == 'high' || $reset_ct_conf ) {
$ct_cache->load_cached_config();
}


//////////////////////////////////////////////////////////////////
// END LOAD CONFIG
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>