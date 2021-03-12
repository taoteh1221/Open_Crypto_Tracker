<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */


// Runtime mode
$runtime_mode = 'int_api';


// Load app config / etc
require("config.php");


// Ip address information
$store_ip = preg_replace("/\./", "_", $remote_ip);
$ip_access = trim( file_get_contents($base_dir . '/cache/events/throttling/local_api_incoming_ip_' . $store_ip . '.dat') );



// Throttle ip addresses reconnecting before $app_config['developer']['local_api_rate_limit'] interval passes
if ( update_cache_file($base_dir . '/cache/events/throttling/local_api_incoming_ip_' . $store_ip . '.dat', ($app_config['developer']['local_api_rate_limit'] / 60) ) == false ) {

$result = array('error' => "Rate limit (maximum of once every " . $app_config['developer']['local_api_rate_limit'] . " seconds) reached for ip address: " . $remote_ip);

app_logging('int_api_error', 'From ' . $remote_ip . ' (Rate limit reached)', 'uri: ' . $_SERVER['REQUEST_URI'] . ';');

// JSON-encode results
$json_result = json_encode($result, JSON_PRETTY_PRINT);

}
// API security check (key request var must match our stored API key, or we abort runtime)
// (POST DATA #ONLY#, FOR HIGH SECURITY OF API KEY TRANSMISSION)
elseif ( !isset($_POST['api_key']) || isset($_POST['api_key']) && $_POST['api_key'] != $api_key ) {
	
	if ( isset($_POST['api_key']) ) {
	$result = array('error' => "Incorrect API key: " . $_POST['api_key']);
	app_logging('int_api_error', 'From ' . $remote_ip . ' (Incorrect API key)', 'api_key: ' . $_POST['api_key'] . '; uri: ' . $_SERVER['REQUEST_URI'] . ';');
	}
	else {
	$result = array('error' => "Missing API key.");
	app_logging('int_api_error', 'From ' . $remote_ip . ' (Missing API key)', 'uri: ' . $_SERVER['REQUEST_URI'] . ';');
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
	if ( update_cache_file($base_dir . '/cache/internal-api/'.$hash_check.'.dat', $app_config['developer']['local_api_cache_time']) == false ) {
		
	$json_result = trim( file_get_contents($base_dir . '/cache/internal-api/'.$hash_check.'.dat') );

	// Log access event for this ip address (for throttling)
	store_file_contents($base_dir . '/cache/events/throttling/local_api_incoming_ip_' . $store_ip . '.dat', time_date_format(false, 'pretty_date_time') );
	
	}
	// No cache / expired cache
	else {


	$data_set_array = explode('/', $_GET['data_set']); // Data request array

	// Cleanup
	$data_set_array = array_map('trim', $data_set_array);

	$all_markets_data_array = explode(",", $data_set_array[2]); // Market data array

	// Cleanup
	$all_markets_data_array = array_map('trim', $all_markets_data_array);


		// /api/price endpoint
		if ( $data_set_array[0] == 'market_conversion' ) {
		$result = market_conversion_internal_api($data_set_array[1], $all_markets_data_array);
		}
		elseif ( $data_set_array[0] == 'asset_list' ) {
		$result = asset_list_internal_api();
		}
		elseif ( $data_set_array[0] == 'exchange_list' ) {
		$result = exchange_list_internal_api();
		}
		elseif ( $data_set_array[0] == 'market_list' ) {
		$result = market_list_internal_api($data_set_array[1]);
		}
		elseif ( $data_set_array[0] == 'conversion_list' ) {
		$result = conversion_list_internal_api();
		}
		// Non-existent endpoint error message
		else {
		$result = array('error' => 'Endpoint does not exist: ' . $data_set_array[0]);
		app_logging('int_api_error', 'From ' . $remote_ip . ' (Endpoint does not exist: ' . $data_set_array[0] . ')', 'uri: ' . $_SERVER['REQUEST_URI'] . ';');
		}
	
	
		// No matches error message
		if ( !isset($result) ) {
		$result = array('error' => 'No matches / results found.');
		app_logging('int_api_error', 'From ' . $remote_ip . ' (No matches / results found)', 'uri: ' . $_SERVER['REQUEST_URI'] . ';');
		}


	$result['minutes_cached'] = $app_config['developer']['local_api_cache_time'];
	
	
	// JSON-encode results
	$json_result = json_encode($result, JSON_PRETTY_PRINT);
	
	// Cache the result
	store_file_contents($base_dir . '/cache/internal-api/'.$hash_check.'.dat', $json_result);

	// Log access event for this ip address (for throttling)
	store_file_contents($base_dir . '/cache/events/throttling/local_api_incoming_ip_' . $store_ip . '.dat', time_date_format(false, 'pretty_date_time') );


	}

}


// Echo result in json format
echo $json_result;

// Log errors / debugging, send notifications
error_logs();
debugging_logs();
send_notifications();

flush(); // Clean memory output buffer for echo
gc_collect_cycles(); // Clean memory cache

?>


