<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


//////////////////////////////////////////////////////////////////
// CRON PLUGINS CONFIG
//////////////////////////////////////////////////////////////////

// Configs for any cron plugins activated in app_config
foreach ( $app_config['power_user']['activate_cron_plugins'] as $key => $value ) {
	
	if ( $value == 'on' ) {
		
	$key = trim($key);
	$cron_plugin_apps[$key] = $base_dir . '/cron-plugins/app/' . $key . '/' . $key . '-app.php'; // Loaded LATER at bottom of cron.php
	$cron_plugin_config = $base_dir . '/cron-plugins/config/' . $key . '/' . $key . '-config.php'; // Loaded NOW to get configs initiated

		if ( file_exists($cron_plugin_config) ) {
		$cron_plugin_name = $key;
		require_once($cron_plugin_config);
		}
	
	}

}
$cron_plugin_name = null;

//////////////////////////////////////////////////////////////////
// END CRON PLUGINS CONFIG
//////////////////////////////////////////////////////////////////

?>