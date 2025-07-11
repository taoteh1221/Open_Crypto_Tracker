<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// Runtime mode
$runtime_mode = 'int_api';


// Load app config / etc
require("app-lib/php/init.php");


header('Content-type: text/html; charset=' . $ct['dev']['charset_default']);

header('Access-Control-Allow-Headers: *'); // Allow ALL headers

// Allow access from ANY SERVER (AS THIS IS AN API ACCESS POINT)
header('Access-Control-Allow-Origin: *');

// Seems useful for javascript-based API connects: 
// https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Access-Control-Allow-Credentials
header('Access-Control-Allow-Credentials: true'); 

// Ip address information
$ip_access_tracking = $ct['base_dir'] . '/cache/events/throttling/local_api_incoming_ip_' . $ct['gen']->compat_file_name($ct['remote_ip']) . '.dat';


// Throttle ip addresses reconnecting before $ct['conf']['int_api']['api_rate_limit'] interval passes
if ( $ct['cache']->update_cache($ip_access_tracking, ($ct['conf']['int_api']['api_rate_limit'] / 60) ) == false ) {

$result = array('error' => "Rate limit (maximum of once every " . $ct['conf']['int_api']['api_rate_limit'] . " seconds) reached for ip address: " . $ct['remote_ip']);

$ct['gen']->log(
			'int_api_error',
			'From ' . $ct['remote_ip'] . ' (Rate limit reached)', 'uri: ' . $_SERVER['REQUEST_URI'] . ';'
			);

// JSON-encode results
$json_result = json_encode($result, JSON_PRETTY_PRINT);

}
// API security check (key request var must match our stored API key, or we abort runtime)
// (POST DATA #ONLY#, FOR HIGH SECURITY OF API KEY TRANSMISSION)
elseif ( !isset($_POST['api_key']) || isset($_POST['api_key']) && $_POST['api_key'] != $int_api_key ) {
	
	if ( isset($_POST['api_key']) ) {
	$result = array('error' => "Incorrect API key: " . $_POST['api_key']);
	
	$ct['gen']->log(
								'int_api_error',
								'From ' . $ct['remote_ip'] . ' (Incorrect API key)', 'api_key: ' . $_POST['api_key'] . '; uri: ' . $_SERVER['REQUEST_URI'] . ';'
								);
	
	}
	else {
		
	$result = array('error' => "Missing API key.");
	
	$ct['gen']->log(
								'int_api_error',
								'From ' . $ct['remote_ip'] . ' (Missing API key)', 'uri: ' . $_SERVER['REQUEST_URI'] . ';'
								);
	
	}

// JSON-encode results
$json_result = json_encode($result, JSON_PRETTY_PRINT);

}
// Cleared to access the API
else {

// Cleanup the requested data
$_GET['data_set'] = strtolower($_GET['data_set']);

$hash_check = md5($_GET['data_set']);


	// If a cache exists for this request that's NOT OUTDATED, use cache to speed things up
	if ( $ct['cache']->update_cache($ct['base_dir'] . '/cache/internal_api/'.$hash_check.'.dat', $ct['conf']['int_api']['int_api_cache_time']) == false ) {
		
	$json_result = trim( file_get_contents($ct['base_dir'] . '/cache/internal_api/'.$hash_check.'.dat') );
	
	}
	// No cache / expired cache
	else {


	$data_set_array = explode('/', $_GET['data_set']); // Data request array

	// Cleanup
	$data_set_array = array_map('trim', $data_set_array);

	$all_mrkts_data_array = explode(",", $data_set_array[2]); // Market data array

	// Cleanup
	$all_mrkts_data_array = array_map('trim', $all_mrkts_data_array);


		// /api/price endpoint
		if ( $data_set_array[0] == 'market_conversion' ) {
		$result = $ct['asset']->market_conv_int_api($data_set_array[1], $all_mrkts_data_array);
		}
		elseif ( $data_set_array[0] == 'asset_list' ) {
		$result = $ct['asset']->asset_list_int_api();
		}
		elseif ( $data_set_array[0] == 'exchange_list' ) {
		$result = $ct['asset']->exchange_list_int_api();
		}
		elseif ( $data_set_array[0] == 'market_list' ) {
		$result = $ct['asset']->market_list_int_api($data_set_array[1]);
		}
		elseif ( $data_set_array[0] == 'conversion_list' ) {
		$result = $ct['asset']->conversion_list_int_api();
		}
		// Non-existent endpoint error message
		else {
			
		$result = array('error' => 'Endpoint does not exist: ' . $data_set_array[0]);
		
		$ct['gen']->log(
					'int_api_error', 
					'From ' . $ct['remote_ip'] . ' (Endpoint does not exist: ' . $data_set_array[0] . ')', 'uri: ' . $_SERVER['REQUEST_URI'] . ';'
					);
		
		}
	
	
		// No matches error message
		if ( !isset($result) ) {
			
		$result = array('error' => 'No matches / results found.');
		
		$ct['gen']->log(
									'int_api_error',
									'From ' . $ct['remote_ip'] . ' (No matches / results found)', 'uri: ' . $_SERVER['REQUEST_URI'] . ';'
									);
		
		}


	$result['minutes_cached'] = $ct['conf']['int_api']['int_api_cache_time'];
	
	
	// JSON-encode results
	$json_result = json_encode($result, JSON_PRETTY_PRINT);
	
	// Cache the result
	$ct['cache']->save_file($ct['base_dir'] . '/cache/internal_api/'.$hash_check.'.dat', $json_result);


	}

}


// Echo result in json format
echo $json_result;

// Log access event for this ip address (for throttling)
$ct['cache']->save_file($ip_access_tracking, $ct['gen']->time_date_format(false, 'pretty_date_time') );

// Access stats logging
$ct['cache']->log_access_stats();

// Log errors / debugging, send notifications
$ct['cache']->app_log();
$ct['cache']->send_notifications();

flush(); // Clean memory output buffer for echo
gc_collect_cycles(); // Clean memory cache

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>