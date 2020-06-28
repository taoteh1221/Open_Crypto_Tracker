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
    
    	foreach($app_config['power_user']['news_feeds'] as $feed) {
    
			// We avoid using array keys for end user config editing UX, BUT STILL UNIQUELY IDENTIFY EACH FEED
    		if ( isset($feed["title"]) && in_array( get_digest($feed["title"], 10) , $feeds_array ) ) {
    		echo "<fieldset class='subsection_fieldset'><legend class='subsection_legend'> ".$feed["title"].'</legend>';
    		echo get_rss_feed($feed["url"], $app_config['power_user']['news_feeds_entries_show']);
    		echo "</fieldset>";    
    		}
    
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