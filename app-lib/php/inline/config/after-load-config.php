<?php
/*
 * Copyright 2014-2026 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


//////////////////////////////////////////////////////////////////
// AFTER LOAD CONFIG
//////////////////////////////////////////////////////////////////


// NEVER USE REQUIRE ONCE IN THIS FILE!

// MAKE SURE **ANYTHING** RUN IN HERE --IS ENGINEERED TO-- BE CLEANLY RELOADED!!

// To be loaded IMMEADIATELY after loading the cached config

// Do NOT use require_once(), AS WE MAY RUN MORE THAN ONCE, UNDER CERTAIN CONDITIONS

// CONFIG AUTO-CORRECT (fix any basic end user data entry errors in possibly user-customized config)
require('app-lib/php/inline/config/config-auto-corrections.php');

// Formatting adjustments, for app config (MUST RUN AFTER AUTO-CORRECTION, FOR REQUIRED CONFIG ARRAYS / SETUP, ETC ETC)
require('app-lib/php/inline/config/config-adjust-formatting.php');

// Flags for any excessive system resource usage thresholds being hit
// (for UI / comm / log alerts, MUST run IMMEDIATELY AFTER all auto-adjusting of config)
require($ct['base_dir'] . '/app-lib/php/inline/system/system-resource-alerts.php');


// We only need mcap stats for the UI
if ( $ct['runtime_mode'] == 'ui' ) {

     // Set marketcap data source global
     if ( $ct['conf']['gen']['primary_marketcap_site'] == 'coingecko' ) {
     $ct['coingecko_api'] = $ct['api']->mcap_data_coingecko();
     }
     elseif ( $ct['conf']['gen']['primary_marketcap_site'] == 'coinmarketcap' ) {
     $ct['coinmarketcap_api'] = $ct['api']->mcap_data_coinmarketcap();
     }

}


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
require('app-lib/php/inline/config/dynamic-throttling-config.php');


// Developer-only configs
$dev_only_configs_mode = 'after-load-config'; // Flag to only run 'after-load-config' section
require('developer-config.php');


//////////////////////////////////////////////////////////////////
// END AFTER LOAD CONFIG
//////////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>