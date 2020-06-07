<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */
 
// Runtime mode
$runtime_mode = 'ajax';

ini_set('max_execution_time', 100);

// Running BEFORE calling config.php

// Log retrevial
if ( isset($_GET['logfile']) ) {
$is_logs = true;
}
// Chart retrieval
elseif ( isset($_GET['asset_data']) ) {
$is_charts = true;
}

// FOR SPEED, $is_logs / $is_charts above triggers only getting app config vars, VERY LITTLE init.php, then EXITING after running the logs / charts library routines

require("config.php");
	
 
// Log errors / debugging, send notifications
error_logs();
debugging_logs();
send_notifications();

 ?>