<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */
 
// Runtime mode
$runtime_mode = 'ajax';

ini_set('max_execution_time', 100);

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
$show_feeds = explode(',', rtrim( ( $_POST['show_feeds'] != '' ? $_POST['show_feeds'] : $_COOKIE['show_feeds'] ) , ',') );
echo get_rss_feeds($show_feeds, $app_config['power_user']['news_feeds_entries_show']); 
}
 
// Log errors / debugging, send notifications
error_logs();
debugging_logs();
send_notifications();

 ?>