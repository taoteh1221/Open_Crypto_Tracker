<?php
/*
 * Copyright 2014-2021 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */
 
 
// Runtime mode
$runtime_mode = 'ajax';

// Running BEFORE calling config.php

// Log retrevial
if ( $_GET['type'] == 'log' ) {
$is_logs = true;
}
// Chart retrieval
elseif ( $_GET['type'] == 'chart' ) {
$is_charts = true;
}

// FOR SPEED, $is_logs / $is_charts above triggers only getting app config vars, VERY LITTLE init.php, then EXITING after running the logs / charts library routines

require("config.php");


// Running AFTER calling config.php

// RSS feed retrieval
if ( $_GET['type'] == 'rss' ) {
	
$batched_feed_hashes_array = explode(',', $_GET['feeds']);

$all_feeds_array = array();
    
    
    	foreach($app_config['power_user']['news_feeds'] as $feed) {
    	$feed_id = get_digest($feed["title"], 10); // We avoid using array keys for end user config editing UX, BUT STILL UNIQUELY IDENTIFY EACH FEED
    	$all_feeds_array[$feed_id] = $feed;
    	}


	// Mitigate DOS attack leverage, since we recieve extrenal calls in ajax.php
	if ( sizeof($batched_feed_hashes_array) <= $app_config['developer']['news_feeds_batched_max'] ) {
    	
	// Reset feed fetch telemetry 
	$_SESSION[$fetched_feeds] = false;
    	
    	// We already alphabetically ordered / pruned before sending to ajax.php
    	foreach($batched_feed_hashes_array as $chosen_feed_hash) {
    	echo "<fieldset class='subsection_fieldset'><legend class='subsection_legend'> " .$all_feeds_array[$chosen_feed_hash]["title"]." </legend>";
    	echo get_rss_feed($all_feeds_array[$chosen_feed_hash]["url"], $app_config['power_user']['news_feeds_entries_show']);
    	echo "</fieldset>"; 
    	}
	
	}

}
 
// Log errors / debugging, send notifications
error_logs();
debugging_logs();
send_notifications();

flush(); // Clean memory output buffer for echo
gc_collect_cycles(); // Clean memory cache

 ?>