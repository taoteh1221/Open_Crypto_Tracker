<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// Runtime mode
$runtime_mode = 'api';


// Load app config / etc
require("config.php");


// Set a max execution time, TO AVOID RUNAWAY PROCESSES FREEZING THE SERVER
if ( $app_config['debug_mode'] != 'off' ) {
ini_set('max_execution_time', 350);
}
else {
ini_set('max_execution_time', $app_config['api_max_execution_time']);
}


// API security check (key request var must match our stored API key, or we abort runtime)
// POST DATA #ONLY#, FOR HIGH SECURITY OF API KEY TRANSMISSION
if ( !isset($_POST['api_key']) || isset($_POST['api_key']) && $_POST['api_key'] != $api_key ) {
	
$result = array('error' => "Incorrect API key: " . $_POST['api_key']);

echo json_encode($result, JSON_PRETTY_PRINT);

exit;

}
else {

$data_set_array = explode('/', $_GET['data_set']); // Data request array

$all_markets_data_array = explode(",", $data_set_array[1]); // Market data array

// Requested market data
$result = market_api(strtolower($data_set_array[0]), $all_markets_data_array);

	if ( !isset($result) ) {
	$result = array('error' => 'No matches / results found.');
	}
	
// Return in json format
echo json_encode($result, JSON_PRETTY_PRINT);

}

?>


