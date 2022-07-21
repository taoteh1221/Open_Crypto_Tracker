<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// PLUGINS CONFIG
//////////////////////////////////////////////////////////////////

// Configs for any plugins activated in ct_conf
foreach ( $ct_conf['power']['activate_plugins'] as $key => $val ) {
	
	if ( $val == 'on' ) {
		
	$key = trim($key);
	
	$plug_conf_file = $base_dir . '/plugins/' . $key . '/plug-conf.php'; // Loaded NOW to have ready for any cached app config resets (for ANY runtime)


		if ( file_exists($plug_conf_file) ) {
			
		$this_plug = $key;
		
		$plug_conf[$this_plug] = array();
		
		
		    if ( !isset($ct_conf['plug_conf'][$this_plug]) ) {
		    require_once($plug_conf_file);
		    $ct_conf['plug_conf'][$this_plug] = $plug_conf[$this_plug]; // Add each plugin's config into the GLOBAL app config
		    //$refresh_cached_ct_conf = 1;  // LEAVE DISABLED, #UNTIL WE SWITCH ON USING THE CACHED USER EDITED CONFIG#
		    }
			else {
			$plug_conf[$this_plug] = $ct_conf['plug_conf'][$this_plug];
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
		// If plugin has been removed, then remove any ct_conf entry
		else {
		unset($ct_conf['plug_conf'][$this_plug]);
		$refresh_cached_ct_conf = 1;
		}
	
	
	$plug_conf_file = null; // Reset
	
	}

}

//////////////////////////////////////////////////////////////////
// END PLUGINS CONFIG
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>