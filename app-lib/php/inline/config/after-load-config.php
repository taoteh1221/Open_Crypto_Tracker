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

// Do NOT use require_once(), AS WE MAY RUN MORE THAN ONCE, UNDER CERTAIN CONDITIONS

// Dynamic app config auto-adjust (MUST RUN FIRST [FOR AUTO-CORRECT, REQUIRED CONFIG ARRAYS / SETUP, ETC ETC])
require('app-lib/php/inline/config/config-auto-adjust.php');


// Get / check system info for debugging / stats (MUST run IMMEADIATELY AFTER auto-adjusting the cached config)
require($ct['base_dir'] . '/app-lib/php/inline/system/system-info.php');


// STRICT curl user agent (for strict API servers list in proxy mode, etc, etc)
// MUST BE SET IMMEDIATELY AFTER system-info.php (AS EARLY AS POSSIBLE FOR ADMIN INPUT VALIDATION)
$ct['strict_curl_user_agent'] = 'Curl/' .$curl_setup["version"]. ' ('.PHP_OS.'; ' . $ct['system_info']['software'] . '; +https://github.com/taoteh1221/Open_Crypto_Tracker)';


// User agent (MUST BE SET VERY EARLY, FOR ANY CURL-BASED API CALLS WHERE USER AGENT IS REQUIRED BY THE API SERVER)
if ( trim($ct['conf']['power']['override_curl_user_agent']) != '' ) {
$ct['curl_user_agent'] = $ct['conf']['power']['override_curl_user_agent'];  // Custom user agent
}
elseif ( $ct['activate_proxies'] == 'on' && is_array($ct['conf']['proxy']['proxy_list']) && sizeof($ct['conf']['proxy']['proxy_list']) > 0 ) {
$ct['curl_user_agent'] = 'Curl/' .$curl_setup["version"]. ' ('.PHP_OS.'; compatible;)';  // If proxies in use, preserve some privacy
}
else {
$ct['curl_user_agent'] = $ct['strict_curl_user_agent']; // SET IN primary-init.php (NEEDED MUCH EARLIER THAN HERE [FOR ADMIN INPUT VALIDATION])
}


// API configs
require('app-lib/php/inline/config/batched-api-config.php');
require('app-lib/php/inline/config/throttled-api-config.php');


// Developer-only configs
$dev_only_configs_mode = 'after-load-config'; // Flag to only run 'after-load-config' section
require('developer-config.php');


// Development status DATA SET from github file:
// https://raw.githubusercontent.com/taoteh1221/Open_Crypto_Tracker/main/.dev-status.json
$ct['dev']['status'] = @$ct['api']->dev_status();


// Sort the alerts by NEWEST
if ( is_array($ct['dev']['status']) && sizeof($ct['dev']['status']) > 0 ) {

usort($ct['dev']['status'], array($ct['var'], 'timestamp_usort_decending') );

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