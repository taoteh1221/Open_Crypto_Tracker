<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


// Initial BLANK arrays

$admin_ui_menus = array();

$change_dir_perm = array();

$sel_opt = array();

$runtime_data = array();

$runtime_data['performance_stats'] = array();

$system_warnings = array();

$system_warnings_cron_interval = array();

$rand_color_ranged =  array();

$processed_msgs = array();

$api_connections = array();

$api_runtime_cache = array();

$limited_api_calls = array();

$coingecko_api = array();

$coinmarketcap_api = array();

$asset_stats_array = array();

$asset_tracking =  array();

$btc_worth_array = array();

$btc_pair_mrkts = array();

$btc_pair_mrkts_excluded = array();

$price_alert_fixed_reset_array = array();

$proxy_checkup = array();

$proxies_checked = array();


// Initial BLANK strings

$cmc_notes = null;

$conf_upgraded = null;

$td_color_zebra = null;

$mcap_data_force_usd = null;
        
$kraken_pairs = null;
        
$upbit_pairs = null;
        
$generic_pairs = null;
        
$generic_assets = null;


//////////////////////////////////////////////////////////////
// Populate PRIMARY global runtime app arrays / vars...
//////////////////////////////////////////////////////////////


//!!!!!!!!!! IMPORTANT, ALWAYS LEAVE THIS HERE !!!!!!!!!!!!!!!
// FOR #UI LOGIN / LOGOUT SECURITY#, WE NEED THIS SET #VERY EARLY# IN INIT TOO,
// EVEN THOUGH WE RUN LOGIC AGAIN FURTHER DOWN IN INIT TO SET THIS UNDER
// ALL CONDITIONS (EVEN CRON RUNTIMES), AND REFRESH VAR CACHE FOR CRON LOGIC
if ( $runtime_mode != 'cron' ) {
$base_url = $ct_gen->base_url();
}


// Set $ct_app_id as a global (MUST BE SET AFTER $base_url / $base_dir)
// (a 10 character install ID hash, created from the base URL or base dir [if cron])
// AFTER THIS IS SET, WE CAN USE EITHER $ct_app_id OR $ct_gen->id() RELIABLY / EFFICIENTLY ANYWHERE
// $ct_gen->id() can then be used in functions WITHOUT NEEDING ANY $ct_app_id GLOBAL DECLARED.
$ct_app_id = $ct_gen->id();


// Session start
session_start(); // New session start

// Give our session a unique name 
// MUST BE SET AFTER $ct_app_id / first $ct_gen->id() call
session_name( $ct_gen->id() );


// Session array
if ( !isset( $_SESSION ) ) {
$_SESSION = array();
}


// Nonce (CSRF attack protection) for user GET links (downloads etc) / admin login session logic WHEN NOT RUNNING AS CRON
if ( $runtime_mode != 'cron' && !isset( $_SESSION['nonce'] ) ) {
$_SESSION['nonce'] = $ct_gen->rand_hash(32); // 32 byte
}


// Nonce for unique runtime logic
$runtime_nonce = $ct_gen->rand_hash(16); // 16 byte


// Current runtime user
if ( function_exists('posix_getpwuid') && function_exists('posix_geteuid') ) {
$current_runtime_user = posix_getpwuid(posix_geteuid())['name'];
}
else {
$current_runtime_user = get_current_user();
}


// Get WEBSERVER runtime user (from cache if currently running from CLI)
// MUST BE SET BEFORE CACHE STRUCTURE CREATION, TO RUN IN COMPATIBILITY MODE (IF NEEDED) FOR THIS PARTICULAR SERVER'S SETUP
// WE HAVE FALLBACKS IF THIS IS NULL IN $ct_cache->save_file() WHEN WE STORE CACHE FILES, SO A BRAND NEW INTALL RUN FIRST VIA CRON IS #OK#
$http_runtime_user = ( $runtime_mode != 'cron' ? $current_runtime_user : trim( file_get_contents('cache/vars/http_runtime_user.dat') ) );

					
// HTTP SERVER setup detection variables (for cache compatibility auto-configuration)
// MUST BE SET BEFORE CACHE STRUCTURE CREATION, TO RUN IN COMPATIBILITY MODE FOR THIS PARTICULAR SERVER'S SETUP
$possible_http_users = array(
						'www-data',
						'apache',
						'apache2',
						'httpd',
						'httpd2',
							);


// Create cache directories AS EARLY AS POSSIBLE (if needed), REQUIRES $http_runtime_user determined further above 
// (for cache compatibility on certain PHP setups)
require_once('app-lib/php/other/directory-creation/cache-directories.php');


$system_info = $ct_gen->system_info(); // MUST RUN AFTER SETTING $base_dir


// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
// MUST RUN #AS SOON AS POSSIBLE IN APP INIT#, SO TELEGRAM COMMS ARE ENABLED FOR #ALL# FOLLOWING LOGIC!
if ( trim($ct_conf['comms']['telegram_your_username']) != '' && trim($ct_conf['comms']['telegram_bot_name']) != '' && trim($ct_conf['comms']['telegram_bot_username']) != '' && $ct_conf['comms']['telegram_bot_token'] != '' ) {
$telegram_activated = 1;
}


// User agent (MUST BE SET EARLY [BUT AFTER SYSTEM INFO VAR], FOR ANY API CALLS WHERE USER AGENT IS REQUIRED BY THE API SERVER)
if ( trim($ct_conf['dev']['override_user_agent']) != '' ) {
$user_agent = $ct_conf['dev']['override_user_agent'];  // Custom user agent
}
elseif ( is_array($ct_conf['proxy']['proxy_list']) && sizeof($ct_conf['proxy']['proxy_list']) > 0 ) {
$user_agent = 'Curl/' .$curl_setup["version"]. ' ('.PHP_OS.'; compatible;)';  // If proxies in use, preserve some privacy
}
else {
$user_agent = 'Curl/' .$curl_setup["version"]. ' ('.PHP_OS.'; ' . $_SERVER['SERVER_SOFTWARE'] . '; PHP/' .phpversion(). '; Open_Crypto_Tracker/' . $app_version . '; +https://github.com/taoteh1221/Open_Crypto_Tracker)';
}


// UI-CACHED VARS THAT !MUST! BE AVAILABLE BEFORE SYSTEM CHECKS, #BUT# MUST RUN AFTER DIRECTORY CREATION
// RUN DURING 'ui' ONLY
if ( $runtime_mode == 'ui' ) {
	
	// Have UI / HTTP runtime mode RE-CACHE the runtime_user data every 24 hours, since CLI runtime cannot determine the UI / HTTP runtime_user 
	if ( $ct_cache->update_cache('cache/vars/http_runtime_user.dat', (60 * 24) ) == true ) {
	$ct_cache->save_file('cache/vars/http_runtime_user.dat', $http_runtime_user); // ALREADY SET FURTHER UP IN INIT.PHP
	}


	// Have UI runtime mode RE-CACHE the app URL data every 24 hours, since CLI runtime cannot determine the app URL (for sending backup link emails during backups, etc)
	if ( $ct_cache->update_cache('cache/vars/base_url.dat', (60 * 24) ) == true ) {
	$base_url = $ct_gen->base_url();
	$ct_cache->save_file('cache/vars/base_url.dat', $base_url);
	}
	else {
	$base_url = trim( file_get_contents('cache/vars/base_url.dat') );
	}

}
else {
$base_url = trim( file_get_contents('cache/vars/base_url.dat') );
}


// Our FINAL $base_url logic has run, so set app host var
if ( isset($base_url) ) {
$parse_temp = parse_url($base_url);
$app_host = $parse_temp['host'];
}


// htaccess login...SET BEFORE system checks
$interface_login_array = explode("||", $ct_conf['gen']['interface_login']);

$htaccess_username = $interface_login_array[0];
$htaccess_password = $interface_login_array[1];

$fetched_feeds = 'fetched_feeds_' . $runtime_mode; // Unique feed fetch telemetry SESSION KEY (so related runtime BROWSER SESSION logic never accidentally clashes)

// If upgrade check enabled / cached var set, set the runtime var for any configured alerts
$upgrade_check_latest_version = trim( file_get_contents('cache/vars/upgrade_check_latest_version.dat') );

// Currencies SUPPORTED BY coinmarketcap.com (coingecko.com can be determined differently)
$coinmarketcap_currencies = array(
                                'USD',
                                'ALL',
                                'DZD',
                                'ARS',
                                'AMD',
                                'AUD',
                                'AZN',
                                'BHD',
                                'BDT',
                                'BYN',
                                'BMD',
                                'BOB',
                                'BAM',
                                'BRL',
                                'BGN',
                                'KHR',
                                'CAD',
                                'CLP',
                                'CNY',
                                'COP',
                                'CRC',
                                'HRK',
                                'CUP',
                                'CZK',
                                'DKK',
                                'DOP',
                                'EGP',
                                'EUR',
                                'GEL',
                                'GHS',
                                'GTQ',
                                'HNL',
                                'HKD',
                                'HUF',
                                'ISK',
                                'INR',
                                'IDR',
                                'IRR',
                                'IQD',
                                'ILS',
                                'JMD',
                                'JPY',
                                'JOD',
                                'KZT',
                                'KES',
                                'KWD',
                                'KGS',
                                'LBP',
                                'MKD',
                                'MYR',
                                'MUR',
                                'MXN',
                                'MDL',
                                'MNT',
                                'MAD',
                                'MMK',
                                'NAD',
                                'NPR',
                                'TWD',
                                'NZD',
                                'NIO',
                                'NGN',
                                'NOK',
                                'OMR',
                                'PKR',
                                'PAB',
                                'PEN',
                                'PHP',
                                'PLN',
                                'GBP',
                                'QAR',
                                'RON',
                                'RUB',
                                'SAR',
                                'RSD',
                                'SGD',
                                'ZAR',
                                'KRW',
                                'SSP',
                                'VES',
                                'LKR',
                                'SEK',
                                'CHF',
                                'THB',
                                'TTD',
                                'TND',
                                'TRY',
                                'UGX',
                                'UAH',
                                'AED',
                                'UYU',
                                'UZS',
                                'VND',
										);


////////////////////////////////////////////////////////////
// END of populating primary vars / arrays 
////////////////////////////////////////////////////////////

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!
 
?>