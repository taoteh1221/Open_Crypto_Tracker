<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// PLUGINS CONFIG
//////////////////////////////////////////////////////////////////


$plug_conf =  array();
////
$plug_class = array();
////
$activated_plugins =  array();


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
		    
		        if ( $admin_area_sec_level == 'normal' ) {
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
		elseif ( $admin_area_sec_level == 'normal' ) {
		unset($ct_conf['plug_conf'][$this_plug]);
        $refresh_config = true;
		}
	
	
	$plug_conf_file = null; // Reset
	
	}

}


// We use the $refresh_config flag, to avoid multiple calls in the loop
if ( $refresh_config == true ) {
$ct_conf = $ct_gen->refresh_cached_ct_conf($ct_conf);
unset($refresh_config); // Unset, since this is an inline global var
}
// Otherwise we are clear to check for and run any upgrades instead, on the CACHED ct_conf (if in NORMAL admin security mode)
elseif ( $admin_area_sec_level == 'normal' ) {
//$ct_conf = $ct_gen->refresh_cached_ct_conf($ct_conf, 'upgrade_checks');
}
        

//////////////////////////////////////////////////////////////////
// END PLUGINS CONFIG
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>