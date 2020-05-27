<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */
 
// Runtime mode
$runtime_mode = 'ajax';


// Running log retrieval, BEFORE calling config.php
if ( isset($_GET['logfile']) ) {
$is_logs = true;
ini_set('max_execution_time', 100);
}

// FOR SPEED, $is_logs above triggers only getting app config vars, VERY LITTLE init.php, then EXITING after loading the logs library routines

require("config.php");


// Set a max execution time, TO AVOID RUNAWAY PROCESSES FREEZING THE SERVER
if ( $app_config['developer']['debug_mode'] != 'off' ) {
ini_set('max_execution_time', 350);
}
else {
ini_set('max_execution_time', $app_config['developer']['ajax_max_execution_time']);
}


// Running chart retrieval
if ( isset($_GET['asset_data']) ) {
require_once('app-lib/php/other/ajax/asset-charts.php');
}
	
 
// Log errors / debugging, send notifications
error_logs();
debugging_logs();
send_notifications();

 ?>