<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// Runtime mode
$runtime_mode = 'api';


// Load app config / etc
require("config.php");


// Set a max execution time, TO AVOID RUNAWAY PROCESSES FREEZING THE SERVER
if ( $app_config['developer']['debug_mode'] != 'off' ) {
ini_set('max_execution_time', 350);
}
else {
ini_set('max_execution_time', $app_config['developer']['api_max_execution_time']);
}


// Ip address information
$store_ip = preg_replace("/\./", "_", $_SERVER['REMOTE_ADDR']);
$ip_access = trim( file_get_contents($base_dir . '/cache/events/throttling/local_api_incoming_ip_' . $store_ip . '.dat') );


// Throttle ip addresses reconnecting before $app_config['power_user']['local_api_rate_limit'] interval passes
if ( update_cache_file($base_dir . '/cache/events/throttling/local_api_incoming_ip_' . $store_ip . '.dat', ($app_config['power_user']['local_api_rate_limit'] / 60) ) == false ) {

$result = array('error' => "Rate limit (maximum of once every " . $app_config['power_user']['local_api_rate_limit'] . " seconds) reached for ip address: " . $_SERVER['REMOTE_ADDR']);

echo json_encode($result, JSON_PRETTY_PRINT);
exit;

}
// API security check (key request var must match our stored API key, or we abort runtime)
// (POST DATA #ONLY#, FOR HIGH SECURITY OF API KEY TRANSMISSION)
elseif ( !isset($_POST['api_key']) || isset($_POST['api_key']) && $_POST['api_key'] != $api_key ) {
	
	if ( isset($_POST['api_key']) ) {
	$result = array('error' => "Incorrect API key: " . $_POST['api_key']);
	}
	else {
	$result = array('error' => "Missing API key.");
	}

echo json_encode($result, JSON_PRETTY_PRINT);
exit;

}
else {

$data_set_array = explode('/', $_GET['data_set']); // Data request array

// Cleanup
$data_set_array = array_map('trim', $data_set_array);
$data_set_array = array_map('strtolower', $data_set_array);

$all_markets_data_array = explode(",", $data_set_array[2]); // Market data array

// Cleanup
$all_markets_data_array = array_map('trim', $all_markets_data_array);
$all_markets_data_array = array_map('strtolower', $all_markets_data_array);


	// /api/price endpoint
	if ( $data_set_array[0] == 'market_conversion' ) {
	$result = market_conversion_api($data_set_array[1], $all_markets_data_array);
	}
	// Non-existent endpoint error message
	else {
	$result = array('error' => 'Endpoint does not exist: ' . $data_set_array[0]);
	}
	
	
	// No matches error message
	if ( !isset($result) ) {
	$result = array('error' => 'No matches / results found.');
	}
	

// Log access event for this ip address (for throttling)
store_file_contents($base_dir . '/cache/events/throttling/local_api_incoming_ip_' . $store_ip . '.dat', time_date_format(false, 'pretty_date_time') );
	
// Return in json format
echo json_encode($result, JSON_PRETTY_PRINT);

}

?>


