<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */


// FOR WHEN WE WANT RELATIVELY QUICK RUNTIMES, WITH MINIMAL INIT LOGIC (captcha / charts / etc)

// Secured cache files global variable for app config (getting captcha settings)
$secured_cache_files = sort_files($base_dir . '/cache/secured', 'dat', 'desc');


foreach( $secured_cache_files as $secured_file ) {

	// App config
	if ( preg_match("/ocpt_conf_/i", $secured_file) ) {
		
		$cached_ocpt_conf = json_decode( trim( file_get_contents($base_dir . '/cache/secured/' . $secured_file) ) , TRUE);
			
			if ( $cached_ocpt_conf == true ) {
			$ocpt_conf = $cached_ocpt_conf; // Use cached pt_conf if it exists, seems intact, and DEFAULT Admin Config (in config.php) hasn't been revised since last check
			}
			else {
			app_logging('config_error', 'Cached pt_conf data appears corrupted (fetching within minimized-sub-init.php)');
			}
			
	}
	
}


// Since we don't run the full init.php for speed, so load some additional required sub-inits...
require_once('app-lib/php/other/app-config-management.php');


// Primary Bitcoin markets for charts (MUST RUN AFTER app config management)
if ( $is_charts ) {
require_once('app-lib/php/other/primary-bitcoin-markets.php');
}

 
 ?>