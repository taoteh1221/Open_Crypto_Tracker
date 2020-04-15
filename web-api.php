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
//if ( $_POST['api_key'] != $api_key ) {
//echo "Incorrect API key. " . $_POST['api_key'];
//exit;
//}

echo "Data set = " . $_GET['data_set'] . '; ';
echo "Data selection = " . $_GET['data_selection'] . '; ';

?>


