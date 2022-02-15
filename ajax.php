<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
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
    
    
    	foreach($ct_conf['power']['news_feed'] as $feed) {
    	$feed_id = $ct_gen->digest($feed["title"], 5); // We avoid using array keys for end user config editing UX, BUT STILL UNIQUELY IDENTIFY EACH FEED
    	$all_feeds_array[$feed_id] = $feed;
    	}


	// Mitigate DOS attack leverage, since we recieve extrenal calls in ajax.php
	if ( is_array($batched_feed_hashes_array) && sizeof($batched_feed_hashes_array) <= $ct_conf['dev']['news_feed_batched_max'] ) {
    	
	// Reset feed fetch telemetry 
	$_SESSION[$fetched_feeds] = false;
    	
    	// We already alphabetically ordered / pruned before sending to ajax.php
    	foreach($batched_feed_hashes_array as $chosen_feed_hash) {
    	echo "<fieldset class='subsection_fieldset'><legend class='subsection_legend'> " .$all_feeds_array[$chosen_feed_hash]["title"]." </legend>";
    	echo $ct_api->rss($all_feeds_array[$chosen_feed_hash]["url"], $_GET['theme'], $ct_conf['power']['news_feed_entries_show']);
    	echo "</fieldset>"; 
    	}
	
	}

}
 
// Log errors / debugging, send notifications
$ct_cache->error_log();
$ct_cache->debug_log();
$ct_cache->send_notifications();

flush(); // Clean memory output buffer for echo
gc_collect_cycles(); // Clean memory cache

 ?>