<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
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
			
		require_once($plug_conf_file);
		
			// Each plugin is allowed to run in more than one runtime, if configured for that (some plugins may run in the UI and cron runtimes, etc)
		
			// Add to activated cron plugins 
			if ( $plug_conf[$this_plug]['runtime_mode'] == 'cron' || $plug_conf[$this_plug]['runtime_mode'] == 'all' ) {
			$activated_plugins['cron'][$this_plug] = $base_dir . '/plugins/' . $this_plug . '/plug-lib/plug-init.php'; // Loaded LATER at bottom of cron.php (if cron runtime)
			}
			
			// Add to activated UI plugins
			if ( $plug_conf[$this_plug]['runtime_mode'] == 'ui' || $plug_conf[$this_plug]['runtime_mode'] == 'all' ) {
			$activated_plugins['ui'][$this_plug] = $base_dir . '/plugins/' . $this_plug . '/plug-lib/plug-init.php'; // NOT IMPLEMENTED YET!
			}
		
		
		$ct_conf['plug_conf'][$this_plug] = $plug_conf[$this_plug]; // Add each plugin's config into the GLOBAL app config
		
		$this_plug = null; // Reset
		
		}
	
	
	$plug_conf_file = null; // Reset
	
	}

}

//////////////////////////////////////////////////////////////////
// END PLUGINS CONFIG
//////////////////////////////////////////////////////////////////

?>