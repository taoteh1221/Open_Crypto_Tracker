<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


// RSS feeds library
 

header('Content-type: text/html; charset=' . $ct_conf['dev']['charset_default']);

header('Access-Control-Allow-Headers: *'); // Allow ALL headers

// Allow access from ANY SERVER (primarily in case the end-user has a server misconfiguration)
if ( $ct_conf['sec']['access_control_origin'] == 'any' ) {
header('Access-Control-Allow-Origin: *');
}
// Strict access from THIS APP SERVER ONLY (provides tighter security)
else {
header('Access-Control-Allow-Origin: ' . $app_host_address);
}


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
 
 
// Log errors / debugging, send notifications
$ct_cache->error_log();
$ct_cache->debug_log();
$ct_cache->send_notifications();

flush(); // Clean memory output buffer for echo
gc_collect_cycles(); // Clean memory cache


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>