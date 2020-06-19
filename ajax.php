<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */
 

// Split sleeps between chart / ajax external calls, AND UI runtime to randomly spread calls apart better
usleep(100000); // Wait 0.1 seconds, so low power devices (like a raspberry pi) don't get ddos attacked by accident

// Runtime mode
$runtime_mode = 'ajax';

// Running BEFORE calling config.php

// Log retrevial
if ( $_GET['type'] == 'log' ) {
$is_logs = true;
}
// Chart retrieval
elseif ( $_GET['type'] == 'asset' || $_GET['type'] == 'system' ) {
$is_charts = true;
}

// FOR SPEED, $is_logs / $is_charts above triggers only getting app config vars, VERY LITTLE init.php, then EXITING after running the logs / charts library routines

require("config.php");


// Running AFTER calling config.php

// RSS feed retrieval
if ( $_GET['type'] == 'rss' ) {
echo rss_feed_data($_GET['feed'], $app_config['power_user']['news_feeds_entries_show']); 
}
 
// Log errors / debugging, send notifications
error_logs();
debugging_logs();
send_notifications();

 ?>