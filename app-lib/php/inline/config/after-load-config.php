<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


//////////////////////////////////////////////////////////////////
// AFTER LOAD CONFIG
//////////////////////////////////////////////////////////////////


// NEVER USE REQUIRE ONCE IN THIS FILE!

// MAKE SURE **ANYTHING** RUN IN HERE --IS ENGINEERED TO-- BE CLEANLY RELOADED!!

// To be loaded IMMEADIATELY after loading the cached config

// API configs
require('app-lib/php/inline/config/batched-api-config.php');
require('app-lib/php/inline/config/throttled-api-config.php');


// Get / check system info for debugging / stats (MUST run IMMEADIATELY AFTER loading the cached config)
require($ct['base_dir'] . '/app-lib/php/inline/system/system-info.php');


// STRICT curl user agent (for strict API servers list in proxy mode, etc, etc)
// MUST BE SET IMMEDIATELY AFTER system-info.php (AS EARLY AS POSSIBLE FOR ADMIN INPUT VALIDATION)
$ct['strict_curl_user_agent'] = 'Curl/' .$curl_setup["version"]. ' ('.PHP_OS.'; ' . $ct['system_info']['software'] . '; +https://github.com/taoteh1221/Open_Crypto_Tracker)';


// Developer-only configs
$dev_only_configs_mode = 'after-load-config'; // Flag to only run 'after-load-config' section
require('developer-config.php');

// Development status DATA SET from github file:
// https://raw.githubusercontent.com/taoteh1221/Open_Crypto_Tracker/main/.dev-status.json
$ct['dev']['status'] = @$ct['api']->dev_status();

//var_dump($ct['dev']['status']);


// Sort the alerts by NEWEST
if ( is_array($ct['dev']['status']) && sizeof($ct['dev']['status']) > 0 ) {

usort($ct['dev']['status'], array($ct['var'], 'integer_usort_decending') );

$ct['dev']['status_data_found'] = true; // Flag as data was found (for loading in interface)

	// Timestamp, of latest important status alert
	foreach ( $ct['dev']['status'] as $dev_alert ) {
	
     	if ( $dev_alert['dummy_entry'] ) {
     	continue;
     	}
     	elseif ( $dev_alert['very_important'] && !isset($ct['dev']['latest_important_dev_alerts_timestamp']) ) {
     	$ct['dev']['latest_important_dev_alerts_timestamp'] = $dev_alert['timestamp'];
     	}
	
	}

}


//////////////////////////////////////////////////////////////////
// END AFTER LOAD CONFIG
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>