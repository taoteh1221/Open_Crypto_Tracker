<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */
 
 
// Runtime mode
$runtime_mode = 'ajax';

// Running BEFORE calling config.php

// Log retrevial
if ( $_GET['type'] == 'log' ) {
$is_logs = true;
}
// Chart retrieval
elseif ( $_GET['type'] == 'asset' || $_GET['type'] == 'system' || $_GET['type'] == 'balance_stats' ) {
$is_charts = true;
}

// FOR SPEED, $is_logs / $is_charts above triggers only getting app config vars, VERY LITTLE init.php, then EXITING after running the logs / charts library routines

require("config.php");


// Running AFTER calling config.php

// RSS feed retrieval
if ( $_GET['type'] == 'rss' ) {
	
$feeds_array = explode(',', $_GET['feeds']);

	// Mitigate DOS attack leverage, since we recieve extrenal calls in ajax.php
	if ( sizeof($feeds_array) <= $app_config['developer']['batched_news_feeds_max'] ) {
		
	// Disable garbage collection (we enable it again after calling rss_feed_data())
	// https://tideways.com/profiler/blog/how-to-optimize-the-php-garbage-collector-usage-to-improve-memory-and-performance
	gc_disable();
	
		foreach ($feeds_array as $feed_hash) {
		//echo rss_feed_data($feed_hash, $app_config['power_user']['news_feeds_entries_show']); 
		}

	// Re-enable garbage collection (that we disabled before calling rss_feed_data()), clean memory cache
	gc_enable();
	gc_collect_cycles();

	}

}
 
// Log errors / debugging, send notifications
error_logs();
debugging_logs();
send_notifications();

// Clean memory cache
gc_collect_cycles();

 ?>