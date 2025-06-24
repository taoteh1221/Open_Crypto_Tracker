<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// RSS feeds library
 

header('Content-type: text/html; charset=' . $ct['dev']['charset_default']);

header('Access-Control-Allow-Headers: *'); // Allow ALL headers

// Allow access from ANY SERVER (primarily in case the end-user has a server misconfiguration)
if ( $ct['conf']['sec']['access_control_origin'] == 'any' ) {
header('Access-Control-Allow-Origin: *');
}
// Strict access from THIS APP SERVER ONLY (provides tighter security)
else {
header('Access-Control-Allow-Origin: ' . $ct['app_host_address']);
}


$batched_feed_hashes_array = explode(',', $_GET['feeds']);

$all_feeds_array = array();
    
    
foreach($ct['conf']['news']['feeds'] as $feed) {
$feed_id = $ct['gen']->digest($feed["title"], 5); // We avoid using array keys for end user config editing UX, BUT STILL UNIQUELY IDENTIFY EACH FEED
$all_feeds_array[$feed_id] = $feed;
}


// Mitigate DOS attack leverage, since we recieve extrenal calls in ajax.php
if ( is_array($batched_feed_hashes_array) && sizeof($batched_feed_hashes_array) <= $ct['conf']['news']['news_feed_batched_maximum'] ) {
    	
// Reset feed fetch telemetry 
$_SESSION[$fetched_feeds] = false;
    	
    	// We already alphabetically ordered / pruned before sending to ajax.php
    	foreach($batched_feed_hashes_array as $chosen_feed_hash) {
    	echo "<fieldset class='subsection_fieldset'><legend class='subsection_legend'> " .$all_feeds_array[$chosen_feed_hash]["title"]." </legend>";
    	echo $ct['api']->rss($all_feeds_array[$chosen_feed_hash]["url"], $_GET['theme'], $ct['conf']['news']['entries_to_show']);
    	echo "</fieldset>"; 
    	}
	
}
 
	    
// v6.01.01 MIGRATIONS...
// Javascript-based cookie deleting MAY not be as reliable
if ( isset($_COOKIE['show_feeds']) ) {
$ct['gen']->store_cookie('show_feeds', '', time()-3600);
unset($_COOKIE['show_feeds']);
}
	

// Access stats logging
$ct['cache']->log_access_stats();
 
// Log errors / debugging, send notifications
$ct['cache']->app_log();
$ct['cache']->send_notifications();

flush(); // Clean memory output buffer for echo
gc_collect_cycles(); // Clean memory cache


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>