<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// FOR WHEN WE WANT RELATIVELY QUICK RUNTIMES, WITH MINIMAL INIT LOGIC (captcha / charts / etc)

// Secured cache files global variable for app config (getting captcha settings)
$secured_cache_files = sort_files($base_dir . '/cache/secured', 'dat', 'desc');


foreach( $secured_cache_files as $secured_file ) {

	// App config
	if ( preg_match("/app_config_/i", $secured_file) ) {
		
		$cached_app_config = json_decode( trim( file_get_contents($base_dir . '/cache/secured/' . $secured_file) ) , TRUE);
			
			if ( $cached_app_config == true ) {
			$app_config = $cached_app_config; // Use cached app_config if it exists, seems intact, and config.php hasn't been revised since last check
			}
			else {
			app_logging('config_error', 'Cached app_config data appears corrupted (fetching within captcha library)');
			}
			
	}
	
	
	// Stored admin login user / hashed password (for admin login authentication)
	elseif ( preg_match("/admin_login_/i", $secured_file) ) {
		
		
		// If we already loaded the newest modified file, delete any stale ones
		if ( $newest_cached_admin_login == 1 ) {
		unlink($base_dir . '/cache/secured/' . $secured_file);
		}
		else {
		$newest_cached_admin_login = 1;
		$active_admin_login_path = $base_dir . '/cache/secured/' . $secured_file; // To easily delete, if we are resetting the login
		$stored_admin_login = explode("||", trim( file_get_contents($active_admin_login_path) ) );
		}
	
	
	}
	
}


// Since we don't run the full init.php for speed, so load some additional required sub-inits...
require_once('app-lib/php/other/app-config-management.php');


 
 ?>