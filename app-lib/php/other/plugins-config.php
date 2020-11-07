<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// PLUGINS CONFIG
//////////////////////////////////////////////////////////////////

// Configs for any plugins activated in app_config
foreach ( $app_config['power_user']['activate_plugins'] as $key => $value ) {
	
	if ( $value == 'on' ) {
		
	$key = trim($key);
	$plugin_config_file = $base_dir . '/plugins/' . $key . '/plugin-config.php'; // Loaded NOW to have ready for any cached app config resets (for ANY runtime)


		if ( file_exists($plugin_config_file) ) {
			
		$this_plugin = $key;
		
		$plugin_config[$this_plugin] = array();
			
		require_once($plugin_config_file);
		
			// Each plugin is allowed to run in more than one runtime, if configured for that (some plugins may run in the UI and cron runtimes, etc)
		
			// Add to activated cron plugins 
			if ( $plugin_config[$this_plugin]['runtime_mode'] == 'cron' || $plugin_config[$this_plugin]['runtime_mode'] == 'all' ) {
			$activated_plugins['cron'][$key] = $base_dir . '/plugins/' . $key . '/plugin-lib/plugin-init.php'; // Loaded LATER at bottom of cron.php (if cron runtime)
			}
			
			// Add to activated UI plugins
			if ( $plugin_config[$this_plugin]['runtime_mode'] == 'ui' || $plugin_config[$this_plugin]['runtime_mode'] == 'all' ) {
			$activated_plugins['ui'][$key] = $base_dir . '/plugins/' . $key . '/plugin-lib/plugin-init.php'; // Loaded LATER at bottom of cron.php (if cron runtime)
			}
		
		
		$app_config['plugin_config'][$this_plugin] = $plugin_config[$this_plugin]; // Add each plugin's config into the global app config
		
		$this_plugin = null; // Reset
		
		}
	
	
	$plugin_config_file = null; // Reset
	
	}

}

//////////////////////////////////////////////////////////////////
// END PLUGINS CONFIG
//////////////////////////////////////////////////////////////////

?>